<div class="metadata-preview-block-multicolumn">
        <div class="metadata-item-label">LOID: </div>
        <div class="metadata-item">{{$meta['loid']}}</div>
        <div class="metadata-item-label">Score: </div>
        <div class="metadata-item">{{$meta['score']}}</div>
    @if(isset($meta['publication']))
        <div class="metadata-item-label">Source: </div>
        <div class="metadata-item">{{$meta['publication']}}</div>
    @endif
    @if(isset($meta['section']))
        <div class="metadata-item-label">Section: </div>
        <div class="metadata-item">{{$meta['section']}}</div>
    @endif
    @if(isset($meta['author']))
        <div class="metadata-item-label">Author: </div>
        <div class="metadata-item">{{$meta['author']}}</div>    
    @endif
    @if(isset($meta['pubdate']))
        <div class="metadata-item-label">Publication Date: </div>
        <div class="metadata-item">{{$meta['pubdate']}}</div>
    @endif
</div>
