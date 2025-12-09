<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
            
            if (!Schema::hasColumn('comments', 'is_approved')) {
                $table->boolean('is_approved')->default(false);
            }
            
            if (!Schema::hasColumn('comments', 'is_rejected')) {
                $table->boolean('is_rejected')->default(false);
            }
            
            if (!Schema::hasColumn('comments', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('comments', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable();
            }
            
            if (!Schema::hasColumn('comments', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn([
                'status', 
                'is_approved', 
                'is_rejected', 
                'moderated_by', 
                'moderated_at',
                'rejection_reason'
            ]);
        });
    }
};