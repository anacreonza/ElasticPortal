<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Elasticsearch\ClientBuilder;
use DOMDocument;
use App\User;

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
        $this->elasticsearch = ClientBuilder::create()->setHosts($hosts)->build();
        # Need to use this $elasticsearch object's methods like get().
        $this->meta_keys = Config::get('meta_mappings.keys');

    }
    
    public static function prepare_results(){

        //Pick out the pieces fo teh results that we need.

        $results = Session::get('results');
        $hits = $results['hits'];
        $hits = $hits['hits'];
        $display_array = array();

        foreach ($hits as $hit){
            $display_item = array();
            # Deal with the different types of items:
            $type = $hit['_source']['OBJECTINFO']['TYPE'];
            if (strpos($type, 'Image')){
                $image['score'] = $hit['_score'];
                $image['loid'] = $hit['_source']['REF'];
                $image['archive'] = $hit['_source']['ARCHIVE'];
                $image['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS']['NEWSPAPER'])){
                    $image['publication'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS']['NEWSPAPER'];
                } else {
                    $image['publicaton'] = "No publication info.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'])) {
                    $image['source'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'];
                } else {
                    $image['source'] = "No source info.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'])){
                    $image['keywords'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'];
                } else {
                    $image['keywords'] = "No keywords.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'])){
                    $image['date'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'];
                } else {
                    $image['date'] = "No date info.";
                }
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $image['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                } else {
                    $image['path'] = "No path info.";
                }
                array_push($display_array, $image);
            } elseif (strpos($type, 'Story')){
                $story['loid'] = $hit['_source']['REF'];
                $story['archive'] = $hit['_source']['ARCHIVE'];
                $story['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'])){
                    $story['source'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'];
                } else {
                    $story['source'] = "No source info.";
                }
                if (isset($hit['_source']['CONTENT']['XMLFLAT'])){
                    $story['content'] = $hit['_source']['CONTENT']['XMLFLAT'];
                } else {
                    $story['content'] = "No content info.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'])){
                    $story['author'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'];
                } else {
                    $story['author'] = "No author info.";
                }
 
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'])){
                    $story['keywords'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'];
                } else {
                    $story['keywords'] = "No keywords.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTITLE'])){
                    $story['title'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTITLE'];
                } else {
                    $story['title'] = "No title info.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CATEGORY'])){
                    $story['category'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CATEGORY'];
                } else {
                    $story['category'] = "No category info.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION'])){
                    
                    $date = new Carbon($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION']);
                    $story['date'] = date("d-M-Y", strtotime($date));
                } else {
                    $story['date'] = "No date info.";
                }
                if (isset($hit['highlight'])){
                    $story['highlight'] = $hit['highlight'];
                } else {
                    $story['highlight'] = "No highlights found.";
                }
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $story['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                } else {
                    $story['path'] = "No path info.";
                }

                array_push($display_array, $story);
            } elseif (strpos($type, 'PDF')){
                $pdf['loid'] = $hit['_source']['REF'];
                $pdf['archive'] = $hit['_source']['ARCHIVE'];
                $pdf['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $pdf['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION'])){
                    $pubdate = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['DATEPUBLICATION'];
                    $pdf['pubdate'] = date("d-M-Y", strtotime($pubdate));
                } else {
                    $pdf['pubdate'] = "No date info found.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'])){
                    $pdf['publication'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'];        
                } else {
                    $pdf['publication'] = "No source info found.";
                }

                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['SECTION'])){
                    $pdf['section'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['SECTION'];
                } else {
                    $pdf['section'] = "No section info found.";
                }

                if (isset($hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['DOCUMENTINFO']['AUTHOR'])){
                    $pdf['author'] = $hit['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['DOCUMENTINFO']['AUTHOR'];
                } else {
                    $pdf['author'] = "No author info found.";
                }

                if ($pdf){
                    array_push($display_array, $pdf);
                }
            } elseif (strpos($type, 'Document')){
                $other_docs['loid'] = $hit['_source']['REF'];
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
                if ($other_docs){
                    array_push($display_array, $other_docs);
                }
            }
        }

        return $display_array;
    }

    public static function var_dump2($info){
        echo('<pre>');
        var_dump($info);
        echo('</pre>');
    }

    public function get_indices(){
        $indices_array = Session::get('indices');
        if ($indices_array){
            return $indices_array;
        }
        $status_json_url = "http://152.111.25.182:9200/_cat/indices?format=json";
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
        
        Session::put('indices', $indices_array);
        return $indices_array;
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
        $doctype_list = Session::get('types');
        if ($doctype_list){
            return $doctype_list;
        }
        $params = [
            'body' => [
                'aggs' => [
                    'document_types' => [
                        'terms' => [
                            'field' => 'ATTRIBUTES.METADATA.GENERAL.DOCTYPE.exact',
                            'size' => 12
                        ]
                    ]
                ]
            ]
        ];
        $doctypes = $this->elasticsearch->search($params);
        $types = $doctypes['aggregations']['document_types']['buckets'];
        $doctype_list[] = 'All';
        foreach ($types as $type){
            $typename = $type['key'];
            $typename = trim($typename);
            $typename = \strtolower($typename);
            if (!\in_array($typename, $doctype_list)){
                $doctype_list[] = $typename;
            }
        }
        Session::put('types', $doctype_list);
        return $doctype_list;
    }

    public function get_publications(){
        $pub_list = Session::get('publications');
        if ($pub_list){
            return $pub_list;
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
        Session::put('publications', $pub_list);
        return $pub_list;
    }

    public function get_authors(){
        $author_list = Session::get('authors');
        if ($author_list){
            return $author_list;
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
        Session::put('authors', $author_list);
        return $author_list;
    }

    public function get_categories(){
        $cat_list = Session::get('categories');
        if ($cat_list){
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

        $total_doc_count = 0;
        foreach ($categories as $category){
            $cat = [];
            $cat['name'] = $category['key'];
            $cat['count'] = $category['doc_count'];
            $cat_list[] = $cat;
            $total_doc_count += $category['doc_count'];
        }
        $all = ['name' => 'All', 'count' => $total_doc_count];
        $cat_list[] = $all;
        $cat_list = array_sort($cat_list);
        Session::put('categories', $cat_list);
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

    public function count_doctypes($results){
        $counts = array();
        $counts['stories'] = 0;
        $counts['images'] = 0;
        $counts['pdfs'] = 0;
        $counts['other_docs'] = 0;
        $buckets = $results['aggregations']['doctypes']['buckets'];
        foreach ($buckets as $bucket) {
            switch ($bucket['key']) {
                case 'Story':
                    $amt = $bucket['doc_count'];
                    $counts['stories'] += $amt;
                    break;
                case 'PDFPage':
                    $amt = $bucket['doc_count'];
                    $counts['pdfs'] += $amt;
                    break;
                case 'ExternalCopy':
                    $amt = $bucket['doc_count'];
                    $counts['stories'] += $amt;
                    break;
                case 'WirePhoto':
                    $amt = $bucket['doc_count'];
                    $counts['images'] += $amt;
                    break;
                case ' Story ':
                    $amt = $bucket['doc_count'];
                    $counts['stories'] += $amt;
                    break;
                case 'Image':
                    $amt = $bucket['doc_count'];
                    $counts['images'] += $amt;
                    break;
                case 'WireGraphic':
                    $amt = $bucket['doc_count'];
                    $counts['images'] += $amt;
                    break;
                case 'WireText':
                    $amt = $bucket['doc_count'];
                    $counts['stories'] += $amt;
                    break;
                case 'Page':
                    $amt = $bucket['doc_count'];
                    $counts['other_docs'] += $amt;
                    break;
                case 'EmailText':
                    $amt = $bucket['doc_count'];
                    $counts['other_docs'] += $amt;
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        Session::put('item_counts', $counts);
        return $counts;
    }

    public static function run_search($terms){

        $query = SearchController::build_query($terms);

        # Put the search terms in the session
        Session::put('terms', $terms);

        # Put the query into the session:
        Session::put('query_string', $query);

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

        $user_ip = request()->ip(); // Grab the user's IP address from the request.

        $message = $username . " with IP " . $user_ip . " searched in " . $terms['index'] . " for " . $terms['text'];
        Log::info($message);
    }

    public static function build_query($terms){

        # Build up query from form terms

        $meta_keys = Config::get('meta_mappings.keys');
        $filters = [];

        #die(SearchController::var_dump2($terms['text']));

        $text_terms = $terms['text'];

        $text_terms_array = \preg_split("/[\s,]+/", $terms['text']);
        $termcount = count($text_terms_array);
        $all_terms_string = "";
        foreach ($text_terms_array as $term) {
            $all_terms_string .= " AND " . $term;
        }
        $all_terms_string = \substr($all_terms_string, 5);

        if(isset($terms['type'])){
            $type_filter = [
                'term' => [
                    $meta_keys['objecttype'] => $terms['type'] //OBJECTINFO.TYPE is more general than DOCTYPE.exact
                ]
            ];
            $filters[] = $type_filter;
        }
 
        if ($terms['publication'] != 'All'){
            $pub_filter = [
                'term' => [
                    $meta_keys['product'] => $terms['publication']
                ]
            ];
            $filters[] = $pub_filter;
        }

        if (isset($terms['from'])){
            $from = $terms['from'];
        } else {
            $from = 0;
        }

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

        if ($terms['startdate'] != '' || $terms['enddate'] != ''){
            if ($terms['startdate'] == ''){
                $terms['startdate'] = '1970-01-01';
            }
            if ($terms['enddate'] == ''){
                $terms['enddate'] = 'now';
            }
            $date_range_filter = [
                'range' => [
                    $meta_keys['issuedate'] => [
                        'gte' => $terms['startdate'],
                        'lte' => $terms['enddate']
                    ]
                ]
            ];
            $filters[] = $date_range_filter;
        } else {
            $date_range_filter = '';
        }

        if ($terms['match'] == 'phrase'){
            $textquery = [
                'multi_match' => [ 
                    "query" => $terms['text'],
                    "type" => "phrase",
                    "fields" => $meta_keys['textsearch_fields']
                    ]
                ];
        } elseif($terms['match'] == 'allwords'){
            $textquery = [
                'query_string' => [
                    'query' => $all_terms_string,
                    'fuzziness' => 0
                    #'minimum_should_match' => $termcount
                ]
            ];
        } else {
            $textquery = [
                'query_string' => [
                    'query' => $terms['text'],
                    'fuzziness' => 'AUTO'
                ]
            ];
        }

        $query = [
            'index' => $terms['index'],
            'from' => $from,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            $textquery
                        ],
                        'filter' => $filters
                    ]
                ],
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
                    'fragment_size' => 200
                ],
                'size' => $terms['size'],
                'min_score' => 15
            ]
        ];

        if(isset($terms['aggs'])){
            $query['body']['aggs'] = $terms['aggs'];
        }

        Session::put('query_string', $query);

        $json_query = json_encode($query, JSON_PRETTY_PRINT);

        // die(SearchController::var_dump2($json_query));

        return $query;
    }

    public function do_advanced_search(Request $request){
        
        # Get all the terms from the form

        $terms = array();
        $terms['index'] = $_GET['archive'];
        $selected_type = $_GET['type'];
        $terms['text'] = $_GET['text'];
        $terms['text'] = trim($terms['text']); //Remove whitespace to prevent crashes when doing all words search.
        $terms['publication'] = $_GET['publication'];
        $terms['sort-by'] = $_GET['sort-by'];
        $terms['startdate'] = $_GET['startdate'];
        $terms['enddate'] = $_GET['enddate'];
        $terms['size'] = $_GET['size'];
        $terms['author'] = $_GET['author'];
        $terms['match'] = $_GET['match'];
        $terms['categories'] = $_GET['category'];

        # Validate them
        
        $request->validate([
            'text' => 'required'
        ]);

        if ($terms['enddate'] != ''){
            $validated_data = $request->validate([
                'enddate' => 'before:now',
                'startdate' => 'required'
            ]);
        }
        if ($terms['startdate'] != ''){
            $validated_data = $request->validate([
                'startdate' => 'before:enddate',
                'enddate' => 'required'
            ]);
        }

        # Send the search terms to the searcher to get the aggregations first

        #$terms['type'] = 'story';

        $terms['aggs'] = [
            "doctypes" => [
                "terms" => [
                    "field" => 'ATTRIBUTES.METADATA.GENERAL.DOCTYPE.exact',
                    "size" => 10
                ]
            ]
        ];

        $results = SearchController::run_search($terms);

        #die(SearchController::var_dump2($results));
        # Then return the view - the view itself will trigger the actual content search.

        Session::put('total_hits', $results['hits']['total']);

        $doctype_counts = $this->count_doctypes($results);

        #Session::put('maxperpage', $terms['show-amount']);
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

        if ($doctype_counts['stories'] > 0){
            return redirect('/results/stories/1');
        } elseif ($doctype_counts['images'] > 0) {
            return redirect('/results/images/1');
        } elseif ($doctype_counts['pdfs'] > 0) {
            return redirect('/results/pdfs/1');
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

    public function prepare_pubstring($publications){

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
            $pub_string = implode(", ", $pub_array);
            
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
            $story_data['publication'] = $this->prepare_pubstring($publications);
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
        $storyxml = file_get_contents($fixed_url);
        $storyxml = \str_replace('xml-formTemplate', 'formTemplate', $storyxml);

        $story_obj = new \SimpleXMLElement($storyxml);
        $doc_content = $this->prepare_story_html($story_obj);

        $story_data['content'] = $doc_content;

        return $story_data;
        
    }
    function prepare_story_html($xml){
        $doc_content = $xml->xpath('story');
        $doc_content = $doc_content[0];
        $html = '';
        if (isset($doc_content->grouphead->headline->ln)){
            $headline = $doc_content->grouphead->headline->ln;
            $html .= '<h2>' . $headline . '</h2>';
        }
        // $photo_caption = $xml->xpath('story/photo-group/photo-caption');
        // if (isset($photo_caption)){
        //     foreach ($photo_caption as $cap => $content){
        //         foreach ($content as $x){
        //             print_r($x);
        //         };
        //     };
        // }
        $body_array = $doc_content->text->p;
        if (isset($doc_content->text->byline->author->name)){
            $byline = $doc_content->text->byline->author->name;
            $html .= '<em>' . $byline . '</em>';
        }
        if (isset($body_array)){
            foreach($body_array as $ptag){
                $html .= '<p>' . $ptag . '</p>';
            }
        }
        return $html;
    }

    public function prepare_image_meta($loid){
        $images_server_ip = Config::get('elastic.content_server.ip');
        $images_server_port = Config::get('elastic.content_server.port');
        $images_server_url = 'http://' . $images_server_ip . ':' . $images_server_port;
        
        $metadata = $this->get_meta_one($loid);

        $image_data['loid'] = $loid;
        $image_data['index'] = $metadata['_index'];
        if (isset($metadata['_source']['SYSTEM']['ALERTPATH'])){
            $image_path = $metadata['_source']['SYSTEM']['ALERTPATH'];
        } else {
            $image_path = "unable to read path.";
        }

        $image_data['url'] = $images_server_url . $image_path;

        // Check if metadata differs between index. Maybe that is why it's sometimes PAPER and sometimes GENERAL

        if (isset($metadata['_source']['OBJECTINFO']['NAME'])){
            $image_data['filename'] = $metadata['_source']['OBJECTINFO']['NAME'];
        } else {
            $image_data['filename'] = "Unable to read name.";
        }

        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTYPE'])){
            $image_data['type'] = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCTYPE'];
        }

        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['PAPER']['PUB_CAPTION'])){
            $image_data['caption'] = $metadata['_source']['ATTRIBUTES']['METADATA']['PAPER']['PUB_CAPTION'];        
        } elseif (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_CAPTION'])) {
            $image_data['caption'] = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_CAPTION'];
        }
        
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['PAPER']['CUSTOM_SOURCE'])){
            $image_data['source'] = $metadata['_source']['ATTRIBUTES']['METADATA']['PAPER']['CUSTOM_SOURCE'];        
        } elseif (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'])) {
            $image_data['source'] = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'];
        }
        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS'])){
            $publications = $metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS'];
            $image_data['publication'] = $this->prepare_pubstring($publications);
        }

        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['PAPER']['DOCAUTHOR'])){
            $image_data['author'] = $metadata['_source']['ATTRIBUTES']['METADATA']['PAPER']['DOCAUTHOR'];        
        } elseif (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'])) {
            $image_data['author'] = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCAUTHOR'];
        }

        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['INFOIMAGE']['COPYRIGHT_NOTICE'])){
            $image_data['copyright'] = $metadata['_source']['ATTRIBUTES']['METADATA']['INFOIMAGE']['COPYRIGHT_NOTICE'];        
        }

        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'])){
            if ( isset($image_data['caption']) && $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'] != $image_data['caption']){
                $image_data['keywords'] = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'];        
            }
        }

        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS']['NEWSPAPER'])){
            $image_data['newspapers'] = $metadata['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['NEWSPAPERS']['NEWSPAPER'];
        }

        if (isset($metadata['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['WIDTH'])){
            $image_data['width'] = $metadata['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['WIDTH'];
        }

        if (isset($metadata['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['HEIGHT'])){
            $image_data['height'] = $metadata['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['HEIGHT'];
        }

        if (isset($metadata['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['COLORTYPE'])){
            $image_data['colourspace'] = $metadata['_source']['SYSATTRIBUTES']['PROPS']['IMAGEINFO']['COLORTYPE'];
        }

        if (isset($metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'])){
            $date = $metadata['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DATE_CREATED'];
            $image_data['date'] = date("d-M-Y", strtotime($date));
        }

        return $image_data;
    }

    public function get_meta_one($loid){
        $query_string = Session::get('query_string');
        $index = $query_string['index'];
        $server_address = Config::get('elastic.server.ip');
        $server_port = Config::get('elastic.server.port');
        $type = "EOM::File";
        $item_url = "http://" . $server_address . ":" . $server_port . "/" . $index . "/" . $type  . "/" . $loid;
        $metadata_json = file_get_contents($item_url);
        $metadata = json_decode($metadata_json, JSON_PRETTY_PRINT);
        return $metadata;
    }

    public function meta_dump($loid){
        $metadata = SearchController::get_meta_one($loid);
        return view('current_meta')->with('metadata', $metadata);
    }

    public function show_imageviewer($loid){
        $metadata = $this->prepare_image_meta($loid);
        return view('results.imageviewer')->with('metadata', $metadata);
    }

    public function show_storyviewer($loid){
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
