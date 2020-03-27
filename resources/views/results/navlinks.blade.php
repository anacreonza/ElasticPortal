<?php
$counts = Session::get('item_counts');
?>
<div class="linkbar">
    <p><a href="/">Search Again</a><span> | </span><a href="/test">Current Raw Results ({{$counts['total']}})</a></p>
</div>