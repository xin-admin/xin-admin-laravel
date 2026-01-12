<?php

namespace App\Http\Controllers;

use App\Support\Trait\RequestJson;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use RequestJson;

    protected string $repository;
}
