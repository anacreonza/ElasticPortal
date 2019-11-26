<div class="pagi-container">
    <ul class="pagination pg-blue" aria-label="Page navigation">
            @php
            $current_page_no_string = (string)$current_page_no; //Must first convert object to string
            $current_page_no_int = (int)$current_page_no_string;
            $next_page_no = $current_page_no_int + 1;
            $previous_page_no = $current_page_no_int - 1;
            $pagination_size = 23;
            $pagination_start_page = $current_page_no_int - $pagination_size;
            if($pagination_start_page <= 0){
                $pagination_start_page = 1;
            }
            $pagination_end_page = $pagination_start_page + $pagination_size;

            $terms = Session::get('terms');
            $item_counts = Session::get('item_counts');
            switch ($current_page) {
                case 'stories':
                    $items = $item_counts['stories'];
                    break;
                case 'images':
                    $items = $item_counts['images'];
                    break;
                case 'pdfs':
                    $items = $item_counts['pdfs'];
                    break;
                case 'other_docs':
                    $items = $item_counts['other_docs'];
                    break;
                
                default:
                    # code...
                    break;
            }
            $items_per_page = $terms['size'];
            $number_of_pages = $items / $items_per_page;
            $number_of_pages = (int)round($number_of_pages, 0, PHP_ROUND_HALF_UP);

            if($pagination_end_page >= $number_of_pages){
                $pagination_end_page = $number_of_pages;
            }

            @endphp

        @if ($number_of_pages > 1)
            <li 
                @if ($current_page_no_string == "1")
                    class="page-item disabled"
                @else
                    class="page-item inactive"
                @endif
                >
                <a href="{{$previous_page_no}}" aria-label="Previous" class="page-link"><span aria-hidden="true">Previous</span></a>
            </li>
            @for ($i = $pagination_start_page; $i <= $pagination_end_page; $i++)
            <li 
                @if ($current_page_no == "$i")
                    class="page-item active"
                @else
                    class="page-item inactive"               
                @endif
                ><a class="page-link" href="/results/{{$current_page}}/{{$i}}">{{$i}}</a></li>
            @endfor
            <li 
                @if ($current_page_no_string == $number_of_pages)
                    class="page-item disabled"
                @else
                    class="page-item inactive"
                @endif
                >
                <a href="{{$next_page_no}}" class="page-link">Next</a>
            </li>
        @endif
    </ul>
</div>
