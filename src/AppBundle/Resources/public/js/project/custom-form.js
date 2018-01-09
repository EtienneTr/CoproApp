var $collectionHolder;
var nbr = 0;
var optBnr = [];
// setup an "add a tag" link
var $addTagLink = $('<a href="#" class="add_survey_link btn btn-default">Ajouter une question</a>');
var $newLinkLi = $('<div></div>').append($addTagLink);

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('div.survey');

    if($collectionHolder.find('#project_survey_0').length) {
        setEditForm($collectionHolder);

    } else {
        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addTagLink.on('click', function (e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see next code block)
            addTagForm($collectionHolder, $newLinkLi);
            addOptions($collectionHolder, nbr);
            nbr++;
        });
    }
    
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
    var $newFormLi = $('<div></div>').append(newForm);

    $newFormLi.append('<a href="#" class="remove-tag btn btn-danger">Supprimer</a>');

    $newLinkLi.before($newFormLi);

    $('.remove-tag').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();
        nbr--;
        return false;
    });

}

function addOptions($surveyCollection, $nbr) {
    var option = $surveyCollection.find("#project_survey_" + $nbr + "_options");
    var prototype = option.data('prototype');
    var jproto = $(prototype);

    jproto.find('label').addClass('control-label');
    jproto.find('label').parent().addClass('form-group');
    jproto.find('input').addClass('form-control');

    prototype = jproto.prop('outerHTML');
    option.append($('<div></div>').append(prototype.replace(/__opt__/g, "1")));
    option.append($('<div></div>').append(prototype.replace(/__opt__/g, "2")));
    optBnr[$nbr] = 3;

    // setup an "add a tag" link
    var $addOptionLink = $('<a href="#" class="add_survey_link btn btn-default">Ajouter une réponse possible</a>');
    var $newLink = $('<div></div>').append($addOptionLink);
    option.append($newLink);

    $addOptionLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        $newLink.before($('<div></div>').append(prototype.replace(/__opt__/g, optBnr[$nbr])));
        optBnr[$nbr]++;

    });

}

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

function setEditForm($collection){
    var nbSurv = 0;
    var nbOpt = 0;
    var $survey = $collection.find('#project_survey_' + nbSurv);
    while($survey.length > 0){
        var $option = $survey.find('#project_survey_' + nbSurv +'_options_' + nbOpt).find('label');
        while($option.length > 0){
            $option.text($option.text().replace(/__opt__/g, ++nbOpt));
            $option = $survey.find('#project_survey_' + nbSurv +'_options_' + nbOpt).find('label');
        }
        nbSurv++;
        $survey = $collection.find('#project_survey_' + nbSurv);
    }

}