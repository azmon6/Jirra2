function getComment(id,issueID) {
    var element = document.getElementById("commentContainer");
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "editcomment.php?id=" + id +"&issue="+issueID, true);
    xmlhttp.send();
    xmlhttp.onload = function() {
        element.innerHTML = xmlhttp.responseText;
    }
}

function removeComment()
{
    var element = document.getElementById("commentContainer");
    element.innerHTML = "";
}