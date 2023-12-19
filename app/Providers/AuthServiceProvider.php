<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\JobPolicy;
use App\Models\Job;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Job::class => JobPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // passport routes
        Passport::routes();

        // ttl time
        Passport::personalAccessTokensExpireIn(now()->addMinutes(30));

        // scopes
        Passport::tokensCan([
            'all_Permession' => 'all Permession',
            'some_Permession' => 'some Permession',
        ]);

    }
}
