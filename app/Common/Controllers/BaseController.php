<?php

namespace App\Common\Controllers;

use App\Common\Trait\RequestJson;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use RequestJson;


}
