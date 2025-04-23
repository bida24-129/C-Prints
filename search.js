function hideSocials() {
    document.getElementById('card-container').style.display = 'none';
}

function showSuggestions(str) {
    if (str.length == 0) {
        document.getElementById("suggestions").innerHTML = "";
        return;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("suggestions").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "suggestions.php?q=" + str, true);
    xmlhttp.send();
}