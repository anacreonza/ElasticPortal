@if (!isset($meta['highlight']))
    <div class="metadata-preview-block-item"><span class="item-label">Filename: </span>{{$meta['filename']}}</div>
@endif
@if (isset($meta['name']))
    <div class="metadata-preview-block-item"><span class="item-label">Highlights: </span>{{$meta['name']}}</div>
@endif
@if (isset($meta['score']))
    <div class="metadata-preview-block-item"><span class="item-label">Score: </span>{{$meta['score']}}</div>
@endif
@if (isset($meta['publication']))
    <div class="metadata-preview-block-item"><span class="item-label">Publication: </span>{{$meta['publication']}}</div>
@endif
@if (isset($meta['source']))
    <div class="metadata-preview-block-item"><span class="item-label">Source: </span>{{$meta['source']}}</div>
@endif
@if (isset($meta['author']))
    <div class="metadata-preview-block-item"><span class="item-label">Author: </span>{{$meta['author']}}</div>
@endif
@if (isset($meta['date']))
    <div class="metadata-preview-block-item"><span class="item-label">Date: </span>{{$meta['date']}}</div>
@endif
