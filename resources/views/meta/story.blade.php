@if (!isset($meta['filename']))
    <div class="metadata-item-label">Filename: </div>
    <div class="metadata-item">{{$meta['filename']}}</div>
@endif
@if (isset($meta['name']))
    <div class="metadata-item-label">Name: </div>
    <div class="metadata-item">{{$meta['name']}}</div>
@endif
@if (isset($meta['score']))
    <div class="metadata-item-label">Score: </div>
    <div class="metadata-item">{{$meta['score']}}</div>
@endif
@if (isset($meta['publication']))
    <div class="metadata-item-label">Publication: </div>
    <div class="metadata-item">{{$meta['publication']}}</div>
@endif
@if (isset($meta['source']))
    <div class="metadata-item-label">Source: </div>
    <div class="metadata-item">{{$meta['source']}}</div>
@endif
@if (isset($meta['author']))
    <div class="metadata-item-label">Author: </div>
    <div class="metadata-item">{{$meta['author']}}</div>
@endif
@if (isset($meta['date']))
    <div class="metadata-item-label">Date: </div>
    <div class="metadata-item">{{$meta['date']}}</div>
@endif
