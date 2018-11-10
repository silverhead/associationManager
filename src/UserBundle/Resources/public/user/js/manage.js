$(function(){
	$('.sortable').orderableList({
		listToOrder: function(orders){
			reloadUserList('order', orders);			
		}		
	});
});

$(document).on('click','.delUser', function(e){
    e.preventDefault();

    $(this).deleteConfirm({
        'text': Translator.trans("user.user.delete.deleteQuestionText"),
        'route': 'user_user_delete',
        'successCallback': function(){
        		reloadUserList('delete');
        }
    });
});

$(document).on('submit', '#userListFilter', function(){
    event.preventDefault();

    var form = $(this);
    var data = form.serialize();
    reloadUserList('', data, 'post');

    return false;
});


function reloadUserList(action, paginatorOrders, type){
    var $container =  $('#users');
    
    $container.reloadlist({
        masterRoute: 'user_manager',
        remoteURL: Routing.generate('user_list_part', {'anchor': $container.data('anchor')}),
        action: action,
        type: type,
        otherData: paginatorOrders,
        remoteSucessCallBack: function(){
	        	$('.sortable').orderableList({
	        		listToOrder: function(orders){
	        			reloadUserList('order', orders);			
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