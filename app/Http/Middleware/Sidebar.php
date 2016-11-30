<?php

namespace App\Http\Middleware;

use App\Admin;
use Closure;
use Menu;

class Sidebar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Menu::make('menu-1', function($menu){

//            dd($menu);
            $data = Admin::menu();
//            dd($data);
            collect($data)->each(function($item) use($menu) {
                $icon = '<i class="menu-icon fa '.$item['icon'].'"></i>';
                $title = '<span class="menu-text">'.$item['title'].'</span>';
                if (!empty($item['url'])) {
                    $menu->add(Admin::url($item['url']), ['class' => 'menu-item'])->prepend($icon.$title);
                } else {
                    $menu->raw('', ['class' => 'menu-item'])->prepend($icon.$title);
                }

                if (!empty($item['children'])) {
                    $name = 'menu-2';
                    $children = $item['children'];
                    Menu::make($name, function($menu) use($children) {
                        collect($children)->each(function($item) use($menu) {

                        });
                    });

                } else {
                    $menu->add('', ['class' => 'menu-item'])->prepend($icon.$title);

                }
            });
        });
        //dd(Menu::get('menu-1')->asUl(['class' => 'menu-1']));
        return $next($request);
    }
}
