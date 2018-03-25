$('.editFee').on('click', function(e){
    e.preventDefault();

    var id  = $(this).data('id');
    var subscriptionLabel = $(this).data('subscription-label');
    var urlEdit = Routing.generate('member_subscription_fees_edit', {'id': id});

    var title = Translator.trans('member.subscriptionFee.edit.title');
        title = title.replace('%subscription%', subscriptionLabel);

    $('#editSubscriptionFeeLabel').text(title);

    $('#editSubscriptionFeeModal .modal-body').load(urlEdit,function(){
        $('#editSubscriptionFeeModal').modal({show:true});
    });
});

$('#saveFeeBtn').on('click', function(){
    $('#editSubscriptionFeeModal').find('form').submit();
});