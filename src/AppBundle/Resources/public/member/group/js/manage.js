$('#addGroup').on('click', function(e){
    e.preventDefault();

    $(this).savePrompt({
        title: Translator('app.member.group.add.title'),
        remoteURL: '',
        data: {'label': ''},
        inputDataName: 'label',
        successCallback: function(){
            reloadMemberGroupList('add');
        }
    });
});

$('document').on('click','.editGroup', function(e){
    e.preventDefault();

    $(this).savePrompt({
        title: Translator('app.member.group.edit.title'),
        remoteURL: '',
        data: {'label': $(this).data('label'), 'id': $(this).data('id')},
        inputDataName: 'label',
        successCallback: function(){
            reloadMemberGroupList('add');
        }
    });

    //
    // var label = $(this).data('label');
    //
    // swal({
    //     title: "Modifier le groupe",
    //     text: "",
    //     type: 'input',
    //     inputValue: label,
    //     showCancelButton: true,
    //     closeOnConfirm: false,
    //     animation: "slide-from-top",
    //     confirmButtonText: "Valider",
    //     cancelButtonText: "Annuler",
    // }, function(inputValue){
    //     console.log("You wrote", inputValue);
    // });
});

$('document').on('click','.delGroup', function(e){
    e.preventDefault();

    var id = $(this).data('id');

    swal({
            title: "Êtes-vous sûr ?",
            text: "La suppression du groupe est définitf !",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Oui, je veux le supprimer !",
            cancelButtonText: "Non, n'annule ma demande !",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                swal("Supprimé !", "Le groupe a été supprimé.", "success");
            }
        });
});

function reloadMemberGroupList(action){
    var $container =  $('#memberGroupsContainer');
    $container.reloadlist({
        masterRoute: 'groups_manager',
        remoteURL: Routing.generate('member_group_list_part', {'anchor': $container.data('anchor')}),
        action: action,
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknow'),
            "error"
        )
    });
}