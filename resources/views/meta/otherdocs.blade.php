<div class="metadata-preview-block-multicolumn">
    <div><span class="item-label">LOID: </span>{{$meta['loid']}}</div>
    <div><span class="item-label">Score: </span>{{$meta['score']}}</div>
    @if(isset($meta['publication']))
    <div><span class="item-label">Source: </span>{{$meta['publication']}}</div>
    @endif
    @if(isset($meta['section']))
    <div><span class="item-label">Section: </span> {{$meta['section']}}</div>
    @endif
    @if(isset($meta['author']))
    <div><span class="item-label">Author: </span> {{$meta['author']}}</div>
    @endif
    @if(isset($meta['pubdate']))
    <div><span class="item-label">Publication Date: </span> {{$meta['pubdate']}}</div>
    @endif
</div>
