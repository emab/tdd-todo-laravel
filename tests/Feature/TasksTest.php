<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Task;
use App\User;

class TasksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     *
     * Users can read all tasks
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
     * Users can read a single task
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

    /**
     * @test
     *
     * Authenticated user can create a new task
     *
     * @return void
     */
    public function authenticated_user_can_create_task()
    {
        // number of current tasks
        $tasks = Task::all()->count();
        // Given we have an authenticated user
        $this->actingAs(factory(User::class)->create());
        // And a task object
        $task = factory(Task::class)->make();
        // When user submits post request to create task
        $this->post('/tasks/create', $task->toArray());
        // It is stored in Database and added to tasks
        $this->assertEquals(($tasks + 1),Task::all()->count());
    }

    /**
     * @test
     *
     * Unauthenticated user cannot create a task
     *
     * @return void
     */
    public function unauthenticated_user_cannot_create_task()
    {
        // Given we have a task
        $task = factory(Task::class)->make();

        // Unauth user should be returned to homepage
        $this->post('/tasks/create', $task->toArray())->assertRedirect('/login');
    }

    /**
     * @test
     *
     * Task must have a title on POST
     *
     * @return void
     */
    public function task_requires_a_title()
    {
        // Auth user
        $this->actingAs(factory(User::class)->create());

        // Task with no title
        $task = factory(Task::class)->make(['title' => null]);

        // Gives error when POST
        $this->post('/tasks/create', $task->toArray())->assertSessionHasErrors('title');
    }

    /**
     * @test
     *
     * Task must have a description on POST
     *
     * @return void
     */
    public function task_requires_a_description()
    {
        // Auth user
        $this->actingAs(factory(User::class)->create());

        // Task with no description
        $task = factory(Task::class)->make(['description' => null]);

        // Gives error when POST
        $this->post('/tasks/create', $task->toArray())->assertSessionHasErrors('description');
    }

    /**
     * @test
     *
     * Test to ensure the user who created a task can edit it
     *
     * @return void
     */
    public function autorised_user_can_update_task()
    {
        // Auth user
        $this->actingAs(factory(User::class)->create());

        // Task created by user with title
        $task = factory(Task::class)->create([
            'user_id' => Auth::id(),
        ]);

        $task->title = "Updated Title";

        // When user tries to update the task using PUT
        $this->put('/tasks/'.$task->id, $task->toArray());

        // The task in the database should be updated
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title'
        ]);
    }

    /**
     * @test
     *
     * Test to ensure who didn't create a task cannot edit it
     *
     * @return void
     */
    public function unauthorised_user_cannot_update_task()
    {
        // Given authed user
        $this->actingAs(factory(User::class)->create());

        // And a task that isn't created by user
        $task = factory(Task::class)->create();

        // WHen user tries to update the title
        $task->title = "Updated title";
        $response = $this->put('/tasks/'.$task->id, $task->toArray());

        // We should expect 403
        $response->assertStatus(403);
    }

    /**
     * @test
     *
     * Test an authorised user can delete a task
     *
     * @return void
     */
    public function authorised_user_can_delete_task()
    {
        // Given authed user
        $this->actingAs(factory(User::class)->create());

        // And a task that is created by user
        $task = factory(Task::class)->create(['user_id' => Auth::id()]);

        // When user tries to delete task
        $this->delete('/tasks/'.$task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /**
     * @test
     *
     * Test an unauthorised user can't delete a task
     *
     * @return void
     */
    public function unauthorised_user_cannot_delete_task()
    {
        // Given authed user
        $this->actingAs(factory(User::class)->create());

        // And a task that is created by user
        $task = factory(Task::class)->create();

        // When user tries to delete task
        $response = $this->delete('/tasks/'.$task->id);

        // Unauthorised response
        $response->assertStatus(403);
    }
}
