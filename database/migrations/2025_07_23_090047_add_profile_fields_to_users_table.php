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
        Schema::table('users', function (Blueprint $table) {
            // 'username' alanı: benzersiz ve boş geçilemez olmalı
            // 'email' alanından sonra ekliyoruz
            $table->string('username')->unique()->nullable()->after('email');
            // 'profile_picture' alanı: resim dosya yolunu tutacak, boş geçilebilir
            $table->string('profile_picture')->nullable()->after('username');
            // 'bio' alanı: kısa biyografi, metin tipinde ve boş geçilebilir
            $table->text('bio')->nullable()->after('profile_picture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // `down` metodu, `up` metodunda yapılan değişiklikleri geri alır.
            // Sütunları eklediğimiz sıranın tersine silmeliyiz.
            $table->dropColumn(['bio', 'profile_picture', 'username']);
        });
    }
};