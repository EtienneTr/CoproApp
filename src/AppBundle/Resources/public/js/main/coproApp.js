var selectpicker = $('.selectpicker');
selectpicker.parent().width('100%');
var nothingSelectedText  = "";
if(selectpicker.hasClass("select_other_than_user")){
    nothingSelectedText = "Aucun élément sélectionné";
}else{
    nothingSelectedText = "Tout le monde";
}

selectpicker.selectpicker({
    noneSelectedText : nothingSelectedText
});