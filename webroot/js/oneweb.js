$(window).resize(function() {
	resize();
});


//Tìm kiếm sidebar
function typeSearch(val){
	if(val==1){
		$("#search_product").show();
		$("#search_post").hide();
	};
	if(val==2){
		$("#search_product").hide();
		$("#search_post").show();
	}
}

//Document
function detail(id){
	if($("#content #doc_"+id).css('display')=='none') $("#content #doc_"+id).fadeIn(300);
	else $("#content #doc_"+id).fadeOut(100);
}

function resize(){
	w_content = $(".full_width").width();
	w_window = windowSize();
	if(w_window[0]>1200){
		$('.auto_dropdown').mouseover(function(){
			$(this).addClass('open');
		});
		$('.auto_dropdown').mouseout(function(){
			$(this).removeClass('open');
		});
	}
	if(w_window[0]<768){
	}

	//Quảng cáo 2 bên
//	w_wrapper = $(".container").width();
//	alert(w_wrapper);
//	alert(w_window[0]);
	w_col_out = Math.floor((w_window[0]-w_content)/2);	//Kich thước cột hiển thị 2 bên web
//	alert(w_col_out);
	w_adv = $("adv_out").width();				//Kich thước quảng cáo 2 bên web
//	alert(w_adv);
	if(w_col_out > w_adv){		//Kiểm tra xem đủ chỗ trống để hiện thị quảng cáo ko
		$("#adv_left_out").show();
		$("#adv_right_out").show();
		$("#adv_left_out").css('left',w_col_out-w_adv-15);
		$("#adv_right_out").css('right',w_col_out-w_adv-15);
	}else{
		//$("#adv_left_out").hide();
		//$("#adv_right_out").hide();
		$("#adv_left_out").css('left',0);
		$("#adv_right_out").css('right',0);
	}

	//Banner chan web
	if($('div').hasClass('banner_bottom')){
		$(".box_banner.banner_bottom").css({'width':w_wrapper});
		$(".box_banner.banner_bottom .caroufredsel_wrapper").css({'width':w_wrapper});
	}


	//popup
	$(window).scroll(function () {
		$(".window").css('top',$(this).scrollTop()+100);
	});
}

function getWidth(min_width){
	w_content = $("#content").width();

	num_item = Math.floor(w_content/min_width);		//Số đối tượng hiển thị trên 1 hàng

	w_item = Math.floor(w_content/num_item)-1;	//Chiều rộng của 1 đối tượng

	return w_item;
}


//Thiết lập thời gian ẩn thông báo
function hideMessage(){
	$("#message_top").fadeOut('medium');
	$("#flashMessage").fadeOut('medium');
}

//Lên đầu trang
function backToTop(){
	// hide #back-top first
	$("#back-top").hide();

	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			w_window = windowSize();
			if ($(this).scrollTop() > 100 & w_window[0] > 767) {
				$('#back-top').fadeIn();
				//fix top menu
				$('#nav_menu').addClass('navbar-fixed-top');
			} else {
				$('#back-top').fadeOut();
				$('#nav_menu').removeClass('navbar-fixed-top');
			}
			// if ($(this).scrollTop() > 100 & w_window[0] < 767) {
			// 	$('#header').addClass('navbar-fixed-top');
			// } else {
			// 	$('#header').removeClass('navbar-fixed-top');
			// }
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		if($(window).width() < 991){
			$('#header').addClass('navbar-fixed-top');
		}
	});

}

//Lấy kích thước màn hình
function windowSize(){
	var width = 0, height = 0;
	if( typeof( window.innerWidth ) == 'number' ) {
	  //Non-IE
	  width = window.innerWidth;
	  height = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
	  //IE 6+ in 'standards compliant mode'
	  width = document.documentElement.clientWidth;
	  height = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
	  //IE 4 compatible
	  width = document.body.clientWidth;
	  height = document.body.clientHeight;
	}
	return [width,height];
}


//Hiển thị Popup
function popup(id){
	var id = '#'+id;

    //Lấy chiều cao và rộng của màn hình
    var maskHeight = $(document).height();
    var maskWidth = $(window).width();

    // định dạng chiều cao và rộng cho cái vùng div chứa cái popup
    $('#mask').css({'width':maskWidth,'height':maskHeight});

    //hiệu ứng xuất hiện
    $('#mask').fadeIn(1000);
    $('#mask').fadeTo("slow",0.8);


    //làm cho cái popup canh giữa màn hình
    $(id).css('left', maskWidth/2-$(id).width()/2);

    //hiệu ứng cho cái popup xuất hiện (xuất hiện từ từ trong vòng 1s)
    $(id).fadeIn(1000);

	//dong khi click vào nút close
	$('.window .close').click(function (e) {
	    //Cancel the link behavior
	    e.preventDefault();

	    $('#mask').hide();
	    $('.window').hide();
	});

	//Nếu vùng div id mask được click thì dong popup
	$('#mask').click(function () {
	    $(this).hide();
	    $('.window').hide();
	});
}


//Xem thêm nội dung
function more_description(id){
	$(".more_"+id).fadeIn(400);
	$(".less_"+id).hide();
}

function less_description(id){
	$(".less_"+id).fadeIn(400);
	$(".more_"+id).hide();
}


function number_format (number, decimals, dec_point, thousands_sep) {
	  // http://kevin.vanzonneveld.net
	  // + original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	  // + improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // + bugfix by: Michael White (http://getsprink.com)
	  // + bugfix by: Benjamin Lupton
	  // + bugfix by: Allan Jensen (http://www.winternet.no)
	  // + revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	  // + bugfix by: Howard Yeend
	  // + revised by: Luke Smith (http://lucassmith.name)
	  // + bugfix by: Diogo Resende
	  // + bugfix by: Rival
	  // + input by: Kheang Hok Chin (http://www.distantia.ca/)
	  // + improved by: davook
	  // + improved by: Brett Zamir (http://brett-zamir.me)
	  // + input by: Jay Klehr
	  // + improved by: Brett Zamir (http://brett-zamir.me)
	  // + input by: Amir Habibi (http://www.residence-mixte.com/)
	  // + bugfix by: Brett Zamir (http://brett-zamir.me)
	  // + improved by: Theriault
	  // + input by: Amirouche
	  // + improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // * example 1: number_format(1234.56);
	  // * returns 1: '1,235'
	  // * example 2: number_format(1234.56, 2, ',', ' ');
	  // * returns 2: '1 234,56'
	  // * example 3: number_format(1234.5678, 2, '.', '');
	  // * returns 3: '1234.57'
	  // * example 4: number_format(67, 2, ',', '.');
	  // * returns 4: '67,00'
	  // * example 5: number_format(1000);
	  // * returns 5: '1,000'
	  // * example 6: number_format(67.311, 2);
	  // * returns 6: '67.31'
	  // * example 7: number_format(1000.55, 1);
	  // * returns 7: '1,000.6'
	  // * example 8: number_format(67000, 5, ',', '.');
	  // * returns 8: '67.000,00000'
	  // * example 9: number_format(0.9, 0);
	  // * returns 9: '1'
	  // * example 10: number_format('1.20', 2);
	  // * returns 10: '1.20'
	  // * example 11: number_format('1.20', 4);
	  // * returns 11: '1.2000'
	  // * example 12: number_format('1.2000', 3);
	  // * returns 12: '1.200'
	  // * example 13: number_format('1 000,50', 2, '.', ' ');
	  // * returns 13: '100 050.00'
	  // Strip all characters but numerical ones.
	  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	  var n = !isFinite(+number) ? 0 : +number,
	    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	    s = '',
	    toFixedFix = function (n, prec) {
	      var k = Math.pow(10, prec);
	      return '' + Math.round(n * k) / k;
	    };
	  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
	  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	  if (s[0].length > 3) {
	    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	  }
	  if ((s[1] || '').length < prec) {
	    s[1] = s[1] || '';
	    s[1] += new Array(prec - s[1].length + 1).join('0');
	  }
	  return s.join(dec);
}

$(document).ready(function(){
	resize();
	backToTop();

//Hiệu ứng hover chuột vào ảnh
	$(".thumb a img").mouseover(function(){
		$(this).animate({opacity: 0.7},200);
	});
	$(".thumb a img").mouseout(function(){
		$(this).animate({opacity: 1},200);
	});


	//Ẩn thông báo
	var t=setTimeout('hideMessage()',5000);

	//Poll

	$("a.poll_result").click(function(){
		$("#poll_result").fadeIn(200);
	});


	//Tìm kiếm sidebar
	typeSearch($(".type_search :checked").val());
	$("input.type_search").change(function(){
		val = $(this).val();
		typeSearch(val);
	});


	//Faq
	$(".question a").click(function(){
		id = $(this).attr('href');

		$(".box_content_faq h4").removeClass('highline');
		$(id).addClass('highline');

		var pos = $(id).offset();
		$('body,html').animate({
			scrollTop: pos.top-50
		}, 500);
		return false;
	});

	//Hieu ung hover product
	if($('div').hasClass('tb')){		//Từ trên xuống
		$(".box_product").hover(function(){
			$(this).find('div.tb').css({top:'-250px'});
			$(this).find('div.tb').animate({top: "0px"},300);
		});
	}
	if($('div').hasClass('bt')){		//Từ dưới lên
		$(".box_product").hover(function(){
			$(this).find('div.bt').css({bottom:'-250px'});
			$(this).find('div.bt').animate({bottom: "0px"},300);
		});
	}
	if($('div').hasClass('lr')){		//Từ trái sang phải
		$(".box_product").hover(function(){
			$(this).find('div.lr').css({left:'-250px'});
			$(this).find('div.lr').animate({left: "0px"},300);
		});
	}
	if($('div').hasClass('rl')){		//Từ phải sang trái
		$(".box_product").hover(function(){
			$(this).find('div.rl').css({right:'-250px'});
			$(this).find('div.rl').animate({right: "0px"},300);
		});
	}
//	/hover danh mục sảm phẩm
	// $('ul.nav > li').mouseover(function(){
	// 	$(this).addClass('select');
	// 	$('ul.nav > li').removeClass('current');
	// });
	// $('ul.nav > li > a').mouseout(function(){
	// 	$("ul.nav > li").removeClass('select');
	// });

	$( ".clk_dropdownmenu" ).click(function() {
		 $("li.submenu .nav").toggle();
		 $("li.submenu.select .nav").addClass('sub_menu');
	});
});

