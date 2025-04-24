<?php

require '../vendor/autoload.php';

$openapi = \OpenApi\Generator::scan(['../app']);

echo $openapi->toJson();
