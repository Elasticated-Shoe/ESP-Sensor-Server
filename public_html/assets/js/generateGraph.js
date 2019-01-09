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
        if(!(sensorType in sensorTypes)) {
            sensorTypes.push(sensorType);
        }
    });
    if(!(sensorTypes.length > 0 & sensorTypes.length < 3)) {
        throw "Too Many Sensor Types Selected";
    }
    return true;
}

function fetchArchivedData(sensors, start, end) {
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
    var archiveUrl = window.location.href + "sensorAPI?timePeriod=" + timePeriod + rangeQuery + sensorsQuery;
    $.get(archiveUrl, function(archiveData) {
        archiveData = JSON.parse(archiveData);
        $("#buttonGenerateGraph").prop('disabled', false);
        
    });
}
$(document).ready(function() {
    $("#buttonGenerateGraph").click(function() {
        $(this).prop('disabled', true);
        try {
            // check that there are not too many types of sensor selected
            if(!validateSelectedSensors()) {
                throw "Sensor Validation Failed";
            }
            // generate array of selected sensor names
            sensorNames = [];
            $(".selected").each(function() {
                var name = $(this).attr("data-name");
                sensorNames.push(name);
            });
            // if inputs are not empty and both convert to a timestamp
            if(!(convertToTimestamp("inputStartRange") === "" && convertToTimestamp("inputEndRange") === "") &&
                (!convertToTimestamp("inputStartRange") || !convertToTimestamp("inputEndRange"))) {
                // invalid range specified so error
                // should have more validation
                throw "Invalid Parameter In Date Ranges";
            }
            fetchArchivedData(sensorNames, convertToTimestamp("inputStartRange"), convertToTimestamp("inputEndRange"));
        }
        catch (errorMessage) {
            alert(errorMessage);
            $(this).prop('disabled', false);
        }
    })
});