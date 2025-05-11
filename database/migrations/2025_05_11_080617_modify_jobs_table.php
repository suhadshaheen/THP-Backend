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

            if (Schema::hasColumn('jobs', 'queue')) {
                $table->dropColumn('queue');
            }

            if (Schema::hasColumn('jobs', 'payload')) {
                $table->dropColumn('payload');
            }

            $table->string('title');
            $table->text('description');
            $table->enum('status', ['pending', 'in_progress,', 'completed'])->default('pending');
            $table->foreignId('job_owner_id')->constrained('users')->onDelete('cascade');
            $table->string('location');
            $table->string('category');
            $table->string('job_requirements')->nullable();
            $table->date('deadline');
            $table->date('posting_date');
            $table->string('JobPhoto')->nullable();

        });

        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {

            $table->string('queue')->nullable();
            $table->longText('payload')->nullable();


            $table->dropColumn([
                'title',
                'description',
                'status',
                'job_owner_id',
                'location',
                'category',
                'job_requirements',
                'deadline',
                'posting_date',
                'JobPhoto',
            ]);
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }
};
