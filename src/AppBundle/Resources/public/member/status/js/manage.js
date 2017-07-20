$(document).on('click', '#addStatus', function(e){
    e.preventDefault();

    swal({
        title: Translator.trans("app.member.status.add.title"),
        text: "",
        type: 'input',
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        confirmButtonText: Translator.trans("app.common.validBtn"),
        cancelButtonText: Translator.trans("app.common.cancelBtn"),
    }, function(inputValue){
        if (inputValue === false) return false;

        if (inputValue === "") {
            swal.showInputError(Translator.trans('app.common.form.validation.notBlank'));
            return false;
        }

        $.ajax({
            'url': Routing.generate('member_status_save_json'),
            'type': 'post',
            'dataType': 'json',
            'data': 'label='+inputValue,
            'success': function(data){
                var title = Translator.trans('app.common.errorTitle');
                if(data.code == 'success'){
                    title = Translator.trans('app.common.successTitle');

                    reloadMemberStatusList();
                }

                swal(title, data.message, data.code);
            },
            'error': function(){
                swal(Translator.trans("app.common.errorTitle"), Translator.trans("app.common.errorUnknow"), "error");
            }
        });
    });
});

$(document).on('click', '.editStatus', function(e){
    e.preventDefault();

    var label = $(this).data('label');
    var id = $(this).data('id');

    swal({
        title: Translator.trans("app.member.status.edit.title"),
        text: "",
        type: 'input',
        inputValue: label,
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        confirmButtonText: Translator.trans("app.common.validBtn"),
        cancelButtonText: Translator.trans("app.common.cancelBtn"),
    }, function(inputValue){
        if (inputValue === false) return false;

        if (inputValue === "") {
            swal.showInputError(Translator.trans('app.common.form.validation.notBlank'));
            return false;
        }

        $.ajax({
            'url': Routing.generate('member_status_save_json'),
            'type': 'post',
            'dataType': 'json',
            'data': {'label': inputValue, 'id': id },
            'success': function(data){
                var title = Translator.trans('app.common.errorTitle');
                if(data.code == 'success'){
                    title = Translator.trans('app.common.successTitle');
                    reloadMemberStatusList();
                }

                swal(title, data.message, data.code);
            },
            'error': function(){
                swal(Translator.trans("app.common.errorTitle"), Translator.trans("app.common.errorUnknow"), "error");
            }
        });
    });
});

$(document).on('click', '.delStatus', function(e){
    e.preventDefault();

    var id = $(this).data('id');

    swal({
            title: Translator.trans("app.common.deleteQuestionTitle"),
            text: Translator.trans("app.member.status.delete.deleteQuestionText"),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: Translator.trans("app.common.deleteQuestionYes"),
            cancelButtonText: Translator.trans("app.common.deleteQuestionNo"),
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                $.ajax({
                    'type':'POST',
                    'url': Routing.generate('member_status_delete', {'id': id}),
                    'dataType': 'json',
                    'success': function(data){
                        var title = Translator.trans('app.common.errorTitle');
                        if(data.code == 'success'){
                            title = Translator.trans('app.common.deleteTitle');

                            reloadMemberStatusList();
                        }

                        swal(title, data.message, data.code);
                    },
                    'error': function(){
                        swal(Translator.trans('app.common.errorTitle'),
                            Translator.trans('app.common.errorUnknow'), "error");
                    }
                });
            }
        });
});

function reloadMemberStatusList(){
    var $container =  $('#memberStatusContainer');
    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('member_status_list_part', {'anchor': $container.data('anchor')}),
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknow'),
            "error"
        )
    });
}