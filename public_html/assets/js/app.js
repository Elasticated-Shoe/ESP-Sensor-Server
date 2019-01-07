$(document).ready(function(){
    $(document).foundation() // initialize foundation framework

    $('.slide-container').slick({
        // initialize any slick slideshows on the page
        dots: true,
        infinite: true,
        vertical: false,
        speed: 500,
        slidesToShow: 1,
        adaptiveHeight: false,
        arrows: true
    });
});
