@extends('layouts.app')
@section('header')
<title>Change Log</title>
@endsection
@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection
@section('content')
<div class="container">
    <br>
    <div class="items-container">
        <p>21-Feb-2020:</p>
        <ul>
            <li>Included image links in articles. Low-resolution images are requested to keep loading times down.</li>  
            <li>Started to implement the basics of authenticaiton system. Created links page for each user.</li>  
            <li>Database migrations added to add place for future local preferences settings.</li>
            <li>Fixed crash relating to last item in list not finding the next story for the pagination button.</li>
            <li>Moved to Git-based deployment system to try to speed up future deployments.</li>
        </ul>
        <p>9-Dec-2019:</p>
        <ul>
            <li>Lowered minimum score (to 10) for results to prevent common words from not appearing.</li>
            <li>Removed items per page setting to allow entire search form to fit onto a small laptop screen without scrolling.</li>
            <li>Cosmetic improvement - fade on search buttons hover.</li>
        </ul>
        <hr>
        <p>26-Nov-2019:</p>
        <ul>
            <li>Aggregation query results are stored locally now to try to speed up the site.</li>
            <li>Updated pagination with next and previous buttons.</li>
            <li>Added ability to page through articles.</li>
            <li>Updated CSS to improve look of site - various cosmetic upgrades.</li>
            <li>Article text is now generated using an XSLT transform - to improve accuracy. Still not ideal as picture caption headlines are not distinguished from regular headlines, and articles that have nothing but picture caption text may display blank.</li>
            <li>Enabled config cache and removed unused libraries to improve speed.</li>
            <li>Updated site to handle the ASPSeek legacy index.</li>
        </ul>
    </div>
</div>
@endsection