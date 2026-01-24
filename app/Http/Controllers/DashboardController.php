<?php

namespace App\Http\Controllers;

use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\GetRoute;
use Inertia\Inertia;
use Inertia\Response;

#[RequestAttribute]
class DashboardController extends BaseController
{

    #[GetRoute('/dashboard')]
    public function edit(): Response
    {
        return Inertia::render('dashboard');
    }

}
