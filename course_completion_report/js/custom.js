require(["jquery"],function($) {

    $('#user_list').on('change', function(ev) {
        ev.preventDefault();
        location.href = '/local/course_completion_report/index.php?id='+this.value;
    });



});
