$(function() {
    $('#subscriptionFeesContainer .sortable').orderableList({
        listToOrder: function (orders) {
            reloadSubscriptionFeesList('order', orders);
        }
    });
});

    $(document).on('click', '.delSubscription', function(e)
{
    e.preventDefault();

    $(this).deleteConfirm({
        'text': Translator.trans('subscription.subscription.delete.deleteQuestionText'),
        'route': 'subscription_subscription_delete',
        'successCallback': function(){
            reloadSubscriptionsList();
        }
    });
});

function reloadSubscriptionsList()
{
    var $container =  $('#subscriptionsContainer');
    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('subscription_subscription_list_part', {'anchor': $container.data('anchor')}),
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknow'),
            "error"
        )
    });
}

function reloadSubscriptionFeesList()
{
    var $container =  $('#subscriptionsContainer');
    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('subscription_subscription_list_part', {'anchor': $container.data('anchor')}),
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknow'),
            "error"
        )
    });
}