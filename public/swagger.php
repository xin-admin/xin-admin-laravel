<?php

require '../vendor/autoload.php';

$openapi = \OpenApi\Generator::scan(['../app/Http']);

echo $openapi->toJson();
