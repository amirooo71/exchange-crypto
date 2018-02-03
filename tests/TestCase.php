<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function JWTSignIn()
    {
        Passport::actingAs(
            factory('App\User')->create(),
            ['create-servers']
        );
    }
}
