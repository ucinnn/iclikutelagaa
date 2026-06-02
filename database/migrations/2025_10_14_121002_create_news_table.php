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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->json('tags')->nullable();
            $table->string('author')->default('System');
            $table->string('updated_by')->nullable();

            $table->longText('content')->nullable();
            $table->text('thumbnail')->nullable(); // Gambar utama berita
            $table->boolean('featuredvideo')->default(false); // untuk video unggulan

            // Publication settings
            $table->enum('status', ['draft', 'published', 'scheduled'])
                ->default('draft');                       // Publication status
            $table->timestamp('published_at')->nullable();  // Scheduled or actual publish time

            // SEO metadata
            $table->string('meta_title', 60)->nullable();   // SEO title
            $table->string('meta_keywords')->nullable();    // SEO keywords
            $table->string('meta_description', 160)->nullable(); // SEO description

            $table->text('keterangan')->nullable();
            $table->unsignedInteger('views')->default(0); // jumlah view
            $table->boolean('featured')->default(false); // untuk berita unggulan

            $table->timestamps();
        });

        // Pivot table untuk relasi news-tags (many-to-many), ini sudah benar.
        Schema::create('news_tags', function (Blueprint $table) {
            $table->foreignId('news_id')->constrained('news')->constrained('news')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('tags_id')->constrained('tags')->constrained('tags')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['news_id', 'tags_id']);
        });

        Schema::create('news_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('category')->onDelete('cascade');
            $table->timestamps(); // opsional
        });

        Schema::create('news_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

            // Pastikan tidak ada duplikasi user dan role
            $table->unique(['news_id', 'users_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel dalam urutan terbalik untuk menghindari error foreign key
        Schema::dropIfExists('news_category');
        Schema::dropIfExists('news_users');
        Schema::dropIfExists('news_tag');
        Schema::dropIfExists('news');
    }
};
