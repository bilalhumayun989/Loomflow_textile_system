<?php

namespace Tests\Feature;

use App\Models\BagTransaction;
use App\Models\FabricStock;
use App\Models\Part;
use App\Models\SizingBeam;
use App\Models\SizingSlip;
use App\Models\User;
use App\Models\YarnQuality;
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
        YarnQuality::create(['name' => '30/1 PC Combed']);
        YarnQuality::create(['name' => '40/1 Cotton']);
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
            'sizing_destination' => 'Test Sizing Mill',
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
        $bagTransaction = BagTransaction::create([
            'transaction_type' => 'purchase_sizing',
            'reference_no' => 'CH-SIZING-10',
            'quality' => '40/1 Cotton',
            'quantity_bags' => 50,
            'source' => 'Test Spinning Mill',
            'destination' => 'Kohinoor Sizing',
            'party_name' => 'Kohinoor Sizing',
            'transaction_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->post(route('sizing.slip.store'), [
            'slip_no' => 'SZ-TEST-10',
            'sizing_party' => 'Kohinoor Sizing',
            'yarn_quality' => '40/1 Cotton',
            'bags_allocated' => 50,
            'bags_used' => 45,
            'bags_returned_to_factory' => 3,
            'bags_held_with_sizing' => 2,
            'total_beams' => 3,
            'back_beam_count' => 5,
            'width_multiplier' => 2,
            'set_length_value' => 12000.00,
            'set_length_unit' => 'meters',
            'total_ends' => 10080,
            'sizing_weight_kg' => 2266,
            'bag_transaction_id' => $bagTransaction->id,
            'slip_date' => now()->toDateString(),
            'notes' => 'Test sizing batch',
        ]);

        $response->assertRedirect(route('sizing.index'));
        $this->assertDatabaseHas('sizing_slips', [
            'slip_no' => 'SZ-TEST-10',
            'bags_used' => 45,
            'bags_returned_to_factory' => 3,
            'bags_held_with_sizing' => 2,
            'back_beam_count' => 5,
            'width_multiplier' => 2,
            'total_length_meters' => 12000,
        ]);

        // Beam records store physical warp length; the slip tracks cloth output separately.
        $this->assertEquals(3, SizingBeam::count());
        $this->assertDatabaseHas('sizing_beams', [
            'yarn_quality' => '40/1 Cotton',
            'total_length_meters' => 4000.00,
        ]);
        $this->assertEquals(24000, SizingSlip::first()->expectedFabricMeters());
        $this->assertEquals(8000, SizingSlip::first()->expectedFabricMetersPerBeam());
        $this->assertNotNull(SizingSlip::first()->estimatedWarpCountNe());

        // Verify returned bags logged to factory store
        $this->assertDatabaseHas('bag_transactions', [
            'transaction_type' => 'return_from_sizing',
            'quantity_bags' => 3,
        ]);
    }

    public function test_sizing_slip_records_unaccounted_bags_instead_of_blocking_the_slip(): void
    {
        $bagTransaction = BagTransaction::create([
            'transaction_type' => 'purchase_sizing',
            'reference_no' => 'CH-GATE-PASS-98',
            'quality' => '40/1 Cotton',
            'quantity_bags' => 52,
            'source' => 'Test Spinning Mill',
            'destination' => 'Kohinoor Sizing',
            'party_name' => 'Kohinoor Sizing',
            'transaction_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->post(route('sizing.slip.store'), [
            'slip_no' => 'SZ-GATE-PASS-98',
            'sizing_party' => 'Kohinoor Sizing',
            'yarn_quality' => '40/1 Cotton',
            'bags_allocated' => 52,
            'bags_used' => 51,
            'bags_returned_to_factory' => 0,
            'bags_held_with_sizing' => 0,
            'total_beams' => 4,
            'back_beam_count' => 5,
            'width_multiplier' => 2,
            'set_length_value' => 22400,
            'set_length_unit' => 'gaz',
            'total_ends' => 10080,
            'sizing_gross_weight_kg' => 2550,
            'sizing_weight_kg' => 2266,
            'bag_transaction_id' => $bagTransaction->id,
            'slip_date' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('sizing.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('sizing_slips', [
            'slip_no' => 'SZ-GATE-PASS-98',
            'bags_allocated' => 52,
            'bags_used' => 51,
            'total_beams' => 4,
            'back_beam_count' => 5,
            'total_length_meters' => 20482.56,
            'width_multiplier' => 2,
        ]);
        $this->assertEquals(1, SizingSlip::first()->bags_allocated - SizingSlip::first()->bags_used);
        $this->assertEquals(40965.12, SizingSlip::first()->expectedFabricMeters());
        $this->assertEquals(4, SizingBeam::count());
        $this->assertEquals(5120.64, (float) SizingBeam::first()->total_length_meters);
    }

    public function test_sizing_slip_accepts_fractional_bag_usage_and_returns_fractional_stock(): void
    {
        $bagTransaction = BagTransaction::create([
            'transaction_type' => 'purchase_sizing',
            'reference_no' => 'CH-FRACTIONAL-1',
            'quality' => '40/1 Cotton',
            'quantity_bags' => 52,
            'source' => 'Test Spinning Mill',
            'destination' => 'Kohinoor Sizing',
            'party_name' => 'Kohinoor Sizing',
            'transaction_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->post(route('sizing.slip.store'), [
            'slip_no' => 'SZ-FRACTIONAL-1',
            'slip_type' => 'raw_perch',
            'sizing_party' => 'Kohinoor Sizing',
            'yarn_quality' => '40/1 Cotton',
            'bags_allocated' => 52,
            'bags_used' => 51.5,
            'bags_returned_to_factory' => 0.5,
            'bags_held_with_sizing' => 0,
            'total_beams' => 1,
            'width_multiplier' => 1,
            'set_length_value' => 1000,
            'set_length_unit' => 'meters',
            'total_ends' => 10080,
            'bag_transaction_id' => $bagTransaction->id,
            'slip_date' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('sizing.index'));
        $response->assertSessionHas('success');

        $slip = SizingSlip::where('slip_no', 'SZ-FRACTIONAL-1')->firstOrFail();
        $this->assertSame(52.0, (float) $slip->bags_allocated);
        $this->assertSame(51.5, (float) $slip->bags_used);
        $this->assertSame(0.5, (float) $slip->bags_returned_to_factory);

        $returnedTransaction = BagTransaction::where('transaction_type', 'return_from_sizing')->firstOrFail();
        $this->assertSame(0.5, (float) $returnedTransaction->quantity_bags);

        $this->actingAs($this->user)
            ->get(route('bags.index'))
            ->assertOk()
            ->assertSee('0.50 bags');
    }

    public function test_blank_final_perch_returned_material_fields_are_saved_as_zero(): void
    {
        $bagTransaction = BagTransaction::create([
            'transaction_type' => 'purchase_sizing',
            'reference_no' => 'CH-FINAL-BLANK-1',
            'quality' => '40/1 Cotton',
            'quantity_bags' => 52,
            'source' => 'Test Spinning Mill',
            'destination' => 'Kohinoor Sizing',
            'party_name' => 'Kohinoor Sizing',
            'transaction_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->post(route('sizing.slip.store'), [
            'slip_no' => 'SZ-FINAL-BLANK-1',
            'slip_type' => 'final_perch',
            'sizing_party' => 'Kohinoor Sizing',
            'yarn_quality' => '40/1 Cotton',
            'bags_allocated' => 52,
            'bags_used' => 52,
            'bags_returned_to_factory' => 0,
            'bags_held_with_sizing' => 0,
            'total_beams' => 1,
            'width_multiplier' => 1,
            'set_length_value' => 1000,
            'set_length_unit' => 'meters',
            'total_ends' => 10080,
            'sizing_gross_weight_kg' => 2550,
            'sizing_rate_per_kg' => 61,
            'bag_transaction_id' => $bagTransaction->id,
            'slip_date' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('sizing.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('sizing_slips', [
            'slip_no' => 'SZ-FINAL-BLANK-1',
            'wet_cone_bags' => 0,
            'wet_cone_weight_kg' => 0,
            'empty_bag_count' => 0,
            'empty_bag_weight_kg' => 0,
            'paper_bag_count' => 0,
            'paper_bag_weight_kg' => 0,
            'sound_cone_count' => 0,
            'sound_cone_weight_kg' => 0,
            'carton_count' => 0,
            'carton_weight_kg' => 0,
            'returned_materials_weight_kg' => 0,
            'sizing_weight_pounds' => round(2550 / 0.45359237, 2),
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
        $slip = SizingSlip::create([
            'slip_no' => 'SZ-PROD-1',
            'sizing_party' => 'Test Sizing Mill',
            'yarn_quality' => '30/1 PC Combed',
            'bags_allocated' => 40,
            'bags_used' => 40,
            'total_beams' => 1,
            'total_length_meters' => 4000,
            'width_multiplier' => 2,
            'slip_date' => now()->toDateString(),
        ]);

        $fabric = FabricStock::create([
            'quality_name' => 'Twill 2/1',
            'construction' => '40x40',
            'width_inches' => 63,
            'current_stock_meters' => 0,
            'available_open_meters' => 0,
            'total_rolls_in_stock' => 0,
        ]);

        $beam = SizingBeam::create([
            'sizing_slip_id' => $slip->id,
            'beam_no' => 'BM-TEST-1',
            'yarn_quality' => '30/1 PC',
            'total_length_meters' => 4000.00,
            'remaining_length_meters' => 4000.00,
            'status' => 'mounted_on_loom',
            'assigned_loom_no' => 'Loom-01',
        ]);

        $response = $this->actingAs($this->user)->post(route('production.store'), [
            'production_date' => now()->toDateString(),
            'shift' => 'Day',
            'loom_no' => 'Loom-01',
            'operator_name' => 'Tariq Weaver',
            'sizing_beam_id' => $beam->id,
            'fabric_stock_id' => $fabric->id,
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
            'expected_fabric_meters' => 620.00,
            'leakage_waste_meters' => 320.00,
        ]);

        // Check beam remaining (4000 - 310 = 3690m)
        $this->assertEquals(3690.00, $beam->fresh()->remaining_length_meters);
        $this->assertEquals('mounted_on_loom', $beam->fresh()->status);
        $this->assertEquals(300, $fabric->fresh()->current_stock_meters);
        $this->assertEquals(300, $fabric->fresh()->daily_received_meters);
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
            'fabric_stock_id' => $fabric->id,
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
