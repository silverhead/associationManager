$(document).ready(function () {
    $('#addDashboardBundle').click(function (e) {
        e.preventDefault();
        var list = $($(this).attr('data-list'));
        // Try to find the counter of the list
        var counter = list.data('widget-counter') | list.children().length;
        // If the counter does not exist, use the length of the list
        if (!counter) { counter = list.children().length; }

        // grab the prototype template
        var newWidget = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data(' widget-counter', counter);

        // create a new list element and add it to the list
        //var newElem = $(list.attr('data-widget-tags')).html(newWidget);
        // newWidget.appendTo(list);
        list.prepend(newWidget);

        recalculIndexRow();
    });
});

$(document).on('click', '.del-bundle', function(){
    var bundleIndex = $(this).data('bundle');

    swal({
            title: "Supprimer l'affichage du module ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Oui",
            cancelButtonText: "Annuler",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (!isConfirm) return false;

            $('#bundle-' + bundleIndex).remove();
            recalculIndexRow();
        });
});

function recalculIndexRow()
{
    var $rows =  $('#collectionContainer').find('.row');

    $rows.each(function(index){
        var num = ++index;
        var idRow = "bundle-" + num;

        $(this).attr('id', idRow);

        $(this).find('.del-bundle').attr('data-bundle', num);
    });
}