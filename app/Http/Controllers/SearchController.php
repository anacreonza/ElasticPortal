<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Session;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Elasticsearch\ClientBuilder;
use DOMDocument;

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
        foreach ($status as $item){
            if ($item->index == "notpublished@methcarch_eomjse11_arch" || $item->index == "published@methcarch_eomjse11_arch"){
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
        Session::put('publications', $pub_list);
        return $pub_list;
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
                            'field' => 'ATTRIBUTES.METADATA.GENERAL.CATEGORY',
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
                            'field' => 'ATTRIBUTES.METADATA.GENERAL.DOCTYPE.exact',
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
                            'field' => 'ATTRIBUTES.METADATA.PUBDATA.PAPER.PRODUCT.keyword',
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

    public function do_advanced_search(Request $request){
        #$request->flash();
        // $validated_data = $request->validate([
        //     'archive' => 'required',
        //     'type' => 'required',
        //     'enddate' => 'before:now',
        //     'startdate' => 'before:enddate'
        // ]);
        $terms = array();
        $terms['index'] = $_GET['archive'];
        $terms['type'] = $_GET['type'];
        $terms['text'] = $_GET['text'];
        $terms['text'] = trim($terms['text']); //Remove whitespace to prevent crashes when doing all words search.
        $terms['publication'] = $_GET['publication'];
        $terms['sort-by'] = $_GET['sort-by'];
        $terms['startdate'] = $_GET['startdate'];
        $terms['enddate'] = $_GET['enddate'];
        
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

        $terms['results-amount'] = $_GET['results-amount'];
        $terms['show-amount'] = $_GET['show-amount'];
        $terms['author'] = $_GET['author'];
        $terms['match'] = $_GET['match'];
        $terms['categories'] = $_GET['category'];
        $date_range['start'] = $terms['startdate'];
        $date_range['end'] = $terms['enddate'];

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

        #$relevance = $terms['relevance'];

        #$relevance_string = '"min_score": ' . $relevance . ',';
        $termstext = $terms['text'];
        $resultsamount = $terms['results-amount'];

        $selected_pub = $terms['publication'];
        $selected_type = $terms['type'];
        $filters = [];

        $text_terms_array = \preg_split("/[\s,]+/", $terms['text']);
        $termcount = count($text_terms_array);
        $all_terms_string = "";
        foreach ($text_terms_array as $term) {
            $all_terms_string .= " AND " . $term;
        }
        $all_terms_string = \substr($all_terms_string, 5);

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

        if ($date_range['start'] != '' || $date_range['end'] != ''){
            if ($date_range['start'] == ''){
                $date_range['start'] = '1970-01-01';
            }
            if ($date_range['end'] == ''){
                $date_range['end'] = 'now';
            }
            $date_range_filter = [
                'range' => [
                    'SYSATTRIBUTES.PROPS.PRODUCTINFO.ISSUEDATE' => [
                        'gte' => $date_range['start'],
                        'lte' => $date_range['end']
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
                    "fields" => [
                        'CONTENT.XMLFLAT',
                        'CONTENT.TEXT',
                        'ATTRIBUTES.METADATA.GENERAL.DOCKEYWORD',
                        'ATTRIBUTES.METADATA.GENERAL.DOCTITLE',
                        'ATTRIBUTES.METADATA.GENERAL.CUSTOM_CAPTION',
                        'SYSATTRIBUTES.PROPS.SUMMARY',
                        ]
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

        # Build up query
        
        $params = [
            'index' => $terms['index'],
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            $textquery
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

        $query_body = $params['body'];
        $queried_index = $params['index'];

        $results = $this->elasticsearch->search($params);

        # Put the search parameters into the session:
        Session::put('query_string', $params);
        Session::put('query_body', $query_body);
        Session::put('queried_index', $queried_index);
        Session::put('terms', $terms);
        Session::put('selected_type', $terms['type']);
        Session::put('selected_pub', $selected_pub);
        Session::put('sorting', $terms['sort-by']);
        Session::put('maxresults', $terms['results-amount']);
        Session::put('maxperpage', $terms['show-amount']);
        Session::put('author', $terms['author']);
        Session::put('match', $terms['match']);
        if ($date_range['start'] != '1970-01-01'){
            Session::put('selected_startdate', $date_range['start']);
        } else {
            Session::put('selected_startdate', '');
        }
        if ($date_range['end'] != 'now'){
            Session::put('selected_enddate', $date_range['end']);
        } else {
            Session::put('selected_enddate', '');
        }

        # Place the search results into the session.
        Session::put('results', $results);

        $output = SearchController::prepare_results();

        Session::put('output', $output);
        Session::put('items_per_page', $terms['show-amount']);

        if ($output['counts']['stories'] > 0){
            return redirect('/results/stories/1');
        } elseif ($output['counts']['images'] > 0) {
            return redirect('/results/images/1');
        } elseif ($output['counts']['pdfs'] > 0) {
            return redirect('/results/pdfs/1');
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

        $meta_keys = [
            '[SYSTEM][ALERTPATH]',
            '[OBJECTINFO][NAME]',
            '[ATTRIBUTES][METADATA][PAPER][PUB_CAPTION]',
            '[ATTRIBUTES][METADATA][GENERAL][CUSTOM_SOURCE]',
            '[ATTRIBUTES][METADATA][PUBDATA][PAPER][NEWSPAPERS]',
            '[ATTRIBUTES][METADATA][GENERAL][DOCAUTHOR]',
            '[ATTRIBUTES][METADATA][INFOIMAGE][COPYRIGHT_NOTICE]',
            '[ATTRIBUTES][METADATA][GENERAL][DOCKEYWORD]',
            '[SYSATTRIBUTES][PROPS][IMAGEINFO][WIDTH] x [SYSATTRIBUTES].[PROPS].[IMAGEINFO].[HEIGHT]',
            '[SYSATTRIBUTES][PROPS][IMAGEINFO][COLORTYPE]',
            '[ATTRIBUTES][METADATA][GENERAL][DATE_CREATED]',
            '[OBJECTINFO][TYPE]'
        ];

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
