<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Contact;
use App\Models\Todo;
use App\Models\User;
use App\Policies\TodoPolicy;
use App\Providers\{
    Guard\TokenGuard,
    User\SimpleUserProvider
};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Todo::class => TodoPolicy::class
    ];

    protected $gatesPost = [
        'get-contact', 'create-contact', 'update-contact', 'delete-contact'
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::extend('token', function (Application $app, string $name, array $config) {
            $tokenGuard = new TokenGuard(
                Auth::createUserProvider($config['provider']),
                $app->make(Request::class)
            );
            app()->refresh('request', $tokenGuard, 'setRequest');
            return $tokenGuard;
        });

        Auth::provider('simple', function (Application $app, array $config) {
            return new SimpleUserProvider();
        });

        // foreach ($this->gatesPost as $gate) {
        //     Gate::define($gate, function (User $user, Contact $contact) {
        //         return $user->id === $contact->user->id
        //             ? Response::allow()
        //             : Response::denyWithStatus(301)->deny('kamu tidak bisa');
        //     });
        // }

        Gate::define('get-contact', function (User $user, Contact $contact) {
            return $user->id === $contact->user_id;
        })
        ->define('create-contact', function (User $user, Contact $contact) {
            return $user->id === $contact->user_id;
        })
        ->define('update-contact', function (User $user, Contact $contact) {
            return $user->id === $contact->user_id;
        })
        ->define('delete-contact', function (User $user) {
            return $user->name == 'james'
                    ? Response::allow()
                    : Response::deny('anda tidak memiliki akses untuk menghapus data tersebut')->code(301);
        });
    }
}
