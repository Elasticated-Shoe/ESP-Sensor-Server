function formValidateDelete() {
    // try catch so that if there are any errors we can return false and stop submission
    try {
        var formData = $("#formDelete").serializeArray();
        var mapFormData = [];
        for(var i in formData) {
            mapFormData[formData[i]["name"]] = formData[i]["value"]
        }
        if(mapFormData["range"] !== "") {
            var timeStamp = new Date(mapFormData["range"]).getTime() / 1000;
        }
        if(timeStamp === NaN) {
            return false;
        }
        return true;
    }
    catch(err) {
        console.log(err)
        return false;
    }
}
function formValidateEdit() {
    // validation to do
    return true;
}

function searchBy(searchOn, searchTerm, templateRendered) {
    $(".searchMode").each(function() {
        if($(this)[0].checked) {
            searchMode = $(this).val();
        }
    });
    // set the options for fuseJS
    var options = {
        shouldSort: true,
        findAllMatches: true,
        includeMatches: true,
        threshold: 0.4,
        location: 0,
        distance: 100,
        maxPatternLength: 32,
        minMatchCharLength: searchTerm.length,
        keys: [
            searchMode,
        ]
    }
    // Do search and get results. Results returned as JSON
    var fuse = new Fuse(searchOn, options);
    var fuseResults = fuse.search(searchTerm);
    // if no results
    if(fuseResults.length === 0) {
        $("#sensorsContainer").html("");
        $("#sensorsContainer").append("<div class='small-12'>No Results Found For " + searchTerm + "</div>")
    }
    else {
        resultArray = [];
        for(result in fuseResults) {
            var resultSensor = fuseResults[result]["item"];
            resultArray.push({
                "Name": resultSensor["Name"],
                "Type": resultSensor["Type"],
                "Location": resultSensor["Location"],
                "lastSeen": resultSensor["lastSeen"]
            });
        }
        renderSearchResult(resultArray);
    }
}

function renderSearchResult(sensorData) {
    var template = _.template($('#metaTemplate').html());
    var vars = {
        "data": sensorData               
    };
    var html = template(vars);
    $("#sensorsContainer").html(html);
    // edit and delete buttons prepare the modals and then show them
    $(".buttonEdit").click(function() {
        var sensorName = $(this).parent().attr("data-sensor");
        $("#setSensorVal2").val(sensorName);
        $("#titleEdit").html(sensorName);
        $("#modalEditButton").foundation('open');
    });
    $(".buttonDelete").click(function() {
        var sensorName = $(this).parent().attr("data-sensor");
        $("#setSensorVal").val(sensorName);
        $("#titleDelete").html(sensorName);
        $("#modalDeleteButton").foundation('open');
    });
}

function refreshSearchJSON(initialLoad) {
    proxy = window.location.href;
    if(proxy.indexOf("/phplogin/") !== -1) {
        proxy = "/phplogin/";
    }
    else {
        proxy = "/"
    }
    $.get(proxy + "sensorAPI?timePeriod=Current", function(recentData) {
        recentData = JSON.parse(recentData);
        searchArray = [];
        for(sensor in recentData) {
            // set unassigned type and location
            if(recentData[sensor]["sensorType"] === "") {
                recentData[sensor]["sensorType"] = "Unassigned";
            }
            if(recentData[sensor]["sensorLocation"] === "") {
                recentData[sensor]["sensorLocation"] = "Unassigned";
            }
            // add to search array, as fuse js expects list of json
            searchArray.push({
                "Name": sensor,
                "Type": recentData[sensor]["sensorType"],
                "Location": recentData[sensor]["sensorLocation"],
                "lastSeen": recentData[sensor]["lastSeen"]
            });
        }
        if(initialLoad === true) {
            renderSearchResult(searchArray);
        }
        $("#searchTerm").off('keyup');
        $("#searchTerm").keyup(function() {
            searchTerm = $("#searchTerm").val();
            if(searchTerm === "") {
                renderSearchResult(searchArray);
            }
            else {
                searchBy(searchArray, searchTerm, "metaTemplate");
            }
        });
    });
}

$(".historicalChecked").hide();
$(document).ready(function(){
    // event handler for checkbox in search, so only one box is checked at once
    $('.searchMode:checkbox').change(function() {
        var justChecked = $(this);
        $('.searchMode').each(function(index) {
            if($(this).val() !== justChecked.val()) {
                $(this)[0].checked = false;
            }
        });
    });
    // event handler for checkbox in delete form, shows additional options if checked
    $('#checkBoxHistorical:checkbox').change(function() {
        if($(this)[0].checked) {
            $(".historicalChecked").show();
        } 
        else {
            $(".historicalChecked").hide();
        }
    });

    // creates and refreshes data for the keyup callback on the search box
    refreshSearchJSON(true);
    setInterval(refreshSearchJSON, 10000);
});