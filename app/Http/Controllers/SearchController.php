<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Session;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Elasticsearch\ClientBuilder;

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
    }
    
    public function prepare_results(){
        $results = Session::get('results');
        $hits = $results['hits'];
        $hits = $hits['hits'];
        $display_array = array();
        $display_array['images'] = array();
        $display_array['stories'] = array();
        $display_array['pdfs'] = array();
        $display_array['html'] = array();
        foreach ($hits as $hit){
            $display_item = array();
            # Deal with the different types of items:
            $type = $hit['_source']['OBJECTINFO']['TYPE'];
            
            if (strpos($type, 'Image')){
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

                // if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'])){
                //     $image['source'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CUSTOM_SOURCE'];
                // } else {
                //     $image['source'] = "No source info.";
                // }
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
                array_push($display_array['images'], $image);
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
                $truncate_length = 500;
                if (strlen($story['content'] < $truncate_length )){
                    $story_preview = substr($story['content'], 0, $truncate_length) . "...";
                    $story_preview_array = \preg_split("/[\s,]+/", $story_preview);

                    #$body_preview = strip_tags($body_preview);
                    $terms = Session::get('terms');
                    $text = $terms['text'];

                    $text_terms_array = \preg_split("/[\s,]+/", $text);

                    foreach ($text_terms_array as $text_item){
                        foreach ($story_preview_array as $item){
                            if (\strcasecmp($item, $text_item) == 0){
                                $story_preview = \str_ireplace($item , '<span class="highlighted-text">' . $item . '</span>', $story_preview);
                            }
                        }
                    }
                    $story['content'] = $story_preview;
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
                    $story['date'] = $date->toDateString();
                } else {
                    $story['date'] = "No date info.";
                }
                if (isset($hit['highlight'])){
                    $story['highlight'] = $hit['highlight'];
                }
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $story['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                } else {
                    $story['path'] = "No path info.";
                }

                array_push($display_array['stories'], $story);
            } elseif (strpos($type, 'PDF')){
                $pdf['loid'] = $hit['_source']['REF'];
                $pdf['archive'] = $hit['_source']['ARCHIVE'];
                $pdf['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $pdf['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'])){
                    $pdf['keywords'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['DOCKEYWORD'];
                } else {
                    $pdf['keywords'] = "No keywords.";
                }
                if ($pdf){
                    array_push($display_array['pdfs'], $pdf);
                } else {
                    array_push($display_array['pdf'], "No PDFs found");
                }

            } elseif (strpos($type, 'Web')){
                $html['loid'] = $hit['_source']['REF'];
                $html['archive'] = $hit['_source']['ARCHIVE'];
                $html['filename'] = $hit['_source']['OBJECTINFO']['NAME'];
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'])){
                    $html['source'] = $hit['_source']['ATTRIBUTES']['METADATA']['PUBDATA']['PAPER']['PRODUCT'];
                } else {
                    $html['source'] = "No source info.";
                }
                if (isset($hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CATEGORY'])){
                    $html['category'] = $hit['_source']['ATTRIBUTES']['METADATA']['GENERAL']['CATEGORY'];
                } else {
                    $html['category'] = "No category info.";
                }
                if (isset($hit['_source']['SYSTEM']['ALERTPATH'])){
                    $html['path'] = $hit['_source']['SYSTEM']['ALERTPATH'];
                } else {
                    $html['path'] = "No path info.";
                }
                if (isset($hit['_source']['CONTENT']['TEXT'])){
                    $html['text'] = $hit['_source']['CONTENT']['TEXT'];

                    $body = $html['text'];

                    $head_start = \strpos($body, '<headline>');
                    $head_end = \strpos($body, '</headline>');
                    $head_len = $head_end - $head_start;

                    $headline = \substr($body, $head_start, $head_len);

                    if ($headline == ""){
                        $head_start = \strpos($body, '<h1>');
                        $head_end = \strpos($body, '</h1>');
                        $head_len = $head_end - $head_start;
                        $headline = \substr($body, $head_start, $head_len);
                    }
                    $headline = \html_entity_decode($headline);

                    $html['headline'] = strip_tags($headline);

                    $body_preview_start = \strpos($body, '<body>');
                    $body_preview_end = \strpos($body, '</body>');
                    $body_preview_len = $body_preview_end - $body_preview_start;

                    $body_preview = \substr($body, $body_preview_start, $body_preview_len);

                    $body_preview = \str_replace($headline, "", $body_preview);
                    #$body_preview = \str_replace('<iframe height="12%" width="98%" src="/disclaimer.html"></iframe>', "", $body_preview);
                    $body_preview = \str_replace("<br>\n<br>", "", $body_preview);
                    $body_preview = \strip_tags($body_preview, '<br>');
                    // $body_preview = \str_replace('<h1>', "", $body_preview);
                    // $body_preview = \str_replace('</h1>', "", $body_preview);
                    // $body_preview = \str_replace('<h2>', "", $body_preview);
                    // $body_preview = \str_replace('</h2>', "", $body_preview);
                    // $body_preview = \str_replace('<headline>', "", $body_preview);
                    // $body_preview = \str_replace('</headline>', "", $body_preview);
                    // $body_preview = \str_replace('<subhead>', "", $body_preview);
                    // $body_preview = \str_replace('</subhead>', "", $body_preview);
                    // $body_preview = \str_replace('<body>', "", $body_preview);
                    // $body_preview = \str_replace('</body>', "", $body_preview);
                    // #$body_preview = \str_replace('<br>', "", $body_preview);

                    
                    $preview_length = 700;
                    $body_preview = \substr($body_preview, 0, $preview_length);
                    $body_preview_array = \preg_split("/[\s,]+/", $body_preview);

                    $terms = Session::get('terms');
                    $text = $terms['text'];

                    $text_terms_array = \preg_split("/[\s,]+/", $text);

                    foreach ($text_terms_array as $text_item){
                        foreach ($body_preview_array as $item){
                            if (\strcasecmp($item, $text_item) == 0){
                                $body_preview = \str_ireplace($item , '<span class="highlighted-text">' . $item . '</span>', $body_preview);
                            }
                        }
                    }
                    $html['bodypreview'] = \html_entity_decode($body_preview);

                }

                if ($html){
                    array_push($display_array['html'], $html);
                } else {
                    array_push($display_array['html'], "No HTML files found");
                }

            } else {

            }
        }
        $display_array['counts']['images'] = count($display_array['images']);
        $display_array['counts']['stories'] = count($display_array['stories']);
        $display_array['counts']['pdfs'] = count($display_array['pdfs']);
        $display_array['counts']['html'] = count($display_array['html']);

        return $display_array;
    }

    public static function image_lookup($loid){
        $image_details = "http://152.111.25.182:9200/published@methcarch_eomjse11_arch_search/Image/" . $loid;
        return $image_details;
    }

    public function get_indices(){
        $status_json_url = "http://152.111.25.182:9200/_cat/indices?format=json";
        $status_json = file_get_contents($status_json_url);
        $status = json_decode($status_json);
        sort($status);
        $indices = count($status);
        $indices_array = [];
        foreach ($status as $item){
            if ($item->index == "notpublished@methcarch_eomjse11_arch" || $item->index == "published@methcarch_eomjse11_arch" || $item->index == "aspseek@methcarch_eomjse11_arch"){
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
        Session::put('publications', $data['publications']);
        return view('advanced_search')->with('data', $data);

    }

    public function get_types(){
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
        return $doctype_list;
    }

    public function get_publications(){
        $params = [
            'body' => [
                'aggs' => [
                    'publications' => [
                        'terms' => [
                            'field' => 'ATTRIBUTES.METADATA.PUBDATA.PAPER.PRODUCT.keyword',
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
        return $pub_list;
    }

    public function get_stats(){
        $params = [
            // 'index' => '/',
            'body' => [
                'aggs' => [
                    'Publications' => [
                        'terms' => [
                            'field' => 'ATTRIBUTES.METADATA.PUBDATA.PAPER.PRODUCT.keyword',
                            'size' => 60
                        ]
                    ]
                ]
            ]
        ];
        $publication_counts = $this->elasticsearch->search($params);

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

        $doc_types = $this->elasticsearch->search($params);

        $params = [
            'body' => [
                'aggs' => [
                    'categories' => [
                        'terms' => [
                            'field' => 'ATTRIBUTES.METADATA.GENERAL.CATEGORY',
                            'size' => 20
                        ]
                    ]
                ]
            ]
        ];

        $categories = $this->elasticsearch->search($params);

        $data['publication_counts'] = $publication_counts;
        $data['doc_types'] = $doc_types;
        $data['categories'] = $categories;

        return view('stats')->with('data', $data);
    }

    public function do_advanced_search(){
        $terms = array();
        $terms['index'] = $_GET['archive'];
        $terms['type'] = $_GET['type'];
        $terms['text'] = $_GET['text'];
        $terms['publication'] = $_GET['publication'];
        $terms['sort-by'] = $_GET['sort-by'];
        $terms['startdate'] = $_GET['startdate'];
        $terms['enddate'] = $_GET['enddate'];
        $terms['results-amount'] = $_GET['results-amount'];
        $terms['show-amount'] = $_GET['show-amount'];
        $terms['relevance'] = $_GET['relevance'];
        $terms['author'] = $_GET['author'];

        $sort_by = $terms['sort-by'];

        switch ($sort_by) {
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
        
        $relevance = $terms['relevance'];

        $relevance_string = '"min_score": ' . $relevance . ',';
        $termstext = $terms['text'];
        $resultsamount = $terms['results-amount'];

        $selected_pub = $terms['publication'];
        $selected_type = $terms['type'];
        $filters = [];

        if ($selected_type != 'All'){
            $type_filter = [
                'term' => [
                    'ATTRIBUTES.METADATA.GENERAL.DOCTYPE' => $selected_type
                ]
            ];
            $filters[] = $type_filter;
        }

        if ($selected_pub != 'All'){
            $pub_filter = [
                'term' => [
                    'ATTRIBUTES.METADATA.PUBDATA.PAPER.PRODUCT.keyword' => $selected_pub
                ]
            ];
            $filters[] = $pub_filter;
        }

        # Build up query
        
        $params = [
            'index' => $terms['index'],
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'query_string' => [
                                'query' => $terms['text']
                            ]
                        ],
                        'filter' => $filters
                    ]
                ],
                'size' => $resultsamount,
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
                ]
            ]
        ];

        $results = $this->elasticsearch->search($params);

        # Put the search parameters into the session:
        Session::put('query_string', $params);
        Session::put('terms', $terms);
        Session::put('selected_type', $terms['type']);
        Session::put('selected_pub', $selected_pub);
        Session::put('sorting', $terms['sort-by']);
        Session::put('maxresults', $terms['results-amount']);
        Session::put('maxperpage', $terms['show-amount']);
        Session::put('author', $terms['author']);

        # Place the search results into the session.
        Session::put('results', $results);

        $output = SearchController::prepare_results();

        Session::put('output', $output);
        Session::put('items_per_page', $terms['show-amount']);

        if ($output['counts']['images'] > 0){
            return redirect('/results/images/1');
        } elseif ($output['counts']['stories'] > 0) {
            return redirect('/results/stories/1');
        } elseif ($output['counts']['pdfs'] > 0) {
            return redirect('/results/pdfs/1');
        } elseif ($output['counts']['html'] > 0) {
            return redirect('/results/html/1');
        } else {
            return view('results.error')->with('data', $params);
        }
    }

    public function elasticsearchTest() {
        #dump($this->elasticsearch);
        # Might be easier to construct query in JSON - PHP is confusing and has weird syntax for empty objects.
        # Use dot notation for sub-fields.
        #"_source": ["ARCHIVE", "OBJECTINFO.NAME", "ATTRIBUTES", "SYSATTRIBUTES", "SYSTEM"],
        //     $query_json = '{
        //     "query": { "match": {"SYSATTRIBUTES.PROPS.SUMMARY": "Giraffe"} },
        //     "size": 30
        // }';
        # query_string searches all fields by default.
        $query_json = '{"query": { "query_string": {"query": "boxing"} },"size": 1000}';
        $params = [
            'index' => 'published@methcarch_eomjse11_arch',
            'body' => $query_json
        ];
            $response = $this->elasticsearch->search($params);
        dump($response);
    }

    public function get_meta_one($loid){
        $query_string = Session::get('query_string');
        $index = $query_string['index'];
        $server_address = Config::get('elastic.server.ip');
        $server_port = Config::get('elastic.server.port');
        $type = "EOM::File";
        $item_url = "http://" . $server_address . ":" . $server_port . "/" . $index . "/" . $type  . "/" . $loid;
        $metadata_json = file_get_contents($item_url);
        $metadata = json_decode($metadata_json);
        return $metadata;
    }

    public function meta_dump($loid){
        $metadata = SearchController::get_meta_one($loid);
        dd($metadata);
    }

    public function show_imageviewer($loid){
        $metadata = SearchController::get_meta_one($loid);
        return view('results.imageviewer')->with('metadata', $metadata);
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
