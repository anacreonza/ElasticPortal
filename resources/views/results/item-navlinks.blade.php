<div class="linkbar">
    @if ($type == 'article')
        <a href="/results/stories/1" role="button">Back to results</a> |
    @endif
    @if ($type == 'image')
        <a href="/results/images/1" role="button">Back to results</a> |
    @endif
    <a href="/metadump/{{$loid}}" role="button">Raw ElasticSearch data</a> |
    <a href="/" role="button">Advanced Search</a>
</div>