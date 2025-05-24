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

            $table->string('firstname')->after('id');
            $table->string('lastname')->after('firstname');
            $table->string('city')->nullable()->after('phone');
            $table->string('country')->nullable()->after('city');
            $table->renameColumn('UserName', 'username');
            $table->dropColumn(['name', 'Address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'lastname', 'city', 'country']);
            $table->renameColumn('username', 'UserName');
            $table->string('name')->nullable();
            $table->string('Address')->nullable();
        });
    }
};
