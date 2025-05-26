<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
        protected User $user;

     protected function setUp(): void
    {
        parent::setUp();

        // Aquí pones el setup común, por ejemplo:
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }
}
