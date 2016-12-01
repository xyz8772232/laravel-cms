<aside class="skykiwi-main-sidebar clearfix">
  <div class="sidebar-l nav-fold-animation">
    <div class="sidebar-unfold" id="eSideBar"><i class="fa fa-chevron-circle-left "></i></div>
    <ul class="menu-1" id="eMenu1">
      @foreach($menu as $item)
            @if(Admin::user()->visible($item['roles']))
                <li class="menu-item e-select
                @if((!empty($active_sidebar['channel']) && $item['title'] == '新闻') || (!empty($active_sidebar['menu']) && $item['title'] != '新闻' && in_array($item['id'], $active_sidebar['menu']))) active @endif
                " data-index="{{$loop->index}}">
              <i class="menu-icon fa {{$item['icon']}}"></i>
              @if (!empty($item['uri']))
                <a class="menu-text" href="{{App\Admin::url($item['uri'])}}">{{$item['title']}}</a>
              @else
                <span class="menu-text">{{$item['title']}}</span>
              @endif
        </li>
            @endif
      @endforeach
    </ul>
  </div>
  <div class="sidebar-r" id="eMenu2">
    @foreach($menu as $grade1)
      @if(Admin::user()->visible($grade1['roles']))
          @if (!empty($grade1['children']))
                <ul class="menu-2
                @if((!empty($active_sidebar['channel']) && $grade1['title'] == '新闻') || (!empty($active_sidebar['menu']) && in_array($grade1['id'], $active_sidebar['menu'])))
                active
                @endif
                ">
                  @foreach($grade1['children'] as $grade2)
                    @if (!empty($grade2['children']))
                      <li class="menu-item e-select
                         @if((!empty($active_sidebar['channel']) && $grade1['title'] == '新闻' && in_array($grade2['channel_id'], $active_sidebar['channel'])) || (($grade1['title'] != '新闻') && !empty($active_sidebar['menu']) && in_array($grade2['id'], $active_sidebar['menu'])))
                          active
                          @endif
                          ">
                        <i class="menu-icon fa fa-angle-right"></i>
                        <span class="menu-text">{{$grade2['title']}}</span>
                        <ul class="menu-3"
                            @if((!empty($active_sidebar['channel']) && $grade1['title'] == '新闻' && in_array($grade2['channel_id'], $active_sidebar['channel'])) || (($grade1['title'] != '新闻') && !empty($active_sidebar['menu']) && in_array($grade2['id'], $active_sidebar['menu'])))
                        style="display: block"
                            @endif
                        >
                          @foreach($grade2['children'] as $grade3)
                            <li class="menu-item
                            @if((!empty($active_sidebar['channel']) && $grade1['title'] == '新闻' && in_array($grade3['channel_id'], $active_sidebar['channel'])) || (($grade1['title'] != '新闻') && !empty($active_sidebar['menu']) && in_array($grade3['id'], $active_sidebar['menu'])))
                                    active
                                    @endif
                                    ">
                              <a class="menu-text" href="{{App\Admin::url($grade3['uri'])}}">{{$grade3['title']}}</a>
                            </li>
                           @endforeach
                        </ul>
                      </li>
                    @else
                      <li class="menu-item">
                          {{--<i class="menu-icon fa fa-angle-right"></i>--}}
                          @if(!empty($grade2['uri']))
                            <a class="menu-text" href="{{App\Admin::url($grade2['uri'])}}">{{$grade2['title']}}</a>
                          @else
                            <div class="menu-text">{{$grade2['title']}}</div>
                          @endif
                      </li>
                    @endif
                  @endforeach
                </ul>
          @else
              <ul class="menu-2"></ul>
          @endif
      @else
          <ul class="menu-2"></ul>
      @endif
      @endforeach
  </div>
</aside>
