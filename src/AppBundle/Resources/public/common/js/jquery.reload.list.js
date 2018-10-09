(function ( $ ) {

    $.fn.reloadlist = function(options) {
        //Set the default parameters
        var defauts = {
            remoteURL: null,
            remoteErrorCallBack: function(){},
            remoteSucessCallBack: function(){},
            masterRoute: null,
            action: null,
            nbRowPerPage: null,
            otherData: "",
            type: 'get'
        };

        var parameters = $.extend(defauts, options);

        var $container = null;

        var paginatorPageParamName = null;

        var pageTagRegEx = null;

        var getPageCount = function()
        {
            var url = window.location.href;
            var nbRow = $container.find('table tbody tr').length;
            var pageCount = 1;
            var pageTag = pageTagRegEx.exec(url);

            if(null !== pageTag){
                var pageCount = parseInt(pageTag.length > 1? pageTag[1]: 1);
            }

            if(parameters.action == 'delete' && nbRow ==  1 && pageCount > 1) {
                pageCount = pageCount - 1;
            }

            return pageCount;
        };

        var updateList = function()
        {
        	var otherData = parameters.otherData;

        	if (otherData!="" && otherData.substr(0,1) != "&"){
                otherData = "&" + otherData;
            }

            $.ajax({
                'url': parameters.remoteURL,
                'type': parameters.type,
                'data': paginatorPageParamName+'='+getPageCount()+ (parameters.masterRoute == null?null:'&masterRoute='+parameters.masterRoute) + otherData,
                'dataType': 'html',
                'success': function(template){
                    $container.html(template);
                    
                    parameters.remoteSucessCallBack();
                },
                'error': function(jqXHR, textStatus, errorThrown){
                    parameters.remoteErrorCallBack();
                }
            });
        };

        return this.each(function () {
            $container = $(this);
            paginatorPageParamName = $container.data('page-parameter-name');
            pageTagRegEx = new RegExp(".*"+paginatorPageParamName+"=(\\d+).*","gi");

            updateList();
        });
    };

}( jQuery ));