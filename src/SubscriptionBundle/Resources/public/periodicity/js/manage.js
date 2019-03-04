$(document).on('click', '.delPeriodicity', function(e)
{
    e.preventDefault();

    $(this).deleteConfirm({
        'text': Translator.trans('subscription.periodicity.delete.deleteQuestionText'),
        'route': 'subscription_periodicity_delete',
        'successCallback': reloadPeriodicitiesList()
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
            Translator.trans('app.common.errorUnknown'),
            "error"
        )
    });
}