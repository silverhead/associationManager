$(document).on('click', '.delPeriodicity', function(e)
{
    e.preventDefault();

    var id = $(this).data('id');

    swal({
            title: Translator.trans('app.common.deleteQuestionTitle'),
            text: Translator.trans('app.subscription.periodicity.delete.deleteQuestionText'),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: Translator.trans('app.common.deleteQuestionYes'),
            cancelButtonText: Translator.trans('app.common.deleteQuestionNo'),
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if(isConfirm){
                $.ajax({
                    'type':'POST',
                    'url': Routing.generate('subscription_periodicity_delete', {'id': id}),
                    'dataType': 'json',
                    'success': function(data){

                        var title = Translator.trans('app.common.errorTitle');
                        if(data.code == 'success'){
                            title = Translator.trans('app.common.deleteTitle');

                            reloadPeriodicitiesList();
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

function reloadPeriodicitiesList()
{
    var $container =  $('#periodicitiesContainer');
    $container.reloadlist({
        masterRoute: 'subscription_manager',
        remoteURL: Routing.generate('subscription_periodicity_list_part', {'anchor': $container.data('anchor')}),
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknow'),
            "error"
        )
    });
}