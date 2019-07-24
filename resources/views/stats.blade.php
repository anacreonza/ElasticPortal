@extends('site')

<?php
$stats_json_url = "http://152.111.25.182:9200/_stats?format=json";
$stats_json = file_get_contents($stats_json_url);
$stats = json_decode($stats_json);

dd($stats);
?>