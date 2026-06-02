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
        Schema::create('pop_up_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();          // Slug otomatis dari judul
            $table->mediumtext('content')->nullable();
            $table->mediumtext('image')->nullable();       // Gambar opsional
            $table->boolean('is_active')->default(true); // Status aktif/tidak
            $table->timestamp('start_at')->nullable(); // Tanggal mulai tampil
            $table->timestamp('end_at')->nullable();   // Tanggal selesai tampil
            $table->string('author')->default('System');
            $table->string('updated_by')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('popupnews_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('popupnews_id')->constrained('news')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

            // Pastikan tidak ada duplikasi user dan role
            $table->unique(['popupnews_id', 'users_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pop_up_news');
    }
};