    <div class="nav-tabs">
        <ul class="nav nav-tabs">
            <?php
            $images = (int) "$images";
            $stories = (int) "$stories";
            $pdfs = (int) "$pdfs";
            $html = (int) "$html";
            ?>
            
            @if ($images > 0)
            @if ($current_page == "images")
            <li class="active">
                @else
                <li>
                @endif
                <a href="/results/images/1">Images <span class="badge badge-info">{{$images}}</span></a></li>
            @endif
        
            @if ($stories > 0)
            @if ($current_page == "stories")
            <li class="active">
                @else
                <li>
                @endif
                <a href="/results/stories/1">Stories <span class="badge badge-info">{{$stories}}</span></a></li>
            @endif
        
            @if ($pdfs > 0)
            @if ($current_page == "pdfs")
            <li class="active">
                @else
                <li>
                @endif
                <a href="/results/pdfs/1">PDFs <span class="badge badge-info">{{$pdfs}}</span></a></li>
            @endif
        
            @if ($html > 0)
            @if ($current_page == "html")
            <li class="active">
                @else
                <li>
                @endif
                <a href="/results/html/1">HTML <span class="badge badge-info">{{$html}}</span></a></li>
            @endif
        </ul>
    </div>