<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cleaning_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name',20)->unique();
            $table->string('description',180)->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->foreignId('priority_id')->nullable()->after('priority')->constrained('cleaning_priorities')->nullOnDelete();
        });
        Schema::table('cleaning_requests', function (Blueprint $table) {
            $table->foreignId('suggested_priority_id')->nullable()->after('suggested_priority')->constrained('cleaning_priorities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cleaning_requests', function (Blueprint $table) {
            $table->dropForeign(['suggested_priority_id']);
            $table->dropColumn('suggested_priority_id');
        });
        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->dropForeign(['priority_id']);
            $table->dropColumn('priority_id');
        });
        Schema::dropIfExists('cleaning_priorities');
    }
};
