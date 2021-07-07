<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    private const TEST_USER = 'Test user';
    private const TEST_EMAIL = 'user@test.domain';
    private const TEST_PASSWORD = 'secret123';
    private const TEST_TASK = 'Test task';

    /**
     * Setting initial values.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Artisan::call('migrate --seed');
        Session::start();
        User::create(['name' => self::TEST_USER, 'email' => self::TEST_EMAIL, 'password' => bcrypt(self::TEST_PASSWORD)]);
        $user = User::firstWhere('name', self::TEST_USER);
        $status = TaskStatus::first();
        Task::create(['name' => self::TEST_USER, 'description' => Str::random(100), 'status_id' => $status->id, 'created_by_id' => $user->id, 'assigned_to_id' => $user->id]);
    }

    /**
     * Test Index in TaskStatusController
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('tasks.index'));
        $response->assertDontSee(strval(__('app.header_actions')));
        $response->assertDontSee(strval(__('app.button_delete')));
        $response->assertDontSee(strval(__('app.button_change')));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('task_statuses.index'));
        $response->assertSee(strval(__('app.header_actions')));
        $response->assertSee(strval(__('app.button_delete')));
        $response->assertSee(strval(__('app.button_change')));
    }

    /**
     * Test Create in TaskStatusController
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->get(route('tasks.create'));
        $response->assertDontSee(strval(__('app.button_create')));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('tasks.create'));
        $response->assertSee(strval(__('app.button_create')));
    }

    /**
     * Test Store in TaskStatusController
     *
     * @return void
     */
    public function testStore()
    {
        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $user = (object)User::firstWhere('name', self::TEST_USER);
        $status = (object)TaskStatus::first();
        $response = $this->post(route('tasks.store'), ['_token' => csrf_token(), 'name' => self::TEST_TASK]);
        $response->assertSessionHasErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['name' => self::TEST_TASK]);

        $response = $this->post(route('tasks.store'), ['_token' => csrf_token(), 'name' => self::TEST_TASK, 'status_id' => $status->id, 'created_by_id' => $user->id]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', ['name' => self::TEST_TASK]);
    }

    /**
     * Test Show in TaskController
     *
     * @return void
     */
    public function testShow()
    {
        $task = (object)Task::first();
        $response = $this->get(route('tasks.show', ['task' => $task->id]));
        $response->assertDontSee($task->name);
        $response->assertDontSee($task->description);

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('tasks.show', ['task' => $task->id]));
        $response->assertSee($task->name);
        $response->assertSee($task->description);
    }

    /**
     * Test Edit in TaskStatusController
     *
     * @return void
     */
    public function testEdit()
    {
        $task = Task::first();
        $response = $this->get(route('tasks.edit', ['task' => $task->id]));
        $response->assertDontSee($task->name);
        $response->assertDontSee(strval(__('app.button_update')));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('tasks.edit', ['task' => $task->id]));
        $response->assertSee($task->name);
        $response->assertSee(strval(__('app.button_update')));
    }

    /**
     * Test Update in TaskStatusController
     *
     * @return void
     */
    public function testUpdate()
    {
        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $task = (object)Task::first();

        $response = $this->patch(route('tasks.update', ['task' => $task->id]), ['_token' => csrf_token(), 'name' => $task->name]);
        $response->assertSessionHasErrors();
        $response->assertRedirect();

        $str = Str::random(100);
        $response = $this->patch(route('tasks.update', ['task' => $task->id]), ['_token' => csrf_token(), 'name' => $str, 'status_id' => $task->status_id, 'created_by_id' => $task->created_by_id]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', ['name' => $str]);
    }

    /**
     * Test Destroy in TaskStatusController
     *
     * @return void
     */
    public function testDelete()
    {
        $task = (object)TaskStatus::first();

        $response = $this->delete(route('tasks.destroy', ['task' => $task->id]), ['_token' => csrf_token()]);
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', ['id' => $task->id]);

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->delete(route('tasks.destroy', ['task' => $task->id]), ['_token' => csrf_token()]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
