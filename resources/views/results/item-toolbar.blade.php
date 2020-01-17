<div class="item-toolbar">
    <a href="{{$url}}" target="_blank"><i class="fa fa-download" aria-hidden="true"></a></i>
    <span class="pager-buttons btn-group">
        @if ($type == 'article')
            @if (isset($surrounding_loids['previous']))
                <a href="/storyviewer/{{$surrounding_loids['previous']}}" class="btn btn-outline-primary" role="button">&lt;</a>
            @else
                <a href="/storyviewer/#}}" class="btn btn-outline-primary disabled" role="button">&lt;</a>
            @endif
            @if (isset($surrounding_loids['next']))
                <a href="/storyviewer/{{$surrounding_loids['next']}}" class="btn btn-outline-primary" role="button">&gt;</a>
            @else
                <a href="/storyviewer/#" class="btn btn-outline-primary disabled" role="button">&gt;</a>
            @endif
        @endif
        @if ($type == 'image')
            @if (isset($surrounding_loids['previous']))
                <a href="/imageviewer/{{$surrounding_loids['previous']}}" class="btn btn-outline-primary" role="button">&lt;</a>
            @else
                <a href="/imageviewer/{{$surrounding_loids['current']}}" class="btn btn-outline-primary disabled" role="button">&lt;</a>
            @endif
            @if (isset($surrounding_loids['next']))
                <a href="/imageviewer/{{$surrounding_loids['next']}}" class="btn btn-outline-primary" role="button">&gt;</a>
            @else
                <a href="/imageviewer/#" class="btn btn-outline-primary" role="button">&gt;</a>
            @endif
        @endif
    </span>
</div>