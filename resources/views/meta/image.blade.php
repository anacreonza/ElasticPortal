<div class="metadata-preview-block">
    <div class="metadata-preview-block-multicolumn">
        @if (isset($meta['loid']))
            <div class="metadata-preview-block-item">
                <span class="item-label">LOID: </span>{{$meta['loid']}}
            </div>
        @endif
    
        @if (isset($meta['score']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Score: </span>{{$meta['score']}}
            </div>
        @endif
        
        @if (isset($meta['type']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Type: </span>{{$meta['type']}}
            </div>
        @endif
        
        @if (isset($meta['publication']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Publication: </span>{{$meta['publication']}}
            </div>
        @endif
        
        @if (isset($meta['source']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Source: </span>{{$meta['source']}}
            </div>
        @endif
        
        @if (isset($meta['category']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Category: </span>{{$meta['category']}}
            </div>
        @endif
        
        @if (isset($meta['author']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Author: </span>{{$meta['author']}}
            </div>
        @endif
        
        @if (isset($meta['colourspace']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Colour Space: </span>{{$meta['colourspace']}}
            </div>
        @endif
        @if (isset($meta['copyright']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Copyright: </span>{{$meta['copyright']}}
            </div>
        @endif
        @if (isset($meta['width']) && isset($meta['height']))
            <div class="metadata-preview-block-item">
                <span class="item-label">Size: </span>{{$meta['width']}} x {{$meta['height']}}
            </div>
        @endif
    </div>
    <div class="metadata-preview-block-singlecolumn">
        @if (isset($meta['caption']))
            <span class="item-label">Caption: </span>{{$meta['caption']}}
        @endif
    </div>
</div>