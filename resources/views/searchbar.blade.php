<?php
$status_json_url = "http://152.111.25.182:9200/_cat/indices?format=json";
$status_json = file_get_contents($status_json_url);
$status = json_decode($status_json);
sort($status);

$indices = Session::get('indices');
$searched_terms = Session::get('terms');
$selected_pub = Session::get('selected_pub');
$publications = Config::get('settings.publications');
?>

<nav class="search-bar">
    <form action="/do_advanced_search" method="GET">
        <div class="row">
            <div class="col-sm-6">
                <input style="height:36px; font-size:14pt" type="text" name="text" id="text" class="form-control" autocomplete="off" autofocus value="{{$terms}}"><br>
            </div>
            <div class="col-sm-2">
                <select name="publication" id="publication" class="form-control">
                    @foreach ($publications as $pub)
                    <option value="{{$pub->name}}"
                    @if ($selected_pub == $pub->name)
                        selected                        
                    @endif
                    >{{$pub->nicename}}</option>                        
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <select name="archive" id="archive" class="form-control">
                    @foreach ($indices as $index)
                        <option value="{{$index}}"
                        @if ($selected_archive == $index)
                            selected
                        @endif
                        >{{$index}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-1">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <input type="hidden" name="type" id="type" value="{{$searched_terms["type"]}}">
            <input type="hidden" name="sort-by" id="sort-by" value="{{$searched_terms["sort-by"]}}">
            <input type="hidden" name="startdate" id="startdate" value="{{$searched_terms["startdate"]}}">
            <input type="hidden" name="enddate" id="enddate" value="{{$searched_terms["enddate"]}}">
            <input type="hidden" name="results-amount" id="results-amount" value="{{$searched_terms["results-amount"]}}">
            <input type="hidden" name="show-amount" id="show-amount" value="{{$searched_terms["show-amount"]}}">
            <input type="hidden" name="relevance" id="relevance" value="{{$searched_terms["relevance"]}}">
        </div>
    </form>
</nav>