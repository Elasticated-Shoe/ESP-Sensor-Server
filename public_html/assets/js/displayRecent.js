function fetchData() {
    $.get(window.location.href + "sensorAPI?timePeriod=Current", function(recentData) {
        recentData = JSON.parse(recentData);
        console.log(recentData);
        renderFetchedData(recentData);
    });
}
function renderFetchedData(data) {
    // make sure previously selected sensors remain selected
    selectedArray = checkSelected();
    for(sensor in data) {
        if(sensor in selectedArray) {
            data[sensor]["selected"] = "selected";
        }
    }
    // add rendered html to element
    var template = _.template($('#recentReadingsTemplate').html());
    var vars = {
        "data": data               
    };
    var html = template(vars);
    $("#readingContainer").html(html);
    // re add click handler
    $(".selectable").click(function() {
        if($(this).hasClass("selected")) {
            $(this).removeClass("selected");
        }
        else {
            $(this).addClass("selected");
        }
    });
}
function checkSelected() {
    var selectedArray = [];
    $(".selectable").each(function() {
        if($(this).hasClass("selected")) {
            var type = $(this).attr("data-type");
            var name = $(this).attr("data-name");
            selectedArray[name] = [];
            selectedArray[name]["Type"] = (type == undefined) ? "" : type;
        }
    });
    return selectedArray;
}
$(document).ready(function() {
    fetchData();
    setInterval(fetchData, 10000);
});