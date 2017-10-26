$(document).on('click','.delUserGroup', function(e){
    e.preventDefault();

    $(this).deleteConfirm({
        'text': Translator.trans("user.groups.delete.deleteQuestionText"),
        'route': 'user_group_delete',
        'successCallback': function(){
            reloadUserGroupList('delete');
        }
    });
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