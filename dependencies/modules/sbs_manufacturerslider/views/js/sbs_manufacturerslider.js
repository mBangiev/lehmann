/**
 * Sbs_ManufacturersSlider
 *
 * @author    silbersaiten <info@silbersaiten.de>
 * @copyright 2020 silbersaiten
 * @license   See joined file licence.txt
 * @link      http://www.silbersaiten.de
 * @support   silbersaiten <support@silbersaiten.de>
 * @category  Module
 * @version   1.0.0
 */

var SbsManufacturerSlider = {
    init: function () {
        this.owlSliderInit();
    },
    owlSliderInit: function () {
        $('section.sbs_manufacturer_slider .owl-carousel').owlCarousel({
            autoplay: SBS_MANUFACTURER_SLIDER_AUTOPLAY,
            autoplaySpeed: SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED,
            margin: SBS_MANUFACTURER_SLIDER_MARGIN,
            loop: SBS_MANUFACTURER_SLIDER_LOOP,
            items: SBS_MANUFACTURER_SLIDER_ITEMS,
            nav: SBS_MANUFACTURER_SLIDER_NAV,
            dots: SBS_MANUFACTURER_SLIDER_DOTS,
        })
    },
};

$(document).ready(function () {
    SbsManufacturerSlider.init();
});
