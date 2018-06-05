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

    $('#display_all_new_fee_coming_soon').on('click', function(){

    });
});

function reloadMemberList(action, paginatorOrders){
    var $container =  $('#members');
    
    $container.reloadlist({
        masterRoute: 'members_manager',
        remoteURL: Routing.generate('member_member_list_part', {'anchor': $container.data('anchor')}),
        action: action,
        otherData: paginatorOrders,
        remoteSucessCallBack: function(){
	        	$('.sortable').orderableList({
	        		listToOrder: function(orders){
	        			reloadMemberList('order', orders);			
	        		}		
	        	});        	
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