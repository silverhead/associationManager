$(function(){
	$('#members .sortable').orderableList({
		listToOrder: function(orders){
			reloadMemberList('order', orders);			
		}		
	});	
	
    $('.delMember').on('click', function(e){
        e.preventDefault();

        var id = $(this).data('id');

        swal({
                title: Translator.trans('app.common.deleteQuestionTitle'),
                text: Translator.trans('member.member.delete.deleteQuestionText'),
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
                    swal(translator.trans('app.common.deleteTitle'), Translator.trans('app.common.deleteQuestionNo'));
                }
            });
    });

    $('[data-original-title]').tooltip();
});

$(document).on('click', '.display_all_new_fee_coming_soon', function(){
    var checked = $('#display_all_new_fee_coming_soon').is(':checked');

    reloadMemberList('list', '&new_fee_coming_soon=' + (checked == true?"1":"0"));
});

$(document).on('click', '.display_all_late_payment_member', function(){
    var checked = $('#display_all_late_payment_member').is(':checked');

    reloadMemberList('list', '&display_all_late_payment_member=' + (checked == true?"1":"0"));
});

$(document).on('click', '#memberFilterBtn', function(event){
    event.preventDefault();

    var form = $(this).parents('form');

    var data = form.serialize();

    reloadMemberList('', data, 'post');

    return false;
});

$(document).on('click', '#send_new_fee_coming_soon', function(){
    $.ajax({
        'type': 'post',
        'dataType': 'json',
        'url' : Routing.generate('member_member_send_soon_fee_new_payment_mail'),
        'success' : function(data)
        {
            swal(
                Translator.trans('member.email.sendingEndOfSubscriptionMailSuccessTitle'),
                Translator.transChoice('member.email.sendingEndOfSubscriptionBody', data['nbMailsSent'], {'nbMailsSent' : data['nbMailsSent']}),
                "success"
            );
        },
        'error': function()
        {
            swal(
                Translator.trans('app.common.errorTitle'),
                Translator.trans('app.common.errorUnknow'),
                "error"
            );
        }
    });
});

$(document).on('click', '#send_late_payment_member', function(){
    $.ajax({
        'type': 'post',
        'dataType': 'json',
        'url' : Routing.generate('member_member_send_late_payment_mail'),
        'success' : function(data)
        {
            swal(
                Translator.trans('member.email.sendingLatePaymentMailSuccessTitle'),
                Translator.transChoice('member.email.sendingLatePaymentBody', data['nbMailsSent'], {'nbMailsSent' : data['nbMailsSent']}),
                "success"
            );
        },
        'error': function()
        {
            swal(
                Translator.trans('app.common.errorTitle'),
                Translator.trans('app.common.errorUnknow'),
                "error"
            );
        }
    });
});

function reloadMemberList(action, paginatorData, type){
    var $container =  $('#members');

    if (typeof (type) == 'undefined'){
        type = 'get';
    }

    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('member_member_list_part', {'anchor': $container.data('anchor')}),
        action: action,
        otherData: paginatorData,
        type: type,
        remoteSucessCallBack: function(){
	        	$('.sortable').orderableList({
	        		listToOrder: function(orders){
	        			reloadMemberList('order', orders);			
	        		}		
	        	});

                $('[data-original-title]').tooltip();
        },
        remoteErrorCallBack: function(){
        		swal(
	            Translator.trans('app.common.errorTitle'),
	            Translator.trans('app.common.errorUnknow'),
	            "error"
            );
        }
    });
}