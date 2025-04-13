<?php

namespace Xin\OpenAI;

use OpenAI\Client;
use OpenAI\Contracts\ClientContract;
use Illuminate\Support\ServiceProvider;

class OpenAIServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(ClientContract::class, function () {
            return OpenAIClient::getClient();
        });

        $this->app->alias(ClientContract::class, 'openai');
        $this->app->alias(ClientContract::class, Client::class);
    }


    /**
     * Get the services provided by the provider.
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