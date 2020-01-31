<?php
function getDashoboardAdmin($page = 'index')
{

    return 'dashboard::admin/home';
}
function getMenu()
{
    $theme = new App\Modules\Dashboard\Http\Controllers\HomeController();
    $current_theme = $theme->modulos();
    return $current_theme;
}