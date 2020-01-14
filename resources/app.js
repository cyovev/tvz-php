$(document).ready(function() {
    // hamburger menu toggler
    $('#nav-toggler').on('click', function() {
        $(this).toggleClass('active');
        $('header ul').slideToggle();
    });

    // select2 using images in <option>
    $('select.basic-select').select2({
        formatResult: select2AddImage,
        formatSelection: select2AddImage,
        escapeMarkup: function(m) { return m; }
    });

    $('.message .close').on('click', function(e) {
        e.preventDefault();
        $('.message').slideUp();
    });

    $('.datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1950:2000',
        dateFormat: 'yy-mm-dd'
    });

});

function select2AddImage(item) {
    var originalOption = item.element;
    var image          = $(originalOption).data('image');

    // if there is data-image attribute, add it in front of the text
    return (image ? "<img class='select2-image' src='" + image + "' />" : "") + item.text;
}