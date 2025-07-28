<?php

use App\Models\Enums\TaskStatus;
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
        Schema::create('tasks', function (Blueprint $table): void {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->string('title', 128);
            $table->integer('project_id')->unsigned();
            $table->bigInteger('assigned_user_id')->unsigned();
            $table->enum('status', TaskStatus::values());
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();
            $table->index('title', 'ik_task_title');
            $table->index('status', 'ik_task_status');
            $table
                ->foreign('project_id', 'fk_task_project')
                ->references('id')
                ->on('projects')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table
                ->foreign('assigned_user_id', 'fk_task_assigned_user')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('tasks');
    }
};
