@if ($grade == 0)
    @if ($channel_sidebar && $channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar))
            {!! $active_class or 'active' !!}
        @endif
    @elseif(!$channel_sidebar && !$channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar, $item['id'], $active_sidebar['menu']))
            {!! $active_class or 'active' !!}
        @endif
    @endif

@elseif ($grade == 1)
    @if ($channel_sidebar && $channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar))
            {!! $active_class or 'active' !!}
        @endif
    @elseif(!$channel_sidebar && !$channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar, $grade1['id'], $active_sidebar['menu']))
            {!! $active_class or 'active' !!}
        @endif
    @endif

@elseif($grade==2)
    @if ($channel_sidebar && $channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar,  $grade1['channel_id'], $active_sidebar['channel']))
            {!! $active_class or 'active' !!}
        @endif
    @elseif(!$channel_sidebar && !$channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar, $grade1['id'], $active_sidebar['menu']))
            {!! $active_class or 'active' !!}
        @endif
    @endif

@elseif ($grade==3)
    @if ($channel_sidebar && $channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar,  $grade2['channel_id'], $active_sidebar['channel']))
            {!! $active_class or 'active' !!}
        @endif
    @elseif(!$channel_sidebar && !$channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar, $grade2['id'], $active_sidebar['menu']))
            {!! $active_class or 'active' !!}
        @endif
    @endif

@elseif ($grade==4)
    @if ($channel_sidebar && $channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar,  $grade3['channel_id'], $active_sidebar['channel']))
            {!! $active_class or 'active' !!}
        @endif
    @elseif(!$channel_sidebar && !$channel_active)
        @if (\App\Tool::isActive($channel_active, $channel_sidebar, $grade3['id'], $active_sidebar['menu']))
            {!! $active_class or 'active' !!}
        @endif
    @endif

@endif
