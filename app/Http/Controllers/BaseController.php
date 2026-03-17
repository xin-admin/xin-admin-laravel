<?php

namespace App\Http\Controllers;

use App\Trait\RequestJson;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use RequestJson;


}
