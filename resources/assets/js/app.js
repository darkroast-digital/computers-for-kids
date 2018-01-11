// *************************************************************************
// *************************************************************************
// *************************************************************************

require('./bootstrap');



// #ACCODION
// =========================================================================

$('.accordion__content').hide();
$('.accordion__content').first().show();
$('.accordion__panel').first().addClass('is--open');

$('.accordion__title').click(function() {
    $('.accordion__panel').removeClass('is--open');
    $(this).parent().addClass('is--open');
    $('.accordion__content').slideUp(200);
    $(this).next('.accordion__content').slideDown(200);
});



// #TABS
// =========================================================================

$('li[data-tab], .tabs__content').first().addClass('is--active');
$('.tabs__content').first().addClass('is--active');

$('li[data-tab]').click(function() {
    var thisTab = $(this).attr('data-tab');
    var tab = $('.tabs__content' + '[data-tab="' + thisTab + '"]');

    $('li[data-tab]').removeClass('is--active');
    $(this).addClass('is--active');
    $('.tabs__content').removeClass('is--active');
    tab.addClass('is--active');
});




// #DROPDOWN
// =========================================================================

$('.dropdown__container').mouseenter(function() {
    $(this).addClass('is--active');
});

$('.dropdown__container').mouseleave(function() {
    $(this).removeClass('is--active');
});

$('.dropdown').mouseleave(function() {
    $(this).parent().removeClass('is--active');
});




// #ALERT NOTIFY
// =========================================================================

$('.alert--notify').click(function() {
    $(this).fadeOut(200);
});



// #OFF CANVAS
// =========================================================================

var offCanvasTrigger = document.querySelector('.off-canvas__trigger');
var offCanvas = document.querySelector('.off-canvas');

if (offCanvasTrigger) {
    offCanvasTrigger.addEventListener('click', function () {
        offCanvas.classList.add('is--open');
        overlay.classList.add('is--active');
    });
}


var adminOffCanvasTrigger = document.querySelector('.admin-off-canvas__trigger');
var adminOffCanvas = document.querySelector('.off-canvas-admin');

if (adminOffCanvasTrigger) {
    adminOffCanvasTrigger.addEventListener('click', function () {
        adminOffCanvas.classList.add('is--open');
        overlay.classList.add('is--active');
    });
}



// #MODAL
// =========================================================================

var modalTrigger = $('.modal__trigger');

modalTrigger.click(function(){
    var thisModal = $(this).data('modal');
    var modal = $(`.modal[data-modal="${ thisModal }"]`);
    modal.addClass('is--open');
    $('.overlay').addClass('is--active');
    $('body').css('overflow', 'hidden');
})




// #KEY CONTROL
// =========================================================================

$(document).keyup(function(e) {
    if (e.keyCode === 27) {
        $('.overlay').removeClass('is--active');
    }
});


if ($('.off-canvas') || $('.off-canvas-admin')) {
    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            $('.off-canvas').removeClass('is--open');
        }
    });

    $('a').click(function(){
        if ($(this).not('.modal__trigger')) {
            $('.off-canvas').removeClass('is--open');
            $('.overlay').removeClass('is--active');
        }
    });
}


if ($('.modal')) {
    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            $('.modal').removeClass('is--open');
            $('body').css('overflow', 'scroll');
        }
    });
}




// #OVERLAY
// =========================================================================

var overlay = document.querySelector('.overlay');

if (overlay) {
    overlay.addEventListener('click', function () {
        this.classList.remove('is--active');
    });
}

if (overlay) {
    overlay.addEventListener('click', function () {
        offCanvas.classList.remove('is--open');
        adminOffCanvas.classList.remove('is--open');
    });
}

if ($('.overlay')) {
    $('.overlay').click(function() {
        $('.modal').removeClass('is--open');
        $('body').css('overflow', 'scroll');
    });
}



// #DOCS
// =========================================================================
//smooth scrolling
$(document).on('click', 'a', function(event){
    // event.preventDefault();

    $('html, body').animate({
        scrollTop: $( $.attr(this, 'href') ).offset().top
    }, 600);
});




// #SIMPLE MDE
// =========================================================================

var mde = document.getElementById('mde')

if (mde) {
    var simplemde = new SimpleMDE({ 
        element: mde,
        hideIcons: [
            'fullscreen',
            'side-by-side'
        ]
    });
}




// #ALERT FADE OUT
// =========================================================================

$('.alert').delay(6000).fadeOut("slow");




// #IMAGE PREVIEW
// =========================================================================

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.image-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$('.image-upload input').change(function() {
    $(this).parent().find('span').hide();
    readURL(this);
    $('.image-preview').show();
})




// #GMAPS
// =========================================================================

var map = new GMaps({
    el:'#map',
    lat:42.283565,
    lng: -83.087051
});

map.addMarker({
    lat:42.283565,
    lng: -83.087051
});
