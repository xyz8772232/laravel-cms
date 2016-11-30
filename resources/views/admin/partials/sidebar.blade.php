<aside class="skykiwi-main-sidebar clearfix">
  <div class="sidebar-l nav-fold-animation">
    <div class="sidebar-unfold" id="eSideBar"><i class="fa fa-chevron-circle-left "></i></div>
    <ul class="menu-1" id="eMenu1">
      @foreach(App\Admin::menu() as $item)
        <li class="menu-item e-select" data-index="{{$loop->index}}">
          <i class="menu-icon fa {{$item['icon']}}"></i>
          @if (!empty($item['uri']))
            <a class="menu-text" href="{{App\Admin::url($item['uri'])}}">{{$item['title']}}</a>
          @else
            <span class="menu-text">{{$item['title']}}</span>
          @endif
        </li>
      @endforeach
    </ul>
  </div>
  <div class="sidebar-r" id="eMenu2">
    @foreach(App\Admin::menu() as $grade1)
      @if (!empty($grade1['children']))
        <ul class="menu-2">
          @foreach($grade1['children'] as $grade2)
            @if (!empty($grade2['children']))
              <li class="menu-item e-select">
                <i class="menu-icon fa fa-angle-right"></i>
                <span class="menu-text">{{$grade2['title']}}</span>
                <ul class="menu-3">
                  @foreach($grade2['children'] as $grade3)
                    <li class="menu-item">
                      <a class="menu-text" href="{{App\Admin::url($grade3['uri'])}}">{{$grade3['title']}}</a>
                    </li>
                   @endforeach
                </ul>
              </li>
            @else
              <li class="menu-item">
                  <i class="menu-icon fa fa-angle-right"></i>
                  @if(!empty($grade2['uri']))
                    <a class="menu-text" href="{{App\Admin::url($grade2['uri'])}}">{{$grade2['title']}}</a>
                  @else
                    <div class="menu-text">{{$grade2['title']}}</div>
                  @endif
              </li>
            @endif
          @endforeach
        </ul>
      @endif
    @endforeach
  </div>
</aside>
