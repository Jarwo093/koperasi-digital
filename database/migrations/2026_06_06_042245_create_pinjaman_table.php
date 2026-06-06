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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('nominal_pinjam', 12, 2);
            $table->integer('tenor_bulan'); // Misal: 6 bulan, 12 bulan
            $table->decimal('bunga_persen', 5, 2); // Misal: 10.00%
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('tanggal_approval')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
