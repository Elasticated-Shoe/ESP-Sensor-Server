function findDistinctValues(data, key) {
    tempArray = [];
    for(item in data) {
        var tempKeyValue = data[item][key];
        if(!(tempArray.includes(tempKeyValue))) {
            tempArray.push(tempKeyValue);
        }
    }
    return tempArray;
}

function fetchData() {
    $.get(window.location.href + "sensorAPI?timePeriod=Current", function(recentData) {
        recentData = JSON.parse(recentData);
        console.log(recentData);
        renderFetchedData(recentData);
    });
}
function renderFetchedData(data) {
    selectedArray = checkSelected();
    for(sensor in data) {
        // make sure previously selected sensors remain selected
        if(sensor in selectedArray) {
            data[sensor]["selected"] = "selected";
        }
        // add class to identify sensor as active or inactive (if reading is no older than 30 seconds)
        currentTimestamp = Math.round(new Date().getTime()/1000) - 32;
        data[sensor]["State"] = "sensor-inactive";
        if(data[sensor]["lastSeen"] >= currentTimestamp) {
            data[sensor]["State"] = "sensor-active";
        }
        // set unassigned type and location
        if(data[sensor]["sensorType"] === "") {
            data[sensor]["sensorType"] = "Unassigned";
        }
        if(data[sensor]["sensorLocation"] === "") {
            data[sensor]["sensorLocation"] = "Unassigned";
        }
        // add name as value // even though it is a key
        data[sensor]["Name"] = sensor;
    }
    // rejig data to format template expects E.G location: {Type: {data}}
    templateFormattedData = {};
    var sensorLocations = findDistinctValues(data, "sensorLocation");
    for(sensorLocation in sensorLocations) {
        for(sensor in data) {
            if(sensorLocations[sensorLocation] === data[sensor]["sensorLocation"]) {
                currentLocation = sensorLocations[sensorLocation];
                if(!(currentLocation in templateFormattedData)) {
                    templateFormattedData[currentLocation] = {};
                }
                var currentType = data[sensor]["sensorType"];
                if(!(currentType in templateFormattedData[currentLocation])) {
                    templateFormattedData[currentLocation][currentType] = [];
                }
                templateFormattedData[currentLocation][currentType].push(data[sensor]);
            }
        }
    }
    // add rendered html to element
    var template = _.template($('#recentReadingsTemplate').html());
    var vars = {
        "data": templateFormattedData               
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
    var today = new Date();
    var min = today.getHours();
    var hour = today.getMinutes();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    if (min < 10) {
        min = '0' + min;
    }
    if (hour < 10) {
        hour = '0' + hour;
    }
    $('#inputStartRange').fdatepicker({
        initialDate: yyyy + '-' + mm + '-' + dd + ' 00:00',
		format: 'yyyy-mm-dd hh:ii',
		disableDblClickSelection: true,
        pickTime: true,
        closeButton: true
    });
    $('#inputEndRange').fdatepicker({
        initialDate: yyyy + '-' + mm + '-' + dd + ' ' + hour + ':' + min,
		format: 'yyyy-mm-dd hh:ii',
		disableDblClickSelection: true,
        pickTime: true,
        closeButton: true
	});
});