$(function(){
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
                    swal(ranslator.trans('app.common.deleteTitle'), Translator.trans('app.common.deleteQuestionNo'));
                }
            });
    });
});