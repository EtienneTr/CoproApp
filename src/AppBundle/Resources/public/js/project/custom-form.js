var $collectionHolder;
var nbr = 0;
var optBnr = [];
// setup an "add a tag" link
var $addTagLink = $('<a href="#" class="add_survey_link">Ajouter une question</a>');
var $newLinkLi = $('<li></li>').append($addTagLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.survey');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
        addOptions($collectionHolder, nbr);
        nbr++;
    });

    //files form
    addFiles();
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = nbr;

    var newForm = prototype;
    //replace index variable
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form
    var $newFormLi = $('<li></li>').append(newForm);

    $newFormLi.append('<a href="#" class="remove-tag">x</a>');

    $newLinkLi.before($newFormLi);

    $('.remove-tag').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();
        nbr--;
        return false;
    });

}

function addOptions($surveyCollection, $nbr) {
    var option = $surveyCollection.find("#project_survey_" + $nbr +"_options");
    var prototype = option.data('prototype');
    option.append($('<li></li>').append(prototype.replace(/__opt__/g, "1")));
    option.append($('<li></li>').append(prototype.replace(/__opt__/g, "2")));
    optBnr[$nbr] = 3;

    // setup an "add a tag" link
    var $addOptionLink = $('<a href="#" class="add_survey_link">Ajouter une réponse possible</a>');
    var $newLink = $('<li></li>').append($addOptionLink);
    option.append($newLink);

    $addOptionLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        $newLink.before($('<li></li>').append(prototype.replace(/__opt__/g, optBnr[$nbr])));
        optBnr[$nbr]++;

    });

}

var fileIndex = 0;
function addFiles(){
    var $addFileBtn = $('<a href="#" class="add_file_link">Ajouter un fichier</a>');
    var $newBtn = $('<li></li>').append($addFileBtn);

    var $collectionFile = $('ul.attachments');
    $collectionFile.append($newBtn);

    $addFileBtn.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        addNewFileForm($collectionFile, $newBtn);
        fileIndex++;

    });

}

function addNewFileForm($collectionFile, $newBtn){

    var prototype = $collectionFile.data('prototype');

    var newForm = prototype.replace(/__name__/g, fileIndex);

    //display
    var $newFormLi = $('<li></li>').append(newForm);

    $newFormLi.append('<a href="#" class="remove-file">x</a>');

    $newBtn.before($newFormLi);


    $('.remove-file').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();
        nbr--;
        return false;
    });
}