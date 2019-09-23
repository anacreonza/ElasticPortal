    <div class="nav-tabs">
        <ul class="nav nav-tabs">
            <?php
            $images = (int) "$images";
            $stories = (int) "$stories";
            $pdfs = (int) "$pdfs";
            $html = (int) "$html";
            ?>
            
            @if ($stories > 0)
            @if ($current_page == "stories")
            <li class="nav-link active">
                @else
                <li class="nav-link">
                @endif
                <a href="/results/stories/1">Stories <span class="badge badge-info">{{$stories}}</span></a></li>
            @endif

            @if ($images > 0)
            @if ($current_page == "images")
            <li class="nav-link active">
                @else
                <li li class="nav-link">
                @endif
                <a href="/results/images/1">Images <span class="badge badge-info">{{$images}}</span></a></li>
            @endif
            
            @if ($pdfs > 0)
            @if ($current_page == "pdfs")
            <li class="nav-link active">
                @else
                <li li class="nav-link">
                @endif
                <a href="/results/pdfs/1">PDFs <span class="badge badge-info">{{$pdfs}}</span></a></li>
            @endif
        
        </ul>
    </div>