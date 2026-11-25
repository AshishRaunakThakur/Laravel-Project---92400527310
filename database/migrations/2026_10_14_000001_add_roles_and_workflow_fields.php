<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('campus_user')->index();
            $table->boolean('is_active')->default(true)->index();
        });

        Schema::create('cleaning_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('location_id')->constrained('campus_locations')->restrictOnDelete();
            $table->foreignId('category_id')->constrained('cleaning_categories')->restrictOnDelete();
            $table->text('description');
            $table->string('suggested_priority', 20)->nullable();
            $table->string('status', 30)->default('Submitted')->index();
            $table->text('admin_remarks')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });

        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->after('id')->constrained('campus_locations')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->after('location_id')->constrained('cleaning_categories')->nullOnDelete();
            $table->foreignId('assigned_staff_id')->nullable()->after('category_id')->constrained('users')->nullOnDelete();
            $table->foreignId('request_id')->nullable()->unique()->after('assigned_staff_id')->constrained('cleaning_requests')->nullOnDelete();
            $table->text('completion_remarks')->nullable();
            $table->timestamp('completed_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['assigned_staff_id']);
            $table->dropForeign(['request_id']);
            $table->dropUnique(['request_id']);
            $table->dropColumn(['location_id', 'category_id', 'assigned_staff_id', 'request_id', 'completion_remarks', 'completed_at']);
        });
        Schema::dropIfExists('cleaning_requests');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['role', 'is_active']));
    }
};
