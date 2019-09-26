<?php
    $kibana_url = Config::get('elastic.kibana.ip');
    $kibana_port = Config::get('elastic.kibana.port');
?>
<a href="/">Advanced Search Page</a> | <a href="/users">User Index</a> | <a href="http://{{$kibana_url}}:{{$kibana_port}}">Kibana</a> | <a href="/stats">Stats</a> | <a href="/status">Index Status</a> | <a href="/phpinfo">PHPInfo</a>