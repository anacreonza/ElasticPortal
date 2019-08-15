@extends('site')
<?php 

$query = json_encode($query_string, JSON_PRETTY_PRINT);
?>
<h2>Current Query:</h2>
<pre>
{{$query}}
</pre>