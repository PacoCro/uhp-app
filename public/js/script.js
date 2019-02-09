
// Send request in predefined interval to server to track if user is still on the site.
setInterval(function(){

    var xhttp = new XMLHttpRequest();

    xhttp.open("post", "update-tracking.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send("js=true");

}, 1000);