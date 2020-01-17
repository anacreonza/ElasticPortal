<?php
    $kibana_url = Config::get('elastic.kibana.ip');
    $kibana_port = Config::get('elastic.kibana.port');
?>
<ul>
    <li><a href="/users">User Index</a></li>
    <li><a href="http://{{$kibana_url}}:{{$kibana_port}}" target="_blank">Kibana</a> </li>
    <li><a href="http://152.111.20.157:5601/app/kibana#/dashboard/35a10dd0-c59f-11e9-b3cc-0503c261002a?embed=true&_g=(refreshInterval%3A(display%3AOff%2Cpause%3A!f%2Cvalue%3A0)%2Ctime%3A(from%3Anow-2y%2Cmode%3Aquick%2Cto%3Anow))" target="_blank">Stats</a></li>
    <li><a href="/status">Index Status</a></li>
    <li><a href="/phpinfo">PHPInfo</a></li>
    <li><a href="/clear_cookies">Clear Site Cookies</a></li>
    <li><a href="/admin/logs">Log viewer</a></li>
</ul>