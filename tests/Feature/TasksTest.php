<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Task;

class TasksTest extends TestCase
{
    /**
     * @test
     *
     * Test returns true if a user can read all tasks
     *
     * @return void
     */
    public function user_can_read_all_tasks()
    {
        // We have a task in the database
        $task = factory(Task::class)->create();

        // A user visits the task page
        $response = $this->get('/tasks');

        // User should be able to read the task
        $response->assertSee($task->title);
    }

    /**
     * @test
     *
     * Test returns true if user can read a single task via URL
     *
     * @return void
     */
    public function user_can_read_single_task()
    {
        // Given there is a task in database
        $task = factory(Task::class)->create();

        // When user gets task via url
        $response = $this->get('/tasks/'.$task->id);

        // Task details should be visible
        $response->assertSee($task->title)
            ->assertSee($task->description);
    }
}
