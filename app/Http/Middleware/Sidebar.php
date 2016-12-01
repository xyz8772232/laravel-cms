<?php

namespace App\Http\Middleware;

use App\Admin;
use Closure;
use Menu;
use View;

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
        $sidebarInfo = Admin::menu();
        $activeSidebar = Admin::activeSidebar();
        $user = Admin::user();

        Menu::make('menu-1', function($menu) use($user, $sidebarInfo, $activeSidebar) {
            $activeType = !empty($activeSidebar['channel']) ? 'channel' : 'menu';
            foreach ($sidebarInfo as $key => $item) {
                $sidebarType = $item['title'] == config('admin.news_column.name') ? 'channel' : 'menu';
                $icon = '<i class="menu-icon fa '.$item['icon'].'"></i>';
                $title = '<span class="menu-text">'.$item['title'].'</span>';
                if ($user->visible($item['roles'])) {
                    if(empty($item['uri'])) {
                        $li = $menu->raw('');
                    } else {
                        $li = $menu->add('', ltrim(Admin::url($item['uri']), '/'));
                        $li->link->attr(['class' => 'menu-a']);
                    }
                    $li->attr(['class' => 'menu-item e-select', 'data-index' => $key]);
                    $li->prepend($icon.$title);
                    if ((($activeType ==  'channel') && ($sidebarType == 'channel')) || ($sidebarType == 'menu' && in_array($item['id'],$activeSidebar['menu']))) {
                        $li->active();
                    }
                }
            }
        });

        $sidebar['menu-1'] = Menu::get('menu-1')->asUl(['class' => 'menu-1', 'id' => 'eMenu1']);

        foreach ($sidebarInfo as $key => $grade1)
        {
            if (!empty($grade1['children']) && $user->visible($grade1['roles'])) {
                $allGrade2 = $grade1['children'];
                $activeType = !empty($activeSidebar['channel']) ? 'channel' : 'menu';
                $sidebarType = $grade1['title'] == config('admin.news_column.name') ? 'channel' : 'menu';
                Menu::make('menu-2'.$key, function($menu) use($grade1, $allGrade2, $activeType, $sidebarType, $activeSidebar) {
                    $icon = '<i class="menu-icon fa fa-angle-right"></i>';
                    foreach ($allGrade2 as $grade2) {
                        $title = '<span class="menu-text">'.$grade2['title'].'</span>';
                        if (!empty($grade2['children'])) {
                            $li = $menu->raw('');
                            $li->attr(['class' => 'menu-item e-select']);

                            if ($activeType == 'channel') {
                                if (static::active($grade2['channel_id'], $activeSidebar['channel'], $activeType, $sidebarType)) {
                                    $li->active();
                                }
                            } else {
                                if (isset($grade2['id']) && static::active($grade2['id'], $activeSidebar['menu'], $activeType, $sidebarType)) {
                                    $li->active();
                                }
                            }
                            $li->prepend($icon.$title);
                            foreach($grade2['children'] as $grade3) {
                                $li3 = $li->add($grade3['title'], ltrim(Admin::url($grade3['uri']), '/'));
                                $li3->attr(['class' => 'menu-item']);
                                $li3->link->attr(['class' => 'menu-text']);
                                if ($activeType == 'channel') {
                                    if (static::active($grade3['channel_id'], $activeSidebar['channel'], $activeType, $sidebarType)) {
                                        $li3->active();
                                    }
                                } else {
                                    if (isset($grade3['id']) && static::active($grade3['id'], $activeSidebar['menu'], $activeType, $sidebarType)) {
                                        $li3->active();
                                    }
                                }

                            }
                        } else {
                            //添加li
                            if (empty($grade2['uri'])) {
                                $li = $menu->raw($grade2['title']);
                            } else {
                                $li = $menu->add($grade2['title'], ltrim(Admin::url($grade2['uri']), '/'));
                                $li->link->attr(['class' => 'menu-text']);
                            }

                            //处理属性
                            $li->attr(['class' => 'menu-item']);
                            if ($activeType == 'channel') {
                                if (static::active($grade2['channel_id'], $activeSidebar['channel'], $activeType, $sidebarType)) {
                                    $li->attr(['class' => 'selected']);
                                }
                            } else {
                                if (isset($grade2['id']) && static::active($grade2['id'], $activeSidebar['menu'], $activeType, $sidebarType)) {
                                    $li->attr(['class' => 'selected']);
                                }
                            }
                        }
                    }
                });

                if (($activeType == 'channel' &&  $sidebarType == 'channel') || ($activeType == 'menu'  && in_array($grade1['id'], $activeSidebar['menu']))){
                    $active = true;
                }
            } else {
                Menu::make('menu-2'.$key, function($menu) {});
                //dd($result);
            }

            $ulClass = !empty($active) ? 'menu-2 active' : 'menu-2';
            //dump(Menu::get('menu-2'.$key));
            $sidebar['menu-2'][] =  Menu::get('menu-2'.$key)->asUl(['class' => $ulClass]);

        }

        //dd($sidebar);
        //View::share('sidebar', $sidebar);
        View::composer(['admin.partials.sidebar', 'admin.partials.sidebar1'], function($view) use ($sidebar){
            $view->with('sidebar', $sidebar);
        });
        return $next($request);
    }

    private static function active($id, $tree, $activeType, $sidebarType)
    {
        if (($activeType == $sidebarType) && in_array($id, $tree)) {
            return true;
        }
        return false;
    }
}
