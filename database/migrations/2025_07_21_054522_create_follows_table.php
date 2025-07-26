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
        Schema::create('follows', function (Blueprint $table) {
            $table->id(); // Tablonun ana anahtarı

            // Takip eden kullanıcının ID'si
            $table->foreignId('follower_id')
                  ->constrained('users') // 'users' tablosundaki 'id' sütununa bağlanır
                  ->onDelete('cascade'); // Takip eden kullanıcı silinirse, ilgili takip kayıtları da silinir

            // Takip edilen kullanıcının ID'si
            $table->foreignId('following_id')
                  ->constrained('users') // 'users' tablosundaki 'id' sütununa bağlanır
                  ->onDelete('cascade'); // Takip edilen kullanıcı silinirse, ilgili takip kayıtları da silinir

            // Bir kullanıcının aynı kişiyi birden fazla takip etmesini engellemek için benzersiz birleşim
            $table->unique(['follower_id', 'following_id']);

            $table->timestamps(); // 'created_at' ve 'updated_at' sütunları
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};