<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreelancerIdAndJobIdToReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // تأكد أن الأعمدة غير موجودة قبل إضافتها (اختياري)
            if (!Schema::hasColumn('reviews', 'freelancer_id')) {
                $table->unsignedBigInteger('freelancer_id')->nullable()->after('bid_id');
                $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('reviews', 'job_id')) {
                $table->unsignedBigInteger('job_id')->nullable()->after('freelancer_id');
                $table->foreign('job_id')->references('id')->on('jobs')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // لحذف المفاتيح الخارجية ثم الأعمدة
            if (Schema::hasColumn('reviews', 'freelancer_id')) {
                $table->dropForeign(['freelancer_id']);
                $table->dropColumn('freelancer_id');
            }

            if (Schema::hasColumn('reviews', 'job_id')) {
                $table->dropForeign(['job_id']);
                $table->dropColumn('job_id');
            }
        });
    }
}
