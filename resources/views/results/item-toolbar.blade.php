<div class="item-toolbar">
    <a href="{{$url}}" target="_blank"><i class="fa fa-download" aria-hidden="true"></a></i>
    <span class="pager-buttons btn-group">
        <a href="/storyviewer/{{Session::get('previous_loid')}}" class="btn btn-outline-primary" role="button">&lt;</a>
        <a href="/storyviewer/{{Session::get('next_loid')}}" class="btn btn-outline-primary" role="button">&gt;</a>
    </span>
</div>