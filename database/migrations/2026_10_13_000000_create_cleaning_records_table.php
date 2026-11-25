<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cleaning_records', function (Blueprint $table) {
            $table->id();
            $table->string('location', 150);
            $table->string('category', 80);
            $table->text('description');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->string('assigned_staff', 120);
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending')->index();
            $table->date('scheduled_date')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_records');
    }
};
