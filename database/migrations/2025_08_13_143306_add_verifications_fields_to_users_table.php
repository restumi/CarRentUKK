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
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('nik')->unique()->nullable()->after('password');
            $table->string('ktp_image')->nullable()->after('nik');
            $table->string('face_image')->nullable()->after('ktp_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'address',
                'nik',
                'ktp_image',
                'face_image'
            ]);
        });
    }
};
