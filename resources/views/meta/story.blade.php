@if (isset($meta['name']))
    <div><span class="item-label">Highlights: </span>{{$meta['name']}}</div>
@endif
@if (isset($meta['score']))
    <div class="item-label">Score:</div>
    <div>{{$meta['score']}}</div>
@endif
@if (isset($meta['publication']))
    <div class="item-label">Publication:</div>
    <div>{{$meta['publication']}}</div>
@endif
@if (isset($meta['source']))
    <div class="item-label">Source:</div>
    <div>{{$meta['source']}}</div>
@endif
@if (isset($meta['author']))
    <div class="item-label">Author:</div>
    <div>{{$meta['author']}}</div>
@endif
@if (isset($meta['date']))
    <div class="item-label">Date:</div>
    <div>{{$meta['date']}}</div>
@endif
