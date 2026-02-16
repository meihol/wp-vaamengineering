// jQuery(function($) {
//     var newItem = $('<li class="vaam-more"><span class="awb-menu__main-background-default awb-menu__main-background-default_center"></span><span class="awb-menu__main-background-active awb-menu__main-background-active_center"></span><a href="#Roll%20Forming" class="awb-menu__main-a awb-menu__main-a_regular"><span class="menu-text">More</span><span class="awb-menu__open-nav-submenu-hover"></span></a></li>');
//     newItem.addClass('menu-item menu-item-type-post_type menu-item-object-page menu-item-2209 awb-menu__li awb-menu__main-li awb-menu__main-li_regular');

//     // Append it to the menu
//     $('#menu-corporation-main-menu').append(newItem);

//     function adjustMenu() {
//         var menu = $('#menu-corporation-main-menu');
//         var items = menu.children('li');
//         console.log(items);
//         var moreMenu = $('.vaam-more');
//         var moreDropdown = $('<ul class="awb-menu__sub-ul awb-menu__sub-ul_main"></ul>');

//         // Remove the unwanted spans
//         menu.find('.awb-menu__main-background-default, .awb-menu__main-background-active').remove();
//         menu.find('.awb-menu__main-li .awb-menu__main-li_regular').removeClass();
//         menu.find('.awb-menu__main-li .awb-menu__main-li_regular').removeClass();
        
//         if ($(window).width() <= 1440) {
//             items.each(function (index) {
//                 if (index >= 5 && !$(this).hasClass('vaam-more')) {
//                     moreDropdown.append($(this));
//                 }
//             });
//             moreMenu.append(moreDropdown);
//         } else {
//             menu.children('.vaam-more ul').children('li').each(function () {
//                 menu.append($(this));
//             });
//             moreMenu.remove();
//         }
//     }
    
//     adjustMenu();
//     $(window).resize(adjustMenu);

// });
