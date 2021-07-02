<aside class="card">
    <div class="card-header">
        Image Info
    </div>
    <div class="card-body">
        {{-- @component('results.item-toolbar', ['type' => 'image', 'url' => $meta['url']])         
        @endcomponent --}}
        <div class="meta-grid">
            @if (isset($meta['loid']))
            <div class="meta-table-div"><div class="metadata-item-label">LOID: </div></div>
            <div>{{$meta['loid']}}</div>
            @endif
    
            @if (isset($meta['index']))
            <div class="meta-table-div"><div class="metadata-item-label">Index: </div></div>
            <div>{{$meta['index']}}</div>
            @endif
    
            @if (isset($meta['type']))
            <div class="meta-table-div"><div class="metadata-item-label">Type:</div></div>
            <div>{{$meta['type']}}</div>
            @endif
    
            @if (isset($meta['filename']))
            <div class="meta-table-div"><div class="metadata-item-label">Filename: </div></div>
            <div>{{wordwrap($meta['filename'], 30, "\n", true)}}</div>
            @endif
    
            @if (isset($meta['publication']))
            <div class="meta-table-div"><div class="metadata-item-label">Publication: </div></div>
            <div>
                @if (is_array($meta['publication']))
                    @foreach ($meta['publication'] as $pub) {{$pub . ' '}} @endforeach
                @else
                    {{$meta['publication']}}
                @endif
            </div>
            @endif
    
            @if (isset($meta['pageref']))
            <div class="meta-table-div"><div class="metadata-item-label">Page Reference: </div></div>
            <div>{{$meta['pageref']}}</div>
            @endif
    
            @if (isset($meta['category']))
            <div class="meta-table-div"><div class="metadata-item-label">Category: </div></div>
            <div>{{$meta['category']}}</div>
            @endif
    
            @if (isset($meta['author']))
            <div><div class="metadata-item-label">Author: </div></div>
            <div>{{$meta['author']}}</div>
            @endif
    
            @if (isset($meta['pubdate']))
            <div><div class="metadata-item-label">Publication Date: </div></div>
            <div>{{$meta['pubdate']}}</div>
            @endif
    
            @if (isset($meta['keywords']))
            <div><div class="metadata-item-label">Keywords: </div></div>
            <div>{{$meta['keywords']}}</div>
            @endif
    
            @if (isset($meta['colourspace']))
            <div><div class="metadata-item-label">Colour Space: </div></div>
            <div>{{$meta['colourspace']}}</div>
            @endif
    
            @if (isset($meta['caption']))
            <div><div class="metadata-item-label">Caption: </div></div>
            <div>{{$meta['caption']}}</div>
            @endif
    
            @if (isset($meta['source']))
            <div><div class="metadata-item-label">Source: </div></div>
            <div>{{$meta['source']}}</div>
            @endif
    
            @if (isset($meta['keywords']))
            <div><div class="metadata-item-label">Keywords: </div></div>
            <div>{{$meta['keywords']}}</div>
            @endif
    
            @if (isset($meta['copyright']))
            <div><div class="metadata-item-label">Copyright: </div></div>
            <div>{{$meta['copyright']}}</div>
            @endif
    
            @if (isset($meta['date']))
            <div><div class="metadata-item-label">Date: </div></div>
            <div>{{$meta['date']}}</div>
            @endif
    
            @if (isset($meta['width']) && isset($meta['height']))
            <div><div class="metadata-item-label">Size: </div></div>
            <div>{{$meta['width']}} x {{$meta['height']}}</div>
            @endif
        </table>
    </div>
</aside>
