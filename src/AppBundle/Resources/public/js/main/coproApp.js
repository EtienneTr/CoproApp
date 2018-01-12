var selectpicker = $('.selectpicker');
selectpicker.parent().width('100%');
var nothingSelectedText  = "";

selectpicker.each(function(){
    if ($(this).hasClass("select_other_than_user")) {
        nothingSelectedText = "Aucun élément sélectionné";
    } else {
        nothingSelectedText = "Tout le monde";
    }

    $(this).selectpicker({
        noneSelectedText: nothingSelectedText
    })
});