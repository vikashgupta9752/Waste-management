<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waste_requests', function (Blueprint $table) {
            // In SQLite, changing from enum to string is the safest way to allow new values
            $table->string('status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('waste_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'assigned', 'collected', 'disposed'])->default('pending')->change();
        });
    }
};
