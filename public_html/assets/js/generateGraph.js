function convertToTimestamp(elementID) {
    var timeToCheck = $("#" + elementID).val();
    if(timeToCheck !== "") {
        var timeStamp = new Date(timeToCheck).getTime() / 1000;
        if(timeStamp === NaN) {
            return false;
        }
        return timeStamp;
    }
    return timeToCheck;
}

function validateSelectedSensors() {
    var sensorTypes = [];
    if($(".selected").length === 0) {
        throw "No Sensors Selected";
    }
    $(".selected").each(function() {
        var sensorType = $(this).attr("data-type");
        if(sensorType === undefined) {
            throw "Sensor Without Type Is Present, Please Set The Type In Admin Page";
        }
        if(!(sensorTypes.includes(sensorType))) {
            sensorTypes.push(sensorType);
        }
    });
    if(!(sensorTypes.length > 0 & sensorTypes.length < 3)) {
        throw "Too Many Sensor Types Selected";
    }
    return true;
}

function fetchArchivedData(sensorTypes, sensors, start, end) {
    // for weak typed languages foo[]=value&foo[]value2 // to send an array in query string
    var sensorsQuery = "";
    for(sensor in sensors) {
        sensorsQuery = sensorsQuery + "&Sensors[]=" + sensors[sensor];
    }
    var timePeriod = "Past";
    if(start === "" || end === "") {
        var rangeQuery = "";
    }
    else {
        var rangeQuery = "&Start=" + start + "&End=" + end;
    }
    proxy = window.location.href;
    if(proxy.indexOf("/phplogin/") !== -1) {
        proxy = "/phplogin/";
    }
    else {
        proxy = "/"
    }
    var archiveUrl = proxy + "sensorAPI?timePeriod=" + timePeriod + rangeQuery + sensorsQuery;
    $.get(archiveUrl, function(archiveData) {
        archiveData = JSON.parse(archiveData);
        generateGraphFromData(sensors, sensorTypes, archiveData)
        $("#buttonGenerateGraph").prop('disabled', false);
    });
}

function generateGraphFromData(sensors, sensorTypes, graphData) {
    // https://plot.ly/javascript/multiple-axes/  plot.ly <3
    // time only need to be generated once
    sensorTimes = [];
    for(sensorTime in graphData) {
        sensorTimes.push(new Date(sensorTime*1000));
    }
    // generate array of unique types
    uniqueSensorTypes = [];
    for(index = 0; index < sensorTypes.length; index++) {
        if(!(uniqueSensorTypes.includes(sensorTypes[index]))) {
            uniqueSensorTypes.push(sensorTypes[index]);
        }
    }
    // generate y values for each, assign column and push to data array
    var data = [];
    for(index = 0; index < sensors.length; index++) {
        xValues = [];
        // get all values for this sensor in array
        for(sensorTime in graphData) {
            // E.G - graphData[12748393][Epsilon]["Reading"]
            var checkIfInt = parseFloat(graphData[sensorTime][sensors[index]]["Reading"]);
            if(isNaN(checkIfInt)) {
                checkIfInt = 0;
            }
            xValues.push(checkIfInt);
        }
        temp = {};
        temp["x"] = sensorTimes;
        temp["y"] = xValues;
        temp["name"] = sensors[index] + ' Data';
        temp["type"] = "scatter";
        yAxisIndex = uniqueSensorTypes.indexOf(sensorTypes[index]);
        if(yAxisIndex !== 0) {
            temp["yaxis"] = "y" + (yAxisIndex + 1);
        }
        data.push(temp);
    }
    // styling for the chart
    var layout = {};
    layout["title"] = sensors.join(", ") + "Sensor Data";
    for(index = 0; index < uniqueSensorTypes.length; index++) {
        yAxisIndex = index + 1;
        if(yAxisIndex === 1) {
            yAxisIndex = "";
        }
        yAxisIndex = "yaxis" + yAxisIndex;
        layout[yAxisIndex] = {};
        layout[yAxisIndex]["title"] = uniqueSensorTypes[index];
        // other axis should go on the right
        if(index !== 0) {
            layout[yAxisIndex]["side"] = "right";
            layout[yAxisIndex]["overlaying"] = 'y'; // important otherwise plots wont load
        }
    }
    // generate chart
    $('#graphContainer').html("");
    Plotly.newPlot('graphContainer', data, layout);
}
$(document).ready(function() {
    $("#buttonGenerateGraph").click(function() {
        $(this).prop('disabled', true);
        try {
            // check that there are not too many types of sensor selected
            if(!validateSelectedSensors()) {
                throw "Sensor Validation Failed";
            }
            // generate array of selected sensor names and types
            sensorNames = [];
            sensorTypes = [];
            $(".selected").each(function() {
                var name = $(this).attr("data-name");
                sensorNames.push(name);
                var type = $(this).attr("data-type");
                sensorTypes.push(type);
            });
            // if inputs are not empty and both convert to a timestamp
            if(!(convertToTimestamp("inputStartRange") === "" && convertToTimestamp("inputEndRange") === "") &&
                (!convertToTimestamp("inputStartRange") || !convertToTimestamp("inputEndRange"))) {
                // invalid range specified so error
                // should have more validation
                throw "Invalid Parameter In Date Ranges";
            }
            // show modal for graph
            $("#modalShowGraph").foundation('open');
            // get data (calls generateGraphFromData() when complete)
            fetchArchivedData(sensorTypes, sensorNames, convertToTimestamp("inputStartRange"), convertToTimestamp("inputEndRange"));
        }
        catch (errorMessage) {
            alert(errorMessage);
            $(this).prop('disabled', false);
        }
    });
    // reset the modal to the loading thingy when it closes
    $('#modalShowGraph').on('closed.zf.reveal', function() {
        $('#graphContainer').html("<h1>Loading Graph...</h1>");
        // clear selected
        $(".selected").each(function() {
            $(".selected").removeClass("selected");
        });
    });
});