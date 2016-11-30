<aside class="skykiwi-main-sidebar clearfix">
  <div class="sidebar-l nav-fold-animation">
    <div class="sidebar-unfold" id="eSideBar"><i class="fa fa-chevron-circle-left "></i></div>
    <ul class="menu-1">
      @foreach(App\Admin::menu() as $item)
      @if ($loop->index == 2)
        <li class="menu-item selected">
      @else
        <li class="menu-item">
      @endif
         <i class="menu-icon fa {{$item['icon']}}"></i>
         <span class="menu-text">{{$item['title']}}</span>
        </li>
      @endforeach
    </ul>
  </div>
  <div class="sidebar-r">
    <ul class="menu-2">
      <li class="menu-item selected">
        <span class="menu-text e-fold"></span>
        <ul class="menu-3">
          <li class="menu-item selected">
            <a class="menu-text" href=""></a>
          </li>
          <li class="menu-item">
            <a class="menu-text" href=""></a>
          </li>
        </ul>
      </li>
      <li class="menu-item">
        <a class="menu-text" href=""></a>
      </li>
    </ul>
  </div>
</aside>
