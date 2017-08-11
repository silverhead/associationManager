$('#addUserGroup').on('click', function(e){
    e.preventDefault();

    $(this).savePrompt({
        title: Translator.trans('user.group.add.title'),
        route: Routing.generate('user_group_save_json'),
        data: {'label': ''},
        inputDataName: 'label',
        successCallback: function(){
            reloadUserGroupList('add');
        }
    });
});

$(document).on('click','.editUserGroup', function(e){
    e.preventDefault();

    $(this).savePrompt({
        title: Translator.trans('user.group.edit.title'),
        route: Routing.generate('user_group_save_json'),
        data: {'label': $(this).data('label'), 'id': $(this).data('id')},
        inputDataName: 'label',
        successCallback: function(){
            reloadUserGroupList('edit');
        }
    });
});

$(document).on('click','.delUserGroup', function(e){
    e.preventDefault();

    $(this).deleteConfirm({
        'text': Translator.trans("user.groups.delete.deleteQuestionText"),
        'route': 'user_group_delete',
        'successCallback': function(){
            reloadUserGroupList('delete');
        }
    });

    // var id = $(this).data('id');
    //
    // swal({
    //         title: "Êtes-vous sûr ?",
    //         text: "La suppression du groupe est définitf !",
    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: "Oui, je veux le supprimer !",
    //         cancelButtonText: "Non, n'annule ma demande !",
    //         closeOnConfirm: false,
    //         closeOnCancel: true
    //     },
    //     function(isConfirm){
    //         if (isConfirm) {
    //             swal("Supprimé !", "Le groupe a été supprimé.", "success");
    //         }
    //     });
});

function reloadUserGroupList(action){
    var $container =  $('#userGroupsContainer');
    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('user_group_list_part', {'anchor': $container.data('anchor')}),
        action: action,
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknow'),
            "error"
        )
    });
}