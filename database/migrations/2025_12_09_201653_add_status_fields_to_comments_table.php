<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('comments', function (Blueprint $table) {
        if (!Schema::hasColumn('comments', 'approved')) {
            $table->boolean('approved')->default(false);
        }
        
        if (!Schema::hasColumn('comments', 'approved_at')) {
            $table->timestamp('approved_at')->nullable();
        }
        
        if (!Schema::hasColumn('comments', 'approved_by')) {
            $table->foreignId('approved_by')->nullable()->constrained('users');
        }
        
        if (!Schema::hasColumn('comments', 'is_approved')) {
            $table->boolean('is_approved')->default(false);
        }
        
        if (!Schema::hasColumn('comments', 'status')) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            //
        });
    }
};
