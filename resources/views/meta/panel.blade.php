<aside class="card">
    <div class="card-header">
        Metadata
    </div>
    <div class="card-body">
        <div class="meta-grid">
            @if (isset($meta['loid']))
            <div class="meta-table-div"><span class="item-label">LOID: </span></div>
            <div>{{$meta['loid']}}</div>
            @endif
    
            @if (isset($meta['index']))
            <div class="meta-table-div"><span class="item-label">Index: </span></div>
            <div>{{$meta['index']}}</div>
            @endif
    
            @if (isset($meta['type']))
            <div class="meta-table-div"><span class="item-label">Type:</span></div>
            <div>{{$meta['type']}}</div>
            @endif
    
            @if (isset($meta['filename']))
            <div class="meta-table-div"><span class="item-label">Filename: </span></div>
            <div>{{wordwrap($meta['filename'], 30, "\n", true)}}</div>
            @endif
    
            @if (isset($meta['publication']))
            <div class="meta-table-div"><span class="item-label">Publication: </span></div>
            <div>{{$meta['publication']}}</div>
            @endif
    
            @if (isset($meta['pageref']))
            <div class="meta-table-div"><span class="item-label">Page Reference: </span></div>
            <div>{{$meta['pageref']}}</div>
            @endif
    
            @if (isset($meta['category']))
            <div class="meta-table-div"><span class="item-label">Category: </span></div>
            <div>{{$meta['category']}}</div>
            @endif
    
            @if (isset($meta['author']))
            <div><span class="item-label">Author: </span></div>
            <div>{{$meta['author']}}</div>
            @endif
    
            @if (isset($meta['pubdate']))
            <div><span class="item-label">Publication Date: </span></div>
            <div>{{$meta['pubdate']}}</div>
            @endif
    
            @if (isset($meta['keywords']))
            <div><span class="item-label">Keywords: </span></div>
            <div>{{$meta['keywords']}}</div>
            @endif
    
            @if (isset($meta['colourspace']))
            <div><span class="item-label">Colour Space: </span></div>
            <div>{{$meta['colourspace']}}</div>
            @endif
    
            @if (isset($meta['caption']))
            <div><span class="item-label">Caption: </span></div>
            <div>{{$meta['caption']}}</div>
            @endif
    
            @if (isset($meta['source']))
            <div><span class="item-label">Source: </span></div>
            <div>{{$meta['source']}}</div>
            @endif
    
            @if (isset($meta['keywords']))
            <div><span class="item-label">Keywords: </span></div>
            <div>{{$meta['keywords']}}</div>
            @endif
    
            @if (isset($meta['copyright']))
            <div><span class="item-label">Copyright: </span></div>
            <div>{{$meta['copyright']}}</div>
            @endif
    
            @if (isset($meta['date']))
            <div><span class="item-label">Date: </span></div>
            <div>{{$meta['date']}}</div>
            @endif
    
            @if (isset($meta['width']) && isset($meta['height']))
            <div><span class="item-label">Size: </span></div>
            <div>{{$meta['width']}} x {{$meta['height']}}</div>
            @endif
        </table>
    </div>
</aside>
