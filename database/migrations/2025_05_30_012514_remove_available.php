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
       Schema::table('jobs', function (Blueprint $table) {
        $table->timestamp('posting_date')->useCurrent()->change();
        $table->dropColumn(['available_at', 'attempts']);
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
        $table->timestamp('available_at')->nullable();
        $table->integer('attempts')->default(0);
        $table->date('posting_date')->change();

    });
    }
};
