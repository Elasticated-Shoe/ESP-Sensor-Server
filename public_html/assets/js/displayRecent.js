function fetchData() {
    $.get(window.location.href + "sensorAPI", function(recentData) {
        recentData = JSON.parse(recentData);
        renderFetchedData(recentData);
    });
}
function renderFetchedData(data) {
    var template = _.template($('#recentReadingsTemplate').html());
    var vars = {
        "data": data               
    };
    var html = template(vars);
    $("#readingContainer").html(html);
}
$(document).ready(function(){
    fetchData();
    setInterval(fetchData, 10000);
});