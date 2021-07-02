<div class="metadata-preview-block-multicolumn">
    @if (isset($meta['loid']))
        <div class="metadata-item-label">LOID: </div>
        <div class="metadata-item">{{$meta['loid']}}</div>
    @endif
    
    @if (isset($meta['score']))
        <div class="metadata-item-label">Score: </div>
        <div class="metadata-item">{{$meta['score']}}</div>
    @endif
    
    @if (isset($meta['type']))
        <div class="metadata-item-label">Type: </div>
        <div class="metadata-item">{{$meta['type']}}</div>
    @endif

    {{--
    @if (isset($meta['publication']))
    <div class="metadata-item-label">Publication: </div>
    <div class="metadata-item">{{$meta['publication']}}</div>
    @endif
    --}}
    @if (isset($meta['source']))
        <div class="metadata-item-label">Source: </div>
        <div class="metadata-item">{{$meta['source']}}</div>
    @endif
    
    @if (isset($meta['category']))
        <div class="metadata-item-label">Category: </div>
        <div class="metadata-item">{{$meta['category']}}</div>
    @endif
    
    @if (isset($meta['author']))
        <div class="metadata-item-label">Author: </div>
        <div class="metadata-item">{{$meta['author']}}</div>
    @endif
    
    @if (isset($meta['colourspace']))
        <div class="metadata-item-label">Colour Space: </div>
        <div class="metadata-item">{{$meta['colourspace']}}</div>
    @endif
    @if (isset($meta['copyright']))
        <div class="metadata-item-label">Copyright: </div>
        <div class="metadata-item">{{$meta['copyright']}}</div>
    @endif
    @if (isset($meta['width']) && isset($meta['height']))
        <div class="metadata-item-label">Size: </div>
        <div class="metadata-item">{{$meta['width']}} x {{$meta['height']}}</div>
    @endif
    @if (isset($meta['caption']))
        <div class="metadata-item-label metadata-item-forcefirst">Caption: </div>
        <div class="metadata-text-box">{{$meta['caption']}}</div>
    @endif
</div>
