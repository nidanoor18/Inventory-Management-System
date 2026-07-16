<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Policies\CategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\StockMovementPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(StockMovement::class, StockMovementPolicy::class);

        // Convenience gate used in a couple of report endpoints that aren't
        // tied to a specific model instance.
        Gate::define('view-reports', fn ($user) => true);
        Gate::define('manage-users', fn ($user) => $user->isAdmin());
    }
}
