<?php

namespace App;

use Closure;
use Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Route;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Layout\Content as AdminContent;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

/**
 * Class Admin.
 */
class Admin
{
    /**
     * @var array
     */
    public static $script = [];

    /**
     * @var array
     */
    public static $css = [];

    /**
     * @var array
     */
    public static $js = [];

    /**
     * @var bool
     */
    protected static $initialized = false;

    /**
     * @var bool
     */
    protected static $bootstrapped = false;

    /**
     * Initialize.
     */
    public static function init()
    {
        if (!static::$initialized) {
            Form::registerBuiltinFields();

            static::$initialized = true;
        }
    }

    /**
     * Bootstrap.
     */
    public static function bootstrap()
    {
        if (!static::$bootstrapped) {
            if (file_exists($bootstrap = admin_path('bootstrap.php'))) {
                require $bootstrap;
            }

            static::$bootstrapped = true;
        }
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return Grid
     */
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return Form
     */
    public function form($model, Closure $callable)
    {
        static::init();
        static::bootstrap();

        return new Form($this->getModel($model), $callable);
    }

    /**
     * Build a tree.
     *
     * @param $model
     *
     * @return Tree
     */
    public function tree($model)
    {
        return new Tree($this->getModel($model));
    }

    /**
     * @param Closure $callable
     *
     * @return AdminContent
     */
    public function content(Closure $callable)
    {
        static::init();
        static::bootstrap();

        Form::collectFieldAssets();

        return new AdminContent($callable);
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public function getModel($model)
    {
        if ($model instanceof EloquentModel) {
            return $model;
        }

        if (is_string($model) && class_exists($model)) {
            return $this->getModel(new $model());
        }

        throw new InvalidArgumentException("$model is not a valid model");
    }

    /**
     * Get namespace of controllers.
     *
     * @return string
     */
    public function controllerNamespace()
    {
        $directory = config('admin.directory');

        return 'App\\'.ucfirst(basename($directory)).'\\Controllers';
    }

    /**
     * Add css or get all css.
     *
     * @param null $css
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function css($css = null)
    {
        if (!is_null($css)) {
            self::$css = array_merge(self::$css, (array) $css);

            return;
        }

        $css = array_get(Form::collectFieldAssets(), 'css', []);

        static::$css = array_merge(static::$css, $css);

        return view('admin::partials.css', ['css' => array_unique(static::$css)]);
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function js($js = null)
    {
        if (!is_null($js)) {
            self::$js = array_merge(self::$js, (array) $js);

            return;
        }

        $js = array_get(Form::collectFieldAssets(), 'js', []);

        static::$js = array_merge(static::$js, $js);

        return view('admin::partials.js', ['js' => array_unique(static::$js)]);
    }

    /**
     * @param string $script
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function script($script = '')
    {
        if (!empty($script)) {
            self::$script = array_merge(self::$script, (array) $script);

            return;
        }

        return view('admin::partials.script', ['script' => array_unique(self::$script)]);
    }

    /**
     * Admin url.
     *
     * @param $url
     *
     * @return string
     */
    public static function url($url)
    {

        $prefix = (string) config('admin.prefix');

        if (empty($prefix) || $prefix == '/') {
            return '/'.trim($url, '/');
        }

        return "/$prefix/".trim($url, '/');
    }

    /**
     * Left sider-bar menu.
     *
     * @return array
     */
    public static function menu()
    {
        $menu = Menu::toTree();
        $newsMenu = Channel::menu();
        $menu[config('admin.news_column.order')]['children'] = $newsMenu;
        return $menu;
        return Cache::remember('admin_menu', 1, function () {
            $menu = Menu::toTree();
            $newsMenu = Channel::menu();
            $menu[config('admin.news_column.order')]['children'] = $newsMenu;
            return $menu;
        });
    }


    /**
     * Get admin title.
     *
     * @return Config
     */
    public static function title()
    {
        return config('admin.title');
    }

    /**
     * @return mixed
     */
    public static function user()
    {
        return Auth::guard('admin')->user();
    }

    public static function activeSidebar()
    {
        $menuUri = '';
        $uri = Route::current()->uri();
        if (Str::startsWith($uri, config('admin.prefix'))) {
            $menuUri = Str::replaceFirst(config('admin.prefix').'/','', $uri);
        }

        if ($menuUri == 'articles') {
            $channel_id = Input::get('channel_id', 1);
            $channelIds = Channel::parentIds($channel_id);
            return ['channel' => $channelIds];
        } else {
            $menu = Menu::where('uri', $menuUri)->first();
            if ($menu) {
                return ['menu' => array_merge(static::ParentIds($menu->id), [$menu->id])];
            }
        }
        return [];
    }

    private static function ParentIds($id, &$parentIds = [])
    {
        $menu = Menu::find($id);
        if (empty($menu)) {
            return $parentIds;
        }
        $parent_id = $menu['parent_id'];
        if ($parent_id) {
            $parentIds[] = $parent_id;
            static::ParentIds($parent_id, $parentIds);
        }
        return $parentIds;
    }
}
