<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Config;

class HealthController extends Controller
{
    public static function check_env(){
        if (\file_exists("/.env")){
            return true;
        } else {
            return false;
        }
    }
    public static function check_db(){
        $db = DB::connection()->getPdo();
        return $db;
    }
    public static function check_elastic(){
        $elastic_config = new \stdClass();
        $elastic_config->ip = config::get("elastic.server.ip");
        $elastic_config->port = config::get("elastic.server.port");
        $elastic_config->k_ip = config::get("elastic.kibana.ip");
        $elastic_config->k_port = config::get("elastic.kibana.port");
        $elastic_config->content_ip = config::get("elastic.content_server.ip");
        $elastic_config->content_port = config::get("elastic.content_server.port");
        return $elastic_config;
    }
}
