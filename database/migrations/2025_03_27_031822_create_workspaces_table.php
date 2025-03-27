<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->bigIncrements('workspace_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('join_code')->unique();
            $table->timestamps();
            $table->foreignId('created_by')->constrained('users', 'user_id');
            $table->SoftDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};
