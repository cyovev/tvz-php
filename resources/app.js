$(document).ready(function(){
    $('#nav-toggler').on('click', function() {
        $(this).toggleClass('active');
        $('header ul').slideToggle();
    });
});