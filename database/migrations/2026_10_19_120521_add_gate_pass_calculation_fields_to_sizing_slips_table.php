<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sizing_slips', function (Blueprint $table) {
            $table->decimal('set_length_value', 12, 2)->nullable()->after('total_beams');
            $table->string('set_length_unit', 20)->default('meters')->after('set_length_value');
            $table->unsignedInteger('total_ends')->nullable()->after('set_length_unit');
            $table->unsignedInteger('reed_count')->nullable()->after('total_ends');
            $table->decimal('fabric_width_inches', 8, 2)->nullable()->after('reed_count');
            $table->unsignedInteger('selvedge_ends')->default(0)->after('fabric_width_inches');
            $table->decimal('sizing_weight_kg', 10, 2)->nullable()->after('selvedge_ends');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizing_slips', function (Blueprint $table) {
            $table->dropColumn([
                'set_length_value',
                'set_length_unit',
                'total_ends',
                'reed_count',
                'fabric_width_inches',
                'selvedge_ends',
                'sizing_weight_kg',
            ]);
        });
    }
};
