<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\GraphQL\Client;

class GraphQLServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\GraphQL\Client', function() {
            return new Client(config('services.arbor.endpoint'), [
                'Authorization' => 'Basic '.base64_encode(config('services.arbor.username').':'.config('services.arbor.password')),
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
