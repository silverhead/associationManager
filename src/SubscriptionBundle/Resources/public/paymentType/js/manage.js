$(document).on('click', '#addPaymentType', function(e){
    e.preventDefault();

    $(this).savePrompt({
        title: Translator.trans("subscription.paymentType.add.title"),
        route: Routing.generate('subscription_payment_type_save_json'),
        data:{'label': ''},
        inputDataName: 'label',
        successCallback: function(){
            reloadPaymentTypesList();
        }
    });
});

$(document).on('click', '.editPaymentType', function(e){
    e.preventDefault();

    var $elmt = $(this);

    $elmt.savePrompt({
        title: Translator.trans("subscription.paymentType.edit.title"),
        route: Routing.generate('subscription_payment_type_save_json'),
        data:{
            'label': $elmt.data('label'),
            'id': $elmt.data('id')
        },
        inputDataName: 'label',
        successCallback: function(){
            reloadPaymentTypesList('edit');
        }
    });
});

$(document).on('click', '.delPaymentType', function(e) {
    e.preventDefault();
    $(this).deleteConfirm({
        'text': Translator.trans("subscription.paymentType.delete.deleteQuestionText"),
        'route': 'subscription_payment_type_delete',
        'successCallback': function(){
            reloadPaymentTypesList('delete');
        }
    });
});

function reloadPaymentTypesList(action){
    var $container =  $('#paymentTypesContainer');
    $container.reloadlist({
        masterRoute: 'subscription_manager',
        remoteURL: Routing.generate('subscription_payment_type_list_part', {'anchor': $container.data('anchor')}),
        action: action,
        remoteErrorCallBack: swal(
            Translator.trans('app.common.errorTitle'),
            Translator.trans('app.common.errorUnknown'),
            "error"
        )
    });
}