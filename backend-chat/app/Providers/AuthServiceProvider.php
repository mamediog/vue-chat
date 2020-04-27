<?php

namespace App\Providers;

use App\Models\Client;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $key = $request->header('Authorization');
                $client = Client::where('api_key', $key)->first();

                if (!empty($client)) {
                    $request->request->add(['userid' => $client->_id]);
                }
                return $client;
            }
        });
    }
}
