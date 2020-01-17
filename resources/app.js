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

    if (typeof (tinymce) == 'object') {
        $('textarea.tinymce').tinymce({
            height: 300,
            menubar: false,
            plugins: [
                "advlist autolink lists link hr code anchor contextmenu paste code"
            ],
            toolbar1: "bold italic underline | bullist numlist | link | alignleft aligncenter alignright alignjustify | outdent indentb | code",
            paste_as_text: true,
            relative_urls: false
        });
    }

    $(document).on('click', '.gallery .thumbs img:not(.active)', function() {
        var $this = $(this),
            src   = $this.attr('src');

        // remove class active from other thumbs
        $this.siblings().removeClass('active');

        // fade out the image, wait for the animation to complete
        // replace the src of the main image using the src of the thumb
        // and then fade back in the image
        $this.parents('.gallery').find('figure > img').fadeOut(300, function() {
            $(this).attr('src', src).fadeIn(300);
        });

        // do the same for the figcaption html using the alt attribute
        $this.parents('.gallery').find('figure > figcaption').html($this.attr('alt'));

        // add class active to current thumb
        $this.addClass('active');
    });

});

function select2AddImage(item) {
    var originalOption = item.element;
    var image          = $(originalOption).data('image');

    // if there is data-image attribute, add it in front of the text
    return (image ? "<img class='select2-image' src='" + image + "' />" : "") + item.text;
}