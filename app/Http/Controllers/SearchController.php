<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Elasticsearch\ClientBuilder;
use DOMDocument;
use App\User;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Client;

class SearchController extends Controller
{
    protected $elasticsearch;
    
    public function __construct() {
        # Build URL for Elastic server from config
        $server_address = Config::get('elastic.server.ip');
        $server_port = Config::get('elastic.server.port');
        $hosts = [
            $server_address . ":" . $server_port
        ];
        $this->site_check($server_address, $server_port);
        $this->elasticsearch = ClientBuilder::create()->setHosts($hosts)->build();
        # Need to use this $elasticsearch object's methods like get().
        $this->meta_keys = Config::get('meta_mappings.keys');
        CookieController::initialise_cookie();
    }
    public function site_check($ip, $port){
        //Check if Elastic site is responding.
        $timeout_secs = 30;
        if($socket =@ fsockopen($ip, $port, $errno, $errstr, $timeout_secs)) {
            fclose($socket);
            return;
        } else {
            $errorstring = "Error: $errno - $errstr. Elastic server at $ip:$port failed to respond within the timeout limit ($timeout_secs seconds). Please check server address settings in config/elastic.php.";
            die($errorstring);
        }
    }

    public static function prepare_image_metadata($hit){
        $image_meta = [];
        #die(dd($hit));
        $image_meta['loid'] = $hit['_source']['REF'];
        $image_meta['archive'] = $hit['_source']['ARCHIVE'];
        $image_meta['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
        if (isset($hit['_score'])){
            $image_meta['score'] = $hit['_score'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS']['NEWSPAPER'])){
            $image_meta['publication'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS']['NEWSPAPER'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTYPE'])){
            $image_meta['type'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTYPE'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'])) {
            $image_meta['source'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'])){
            $image_meta['keywords'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'])){
            $image_meta['date'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'];
        }
        if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
            $image_meta['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PAPER']['PUB_CAPTION'])){
            $image_meta['caption'] = $hit['_source']['ATTRIBUTES']['METADATA']['PAPER']['PUB_CAPTION'];        
        } elseif (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_CAPTION'])) {
            $image_meta['caption'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_CAPTION'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PAPER']['CUSTOM_SOURCE'])){
            $image_meta['source'] = $hit['_source']['ATTRIBUTES']['METADATA']['PAPER']['CUSTOM_SOURCE'];        
        } elseif (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'])) {
            $image_meta['source'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS'])){
            $publications = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS'];
            $image_meta['publication'] = SearchController::prepare_pubstring($publications);
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PAPER']['DOCAUTHOR'])){
            $image_meta['author'] = $hit['_source']['ATTRIBUTES']['METADATA']['PAPER']['DOCAUTHOR'];        
        } elseif (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'])) {
            $image_meta['author'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['INFOIMAGE']['COPYRIGHT_NOTICE'])){
            $image_meta['copyright'] = $hit['_source']['ATTRIBUTES']['METADATA']['INFOIMAGE']['COPYRIGHT_NOTICE'];        
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'])){
            if (isset($image_meta['caption']) && $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'] != $image_meta['caption']){
                $image_meta['keywords'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'];        
            }
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS']['NEWSPAPER'])){
            $image_meta['newspapers'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS']['NEWSPAPER'];
        }
        if (isset($hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['WIDTH'])){
            $image_meta['width'] = $hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['WIDTH'];
        }
        if (isset($hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['HEIGHT'])){
            $image_meta['height'] = $hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['HEIGHT'];
        }
        if (isset($hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['COLORTYPE'])){
            $image_meta['colourspace'] = $hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['COLORTYPE'];
        }
        if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'])){
            $date = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'];
            $image_meta['date'] = date("d-M-Y", strtotime($date));
        }
        return $image_meta;
    }

    public static function prepare_results(){

        //Pick out the pieces of the results that we need.

        $results = Session::get('results');
        $hits = $results['hits'];
        $hits = $hits['hits'];

        $display_array = [];

        foreach ($hits as $hit){
            $display_item = array();
            # Deal with the different types of items:
            $type = $hit['_source']['OBJECTINFO']['TYPE'];
            if (strpos($type, 'Image')){
                $image = SearchController::prepare_image_metadata($hit);
                array_push($display_array, $image);
            } elseif (strpos($type, 'Story')){
                $story['loid'] = $hit['_source']['REF'];
                $story['score'] = $hit['_score'];
                $story['archive'] = $hit['_source']['ARCHIVE'];
                $story['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'])){
                    $story['source'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'];
                }
                if (isset($hit['_source']['CONTENT']['XMLFLAT'])){
                    $story['content'] = $hit['_source']['CONTENT']['XMLFLAT'];
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'])){
                    $story['author'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'];
                } 
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'])){
                    $story['keywords'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'];
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTITLE'])){
                    $story['title'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTITLE'];
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CATEGORY'])){
                    $story['category'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CATEGORY'];
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION'])){
                    
                    $date = new Carbon($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION']);
                    $story['date'] = date("d-M-Y", strtotime($date));
                }
                if (isset($hit['highlight'])){
                    $story['highlight'] = $hit['highlight'];
                }
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $story['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                } else {
                    $story['path'] = "No path info.";
                }

                array_push($display_array, $story);
            } elseif (strpos($type, 'PDF')){
                $pdf['score'] = $hit['_score'];
                $pdf['loid'] = $hit['_source']['REF'];
                $pdf['archive'] = $hit['_source']['ARCHIVE'];
                $pdf['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $pdf['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTYPE'])){
                    $pdf['type'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTYPE'];
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION'])){
                    $pubdate = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION'];
                    $pdf['pubdate'] = date("d-M-Y", strtotime($pubdate));
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'])){
                    $pdf['publication'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'];        
                }

                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['SECTION'])){
                    $pdf['section'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['SECTION'];
                }

                if (isset($hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['DOCUMENTINFO']['AUTHOR'])){
                    $pdf['author'] = $hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['DOCUMENTINFO']['AUTHOR'];
                }

                if ($pdf){
                    array_push($display_array, $pdf);
                }
            } else {
                $other_docs['loid'] = $hit['_source']['REF'];
                $other_docs['score'] = $hit['_score'];
                $other_docs['archive'] = $hit['_source']['ARCHIVE'];
                $other_docs['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $other_docs['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                }
                if (isset($hit['_source']['OBJECTINFO']['NAME'])){
                    $other_docs['name'] = $hit['_source']['OBJECTINFO']['NAME'];
                }
                if (isset($hit['_source']['SYSTEM']['OBJECTTYPE'])){
                    $other_docs['object_type'] = $hit['_source']['SYSTEM']['OBJECTTYPE'];
                }
                if (isset($hit['highlight'])){
                    $other_docs['highlight'] = $hit['highlight'];
                }
                if (isset($hit['_source']['CONTENT']['TEXT'])){
                    $html = $hit['_source']['CONTENT']['TEXT'];
                    $other_docs['preview'] = SearchController::prepare_aspseek_html($html);
                }
                if ($other_docs){
                    array_push($display_array, $other_docs);
                }
            }
        }

        $loids = Array();

        foreach ($display_array as $item){
            array_push($loids, $item['loid']);
        }
        
        Session::put('loids', $loids);

        return $display_array;
    }

    public static function var_dump2($info){
        echo('<pre>');
        var_dump($info);
        echo('</pre>');
    }

    public static function get_next_and_previous_loid($loid){
        $surrounging_loids = [];
        $surrounging_loids['current'] = $loid;
        $loids = Session::get('loids');
        $current_loid_index = array_search($loid, $loids);
        $results_per_page = UserController::get_user_prefs()->results_per_page;
        if (isset($loids[$current_loid_index + 1])){
            $next_loid = $loids[$current_loid_index + 1];
            $surrounging_loids['next'] = $next_loid;
        }

        if ($current_loid_index != 0){
            $previous_loid = $loids[$current_loid_index - 1];
            $surrounging_loids['previous'] = $previous_loid;
        }
        return $surrounging_loids;
    }

    public function get_indices(){

        // Try to get the indices from the session or a cookie before falling back on doing the slow aggs search.

        $cached_indices = Session::get('indices');
        if (isset($cached_indices)){
            $indices_array = $cached_indices;
            return $indices_array;
        } elseif (isset($_COOKIES['Indices'])){
            $indices_json = $_COOKIE['Indices'];
            $indices_array = \json_decode($indices_json);
            return $indices_array;
        }
        $server_address = Config::get('elastic.server.ip');
        $server_port = Config::get('elastic.server.port');
        $status_json_url = "http://" . $server_address . ":" . $server_port . '/_cat/indices?format=json';
        $status_json = file_get_contents($status_json_url);
        $status = json_decode($status_json);
        sort($status);
        $indices = count($status);
        $indices_array = [];
        $selected_indices = Config::get('elastic.selected_indices');
        foreach ($status as $item){
            if (in_array($item->index, $selected_indices)){
                array_push($indices_array, $item->index);
            }
        }
        $indices_json = \json_encode($indices_array);
        Session::put('indices', $indices_array);
        setcookie('Indices', $indices_json, time()+(3600*12)); //3600 = 1 hour
        if ($indices_array){
            return $indices_array;
        } else {
            die("No indices");
        }
    }

    public function advanced_search_form(){
        
        $data['indices'] = $this->get_indices();
        $data['publications'] = $this->get_publications();
        $data['types'] =  $this->get_types();
        $data['authors'] =  $this->get_authors();
        $data['categories'] = $this->get_categories();
        return view('advanced_search')->with('data', $data);

    }

    public function get_types(){
        // Retrieve document types array from config. Use a static list because the types don't change that much.
        $doctype_array = Config::get("meta_mappings.doc_types");
        $doctype_keys = array_keys($doctype_array);
        $doctypes_json = \json_encode($doctype_keys);
        Session::put('types', $doctype_keys);
        setcookie('Types', $doctypes_json, time()+(3600*12));
        return $doctype_keys;
    }

    public function get_publications(){
        
        $cached_publications = Session::get('publications');
        
        if (isset($cached_publications)){
            return $cached_publications;
        } elseif (isset($_COOKIE['Publications'])){
            $pub_list_json = $_COOKIE['Publications'];
            if ($pub_list_json){
                $pub_list = \json_decode($pub_list_json);
                return $pub_list;
            }
        }

        $params = [
            'body' => [
                'aggs' => [
                    'publications' => [
                        'terms' => [
                            'field' => $this->meta_keys['product'],
                            'size' => 60
                        ]
                    ]
                ]
            ]
        ];
        $pubs = $this->elasticsearch->search($params);
        $publications = $pubs['aggregations']['publications']['buckets'];
        $pub_list[] = 'All';
        foreach ($publications as $publication){
            $pub_list[] = $publication['key'];
        }
        $pub_list_json = \json_encode($pub_list);
        Session::put('publications', $pub_list);
        setcookie('Publications', $pub_list_json, time()+(3600*12));
        return $pub_list;
    }

    public function get_authors(){
        
        if($cached_authors = Session::get('authors')){
            return $cached_authors;
        } elseif (isset($_COOKIE['Authors'])){
            $author_list_json = $_COOKIE['Authors'];
            if ($author_list_json){
                $author_list = \json_decode($author_list_json);
                return $author_list;
            }
        }
        $params = [
            'body' => [
                'aggs' => [
                    'authors' => [
                        'terms' => [
                            'field' => $this->meta_keys['docauthor'],
                            'size' => 60
                        ]
                    ]
                ]
            ]
        ];
        $auths = $this->elasticsearch->search($params);
        $authors = $auths['aggregations']['authors']['buckets'];
        $author_list[] = 'All';
        foreach ($authors as $author){
            $author_list[] = $author['key'];
        }
        $author_list_json = \json_encode($author_list);
        Session::put('authors', $author_list);
        setcookie('Authors', $author_list_json, time()+(3600*12));
        return $author_list;
    }

    public function get_categories(){
        
        $cached_categories = Session::get('categories');
        if (isset($cached_categories)){
            return $cached_categories;
        } elseif (isset($_COOKIE['Categories'])){
            $cat_list_json = $_COOKIE['Categories'];
            $cat_list = \json_decode($cat_list_json);
            return $cat_list;
        }

        $params = [
            'body' => [
                'aggs' => [
                    'categories' => [
                        'terms' => [
                            'field' => $this->meta_keys['category'],
                            'size' => 20
                        ]
                    ]
                ]
            ]
        ];

        $cats = $this->elasticsearch->search($params);
        $categories = $cats['aggregations']['categories']['buckets'];


        $cat_list[] = 'All';
        
        foreach ($categories as $category){
            // $cat = [];
            // $cat['name'] = $category['key'];
            // $cat['count'] = $category['doc_count'];
            $cat_list[] = $category['key'];
            // $total_doc_count += $category['doc_count'];
        }
        // $all = ['name' => 'All', 'count' => $total_doc_count];
        // $cat_list[] = $all;
        $cat_list = array_sort($cat_list);

        $cat_list_json = \json_encode($cat_list);
        setcookie('Categories', $cat_list_json, time()+(3600*12));
        return $cat_list;
    }

    function get_doctypes(){
        $params = [
            'body' => [
                'aggs' => [
                    'document_types' => [
                        'terms' => [
                            'field' => $this->meta_keys['doctype_exact'],
                            'size' => 12
                        ]
                    ]
                ]
            ]
        ];

        $doc_types = $this->elasticsearch->search($params);
        return $doc_types;
    }

    public function get_stats(){
        $params = [
            // 'index' => '/',
            'body' => [
                'aggs' => [
                    'Publications' => [
                        'terms' => [
                            'field' => $this->meta_keys['product'],
                            'size' => 60
                        ]
                    ]
                ]
            ]
        ];
        $publication_counts = $this->elasticsearch->search($params);


        $categories = $this->get_categories();
        $doc_types = $this->get_doctypes();

        $data['publication_counts'] = $publication_counts;
        $data['doc_types'] = $doc_types;
        $data['categories'] = $categories;

        return view('stats')->with('data', $data);
    }

    public function classify_results($results){
        // Classify document types
        $counts = array();
        $counts['stories'] = 0;
        $counts['images'] = 0;
        $counts['other_docs'] = 0;

        $buckets = $results['aggregations']['doctypes']['buckets'];
        #dd($buckets);
        foreach ($buckets as $bucket) {
            switch ($bucket['key']) {
                case 'Image':
                    $amt = $bucket['doc_count'];
                    $counts['images'] += $amt;
                    break;
                case 'EOM::Story':
                    $amt = $bucket['doc_count'];
                    $counts['stories'] += $amt;
                    break;
                default:
                    $counts['other_docs'] += $bucket['doc_count'];
                    break;
            }
        }
        $counts['total'] = $counts['stories']+$counts['images']+$counts['other_docs'];

        #dd($counts);
        Session::put('item_counts', $counts);
        return $counts;
    }

    public static function run_search($terms){

        $query = SearchController::build_query($terms);

        # Put the search terms in the session
        Session::put('terms', $terms);

        # Put the query into the session:
        $query_json = json_encode($query, JSON_PRETTY_PRINT);
        Session::put('query_json', $query_json);

        #die(SearchController::var_dump2($query_json));

        # Build URL for Elastic server from config
        $server_address = Config::get('elastic.server.ip');
        $server_port = Config::get('elastic.server.port');
        $hosts = [
            $server_address . ":" . $server_port
        ];
        $elasticsearch = ClientBuilder::create()->setHosts($hosts)->build();

        $results = $elasticsearch->search($query);

        # Place the search results into the session.
        Session::put('results', $results);

        # Log the search
        SearchController::write_to_log($terms);

        return $results;
    }

    public static function write_to_log($terms){
        $user = Auth::user();
        if (isset($user)){
            $username = $user->name;
        } else {
            $username = "Anonymous user";
        }

        $user_ip = \request()->ip(); // Grab the user's IP address from the request.

        $user_agent = \request()->server('HTTP_USER_AGENT');

        if (!isset($terms['text'])){
            $terms['text'] = "No text search (all files).";
        }

        $message = "Username: " . $username . ", IP: " . $user_ip . ", UserAgent: " . $user_agent . ", Index: " . $terms['index'] . ", Text: " . $terms['text'];
        Log::info($message);
    }

    public static function build_query($terms){

        # Build up query from form terms
        $meta_keys = Config::get('meta_mappings.keys');
        $filters = [];

        // If no special criteria are selected then do a match all query. Otherwise select one of the criteria as a must clause and the rest as filter clauses.

        // Converted all searches to multi_match as it's better than just simple query_string. Need to add the fields for images to the fields list however.
        # Build query clause
        function build_query_clause($terms){

            $searchable_fields = [
                'CONTENT.*',
                'SYSATTRIBUTES.PROPS.SUMMARY^2', # Boost up summary for articles.
                'SYSTEM.ALERTPATH',
                'OBJECTINFO.NAME',
                'ATTRIBUTES.METADATA.PAPER.PUB_CAPTION',
                'ATTRIBUTES.METADATA.GENERAL.DOCAUTHOR',
                'ATTRIBUTES.METADATA.INFOIMAGE.COPYRIGHT_NOTICE',
                'ATTRIBUTES.METADATA.GENERAL.DOCKEYWORD^2' # Boost up keywords for images.
            ];
            
            // Cater for when user does not narrow search down at all.
            if ( $terms['match'] == 'alldocs' && 
                $terms['type'] == 'All' && 
                $terms['publication'] == 'All' && 
                $terms['category'] == 'All' && 
                ($terms['startdate'] == '' || $terms['enddate'] == ''))
                {
                $query_clause = [ 'match_all' => new \stdClass() ];
                return $query_clause;
            }
            function prepare_allwords_text_string($terms){
                $text_terms = $terms['text'];
                $text_terms_array = \preg_split("/[\s,]+/", $terms['text']);
                $termcount = count($text_terms_array);
                $all_terms_string = "";
                foreach ($text_terms_array as $term) {
                    $all_terms_string .= " AND " . $term;
                }
                $all_terms_string = \substr($all_terms_string, 5);
                return $all_terms_string;
            }
            function build_allwords_query_clause($terms, $searchable_fields){
                // Need to add fields for image search to multi-match
                $query_clause = [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'query' => prepare_allwords_text_string($terms),
                                'fuzziness' => 0,
                                'type' => 'best_fields',
                                'fields' => $searchable_fields
                            ]
                        ]                          
                    ]
                ];
                // Add filter if necessary.
                if (build_filter_clause($terms)){
                    $query_clause['bool']['filter'] = build_filter_clause($terms);
                }
                return $query_clause;
            }
            function build_phrase_query_clause($terms, $searchable_fields){
                $query_clause = [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'query' => $terms['text'],
                                'type' => 'phrase',
                                'fields' => $searchable_fields
                            ]
                        ]
                    ]
                ];
                // Add filter if necessary.
                if (build_filter_clause($terms)){
                    $query_clause['bool']['filter'] = [
                        (build_filter_clause($terms))
                    ];
                }
                return $query_clause;
            }
            function build_anytext_query_clause($terms, $searchable_fields){
                $query_clause = [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'query' => $terms['text'],
                                'fuzziness' => 'AUTO',
                                'type' => 'best_fields',
                                'fields' => $searchable_fields
                            ]
                        ]
                    ]
                ];
                // Add filter if there needs to be one.
                if (build_filter_clause($terms)){
                    $query_clause['bool']['filter'] = [
                        (build_filter_clause($terms))
                    ];
                }
                return $query_clause;
            }
            function build_alldocs_query_clause($terms){
                // In an alldocs search you can just add each criterion to its own match clause.
                // need to cater for each filter individually
                $meta_keys = Config::get('meta_mappings.keys');

                $query_clause = [];
                // Type filter
                if($terms['type'] != 'All'){
                    $key = $meta_keys['doctype'];
                    if ($terms['type'] == "Articles"){
                        $selected_types = Config::get('meta_mappings.doc_types.Articles');
                    } elseif ($terms['type'] == "Images"){
                        $selected_types = Config::get('meta_mappings.doc_types.Images');
                    } else {
                        $selected_types = Config::get('meta_mappings.doc_types.Other Documents');
                    }
                    
                    $selected_types_search_string = implode(" OR ", $selected_types);

                    $query_clause['bool']['must'][] = [
                        'match' => [
                            $key => $selected_types_search_string
                        ]
                    ];
                }
                // Publication filter
                if($terms['publication'] != 'All'){
                    $key = $meta_keys['product'];
                    $value = $terms['publication'];
                    $query_clause['bool']['must'][] = [
                        'match' => [
                            $key => $value
                        ]
                    ];
                }
                // Category filter
                if($terms['category'] != 'All'){
                    $key = $meta_keys['category'];
                    $value = $terms['category'];
                    $query_clause['bool']['must'][] = [
                        'match' => [
                            $key => $value
                        ]
                    ];
                }
                // Date filter
                if ($terms['startdate'] != '' || $terms['enddate'] != ''){
                    if ($terms['startdate'] == ''){
                        $terms['startdate'] = '1970-01-01';
                    }
                    if ($terms['enddate'] == ''){
                        $terms['enddate'] = 'now';
                    }
                    $query_clause['bool']['must'][] = [
                        'range' => [
                            $meta_keys['issuedate'] => [
                                'gte' => $terms['startdate'],
                                'lte' => $terms['enddate']
                            ]
                        ]
                    ];
                }

                // die(SearchController::var_dump2($query_clause));
                return $query_clause;
            }
            # High level logic:
            switch ($terms['match']) {
            case 'allwords':
                $query_clause = build_allwords_query_clause($terms, $searchable_fields);
                break;
            
            case 'phrase':
                $query_clause = build_phrase_query_clause($terms, $searchable_fields);
                break;
            
            case 'any':
                $query_clause = build_anytext_query_clause($terms, $searchable_fields);
                break;
            
            case 'alldocs':
                $query_clause = build_alldocs_query_clause($terms);
                break;
            
            default:
                die("Unknown match option");
                break;
            }
            #$query_clause = null;
            return $query_clause;
        }
        function build_filter_clause($terms){
            $meta_keys = Config::get('meta_mappings.keys');
            if ($terms['type'] != 'All'){
                
                if ($terms['type'] == "Articles"){
                    $selected_types = Config::get('meta_mappings.doc_types.Articles');
                } elseif ($terms['type'] == "Images"){
                    $selected_types = Config::get('meta_mappings.doc_types.Images');
                } else {
                    $selected_types = Config::get('meta_mappings.doc_types.Other Documents');
                }
                // Use terms rather than term - so we can use an array of terms.
                $type_filter = [
                    "terms" => [
                        $meta_keys['doctype'] => $selected_types
                    ]
                ];
            }
     
            if ($terms['publication'] != 'All'){
                $publication_filter = [
                    "term" => [
                        $meta_keys['product'] => $terms['publication']
                    ]
                ];
            }
    
            if ($terms['category'] != 'All'){
                $category_filter = [
                    "term" => [
                        $meta_keys['category'] => $terms['category']
                    ]
                ];
            }
    
            if ($terms['startdate'] != '' || $terms['enddate'] != ''){
                if ($terms['startdate'] == ''){
                    $terms['startdate'] = '1970-01-01';
                }
                if ($terms['enddate'] == ''){
                    $terms['enddate'] = 'now';
                }
                $date_range_filter = [
                    "range" => [
                        $meta_keys['issuedate'] => [
                            'gte' => $terms['startdate'],
                            'lte' => $terms['enddate']
                        ]
                    ]
                ];
            }

            if (isset($type_filter)) {
                $filter_clause[] = $type_filter;
            }
            if (isset($publication_filter)){
                $filter_clause[] = $publication_filter;
            }
            if (isset($category_filter)){
                $filter_clause[] = $category_filter;
            }
            if (isset($date_range_filter)){
                $filter_clause[] = $date_range_filter;
            }
            if (isset($filter_clause)) {
                #die(SearchController::var_dump2(\json_encode($filter_clause, JSON_PRETTY_PRINT)));
                return $filter_clause;
            } else {
                return false;
            }
        }
        // Pagination stuff
        if (isset($terms['from'])){
            $from = $terms['from'];
        } else {
            $from = 0;
        }

        // Sorting order
        switch ($terms['sort-by']) {
            case 'date':
                $sort_string = 'OBJECTINFO.CREATED';
                $sort_order = 'desc';
                break;
            case 'score':
                $sort_string = '_score';
                $sort_order = 'desc';
                break;
            case 'size':
                $sort_string = 'OBJECTINFO.SIZE';
                $sort_order = 'desc';
                break;
            
            default:
                # code...
                break;
        }

        // Build up the final query object
        $query = [
            'index' => $terms['index'],
            'from' => $from,
            'body' => [
                'query' => build_query_clause($terms),
                // 'size' => $resultsamount,
                'sort' => [
                    $sort_string => $sort_order
                ],
                'highlight' => [
                    'pre_tags' => "<span class='highlighted-text'>",
                    'post_tags' => "</span>",
                    'fields' => [
                        'CONTENT.XMLFLAT' => new \stdClass() # Needs empty object - won't accept empty array.
                    ],
                    'fragment_size' => 300
                ],
                'size' => $terms['size'],
                // 'min_score' => 10
            ]
        ];

        if(isset($terms['aggs'])){
            $query['body']['aggs'] = $terms['aggs'];
        }

        Session::put('query_string', $query);

        $json_query = json_encode($query, JSON_PRETTY_PRINT);
        return $query;
    }
    public function basic_search(Request $request){
        return view('basic_search');
    }

    public function do_advanced_search(Request $request){
        
        # Get all the terms from the form

        $terms = array();
        $terms['match'] = $_GET['match'];
        if ($terms['match'] != 'alldocs'){
            $terms['text'] = $_GET['text'];
            $terms['text'] = trim($terms['text']); //Remove whitespace to prevent crashes when doing all words search.
        }
        $terms['index'] = $_GET['archive'];
        $terms['publication'] = $_GET['publication'];
        $terms['sort-by'] = $_GET['sort-by'];
        $terms['startdate'] = $_GET['startdate'];
        $terms['enddate'] = $_GET['enddate'];
        $terms['size'] = $_GET['size'];
        $terms['author'] = $_GET['author'];
        $terms['category'] = $_GET['category'];
        $terms['type'] = $_GET['type'];

        # Validate them
        
        if ($terms['match'] != 'alldocs'){
            $request->validate([
                'text' => 'required'
            ]);
        }

        if ($terms['enddate'] != ''){
            $validated_data = $request->validate([
                'enddate' => 'before_or_equal:now',
                'startdate' => 'required'
            ]);
        }
        if ($terms['startdate'] != ''){
            $validated_data = $request->validate([
                'startdate' => 'before_or_equal:enddate',
                'enddate' => 'required'
            ]);
        }

        # Send the search terms to the searcher to get the aggregations first

        $terms['aggs'] = [
            "doctypes" => [
                "terms" => [
                    "field" => 'SYSTEM.OBJECTTYPE',
                    "size" => 20
                ]
            ]
        ];
        $results = SearchController::run_search($terms);

        #die(dd($results));
        # Then return the view - the view itself will trigger the actual content search.

        Session::put('total_hits', $results['hits']['total']);

        $doctype_counts = $this->classify_results($results);

        Session::put('selected_match_option', $terms['match']);

        Session::put('selected_sorting', $terms['sort-by']);

        if ($terms['publication'] != 'All') {
            Session::put('selected_pub', $terms['publication']);
        } else {
            Session::put('selected_pub', '');
        }

        if ($terms['category'] != 'All'){
            Session::put('selected_category', $terms['category']);
        } else {
            Session::put('selected_category', '');
        }

        if ($terms['startdate'] != '1970-01-01'){
            Session::put('selected_startdate', $terms['startdate']);
        } else {
            Session::put('selected_startdate', '');
        }
        if ($terms['enddate'] != 'now'){
            Session::put('selected_enddate', $terms['enddate']);
        } else {
            Session::put('selected_enddate', '');
        }

        if ($terms['type'] != 'All'){
            Session::put('selected_type' , $terms['type']);
        } else {
            Session::put('selected_type', '');
        }
        if ($doctype_counts['stories'] > 0){
            return redirect('/results/stories/1');
        } elseif ($doctype_counts['images'] > 0) {
            return redirect('/results/images/1');
        } elseif ($doctype_counts['other_docs'] > 0) {
            return redirect('/results/other_docs/1');
        } else {
            return view('errors.noresults');
        }
    }

    public function elasticsearchTest() {

        # query_string searches all fields by default.
        $query_json = '{"query": { "query_string": {"query": "boxing"} },"size": 1000}';
        $params = [
            'index' => 'published@methcarch_eomjse11_arch',
            'body' => $query_json
        ];
            $response = $this->elasticsearch->search($params);
        dump($response);
    }

    public static function prepare_pubstring($publications){

        $pub_string = '';

        if (is_array($publications)){
            $pub_array = [];
            foreach ($publications as $pub){
                if (isset($pub['NEWSPAPER'])){
                    array_push($pub_array, $pub['NEWSPAPER']);
                } else {
                    array_push($pub_array, $pub);
                }
            }
            $pub_array = array_unique($pub_array);
            $pub_string = $pub_array[0];
            // $pub_string = implode(", ", $pub_array);
            
        } else {
            $pub_string = $publications;
        }

        return $pub_string;
    }

    public function prepare_story_data($loid){
        $content_server_ip = Config::get('elastic.content_server.ip');
        $content_server_port = Config::get('elastic.content_server.port');
        $content_server_url = 'http://' . $content_server_ip . ':' . $content_server_port;

        $metadata = $this->get_meta_one($loid);

        $story_data = [];
        $story_data['loid'] = $loid;
        $story_data['path'] = $metadata['_source']['SYSTEM']['ALERTPATH'];
        
        $story_data['url'] = $content_server_url . $story_data['path'];
        $story_data['index'] = $metadata['_index'];
        $story_data['filename'] = $metadata['_source']['OBJECTINFO']['NAME'];
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS'])){
            $publications = $metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS'];
            $story_data['publication'] = SearchController::prepare_pubstring($publications);
        }
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTYPE'])){
            $story_data['type'] = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTYPE'];
        }
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PAGEREFERENCE'])){
            $story_data['pageref'] = $metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PAGEREFERENCE'];
        }
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'])){
            $date = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'];
            $story_data['createddate'] = date("d-M-Y", strtotime($date));
        }
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CATEGORY'])){
            $story_data['category'] = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CATEGORY'];
        }
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION'])){
            $pubdate = $metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION'];
            $story_data['pubdate'] = date("d-M-Y", strtotime($pubdate));
        }
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'])){
            $story_data['author'] = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'];
        }

        $encodedurl = rawurlencode($story_data['url']);
        $fixed_slashes = \str_replace("%2F", "/", $encodedurl);
        $fixed_url = \str_replace("%3A", ":", $fixed_slashes);

        // Need to remove illegal XML tag from file or DOMDocument has conniption fits.

        $illegal_tag = "<?xml-formTemplate /SysConfig/Templates/Son/Daily/story_brief.xpt?>";
        $story_raw = $this->get_raw_story($fixed_url);
        $story_raw = \str_replace('xml-formTemplate', 'formTemplate', $story_raw);
    
        $story_obj = new \DOMDocument();
        $story_obj->loadXML($story_raw);

        $doc_content = $this->prepare_story_html($story_obj);

        $story_data['content'] = $doc_content;
        // Just get the raw text out of the index.
        // $story_data['content'] = $metadata['_source']['CONTENT']['XMLFLAT'];
        return $story_data;
        
    }
    function prepare_story_html($xml){

        // Run an XSLT transform to convert the XML to HTML.

        $xsl = new \DOMDocument();
        $xsl->load('xslt/ArchContentHTML.xsl');
        $xslproc = new \XSLTProcessor();
        $xsl = $xslproc->importStylesheet($xsl);
        $xslproc->setParameter(null, "", "");
        $html = $xslproc->transformToDoc($xml);
        $htmlstring = $html->saveHTML();
        # Replace incorrect bullet
        $htmlstring = \str_replace('<p>)', '<p>&bull; ', $htmlstring);
        return $htmlstring;
    }

    function get_raw_story($url){
        $client = new Client([
            'auth' => [Config::get('elastic.content_server.username'), Config::get('elastic.content_server.password')],
            'timeout' => 15.0,
        ]);
        $response = $client->get($url);
        $body = $response->getBody();
        return $body;
    }

    public static function get_image($url){
        $client = new Client([
            'auth' => [Config::get('elastic.content_server.username'), Config::get('elastic.content_server.password')],
            'timeout' => 15.0,
        ]);
        $response = $client->get($url);
        $data = $response->getBody()->getContents();
        $image_data = "data:image/png;base64," . base64_encode($data);
        return $image_data;
    }

    public static function prepare_aspseek_html($html){

        $bodystart = strpos($html, '<body>');

        $body = \substr($html, $bodystart+6);
        
        $body = \str_replace('<iframe height="12%" width="98%" src="/disclaimer.html"></iframe>', '', $body);
        $body = \str_replace('</body>', '', $body);
        $body = \str_replace('</html>', '', $body);

        # No preview for articles with tables - avoids all the issues that creates.
        if (strpos($body, '<table')){
            $body = null;
            return $body;
        }

        $body = \substr($body, 0, 650) . '...';

        return $body;
    }

    public function get_image_meta($loid){
        $images_server_ip = Config::get('elastic.content_server.ip');
        $images_server_port = Config::get('elastic.content_server.port');
        $images_server_url = 'http://' . $images_server_ip . ':' . $images_server_port;
        
        $metadata = $this->get_meta_one($loid);

        if ($metadata == false){
            die('Unable to retrieve metadata.');
        }

        $image_data = SearchController::prepare_image_metadata($metadata);
        $image_data['url'] = $images_server_url . $image_data['path'];

        return $image_data;
    }

    public function get_meta_one($loid){
        // Build up a one-time query to retrieve one item by its ID/LOID
        $query_string = Session::get('query_string');
        if (!isset($query_string)){
            return false;
        }
        $query = [
            'query' => [
                'term' => [
                    '_id' => [
                        'value' => $loid
                    ]
                ]
            ]
        ];
        $query_json = json_encode($query);
        $params = [
            'index' => $query_string['index'],
            'body' => $query_json
        ];
        $result = $this->elasticsearch->search($params);
        $metadata = $result['hits']['hits'][0];
        return $metadata;
    }

    public function meta_dump($loid){
        $metadata = SearchController::get_meta_one($loid);
        return view('current_meta')->with('metadata', $metadata);
    }

    public function show_imageviewer($loid){
        $terms = Session::get('terms');
        if (!isset($terms)){
            return redirect('/')->withErrors('Unable to retrieve search terms, probably due to an expired session. Please run your search again.');
        }
        $metadata = $this->get_image_meta($loid);
        $preview_data = SearchController::get_image($metadata['url'] . "?f=image_lowres");
        return view('results.imageviewer')->with('metadata', $metadata)->with('preview', $preview_data);
    }

    public function show_storyviewer($loid){
        $terms = Session::get('terms');
        if (!isset($terms)){
            return redirect('/')->withErrors('Unable to retrieve search terms, probably due to an expired session. Please run your search again.');
        }
        $story = $this->prepare_story_data($loid);
        return view('results.storyviewer')->with('story', $story);
    }

    public function search_all(){
        $terms = $_GET['terms'];

        # Place the search terms into the session:
        Session::put('terms', $terms);
        $query_json = '{
            "query": { "query_string": {"query": "'. $terms .'"} },
            "size": 30
        }';
        $params = [
            'index' => 'published@methcarch_eomjse11_arch',
            'body' => $query_json
        ];
        $results = $this->elasticsearch->search($params);

        # Place the search results into a session.
        Session::put('results', $results);
        return redirect('/results/images');
    }

}
