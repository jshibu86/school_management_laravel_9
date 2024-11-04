$(function () {
    "use strict";
    // search bar
    $(".search-btn-mobile").on("click", function () {
        $(".search-bar").addClass("full-search-bar");
    });
    $(".search-arrow-back").on("click", function () {
        $(".search-bar").removeClass("full-search-bar");
    });
    $(document).ready(function () {
        $(window).on("scroll", function () {
            if ($(this).scrollTop() > 300) {
                $(".top-header").addClass("sticky-top-header");
            } else {
                $(".top-header").removeClass("sticky-top-header");
            }
        });
        $(".back-to-top").on("click", function () {
            $("html, body").animate(
                {
                    scrollTop: 0,
                },
                600
            );
            return false;
        });
    });
    $(function () {
        $(".metismenu-card").metisMenu({
            toggle: false,
            triggerElement: ".card-header",
            parentTrigger: ".card",
            subMenu: ".card-body",
        });
    });
    // Tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    // Metishmenu card collapse
    $(function () {
        $(".card-collapse").metisMenu({
            toggle: false,
            triggerElement: ".card-header",
            parentTrigger: ".card",
            subMenu: ".card-body",
        });
    });
    // toggle menu button
    $(".toggle-btn").click(function () {
        if ($(".wrapper").hasClass("toggled")) {
            // unpin sidebar when hovered
            $(".wrapper").removeClass("toggled");
            $(".sidebar-wrapper").unbind("hover");
        } else {
            $(".wrapper").addClass("toggled");
            $(".sidebar-wrapper").hover(
                function () {
                    $(".wrapper").addClass("sidebar-hovered");
                },
                function () {
                    $(".wrapper").removeClass("sidebar-hovered");
                }
            );
        }
    });
    $(".toggle-btn-mobile").on("click", function () {
        $(".wrapper").removeClass("toggled");
    });
    // chat toggle
    $(".chat-toggle-btn").on("click", function () {
        $(".chat-wrapper").toggleClass("chat-toggled");
    });
    $(".chat-toggle-btn-mobile").on("click", function () {
        $(".chat-wrapper").removeClass("chat-toggled");
    });
    // email toggle
    $(".email-toggle-btn").on("click", function () {
        $(".email-wrapper").toggleClass("email-toggled");
    });
    $(".email-toggle-btn-mobile").on("click", function () {
        $(".email-wrapper").removeClass("email-toggled");
    });
    // compose mail
    $(".compose-mail-btn").on("click", function () {
        $(".compose-mail-popup").show();
    });
    $(".compose-mail-close").on("click", function () {
        $(".compose-mail-popup").hide();
    });
    // === sidebar menu activation js
    $(function () {
        for (
            var i = window.location,
                o = $(".metismenu li a")
                    .filter(function () {
                        return this.href == i;
                    })
                    .addClass("")
                    .parent()
                    .addClass("mm-active");
            ;

        ) {
            console.log("for");
            if (!o.is("li")) break;

            o = o
                .parent("")
                .addClass("mm-show")
                .parent("")
                .addClass("mm-active");
        }
    }),
        // metismenu
        $(function () {
            $("#menu").metisMenu();
        });
    /* Back To Top */
    $(document).ready(function () {
        var activeMenuItem = $(".metismenu .mm-active");

        if (activeMenuItem.length) {
            // Get the top offset of the active menu item relative to the .simplebar-content wrapper
            var itemOffsetTop = activeMenuItem.position().top;

            // Get the height of the active menu item
            var itemHeight = activeMenuItem.outerHeight();

            // Get the height of the visible scrollable area (viewport height)
            var viewportHeight = $(".sidebar-wrapper").height();

            // Get the current scroll position of the sidebar (simplebar-content)
            var currentScroll = $(".simplebar-content").scrollTop();

            console.log("Item Offset:", itemOffsetTop);
            console.log("Item Height:", itemHeight);
            console.log("Viewport Height:", viewportHeight);
            console.log("Current Scroll:", currentScroll);

            // Calculate the new scroll position
            var newScrollPosition =
                currentScroll +
                itemOffsetTop -
                viewportHeight / 2 +
                itemHeight / 2;

            console.log("New Scroll Position:", newScrollPosition);

            if (
                itemOffsetTop + itemHeight > viewportHeight ||
                itemOffsetTop < 0
            ) {
                console.log("Scrolling to position:", newScrollPosition);
                $(".simplebar-content-wrapper").animate(
                    {
                        scrollTop: newScrollPosition,
                    },
                    600
                );
            } else {
                console.log("Menu item is already visible.");
            }
        } else {
            console.log("No active menu item found.");
        }

        $(window).on("scroll", function () {
            console.log("scroll");
            if ($(this).scrollTop() > 300) {
                console.log("scr if");
                $(".back-to-top").fadeIn();
            } else {
                console.log("scr else");
                $(".back-to-top").fadeOut();
            }
        });

        $(".back-to-top").on("click", function () {
            console.log("backtotop");
            $("html, body").animate(
                {
                    scrollTop: 0,
                },
                600
            );
            return false;
        });
    });

    /*switcher*/
    $(".switcher-btn").on("click", function () {
        $(".switcher-wrapper").toggleClass("switcher-toggled");
    });

    $("#darkmode").on("click", function () {
        $("html").attr("class", "dark-theme");
    }),
        $("#lightmode").on("click", function () {
            $("html").attr("class", "light-theme");
        }),
        $("#darksidebar").on("click", function () {
            $("html").attr("class", "dark-sidebar");
        });

    $("#ColorLessIcons").on("click", function () {
        $("html").toggleClass("ColorLessIcons");
    });
});

// /* perfect scrol bar */
// new PerfectScrollbar(".header-message-list");
// new PerfectScrollbar(".header-notifications-list");
