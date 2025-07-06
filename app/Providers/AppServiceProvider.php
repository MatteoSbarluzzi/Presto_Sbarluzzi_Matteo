<?php

namespace App\Providers;


use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

 
    // Controllo se la tabella categories esiste, se si, condivido la variabile categories con tutte le view
    public function boot(): void
    {
        Paginator::useBootstrap();// Per usare Bootstrap al posto di TailwindCSS, quella di default

        if (Schema::hasTable('categories')){
            View::share('categories', Category::all());
        }
    }
}
