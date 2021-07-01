<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;

class TaskStatusControllerTest extends TestCase
{
    private const EMAIL = 'user@test.domain';
    private const PASSWORD = 'secret123';
    private const TEST_STATUS = 'Test status';

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
        User::create([
            'name' => 'Test',
            'email' => self::EMAIL,
            'password' => bcrypt(self::PASSWORD),
        ]);
    }

    /**
     * Test Index in TaskStatusController
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertDontSee(__('app.header_actions'));
        $response->assertDontSee(__('app.button_delete'));
        $response->assertDontSee(__('app.button_change'));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::EMAIL, 'password' => self::PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('task_statuses.index'));
        $response->assertSee(__('app.header_actions'));
        $response->assertSee(__('app.button_delete'));
        $response->assertSee(__('app.button_change'));
    }

    /**
     * Test Create in TaskStatusController
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertDontSee(__('app.button_create'));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::EMAIL, 'password' => self::PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('task_statuses.create'));
        $response->assertSee(__('app.button_create'));
    }

    /**
     * Test Store in TaskStatusController
     *
     * @return void
     */
    public function testStore()
    {
        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::EMAIL, 'password' => self::PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->post(route('task_statuses.store'), ['_token' => csrf_token(), 'name' => self::TEST_STATUS]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', ['name' => self::TEST_STATUS]);

        $response = $this->post(route('task_statuses.store'), ['_token' => csrf_token(), 'name' => self::TEST_STATUS]);
        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    /**
     * Test Edit in TaskStatusController
     *
     * @return void
     */
    public function testEdit()
    {
        $status = TaskStatus::first();
        $response = $this->get(route('task_statuses.edit', ['task_status' => $status->id]));
        $response->assertDontSee($status->name);
        $response->assertDontSee(__('app.button_update'));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::EMAIL, 'password' => self::PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('task_statuses.edit', ['task_status' => $status->id]));
        $response->assertSee($status->name);
        $response->assertSee(__('app.button_update'));
    }

    /**
     * Test Update in TaskStatusController
     *
     * @return void
     */
    public function testUpdate()
    {
        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::EMAIL, 'password' => self::PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $status = TaskStatus::first();

        $response = $this->patch(route('task_statuses.update', ['task_status' => $status->id]), ['_token' => csrf_token(), 'name' => $status->name]);
        $response->assertSessionHasErrors();
        $response->assertRedirect();

        $str = Str::random(20);
        $response = $this->patch(route('task_statuses.update', ['task_status' => $status->id]), ['_token' => csrf_token(), 'name' => $str]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', ['name' => $str]);
    }

    /**
     * Test Destroy in TaskStatusController
     *
     * @return void
     */
    public function testDelete()
    {
        $status = TaskStatus::first();

        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $status->id]), ['_token' => csrf_token()]);
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', ['id' => $status->id]);

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::EMAIL, 'password' => self::PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $status->id]), ['_token' => csrf_token()]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('task_statuses', ['id' => $status->id]);
    }

}
