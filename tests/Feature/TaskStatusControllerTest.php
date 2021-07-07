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
    private const TEST_USER = 'Test user';
    private const TEST_EMAIL = 'user@test.domain';
    private const TEST_PASSWORD = 'secret123';
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
        User::create(['name' => self::TEST_USER, 'email' => self::TEST_EMAIL, 'password' => bcrypt(self::TEST_PASSWORD)]);
    }

    /**
     * Test Index in TaskStatusController
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('task_statuses.index'));
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
        $response = $this->get(route('task_statuses.create'));
        $response->assertDontSee(strval(__('app.button_create')));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('task_statuses.create'));
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
        $status = (object)TaskStatus::first();
        $response = $this->get(route('task_statuses.edit', ['task_status' => $status->id]));
        $response->assertDontSee($status->name);
        $response->assertDontSee(strval(__('app.button_update')));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('task_statuses.edit', ['task_status' => $status->id]));
        $response->assertSee($status->name);
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

        $status1 = (object)TaskStatus::orderBy('name')->first();
        $status2 = (object)TaskStatus::orderBy('name', 'desc')->first();
        $response = $this->patch(route('task_statuses.update', ['task_status' => $status1->id]), ['_token' => csrf_token(), 'name' => $status2->name]);
        $response->assertSessionHasErrors();
        $response->assertRedirect();

        $str = Str::random(100);
        $response = $this->patch(route('task_statuses.update', ['task_status' => $status1->id]), ['_token' => csrf_token(), 'name' => $str]);
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
        $status = (object)TaskStatus::first();

        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $status->id]), ['_token' => csrf_token()]);
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', ['id' => $status->id]);

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $status->id]), ['_token' => csrf_token()]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('task_statuses', ['id' => $status->id]);
    }
}
