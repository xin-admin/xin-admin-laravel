<?php

namespace App\Controllers;

use App\Support\Trait\RequestJson;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use RequestJson;


}
