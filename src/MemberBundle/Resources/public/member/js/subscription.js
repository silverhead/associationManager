$(function(){

    $('.panel-group .panel-heading a').click(function(){
        var $this = $(this);
        var alreadyLoading = $this.data('alreadyloading');

        if("0" == alreadyLoading){
            var id = $this.data('subscription-historical-id');
            var container = $this.data('parent');
            var $listContainer = $('#' + container).find('.panel-body');

            $listContainer.reloadlist({
                masterRoute: 'member_view',
                remoteURL: Routing.generate('member_subscription_fees_list_part', {'subHistId': id, 'anchor': $listContainer.data('anchor')}),
                action: 'list',
                remoteSucessCallBack: function(){
                    $this.data('alreadyloading', 1);
                },
                remoteErrorCallBack: function(){
                    swal(
                        Translator.trans('app.common.errorTitle'),
                        Translator.trans('app.common.errorUnknown'),
                        "error"
                    );
                }
            });
        }
    });

    setSubformField();

    $('#member_member_subscription_form_type_subscription').change(function(elt){
        setSubformField();
    });
});

function setSubformField(){
    var selectedOption = $('#member_member_subscription_form_type_subscription').find('option:selected');

    var cost = selectedOption.data('cost');

    var periodicities = selectedOption.data('periodicities').toString().split(",");
    
    var duration = selectedOption.data('duration');

    var startDate = $('#member_member_subscription_form_type_startDate').val();

    var endDate = moment(startDate, "YYYY-MM-DD").add(duration, "days").format("YYYY-MM-DD");

    $('#member_member_subscription_form_type_endDate').val(endDate);

    $('#member_member_subscription_form_type_cost').val( parseFloat(cost).toFixed(2));

    $('#member_member_subscription_form_type_subscriptionPaymentPeriodicity').find('option:selected').removeAttr('selected');
    $('#member_member_subscription_form_type_subscriptionPaymentPeriodicity').find('option').prop('disabled', true);

    for(var i in periodicities){
        $('#member_member_subscription_form_type_subscriptionPaymentPeriodicity').find('option[value='+periodicities[i]+']').prop('disabled', false);
    }
    $('#member_member_subscription_form_type_subscriptionPaymentPeriodicity').find('option[value='+periodicities[0]+']').prop('selected', true);

    $('#member_member_subscription_form_type_subscriptionPaymentPeriodicity').selectpicker('refresh');
}

$('.renewBtn').on('click', function(e){
    e.preventDefault();

    var id  = 0;//$(this).data('id');
    //var subscriptionLabel = $(this).data('subscription-label');
    var urlEdit = Routing.generate('member_subscription_renew', {'id': id});

    var title = Translator.trans('member.subscriptionFee.edit.title');
    //title = title.replace('%subscription%', subscriptionLabel);

    //$('#editSubscriptionFeeLabel').text(title);

    $('#renewSubscriptionModal .modal-body').load(urlEdit,function(){
        $('#renewSubscriptionModal').modal({show:true});
    });
});