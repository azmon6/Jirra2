function yourJsFunction(){
    console.log("Works");
}

function testing()
{
    var element = document.getElementById("plach");
    element.innerHTML = "WHAT?";
    request = new XMLHttpRequest();
    request.open("GET", "testtable.php");
    request.send(null);
    request.onload = function() {
        element.innerHTML = request.responseText;
    }
}