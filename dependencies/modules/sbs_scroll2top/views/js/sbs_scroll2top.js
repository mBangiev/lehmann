/**
 * NTScroll2Top
 *
 * @category  Module
 * @author    silbersaiten <info@silbersaiten.de>
 * @support   silbersaiten <support@silbersaiten.de>
 * @copyright 2021 silbersaiten
 * @version   1.0.3
 * @link      http://www.silbersaiten.de
 * @license   See joined file licence.txt
 */

$(document).ready(function () {
    var scroll2 = $('#scroll2Top a');
    var view = $(window);

    scroll2.click(function () {
        $("html, body").animate({scrollTop: 0}, 300);
    });

    window.addEventListener("scroll", function () {
        var heightView = $(window).height();
        var scroll = this.scrollY;
        
        if (heightView < scroll * 3) {
            scroll2.show();
        } else {
            scroll2.hide();
        }
    });
});
