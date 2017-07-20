(function ( $ ) {

    $.fn.reloadlist = function(options) {
        //Set the default parameters
        var defauts = {
            remoteURL: null,
            remoteErrorCallBack: function(){},
            masterRoute: null
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

            if(nbRow ==  1 && pageCount > 1) {
                pageCount = pageCount - 1;
            }

            return pageCount;
        };

        var updateList = function()
        {
            $.ajax({
                'url': parameters.remoteURL,
                'type': 'GET',
                'data': paginatorPageParamName+'='+getPageCount()+ (parameters.masterRoute == null?null:'&masterRoute='+parameters.masterRoute),
                'dataType': 'html',
                'success': function(template){
                    $container.html(template);
                },
                'error': function(){
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