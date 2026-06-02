<?php

namespace App\Providers;

use App\Models\User;
use App\Models\News;
use App\Models\Tags;
use App\Models\Category;
use App\Policies\UserPolicy;
use App\Policies\NewsPolicy;
use App\Policies\TagsPolicy;
use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        News::class => NewsPolicy::class,
        Tags::class => TagsPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
