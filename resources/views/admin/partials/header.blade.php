<!-- Main Header -->
<header class="skykiwi-main-header">
  <div class="logo nav-fold-animation">
    <a href="/{{ trim(config('admin.prefix'), '/') }}">
      <i class="icon logo-icon"></i>
      <i class="icon logo-text"></i>
    </a>
  </div>
  <div class="user">
    <span class="user-name" id="eUser">{{ Admin::user()->name }}<i class="fa fa-caret-down"></i></span>
    <ul class="user-menu">
      <li class="menu-item">
        <a class="menu-a" href="{{ Admin::url('auth/logout') }}">退出</a>
      </li>
    </ul>
  </div>
</header>
