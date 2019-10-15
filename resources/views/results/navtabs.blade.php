    <div class="nav-tabs">
        <ul class="nav nav-tabs">
            @php
            $item_counts = Session::get('item_counts');
            @endphp

            @if ($item_counts['stories'] > 0)
                @if ($current_page == "stories")
                <li class="nav-link active">
                @else
                <li class="nav-link">
                @endif
                <a href="/results/stories/1">Stories <span class="badge badge-info">{{$item_counts['stories']}}</span></a></li>
            @endif

            @if ($item_counts['images'] > 0)
                @if ($current_page == "images")
                <li class="nav-link active">
                @else
                <li li class="nav-link">
                @endif
                <a href="/results/images/1">Images <span class="badge badge-info">{{$item_counts['images']}}</span></a></li>
            @endif
            
            @if ($item_counts['pdfs'] > 0)
                @if ($current_page == "pdfs")
                <li class="nav-link active">
                @else
                <li li class="nav-link">
                @endif
                <a href="/results/pdfs/1">PDFs <span class="badge badge-info">{{$item_counts['pdfs']}}</span></a></li>
            @endif

            @if ($item_counts['other_docs'] > 0)
                @if ($current_page == "other_docs")
                <li class="nav-link active">
                @else
                <li li class="nav-link">
                @endif
                <a href="/results/other_docs/1">Other Documents <span class="badge badge-info">{{$item_counts['other_docs']}}</span></a></li>
            @endif
        
        </ul>
    </div>