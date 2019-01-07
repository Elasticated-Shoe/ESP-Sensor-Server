$(document).ready(function(){
    $(".buttonEdit").click(function() {
        var sensorName = $(this).parent().attr("data-sensor");
        $.get("?Action=Edit&Sensor=" + sensorName, function(refreshedItems) {
            console.log(refreshedItems);
        });
    });
    $(".buttonDelete").click(function() {
        var sensorName = $(this).parent().attr("data-sensor");
        $.get("?Action=Delete&Sensor=" + sensorName, function(refreshedItems) {
            console.log(refreshedItems);
        });
    });
});