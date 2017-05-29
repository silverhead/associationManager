$(document).on('click', '.delSubscription', function(e)
{
    e.preventDefault();

    var id = $(this).data('id');

    swal({
            title: Translator.trans('app.common.deleteQuestionTitle'),
            text: Translator.trans('app.subscription.subscription.delete.deleteQuestionText'),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: Translator.trans('app.common.deleteQuestionYes'),
            cancelButtonText: Translator.trans('app.common.deleteQuestionNo'),
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                $.ajax({
                    'type':'POST',
                    'url': Routing.generate('subscription_subscription_delete', {'id': id}),
                    'dataType': 'json',
                    'success': function(data){
                        var title = Translator.trans('app.common.errorTitle');
                        if(data.code == 'success'){
                            title = Translator.trans('app.common.deleteTitle');

                            reloadSubscriptionsList();
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

function reloadSubscriptionsList()
{
    var $periodicitiesContainer = $('#subscriptionsContainer');
    var paginatorPageParam = $periodicitiesContainer.data('page-parameter-name');
    var nbRow = $periodicitiesContainer.find('table tbody tr').length;
    var url = window.location.href;

    var anchor = $periodicitiesContainer.data("anchor");

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
        'url': Routing.generate('subscription_subscription_list_part', {'anchor': anchor}),
        'type': 'GET',
        'data': paginatorPageParam+'='+pageCount,
        'dataType': 'html',
        'success': function(template){
            $('#subscriptionsContainer').html(template);
        },
        'error': function(){
            swal(Translator.trans('app.common.errorTitle'),
                Translator.trans('app.common.errorUnknow'), "error");
        }
    });
}