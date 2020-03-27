function textEntryDisabler(){
    var disableTextSearch = document.getElementById('disableTextSearch');
    var matchExactWords = document.getElementById('matchExactWords');
    var matchExactPhrase = document.getElementById('matchExactPhrase');
    var matchAnyText = document.getElementById('matchAnyText');
    var textInput = document.getElementById('textInput');
    
    disableTextSearch.addEventListener('click', function(){
        textInput.disabled = true;
    });
    matchExactWords.addEventListener('click', function(){
        textInput.disabled = false;
    });
    matchExactPhrase.addEventListener('click', function(){
        textInput.disabled = false;
    });
    matchAnyText.addEventListener('click', function(){
        textInput.disabled = false;
    });
}
