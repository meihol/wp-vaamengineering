jQuery(function($) {
    const images = term.images
    $('.swiper-wrapper.awb-image-carousel-wrapper').html('');
    $.each(images, function(index, value) {
        const $slide = $('<div>', {
            class: 'swiper-slide',
            'data-swiper-slide-index': index,
            role: 'group',
            'aria-label': `${index+1} / ${images.length}`
        });
        const $itemWrapper = $('<div>', {
            class: 'fusion-carousel-item-wrapper'
        });
        const $imageWrapper = $('<div>', {
            class: 'fusion-image-wrapper hover-type-none'
        });
        const $image = $('<img>', {
            src: value,
            class: 'attachment-full size-full ls-is-cached lazyloaded',
            alt: `services-${index}`,
            decoding: 'async',
            'data-orig-src': value
        });

        $imageWrapper.append($image);
        $itemWrapper.append($imageWrapper);
        $slide.append($itemWrapper);
        $('.swiper-wrapper.awb-image-carousel-wrapper').append($slide);
    });

    $(".fusion-tabs .nav-tabs li").on("click", function(e) {
        e.preventDefault();
        $(".fusion-tabs .nav-tabs li").removeClass("active");
        $(this).addClass("active");

        var getTabName = $(this).find('a').attr('id')
            // String replace "fusion-tab" to ""
        var getTabName = getTabName.replace("fusion-tab-", "tab-");

        // add class "active" and "in" to gettabname
        $('.tab-content .tab-pane').removeClass('active').removeClass('in')
        $(`#${getTabName}`).addClass('active').addClass('in')

        var swip = document.querySelector('.awb-carousel').swiper;
        swip.slideTo($(this).index(), 200);
    });

})