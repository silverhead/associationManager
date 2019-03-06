$(document).on('click', '.addGroup', function(e){
    e.preventDefault();
    displayMemberGroupEditModal(0);
});

$(document).on('click', '.editGroup', function(e){

    e.preventDefault();

    var id = $(this).data('id');

    displayMemberGroupEditModal(id);
});

$(document).on('click', '.delMemberGroup', function(e) {
    e.preventDefault();
    $(this).deleteConfirm({
        'text': Translator.trans("member.groups.delete.deleteQuestionText"),
        'route': 'member_group_delete',
        'successCallback': function(){
            reloadMemberGroupList('delete');
        }
    });
});


function displayMemberGroupEditModal(id){
    $.ajax({
        'type': 'post',
        'dataType': 'html',
        'url' : (!isNaN(id)?Routing.generate('member_group_edit', {'id': id}):Routing.generate('member_group_add')),
        'success': function(modalHtml){
            $('#membergroupsListTable').after(modalHtml);

            $('#memberGroupModalLabel').modal();

            $('#member_bundle_member_group_form_type_members').selectpicker();

            $('#btnSaveMemberGroup').click(function(e){
                e.preventDefault();

                saveMemberGroup(id);
            });

            $('#btnCancelMemberGroup').click(function(){
                memberGroupEditModalDestroy();
            });
        }
    });
}

function saveMemberGroup(id)
{
    var saveRoute = Routing.generate('member_group_save_json');

    if (typeof(id) != 'undefined'){
        saveRoute = Routing.generate('member_group_save_json', {'id': id});
    }

    $.ajax({
        'type': 'post',
        'dataType': 'json',
        'url' : saveRoute,
        'data': $('#member_group_form_type').serialize(),
        'success': function(data){
            var title = Translator.trans('app.common.errorTitle');
            if (data.code == 'success'){
                title = Translator.trans('app.common.successTitle');
                memberGroupEditModalDestroy();

                reloadMemberGroupList('edit');
            }

            swal(title, data.message, data.code);
        },
        'error': function()
        {
            swal(Translator.trans("app.common.errorTitle"), Translator.trans("app.common.errorUnknown"), "error");
        }
    });
}

function memberGroupEditModalDestroy()
{
    $('#memberGroupModalLabel').modal('hide');
    $('#memberGroupModalLabel').on('hidden.bs.modal', function(){
        $('#memberGroupModalLabel').remove();
    });
}

function reloadMemberGroupList(action){
    var $container =  $('#memberGroupsContainer');
    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('member_groups_list_part', {'anchor': $container.data('anchor')}),
        action: action,
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknown'),
            "error"
        )
    });
}