<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Contacto;
use App\Policies\UserPolicy;
use App\Policies\ContactoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Contacto::class => ContactoPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
	    Gate::define('ver-contacto', function (User $user, Contacto $contacto) {
	        return $user->id === $contacto->owner_id;
	    });
	    Gate::define('edit-contacto', function (User $user, Contacto $contacto) {
	        return $user->id === $contacto->owner_id;
	    });
	    Gate::define('delete-contacto', function (User $user, Contacto $contacto) {
	        return $user->id === $contacto->owner_id;
	    });	   
	    Gate::define('update-contacto', function (User $user, Contacto $contacto) {
	        return $user->id === $contacto->owner_id;
	    });
    }
}
