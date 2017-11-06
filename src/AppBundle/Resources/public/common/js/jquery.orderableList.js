(function ( $ ) {
    $.fn.orderableList = function(options) {
        //Set the default parameters
        var defauts = {
            dataFieldName: 'order-sortname',
            dataSort: 'order-sort',
            className: 'sortable',
            orderVarName: 'orders',
            listToOrder: function(orders){}
        };
        
        var masterFieldName = null;
        var masterSort = null;        
        
        var parameters = $.extend(defauts, options);
        
        var init = function(){
        		setSortingClass();
        };
        
        var setSortingClass = function(){
	        	$('.' + parameters.className).each(function(){
	        		var name = $(this).data(parameters.dataFieldName);
	        		var sort = $(this).data(parameters.dataSort);		
	        		
	        		var classNameReg = /^sorting(_asc|_desc)?$/
	        		
	        		var 	sortClass = sort == 'asc'?'_asc':(sort == 'desc'?'_desc': '');
	        			
	        		$(this).removeClass(classNameReg);
	        		$(this).addClass('sorting' + sortClass);
	        	});        		
        	
        };
        
        var orderList = function(){
	        	console.log(masterFieldName);	        		        	
	        	
	        	var sorting = "";
	        	$('.' + parameters.className).each(function(){
	        		var name = $(this).data(parameters.dataFieldName);
	        		var sort = $(this).data(parameters.dataSort);		
	        		
	        		if(masterFieldName != name){
	        			sorting += '&'+ parameters.orderVarName + '[' + name +']=' + sort;			
	        		}
	        	});
	        	
	        	sorting += '&'+ parameters.orderVarName +'['+ masterFieldName +']=' + (masterSort == 'asc'?'desc': masterSort == 'desc'?'':'asc');
	        	parameters.listToOrder(sorting);	  	        	
        }

        return this.each(function () {
        		init();
            $(this).on('click', function(){
	            	masterFieldName = $(this).data(parameters.dataFieldName);
	            	masterSort = $(this).data(parameters.dataSort);            	
                orderList();            	
            });
        });
    };

}( jQuery ));