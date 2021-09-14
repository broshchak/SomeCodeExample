<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            if ($event->job->resolveName() == 'App\Jobs\SomeJob') {
                $payload = $event->job->payload();
                $job_data = unserialize($payload['data']['command']);
                var_dump($job_data->some_useful_data);
                var_dump($event->job->getName());
                var_dump($event->job->resolveName());
                var_dump($event->job->getConnectionName());
                var_dump($event->job->getQueue());
                var_dump($event->job->getRawBody());
            }
        });

        Queue::after(function (JobProcessed $event) {
            if ($event->job->resolveName() == 'App\Jobs\SomeJob') {
                $payload = $event->job->payload();
                $job_data = unserialize($payload['data']['command']);
                var_dump($job_data->some_useful_data);
            }
        });
    }
}
