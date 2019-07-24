<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Session;
use Carbon\Carbon;
class ResultsObject {

};

/*
archive = _source[ARCHIVE]
filename = _source[NAME]
type = _source[OBJECTINFO][TYPE]
publication = _source[ATTRIBUTES][METADATA][PAPER][CUSTOM_SOURCE]
keywords = _source[ATTRIBUTES][METADATA][PAPER][DOCKEYWORD]
doctype = _source[ATTRIBUTES][METADATA][PAPER][DOCTYPE]
pubdate = _source[ATTRIBUTES][METADATA][PAPER][DATE_CREATED]
itempath = _source[SYSTEM][ALERTPATH]
http://152.111.25.125:4700//Published/2009-2010/Beeld/2009/03/20/Images/B1/Jacob%20Zuma%2023.jpg
http://152.111.25.125:4700/?eomid=1.0.1662258397&fa=lowres&vn=-1&dbid=43
*/
class ResultsController extends Controller
{


    public function index(){
        # Retrieve the search results from the session.
        $output = ResultsController::prepare_results();
        return view('results.images')->with('output', $output);
    }
    
    public function stories(){
        $output = ResultsController::prepare_results();
        return view('results.stories')->with('output', $output);
    }
    public function pdfs(){
        $output = ResultsController::prepare_results();
        return view('results.pdfs')->with('output', $output);
    }
    public function html(){
        $output = ResultsController::prepare_results();
        return view('results.html')->with('output', $output);
    }
}
