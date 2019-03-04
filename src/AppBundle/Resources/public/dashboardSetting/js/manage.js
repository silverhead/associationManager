$('.editDashboardBundleDisplay').on('click', function(e){
    e.preventDefault();

    var id  = parseInt($(this).data('id'));
    var urlEdit = Routing.generate('dashboard_edit', {'id': id});

    var title = Translator.trans('dashboard.form.add_title');

    if(id > 0){
        title = Translator.trans('dashboard.form.edit_title');
    }

    $('#editDashboardBundleTitle').text(title);
    
    $('#saveDashboardBundleBtn').data('id', id);

    var $editDashboardBundleModal = $('#editDashboardBundleModal');

    $editDashboardBundleModal.find('.modal-body').load(urlEdit,function(){
        $editDashboardBundleModal.modal({show:true});
    });
});

$('#saveDashboardBundleBtn').on('click', function(){
    var id  = parseInt($(this).data('id'));
    var formData = $('#editDashboardBundleModal').find('form').serialize();

    $.ajax({
        'url': Routing.generate('dashboard_save', {'id': id}),
        'type': 'post',
        'dataType': 'json',
        'data': formData,
        'success': function(data){
            var title = Translator.trans('app.common.errorTitle');
            if(data.code == 'success'){
                title = Translator.trans('app.common.successTitle');
            }

            $('#editDashboardBundleModal').modal('hide');

            swal(title, data.message, data.code);

            reloadDashBundlesList('edit');
        },
        'error': function(){
            swal(Translator.trans("app.common.errorTitle"), Translator.trans("app.common.errorUnknown"), "error");
        }
    });
});

$(document).on('click','.del-bundle', function(e){
    e.preventDefault();

    $(this).deleteConfirm({
        'text': Translator.trans("dashboard.delete.deleteQuestionText"),
        'route': 'dashboard_delete',
        'successCallback': function(){
            reloadDashBundlesList('delete');
        }
    });
});

function reloadDashBundlesList(action){
    var $container =  $('#dashboardSettingContainer');

    $container.reloadlist({
        masterRoute: 'dashboard_manager',
        remoteURL: Routing.generate('dashboard_list_part', {}),
        action: action,
        otherData: '',
        remoteErrorCallBack: function(){
            swal(
                Translator.trans('app.common.errorTitle'),
                Translator.trans('app.common.errorUnknown'),
                "error"
            );
        }
    });
}

// $(document).ready(function () {
//     $('#addDashboardBundle').click(function (e) {
//         e.preventDefault();
//         var list = $($(this).attr('data-list'));
//         // Try to find the counter of the list
//         var counter = list.data('widget-counter') | list.children().length;
//         // If the counter does not exist, use the length of the list
//         if (!counter) { counter = list.children().length; }
//
//         // grab the prototype template
//         var newWidget = list.attr('data-prototype');
//         // replace the "__name__" used in the id and name of the prototype
//         // with a number that's unique to your emails
//         // end name attribute looks like name="contact[emails][2]"
//         newWidget = newWidget.replace(/__name__/g, counter);
//         // Increase the counter
//         counter++;
//         // And store it, the length cannot be used if deleting widgets is allowed
//         list.data(' widget-counter', counter);
//
//         // create a new list element and add it to the list
//         //var newElem = $(list.attr('data-widget-tags')).html(newWidget);
//         // newWidget.appendTo(list);
//         list.prepend(newWidget);
//
//         recalculIndexRow();
//     });
// });
//
// $(document).on('click', '.del-bundle', function(){
//     var bundleIndex = $(this).data('bundle');
//
//     swal({
//             title: Translator.trans('dashboard.manager.form.deleteQuestionText'),
//             text: "",
//             type: "warning",
//             showCancelButton: true,
//             confirmButtonClass: "btn-danger",
//             confirmButtonText: Translator.trans('app.common.validBtn'),
//             cancelButtonText: Translator.trans('app.common.cancelBtn'),
//             closeOnConfirm: true,
//             closeOnCancel: true
//         },
//         function(isConfirm) {
//             if (!isConfirm) return false;
//
//             $('#bundle-' + bundleIndex).remove();
//             recalculIndexRow();
//         });
// });
//
// function recalculIndexRow()
// {
//     var $rows =  $('#collectionContainer').find('.row');
//
//     $rows.each(function(index){
//         var num = ++index;
//         var idRow = "bundle-" + num;
//
//         $(this).attr('id', idRow);
//
//         $(this).find('.del-bundle').attr('data-bundle', num);
//     });
// }