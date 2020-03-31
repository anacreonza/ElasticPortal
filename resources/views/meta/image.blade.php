@if (isset($meta['loid']))
    <div class="item-label">LOID:</div>
    <div>{{$meta['loid']}}</div>
@endif
@if (isset($meta['score']))
    <div class="item-label">Score:</div>
    <div>{{$meta['score']}}</div>
@endif
@if (isset($meta['type']))
    <div class="item-label">Type:</div>
    <div>{{$meta['type']}}</div>
@endif
@if (isset($meta['publication']))
    <div class="item-label">Publication:</div>
    <div>{{$meta['publication']}}</div>
@endif
@if (isset($meta['source']))
    <div class="item-label">Source:</div>
    <div>{{$meta['source']}}</div>
@endif
@if (isset($meta['category']))
    <div class="item-label">Category:</div>
    <div>{{$meta['category']}}</div>
@endif
@if (isset($meta['author']))
<div class="item-label">Author:</div>
<div>{{$meta['author']}}</div>
@endif
@if (isset($meta['colourspace']))
<div class="item-label">Colour Space:</div>
<div>{{$meta['colourspace']}}</div>
@endif
@if (isset($meta['copyright']))
<div class="item-label">Copyright:</div>
<div>{{$meta['copyright']}}</div>
@endif
@if (isset($meta['width']) && isset($meta['height']))
<div class="item-label">Size:</div>
<div>{{$meta['width']}} x {{$meta['height']}}</div>
@endif
@if (isset($meta['caption']))
    <div class="item-label">Caption:</div>
    <div>{{$meta['caption']}}</div>
@endif