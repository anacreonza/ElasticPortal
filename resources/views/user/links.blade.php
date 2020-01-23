<?php
    $kibana_url = Config::get('elastic.kibana.ip');
    $kibana_port = Config::get('elastic.kibana.port');
?>
<div class="control-panel">
    <div class="control-panel-item">
        <a href="/">
            <div class="icon-block">
                <i class="fa fa-search big-icons" aria-hidden="true"></i>
            </div>
            <div class="item-description">Advanced Search</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="/user/prefs">
            <div class="icon-block">
                <i class="fa fa-user big-icons" aria-hidden="true"></i>
            </div>
            <div class="item-description">My Preferences</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="/current_query/">
            <div class="icon-block">
                <i class="fa fa-users big-icons" aria-hidden="true"></i>
            </div>
            <div class="item-description">Last Query</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="/users/">
            <div class="icon-block">
                <i class="fa fa-users big-icons" aria-hidden="true"></i>
            </div>
            <div class="item-description">User Index</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="http://{{$kibana_url}}:{{$kibana_port}}" target="_blank">
            <div class="icon-block">
                <img src="/logos/elastic-kibana-logo-png-transparent.png" alt="Kibana Logo" width="80">
            </div>
            <div class="item-description">Kibana</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="http://152.111.20.157:5601/app/kibana#/dashboard/35a10dd0-c59f-11e9-b3cc-0503c261002a?embed=true&_g=(refreshInterval%3A(display%3AOff%2Cpause%3A!f%2Cvalue%3A0)%2Ctime%3A(from%3Anow-2y%2Cmode%3Aquick%2Cto%3Anow))" target="_blank">
            <div class="icon-block">
                <img src="/logos/elastic-kibana-logo-png-transparent.png" alt="Kibana Logo" width="80">
            </div>
            <div class="item-description">Stats Dashboard</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="/status">
            <div class="icon-block">
                <i class="fa fa-users big-icons" aria-hidden="true"></i>
            </div>
            <div class="item-description">Index Status</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="/phpinfo">
            <div class="icon-block">
                <img src="https://www.php.net/images/logos/php-logo-bigger.png" alt="PHP Logo" width="80">
            </div>
            <div class="item-description">PHPInfo</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="/clear_cookies">
            <div class="icon-block">
                <i class="fa fa-eraser big-icons"></i>
            </div>
            <div class="item-description">Clear Cookies</div>
        </a>
    </div>
    <div class="control-panel-item">
        <a href="/admin/logs">
            <div class="icon-block">
                <i class="fas fa-binoculars"></i>
            </div>
            <div class="item-description">Log Viewer</div>
        </a>
    </div>
</div>