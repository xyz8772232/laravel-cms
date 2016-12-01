<?php

namespace App\Http\ViewComposers;

/**
 * Class SidebarComposer
 *
 * @package \App\Http\ViewComposers
 */
class SidebarComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        // Dependencies automatically resolved by service container...
        $this->users = $users;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $sidebar = Menu::get('MyNavBar')->asUl();

        $view->with('sidebar', $sidebar);
    }

}
