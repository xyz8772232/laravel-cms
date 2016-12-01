@define($channel_active = !empty($active_sidebar['channel']))
<aside class="skykiwi-main-sidebar clearfix">
    <div class="sidebar-l nav-fold-animation">
        <div class="sidebar-unfold" id="eSideBar"><i class="fa fa-chevron-circle-left "></i></div>
        <ul class="menu-1" id="eMenu1">
            @foreach($menu as $item)
                @if(Admin::user()->visible($item['roles']))
                    @define($channel_sidebar = $item['title'] == config('admin.news_column.name'))
                    <li data-index="{{$loop->index}}" class="menu-item e-select
                  @include('admin.partials.active', ['grade' => 0, ])">
                        @if (!empty($item['uri']))
                            <a class="menu-a" href="{{App\Admin::url($item['uri'])}}">
                                <i class="menu-icon fa {{$item['icon']}}"></i>
                                <span class="menu-text">{{$item['title']}}</span>
                            </a>
                        @else
                            <i class="menu-icon fa {{$item['icon']}}"></i>
                            <span class="menu-text">{{$item['title']}}</span>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
    <div class="sidebar-r" id="eMenu2">
        @foreach($menu as $grade1)
            @if(Admin::user()->visible($grade1['roles']) && !empty($grade1['children']))
                @define($channel_sidebar = $grade1['title'] == config('admin.news_column.name'))
                <ul class="menu-2
            @include('admin.partials.active', ['grade' => 1])">
                    @foreach($grade1['children'] as $grade2)
                        @if (!empty($grade2['children']))
                            <li class="menu-item e-select
                    @include('admin.partials.active', ['grade' => 1])">
                                <i class="menu-icon fa fa-angle-right"></i>
                                <span class="menu-text">{{$grade2['title']}}</span>
                                <ul class="menu-3"
                                        @include('admin.partials.active', ['grade' => 3, 'active_class' => 'style="display: block"'])>
                                    @foreach($grade2['children'] as $grade3)
                                        <li class="menu-item
                                @include('admin.partials.active', ['grade' => 4,])">
                                            <a class="menu-text" href="{{App\Admin::url($grade3['uri'])}}">{{$grade3['title']}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="menu-item
                    @include('admin.partials.active', ['grade' => 3, 'active_class' => 'selected'])">
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
        @endforeach
    </div>
</aside>
