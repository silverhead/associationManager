(function ( $ ) {

    $.fn.deleteConfirm = function(options) {
        var defauts = {
            title: Translator.trans('app.common.deleteQuestionTitle'),
            text: null,
            route: null,
            dataIdName: 'id',
            successCallback: function(){}
        };

        var parameters = $.extend(defauts, options);

        var dataId = null;

        var deleteConfirm = function(){
            swal({
                    title: parameters.title,
                    text: parameters.text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: Translator.trans('app.common.deleteQuestionYes'),
                    cancelButtonText: Translator.trans('app.common.deleteQuestionNo'),
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm){
                    if(isConfirm){
                        var data = jQuery.parseJSON('{"'+parameters.dataIdName+'":'+dataId+"}");

                        $.ajax({
                            'type':'POST',
                            'url': Routing.generate(parameters.route, data),
                            'dataType': 'json',
                            'success': function(data){

                                var title = Translator.trans('app.common.errorTitle');
                                if(data.code == 'success'){
                                    title = Translator.trans('app.common.deleteTitle');

                                    parameters.successCallback();
                                }

                                swal(title, data.message, data.code);
                            },
                            'error': function(){
                                swal(Translator.trans('app.common.errorTitle'),
                                    Translator.trans('app.common.errorUnknow'), "error");
                            }
                        });
                    }
                });
        };

        return this.each(function () {
                dataId = $(this).data(parameters.dataIdName);
                deleteConfirm();
        });
    };

}( jQuery ));