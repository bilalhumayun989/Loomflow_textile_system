<?php

namespace Tests\Feature;

use App\Models\FabricStock;
use App\Models\Part;
use App\Models\SizingBeam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoomOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_bag_purchase_with_split_stores_and_creates_transactions(): void
    {
        $response = $this->actingAs($this->user)->post(route('bags.purchase'), [
            'challan_no' => 'CH-TEST-1',
            'supplier_name' => 'Test Spinning Mill',
            'quality' => '30/1 PC Combed',
            'total_bags' => 100,
            'bags_to_sizing' => 60,
            'bags_to_factory' => 40,
            'rate_per_bag' => 14000.00,
            'purchase_date' => now()->toDateString(),
            'notes' => 'Test Split Order',
        ]);

        $response->assertRedirect(route('bags.index'));
        $this->assertDatabaseHas('bag_purchases', [
            'challan_no' => 'CH-TEST-1',
            'total_bags' => 100,
            'bags_to_sizing' => 60,
            'bags_to_factory' => 40,
        ]);
        $this->assertDatabaseHas('bag_transactions', [
            'reference_no' => 'CH-TEST-1',
            'transaction_type' => 'purchase_factory',
            'quantity_bags' => 40,
        ]);
        $this->assertDatabaseHas('bag_transactions', [
            'reference_no' => 'CH-TEST-1',
            'transaction_type' => 'purchase_sizing',
            'quantity_bags' => 60,
        ]);
    }

    public function test_sizing_slip_creates_beams_and_tracks_returned_bags(): void
    {
        $response = $this->actingAs($this->user)->post(route('sizing.slip.store'), [
            'slip_no' => 'SZ-TEST-10',
            'sizing_party' => 'Kohinoor Sizing',
            'yarn_quality' => '40/1 Cotton',
            'bags_allocated' => 50,
            'bags_used' => 45,
            'bags_returned_to_factory' => 3,
            'bags_held_with_sizing' => 2,
            'total_beams' => 3,
            'total_length_meters' => 12000.00,
            'slip_date' => now()->toDateString(),
            'notes' => 'Test sizing batch',
        ]);

        $response->assertRedirect(route('sizing.index'));
        $this->assertDatabaseHas('sizing_slips', [
            'slip_no' => 'SZ-TEST-10',
            'bags_used' => 45,
            'bags_returned_to_factory' => 3,
            'bags_held_with_sizing' => 2,
        ]);

        // Verify beams generated (12000 / 3 = 4000m each)
        $this->assertEquals(3, SizingBeam::count());
        $this->assertDatabaseHas('sizing_beams', [
            'yarn_quality' => '40/1 Cotton',
            'total_length_meters' => 4000.00,
        ]);

        // Verify returned bags logged to factory store
        $this->assertDatabaseHas('bag_transactions', [
            'transaction_type' => 'return_from_sizing',
            'quantity_bags' => 3,
        ]);
    }

    public function test_part_issuance_decrements_store_stock(): void
    {
        $part = Part::create([
            'part_name' => 'Rapier Gripper Clip',
            'part_code' => 'GRP-CLP',
            'category' => 'Gripper',
            'stock_quantity' => 10,
            'min_alert_threshold' => 2,
            'unit' => 'pcs',
            'unit_price' => 500.00,
        ]);

        $response = $this->actingAs($this->user)->post(route('parts.issue'), [
            'part_id' => $part->id,
            'loom_no' => 'Loom-05',
            'issued_to_person' => 'Master Aslam',
            'quantity' => 2,
            'issuance_date' => now()->toDateString(),
            'remarks' => 'Replacement clip',
        ]);

        $response->assertRedirect(route('parts.index'));
        $this->assertEquals(8, $part->fresh()->stock_quantity);
    }

    public function test_production_calculates_leakage_and_decrements_beam_length(): void
    {
        $beam = SizingBeam::create([
            'beam_no' => 'BM-TEST-1',
            'yarn_quality' => '30/1 PC',
            'total_length_meters' => 4000.00,
            'remaining_length_meters' => 4000.00,
            'status' => 'ready_in_store',
        ]);

        $response = $this->actingAs($this->user)->post(route('production.store'), [
            'production_date' => now()->toDateString(),
            'shift' => 'Day',
            'loom_no' => 'Loom-01',
            'operator_name' => 'Tariq Weaver',
            'sizing_beam_id' => $beam->id,
            'fabric_quality' => 'Twill 2/1',
            'start_meter' => 0.00,
            'end_meter' => 300.00, // 300m produced
            'beam_meters_consumed' => 310.00, // 310m taken -> 10m leakage
            'picks_per_inch' => 60,
            'rpm' => 350,
            'remarks' => 'Smooth shift',
        ]);

        $response->assertRedirect(route('production.index'));

        // Check production record
        $this->assertDatabaseHas('productions', [
            'loom_no' => 'Loom-01',
            'net_meters_produced' => 300.00,
            'beam_meters_consumed' => 310.00,
            'leakage_waste_meters' => 10.00,
        ]);

        // Check beam remaining (4000 - 310 = 3690m)
        $this->assertEquals(3690.00, $beam->fresh()->remaining_length_meters);
        $this->assertEquals('mounted_on_loom', $beam->fresh()->status);
    }

    public function test_sales_slip_decrements_available_fabric_stock(): void
    {
        $fabric = FabricStock::create([
            'quality_name' => 'Cotton Sheeting 68x68',
            'construction' => '30x30',
            'width_inches' => 63.0,
            'current_stock_meters' => 5000.00,
            'available_open_meters' => 5000.00,
            'total_rolls_in_stock' => 50,
        ]);

        $response = $this->actingAs($this->user)->post(route('sales.store'), [
            'slip_no' => 'SLIP-TEST-1',
            'sale_type' => 'open_market',
            'buyer_name' => 'Test Cloth Merchant',
            'fabric_quality' => $fabric->quality_name,
            'meters_sold' => 1000.00,
            'rolls_sold' => 10,
            'rate_per_meter' => 250.00,
            'discount' => 1000.00,
            'payment_type' => 'cash',
            'payment_status' => 'paid',
            'sale_date' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('sales.index'));
        $this->assertEquals(4000.00, $fabric->fresh()->current_stock_meters);
        $this->assertEquals(4000.00, $fabric->fresh()->available_open_meters);
        $this->assertEquals(40, $fabric->fresh()->total_rolls_in_stock);
    }
}
