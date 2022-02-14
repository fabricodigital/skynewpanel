export default class Global {

    constructor() {
        this.noImage();
        this.sidebar();
        this.preloaderAnimation();
        this.mobileTabs();
    }

    noImage() {
        $("img").on("error", function(){
            $(this).attr("src", "/images/admin-panel/no-image.png");
        });
        $(document).ajaxComplete(function(){
            $("img").off("error");
            $("img").on("error", function(){
                $(this).attr("src", "/images/admin-panel/no-image.png");
            });
        });
    }

    sidebar() {
        if(breakpoint("sm")){
            $("body").removeClass("fixed");
        }else if(breakpoint("sm")){
        }else if(breakpoint("md")){
            $(".sidebar-toggle").trigger("click");
        }
    }

    preloaderAnimation() {
        new WOW.WOW({ mobile: false }).init();

        $("#status").fadeOut();
        $("#preloader").fadeOut("slow");

        if(window.location.hash){
            $('html, body').animate({
                scrollTop: $(window.location.hash).offset().top
            }, 500);
        }
    }

    mobileTabs() {
        if(breakpoint("xs")){
            $('.nav-tabs').slick({
                infinite: false,
            });
            $('.nav-tabs').on('beforeChange', function(event, slick, currentSlide, nextSlide){
                var tabID = $(slick.$slides[nextSlide]).find("a").attr("href");
                var tab = $(tabID);
                $(slick.$slides[nextSlide]).find("a").trigger("click");
                $('.tab-pane').removeClass("active");
                $('.tab-pane').hide();
                tab.addClass('active');
                tab.fadeIn();
                tab.animate({
                    scrollTop: 0
                });
            });
        }
    }
}
