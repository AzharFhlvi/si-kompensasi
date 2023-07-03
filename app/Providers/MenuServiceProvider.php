<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson);
    $menuJson = file_get_contents(base_path('resources/menu/menu.json'));
    $menuData = json_decode($menuJson);

    // Share all menuData to all the views
    \View::share('menuData', [$verticalMenuData]);
    \View::share('menuBar', [$menuData]);


  }
}
