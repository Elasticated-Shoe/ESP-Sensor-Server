$(document).ready(function(){
    $("#buttonRefresh").click(function() {
        console.log("Button Clicked");
        $.get("?Action=Refresh", function(refreshedItems) {
            console.log(refreshedItems);
        });
    });
});