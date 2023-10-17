function makeGalleryPhotoBoxSameHeight() {
    var maxHeight = 0;
    $.each([".title", ".description"], function (index, element) {
      maxHeight = 0;
      $(".item_photo " + element).height("auto");
      $(".item_photo " + element).each(function () {
        if ($(this).height() > maxHeight) {
          maxHeight = $(this).height();
        }
      });
      if ($(window).width() > 576) {
        $(".item_photo " + element).height(maxHeight);
      }
    });
  }
  
  function makeVideoBoxSameHeight() {
    var maxHeight = 0;
    $.each([".name", ".description"], function (index, element) {
      maxHeight = 0;
      $(".item-video " + element).height("auto");
      $(".item-video " + element).each(function () {
        if ($(this).height() > maxHeight) {
          maxHeight = $(this).height();
        }
      });
      if ($(window).width() > 576) {
        $(".item-video " + element).height(maxHeight);
      }
    });
  }
  
  function makePostBoxSameHeight() {
    var maxHeight = 0;
    $.each([".name", ".summary"], function (index, element) {
      maxHeight = 0;
      $(".list_post .c_post:not(.onecol) " + element).height("auto");
      $(".list_post .c_post:not(.onecol) " + element).each(function () {
        if ($(this).height() > maxHeight) {
          maxHeight = $(this).height();
        }
      });
      if ($(window).width() > 576) {
        $(".list_post .c_post:not(.onecol) " + element).height(maxHeight);
      }
    });
  }
  
  function makeProductBoxSameHeight() {
    var maxHeight = 0;
    $.each([".name, .infos"], function (index, element) {
      maxHeight = 0;
      $(".product-grid-item " + element).height("auto");
      $(".product-grid-item " + element).each(function () {
        if ($(this).height() > maxHeight) {
          maxHeight = $(this).height();
        }
      });
      if ($(window).width() > 576) {
        $(".product-grid-item " + element).height(maxHeight);
      }
    });
  }
  
  function makeVideosSameHeight() {
    if ($(window).width() > 991) {
      $(".small-videos").height($(".big-video").height());
    } else {
      $(".small-videos").height("auto");
    }
  }
  
  function buy(t) {
    addToCart(t, 1, true);
    return false;
  }
  
  function makeStickyMainNav() {
    if ($(window).width() > 576) {
      var navMenu = $("#nav-menu");
      navMenu.removeClass("stick");
      var navMenuPosition = navMenu.position();
      if ($(window).scrollTop() >= navMenuPosition.top) {
        navMenu.addClass("stick");
      } else {
        navMenu.removeClass("stick");
      }
    }
  }
  
  $(window).on("load", function () {
    if ($(".filter-best-buy .owl-stage").eq(0).width() > $(".filter-best-buy .owl-stage-outer").eq(0).width()) {
      $(".filter-best-buy .owl-nav").addClass("show");
    }
  });
  
  $(document).ready(function () {
    $("#fixed-adv-bottom .x-close").on("click", function () {
      $("#fixed-adv-bottom").hide();
      $("#header_top").removeClass("has-adv-bottom");
    });
  
    $(".submenu-caret-wrapper").on("click", function (event) {
      event.stopPropagation();
      event.preventDefault();
      $(this).closest("li").toggleClass("open");
    });
  
    $("#product-nav-tree").on("click", function (event) {
      $(this).toggleClass("open");
    });
  
    $("#tree .caret-wrapper").on("click", function (event) {
      $(this).closest("li").find("> ul").toggle();
    });
  
    makeGalleryPhotoBoxSameHeight();
    makeVideoBoxSameHeight();
    $(window).on("load resize", function () {
      makeGalleryPhotoBoxSameHeight();
      makeVideoBoxSameHeight();
    });
  
    makePostBoxSameHeight();
    $(window).on("load resize", function () {
      makePostBoxSameHeight();
    });
  
    makeProductBoxSameHeight();
    $(window).on("load resize", function () {
      makeProductBoxSameHeight();
    });
  
    makeVideosSameHeight();
    $(window).on("resize", function () {
      makeVideosSameHeight();
    });
  
    $("#product-nav-tree .show-more").on("click", function (event) {
      event.stopPropagation();
      event.preventDefault();
      $("#product-nav-tree > ul > li").toggleClass("showing-more");
    });
  
    $("#product-nav-tree-slideshow .show-more").on("click", function (event) {
      event.stopPropagation();
      event.preventDefault();
      $("#product-nav-tree-slideshow > ul > li").toggleClass("showing-more");
    });
  
    $("#product-nav-tree-othersite .show-more").on("click", function (event) {
      event.stopPropagation();
      event.preventDefault();
      $("#product-nav-tree-othersite > ul > li").toggleClass("showing-more");
    });
  
    $(".box_content iframe").each(function () {
      $(this).attr("src");
      $(this).wrap('<div class="videoshow"></div>');
      $(this).wrap('<div class="videowrapper"></div>');
    });
  
    $(".box_info_page iframe").each(function () {
      $(this).attr("src");
      $(this).wrap('<div class="videoshow"></div>');
      $(this).wrap('<div class="videowrapper"></div>');
    });
  
    $(".detail_post .description img").each(function () {
      var imageUrl = $(this).attr("src");
      $(this).wrap('<a href="' + imageUrl + '" data-fancybox="group"></a>');
    });
  
    if ($(window).width() < 576) {
      $("[data-fancybox]").fancybox({
        slideShow: true,
        loop: true,
        toolbar: false,
        smallBtn: true,
        type: "iframe",
        iframe: {
          preload: true,
          css: {
            width: "100%",
            height: "auto"
          }
        }
      });
    } else {
      $("[data-fancybox]").fancybox({
        slideShow: true,
        loop: true,
        toolbar: false,
        smallBtn: true,
        type: "iframe",
        iframe: {
          preload: true
        }
      });
    }
  });
  
  $(document).ready(function ($) {
    $("#form-filter-basic .search-btn .glyphicon-search").on("click", function () {
      $(".search-input").toggle();
      $(".search-input input").focus();
    });
  
    $(window).on("load resize scroll", function () {
      makeStickyMainNav();
    });
  
    $("#product-nav-tree.mobile").addClass("open");
  });
  
  $(document).ready(function ($) {
    $("#toggle-product-menu").on("click", function () {
      $("#nav_category_list").toggle();
    });
  });
  