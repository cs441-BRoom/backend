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
        Schema::create('assignments', function (Blueprint $table) {
            $table->bigIncrements('assignment_id');
            $table->foreignId('workspace_id')->constrained('workspaces', 'workspace_id');
            $table->string('title');
            $table->text('description');
            $table->dateTime('due_date');
            $table->timestamps();
            $table->foreignId('created_by')->constrained('users', 'user_id');
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
