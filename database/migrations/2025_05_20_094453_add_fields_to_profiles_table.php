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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('whatsappNumber')->after('bio');
            $table->string('InstagramLink')->nullable()->after('whatsappNumber');
            $table->string('FacebookLink')->nullable()->after('InstagramLink');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('whatsappNumber');
            $table->dropColumn('InstagramLink');
            $table->dropColumn('FacebookLink');
        });
    }
};
