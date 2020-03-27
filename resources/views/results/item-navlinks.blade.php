<div class="linkbar">
    @if ($type == 'article')
        <a href="/results/stories/1" role="button">Back to results</a> |
    @endif
    @if ($type == 'image')
        <a href="/results/images/1" role="button">Back to results</a> |
    @endif
    <a href="/" role="button">Search Again</a> |
    <a href="/metadump/{{$loid}}" role="button">Raw ElasticSearch data</a>
</div>