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
            title: "Êtes-vous sûr ?",
            text: "La suppression du status est définitf !",
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
                swal("Supprimé !", "Le status a été supprimé.", "success");
            }
        });
});

function reloadMemberStatusList(){
    var $memberStatusContainer = $('#memberStatusContainer');
    var paginatorPageParam = $memberStatusContainer.data('page-parameter-name');
    var anchor = $memberStatusContainer.data('anchor');
    var nbRow = $memberStatusContainer.find('table tbody tr').length;
    var url = window.location.href;

    var regExp = new RegExp(".*"+paginatorPageParam+"=(\\d+).*","gi");

    var pageTag = regExp.exec(url);

    var pageCount = 1;
    if(null !== pageTag){
        var pageCount = parseInt(pageTag.length > 1? pageTag[1]: 1);
    }

    if(nbRow ==  1 && pageCount > 1){
        pageCount = pageCount -1;
    }

    $.ajax({
        'url': Routing.generate('member_status_list_part', {'anchor': anchor}),
        'type': 'POST',
        'data': paginatorPageParam+'='+pageCount,
        'dataType': 'html',
        'success': function(template){
            console.log(template);
            $memberStatusContainer.html(template);
        },
        'error': function(){
            swal(Translator.trans('app.common.errorTitle'),
                Translator.trans('app.common.errorUnknow'), "error");
        }
    });
}