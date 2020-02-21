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
                <i class="fas fa-question-circle big-icons"></i>
            </div>
            <div class="item-description">Last Query</div>
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
</div>