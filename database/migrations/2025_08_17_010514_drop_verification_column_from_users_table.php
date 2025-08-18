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
            $columns = ['phone_number', 'address', 'nik', 'ktp_image', 'face_image'];

            foreach ($columns as $col) {
                if(Schema::hasColumn('users', $col)){
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('nik', 20)->nullable()->unique();
            $table->string('ktp_image')->nullable();
            $table->string('face_image')->nullable();
        });
    }
};
