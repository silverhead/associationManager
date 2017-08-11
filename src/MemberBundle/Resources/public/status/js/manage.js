$(document).on('click', '#addStatus', function(e){
    e.preventDefault();

    $(this).savePrompt({
        title: Translator.trans("member.status.add.title"),
        route: Routing.generate('member_status_save_json'),
        data:{'label': ''},
        inputDataName: 'label',
        successCallback: function(){
            reloadMemberStatusList();
        }
    });
});

$(document).on('click', '.editStatus', function(e){
    e.preventDefault();

    var $elmt = $(this);

    $elmt.savePrompt({
        title: Translator.trans("member.status.edit.title"),
        route: Routing.generate('member_status_save_json'),
        data:{
            'label': $elmt.data('label'),
            'id': $elmt.data('id')
        },
        inputDataName: 'label',
        successCallback: function(){
            reloadMemberStatusList('edit');
        }
    });
});

$(document).on('click', '.delStatus', function(e) {
    e.preventDefault();

    $(this).deleteConfirm({
        'text': Translator.trans("member.status.delete.deleteQuestionText"),
        'route': 'member_status_delete',
        'successCallback': function(){
            reloadMemberStatusList('delete');
        }
    });
});

function reloadMemberStatusList(action){
    var $container =  $('#memberStatusContainer');
    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('member_status_list_part', {'anchor': $container.data('anchor')}),
        action: action,
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknow'),
            "error"
        )
    });
}