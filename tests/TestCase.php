<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;


abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // change database to test
        config(['database.default' => 'testSqlite']);

        // run migration with seed
        $this->artisan('migrate:fresh --seed');

        $user = User::factory()->createOne()->first();

        $this->actingAs($user);
    }
}
