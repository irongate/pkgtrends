<?php

namespace IronGate\Pkgtrends\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Cloud\BigQuery\BigQueryClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->singleton(BigQueryClient::class, function () {
            return new BigQueryClient([
                'projectId'   => 'package-trends',
                'keyFilePath' => storage_path('creds/google-bigquery.json'),
            ]);
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Register all the package sources as singletons
        foreach ((array)config('app.sources') as $provider) {
            $this->app->singleton($provider, function () use ($provider) {
                return new $provider;
            });
        }
    }
}
