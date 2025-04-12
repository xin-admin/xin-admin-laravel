<?php

namespace Xin\OpenAI;

use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use OpenAI;
use OpenAI\Client;
use OpenAI\Contracts\ClientContract;

class OpenAIClient extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(ClientContract::class, static function (): Client {
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
        });

        $this->app->alias(ClientContract::class, 'openai');
        $this->app->alias(ClientContract::class, Client::class);
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            Client::class,
            ClientContract::class,
            'openai',
        ];
    }

}