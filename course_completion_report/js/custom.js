require(["jquery"],function($) {

    $('#user_list').on('change', function(ev) {
        //ev.preventDefault();
        //location.href = 'index.php?id='+this.value;
        ev.preventDefault();
        $('#mform1').attr('action', 'index.php?id='+this.value).submit();
    });



});
