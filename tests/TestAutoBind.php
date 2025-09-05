<?php

namespace Tests;

use App\Providers\AutoBindServiceProvider;
use Tests\TestCase;

class TestAutoBind extends TestCase
{

    public function test_bind()
    {
        $provider = new AutoBindServiceProvider(app());
        var_dump($provider->getBoundClasses());
    }

}