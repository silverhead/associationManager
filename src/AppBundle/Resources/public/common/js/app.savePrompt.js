(function($){
    $.fn.savePrompt = function(options) {
        var defauts = {
            title: null,
            text: null,
            route: null,
            data: {},
            inputDataName: null,
            successCallback: function () {
            }
        };

        var parameters = $.extend(defauts, options);


        var savePrompt = function(){
            swal({
                title: parameters.title,
                text: parameters.text,
                type: 'input',
                inputValue: parameters.data[parameters.inputDataName],
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                confirmButtonText: Translator.trans("app.common.validBtn"),
                cancelButtonText: Translator.trans("app.common.cancelBtn"),
            }, function(inputValue){
                if (inputValue === false) return false;

                if (inputValue === "") {
                    swal.showInputError(Translator.trans('app.common.form.validation.notBlank'));
                    return false;
                }
                parameters.data[parameters.inputDataName] = inputValue;

                $.ajax({
                    'url': parameters.route,
                    'type': 'post',
                    'dataType': 'json',
                    'data': parameters.data,
                    'success': function(data){
                        var title = Translator.trans('app.common.errorTitle');
                        if(data.code == 'success'){
                            title = Translator.trans('app.common.successTitle');
                            reloadMemberStatusList();
                        }

                        swal(title, data.message, data.code);
                    },
                    'error': function(){
                        swal(Translator.trans("app.common.errorTitle"), Translator.trans("app.common.errorUnknow"), "error");
                    }
                });
            });
        };

        return this.each(function () {
            savePrompt();
        });
    };
}( jQuery ));