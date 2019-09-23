<div class="card">
    <div class="card-header">
        Metadata
    </div>
    <table>
        @if (isset($meta['loid']))
        <tr>
            <td class="meta-table-div"><span class="item-label">LOID: </span></td>
            <td>{{$meta['loid']}}</td>
        </tr>
        @endif

        @if (isset($meta['index']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Index: </span></td>
            <td>{{$meta['index']}}</td>
        </tr>
        @endif

        @if (isset($meta['filename']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Filename: </span></td>
            <td>{{$meta['filename']}}</td>
        </tr>
        @endif

        @if (isset($meta['publication']))
        <tr>
            <td class="meta-table-div"><span class="item-label">Publication: </span></td>
            <td>{{$meta['publication']}}</td>
        </tr>
        @endif

        @if (isset($meta['pageref']))
        <tr>
            <td class="meta-table-div"><span class="item-label">Page Reference: </span></td>
            <td>{{$meta['pageref']}}</td>
        </tr>
        @endif

        @if (isset($meta['category']))
        <tr>
            <td class="meta-table-div"><span class="item-label">Category: </span></td>
            <td>{{$meta['category']}}</td>
        </tr>
        @endif

        @if (isset($meta['author']))
        <tr>
            <td class="meta-table-div"><span class="item-label">Author: </span></td>
            <td>{{$meta['author']}}</td>
        </tr>
        @endif

        @if (isset($meta['pubdate']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Publication Date: </span></td>
            <td>{{$meta['pubdate']}}</td>
        </tr>
        @endif

        @if (isset($meta['keywords']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Keywords: </span></td>
            <td>{{$meta['keywords']}}</td>
        </tr>
        @endif

        @if (isset($meta['colourspace']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Colour Space: </span></td>
            <td>{{$meta['colourspace']}}</td>
        </tr>
        @endif

        @if (isset($meta['caption']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Caption: </span></td>
            <td>{{$meta['caption']}}</td>
        </tr>
        @endif

        @if (isset($meta['source']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Source: </span></td>
            <td>{{$meta['source']}}</td>
        </tr>
        @endif

        @if (isset($meta['keywords']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Keywords: </span></td>
            <td>{{$meta['keywords']}}</td>
        </tr>
        @endif

        @if (isset($meta['copyright']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Copyright: </span></td>
            <td>{{$meta['copyright']}}</td>
        </tr>
        @endif

        @if (isset($meta['date']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Date: </span></td>
            <td>{{$meta['date']}}</td>
        </tr>
        @endif

        @if (isset($meta['width']) && isset($meta['height']))
        <tr>
                <td class="meta-table-div"><span class="item-label">Size: </span></td>
            <td>{{$meta['width']}} x {{$meta['height']}}</td>
        </tr>
        @endif


    </table>
</div>
