<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    private const TEST_USER = 'Test user';
    private const TEST_EMAIL = 'user@test.domain';
    private const TEST_PASSWORD = 'secret123';
    private const TEST_LABEL = 'Test label';

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
        Label::create(['name' => Str::random(100), 'description' => Str::random(100)]);
    }

    /**
     * Test Index in LabelController
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('labels.index'));
        $response->assertDontSee(strval(__('app.header_actions')));
        $response->assertDontSee(strval(__('app.button_delete')));
        $response->assertDontSee(strval(__('app.button_change')));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('labels.index'));
        $response->assertSee(strval(__('app.header_actions')));
        $response->assertSee(strval(__('app.button_delete')));
        $response->assertSee(strval(__('app.button_change')));
    }

    /**
     * Test Create in LabelController
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->get(route('labels.create'));
        $response->assertDontSee(strval(__('app.button_create')));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('labels.create'));
        $response->assertSee(strval(__('app.button_create')));
    }

    /**
     * Test Store in LabelController
     *
     * @return void
     */
    public function testStore()
    {
        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->post(route('labels.store'), ['_token' => csrf_token(), 'name' => self::TEST_LABEL, 'description' => Str::random(100)]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', ['name' => self::TEST_LABEL]);

        $response = $this->post(route('labels.store'), ['_token' => csrf_token(), 'name' => self::TEST_LABEL, 'description' => Str::random(100)]);
        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    /**
     * Test Edit in LabelController
     *
     * @return void
     */
    public function testEdit()
    {
        $label = Label::first();
        settype($label, 'object');
        $response = $this->get(route('labels.edit', ['label' => $label->id]));
        $response->assertDontSee($label->name);
        $response->assertDontSee(strval(__('app.button_update')));

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->get(route('labels.edit', ['label' => $label->id]));
        $response->assertSee($label->name);
        $response->assertSee(strval(__('app.button_update')));
    }

    /**
     * Test Update in LabelController
     *
     * @return void
     */
    public function testUpdate()
    {
        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $label = Label::first();
        settype($label, 'object');
        Label::create(['name' => self::TEST_LABEL, 'description' => Str::random(100)]);

        $response = $this->patch(route('labels.update', ['label' => $label->id]), ['_token' => csrf_token(), 'name' => self::TEST_LABEL]);
        $response->assertSessionHasErrors();
        $response->assertRedirect();

        $str = Str::random(100);
        $response = $this->patch(route('labels.update', ['label' => $label->id]), ['_token' => csrf_token(), 'name' => $str]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', ['name' => $str]);
    }

    /**
     * Test Destroy in LabelController
     *
     * @return void
     */
    public function testDelete()
    {
        $label = Label::first();
        settype($label, 'object');

        $response = $this->delete(route('labels.destroy', ['label' => $label->id]), ['_token' => csrf_token()]);
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', ['id' => $label->id]);

        $response = $this->post('/login', ['_token' => csrf_token(), 'email' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD]);
        $response->assertRedirect();
        $this->assertAuthenticated();

        $response = $this->delete(route('labels.destroy', ['label' => $label->id]), ['_token' => csrf_token()]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }
}
