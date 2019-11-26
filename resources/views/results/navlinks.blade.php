<?php
$counts = Session::get('item_counts');
?>
<div class="navlinks">
    <p><a href="/current_query">Current JSON Query</a><span> | </span><a href="/test">Current Raw Results ({{$counts['total']}})</a><span> | </span><a href="/">Advanced Search</a></p>
</div>