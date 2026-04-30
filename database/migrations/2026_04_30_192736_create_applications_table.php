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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('full_name');
            $table->string('email');
            $table->string('student_id');
            $table->string('course');
            $table->string('year_level');

            $table->string('scholarship_type');
            $table->text('reason_for_applying')->nullable();

            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            $table->string('tally_submission_id')->nullable()->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
