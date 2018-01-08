jQuery(document).ready(function () {
    //files form
    addFiles();
});


var fileIndex = 0;

function addFiles() {
    var $addFileBtn = $('<a href="#" class="add_file_link btn btn-default">Ajouter un fichier</a>');
    var $newBtn = $('<div></div>').append($addFileBtn);

    var $collectionFile = $('div.attachments');
    $collectionFile.append($newBtn);

    $addFileBtn.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        addNewFileForm($collectionFile, $newBtn);
        fileIndex++;

    });

}

function addNewFileForm($collectionFile, $newBtn) {

    var prototype = $collectionFile.data('prototype');

    var newForm = prototype.replace(/__name__/g, fileIndex);

    //display
    var $newFormLi = $('<div></div>').append(newForm);

    $newFormLi.append('<a href="#" class="remove-file btn btn-danger">Supprimer</a>');

    $newBtn.before($newFormLi);


    $('.remove-file').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();
        nbr--;
        return false;
    });
}