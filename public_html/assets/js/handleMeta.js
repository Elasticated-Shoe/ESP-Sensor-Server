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
});