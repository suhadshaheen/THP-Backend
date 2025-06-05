<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('freelancer_id')->nullable()->after('reviewer_id');
            $table->unsignedBigInteger('job_id')->nullable()->after('freelancer_id');

            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['freelancer_id']);
            $table->dropForeign(['job_id']);
            $table->dropColumn(['freelancer_id', 'job_id']);
        });
    }
};
