<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('lock_centers', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable(); // Add price column
        });
    }
    
    public function down()
    {
        Schema::table('lock_centers', function (Blueprint $table) {
            $table->dropColumn('price'); // Rollback
        });
    }
    
};
