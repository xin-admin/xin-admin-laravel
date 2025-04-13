<?php

namespace Xin\OpenAI;

use InvalidArgumentException;
use OpenAI;
use OpenAI\Client;

class OpenAIClient
{

    public static function getClient(): Client
    {
        $apiKey = config('openai.api_key');
        $organization = config('openai.organization');
        $baseUri = config('openai.base_uri');
        if (! is_string($apiKey) || ($organization !== null && ! is_string($organization))) {
            throw new InvalidArgumentException('The OpenAIFacades API Key is missing. Please publish the [openai.php] configuration file and set the [api_key].');
        }
        return OpenAI::factory()
            ->withApiKey($apiKey)
            ->withOrganization($organization)
            ->withBaseUri($baseUri)
            ->withHttpClient(new \GuzzleHttp\Client(['timeout' => config('openai.request_timeout', 30), 'verify' => false,]))
            ->make();
    }

}