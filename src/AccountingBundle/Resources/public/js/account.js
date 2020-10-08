$(function() {
    $('#exerciseSelect').change(function(e) {
        var urlExercise = $('option:selected', this).attr('data-urlexercise');
        console.debug(urlExercise);
        window.location = Routing.generate('user_list_part', {'anchor': $container.data('anchor')})
    });
});
