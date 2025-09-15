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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('stamped_file')->nullable()->after('invoice_file');
            $table->unsignedBigInteger('stamped_by')->nullable()->after('stamped_file');
            $table->timestamp('stamped_at')->nullable()->after('stamped_by');
            
            $table->foreign('stamped_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['stamped_by']);
            $table->dropColumn(['stamped_file', 'stamped_by', 'stamped_at']);
        });
    }
};
