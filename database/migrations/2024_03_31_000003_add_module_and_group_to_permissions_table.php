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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('description')->nullable()->after('guard_name');
            $table->foreignId('module_id')->nullable()->after('description')->constrained()->onDelete('set null');
            $table->foreignId('permission_group_id')->nullable()->after('module_id')->constrained('permission_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropForeign(['permission_group_id']);
            $table->dropColumn(['description', 'module_id', 'permission_group_id']);
        });
    }
};
