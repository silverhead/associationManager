(function ( $ ) {

    $.fn.reloadlist = function(options) {
        //Set the default parameters
        var defauts = {
            remoteURL: null
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

        return this.each(function () {
            $container = $(this);
            paginatorPageParamName = $container.data('page-parameter-name');

            pageTagRegEx = new RegExp(".*"+paginatorPageParamName+"=(\\d+).*","gi");

        });


        // var $periodicitiesContainer = $('#subscriptionsContainer');
        // var paginatorPageParam = $periodicitiesContainer.data('page-parameter-name');
        // var nbRow = $periodicitiesContainer.find('table tbody tr').length;
        // var url = window.location.href;
        //
        // var anchor = $periodicitiesContainer.data("anchor");
        //
        // var regExp = new RegExp(".*"+paginatorPageParam+"=(\\d+).*","gi");
        //
        // var pageTag = regExp.exec(url);
        //
        // var pageCount = 1;
        // if(null !== pageTag){
        //     var pageCount = parseInt(pageTag.length > 1? pageTag[1]: 1);
        // }
        //
        // if(nbRow ==  1 && pageCount > 1){
        //     pageCount = pageCount -1;
        // }
        //
        // $.ajax({
        //     'url': Routing.generate('subscription_subscription_list_part', {'anchor': anchor}),
        //     'type': 'GET',
        //     'data': paginatorPageParam+'='+pageCount,
        //     'dataType': 'html',
        //     'success': function(template){
        //         $('#subscriptionsContainer').html(template);
        //     },
        //     'error': function(){
        //         swal(Translator.trans('app.common.errorTitle'),
        //             Translator.trans('app.common.errorUnknow'), "error");
        //     }
        // });
    };

}( jQuery ));