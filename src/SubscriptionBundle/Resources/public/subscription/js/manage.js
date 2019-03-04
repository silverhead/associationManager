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


    $(document).on('click', '#subscriptionFeeFilterBtn', function(event){
        event.preventDefault();

        submitFilter($(this));
    });
});

function submitFilter(formElement)
{
    var form = formElement.parents('form');
    var data = form.serialize();
    reloadSubscriptionsList(data, 'post');

    return false;
}



function reloadSubscriptionsList()
{
    var $container =  $('#subscriptionsContainer');
    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('subscription_subscription_list_part', {'anchor': $container.data('anchor')}),
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknown'),
            "error"
        )
    });
}

function reloadSubscriptionFeesList(data, type)
{
    if (typeof (type) == 'undefined'){
        type = 'get';
    }

    var $container =  $('#subscriptionsContainer');
    $container.reloadlist({
        masterRoute: 'members_manager',
        otherData: paginatorData,
        type: type,
        remoteURL: Routing.generate('subscription_subscription_list_part', {'anchor': $container.data('anchor')}),
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknown'),
            "error"
        )
    });
}