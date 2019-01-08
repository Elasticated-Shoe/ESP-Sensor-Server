function formValidateDelete() {
    try {
        var formData = $("#formDelete").serializeArray();
        var mapFormData = [];
        for(var i in formData) {
            mapFormData[formData[i]["name"]] = formData[i]["value"]
        }
        if(mapFormData["range"] !== "") {
            //mapFormData["range"] = mapFormData["range"].split("/").join(".");
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

$(".historicalChecked").hide();
$(document).ready(function(){
    // event handler for checkbox
    $('#checkBoxHistorical:checkbox').change(function() {
        if($(this)[0].checked) {
            $(".historicalChecked").show();
        } 
        else {
            $(".historicalChecked").hide();
        }
    });

    $(".buttonEdit").click(function() {
        var sensorName = $(this).parent().attr("data-sensor");
        $("#modalEditButton").foundation('open');
    });
    $(".buttonDelete").click(function() {
        var sensorName = $(this).parent().attr("data-sensor");
        $("#setSensorVal").val(sensorName)
        $("#modalDeleteButton").foundation('open');
    });
});