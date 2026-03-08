<?php

namespace Tests;

use App\Common\Providers\AutoBindServiceProvider;

class TestAutoBind extends TestCase
{

    public function test_bind()
    {
        $provider = new AutoBindServiceProvider(app());
        var_dump($provider->getBoundClasses());
    }

}