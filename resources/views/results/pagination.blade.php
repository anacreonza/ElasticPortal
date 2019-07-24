<div class="pagi-container">
    <ul class="pagination">
        <?php 
            $items_per_page_int = (int) "$items_per_page";
            $items_count_int = (int) "$items_count";
            $number_of_pages = $items_count_int / $items_per_page_int;
            $number_of_pages = round($number_of_pages, 0, PHP_ROUND_HALF_UP);
        ?>
        @if ($number_of_pages > 1)
            @for ($i = 1; $i <= $number_of_pages; $i++)
            <li 
                @if ($current_page_no == "$i")
                    class="active"
                @else
                    class="inactive"               
                @endif
                ><a href="/results/{{$current_page}}/{{$i}}">{{$i}}</a></li>
            @endfor
        @endif
    </ul>
</div>
