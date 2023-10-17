$(window).resize(function() {
	setSize();
});

$(document).ready(function(){
	setSize();

	check();		//Kiểm tra đơn hàng, liên hệ mới
	var int=self.setInterval("check()",5000);

	$('.del_cache').click(function(){
		$("#loading").show();
	});

	//confirm
	$('.trash').jConfirmAction({
		question:'Ban có chắc chắn muốn đưa vào thùng rác ?',
		yesAnswer: "Đồng ý",
		cancelAnswer: "Không"
	});

	//confirm
	$('.delete').jConfirmAction({
		question:'Ban có chắc chắn muốn xóa ?',
		yesAnswer: "Đồng ý",
		cancelAnswer: "Không"
	});

	//confirm
	$('.restore').jConfirmAction({
		question:'Ban có chắc chắn khôi phục ?',
		yesAnswer: "Đồng ý",
		cancelAnswer: "Không"
	});

	//--- Tab
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});


	//.tab_content_2
	$(".tab_content_2").hide(); //Hide all content
	$("ul.tabs_2 li:first").addClass("active").show(); //Activate first tab
	$(".tab_content_2:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs_2 li").click(function() {
		$("ul.tabs_2 li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content_2").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});
	//--- end Tab


	//Slide box_manager - trang chu
	$(".box_manager").hover(function(){
		$(this).find("img").animate({top: "-166px"},150);
		$(this).find("img").animate({top: "-166px", left: "500px"},0);
		$(this).find("img").animate({top: "166px", left: "500px"},0);
		$(this).find("img").animate({top: "166px", left: "0px"},0);
	},function(){
		$(this).find("img").animate({top: "-10px"},150);
		$(this).find("img").animate({top: "0px"},250);
	});


	//An hien sub muc thong tin
	$(".get_info").click(function(){
		if($(this).hasClass('more')){
			$("tr.info"+$(this).attr('name')).fadeIn(150);
			$(this).removeClass('more');
			$(this).addClass('sub');
		}else{
			$("tr.info"+$(this).attr('name')).fadeOut(150);
			$(this).removeClass('sub');
			$(this).addClass('more');
		}
	});

	//Ẩn dòng thông báo
	var t=setTimeout('hideMessage()',5000);

	if($('div').hasClass('box_select')){
		$(".box_select").hover(function(){
			$(this).find('ul:first-child').toggle();
		});
	};

	//Hành động save
	$('ul.submit li').click(function(){
		$(this).find('span').show();
		var t=setTimeout('resetButton()',2000);
	});

});

function noBack() { window.history.forward(); }

function resetButton(){
	$('ul.submit li span').hide();
}

//Xem danh sach danh mục
function viewListCategory(){
	$('ul.list_category').toggle(50);
}

function hideMessage(){
	$("#flashMessage").fadeOut(200);
}

function setSize(w_colum_left){
	size = windowSize();
	left_middle = size[1]-112;
	content_h = size[1]-86;
	content_w = size[0]-220;


	if(content_w<1000-220) content_w = 1000-220;
	if(content_w>1500-220) content_w = 1500-220;

	nav_column_left = $("#nav_column_left").height()+70;

	if(left_middle<nav_column_left){
		$("#left_footer").css({'box-shadow':'-1px -1px 1px #D3D3D3','border-top':'1px solid #BFBFBF'});
	}else{
		$("#left_footer").css({'box-shadow':'none','border':'none'});
	}

	$("#content").height(content_h);
	$("#content").width(content_w);
	$("#left_middle").height(left_middle);

	//Tab
	w_tab_container_2 = content_w-60;
	if(!$('div').hasClass('config')){
		if(w_tab_container_2>900) w_tab_container_2 = 950;
		$("#column_right .tab_container_2").width(w_tab_container_2);
	}else{
		$("#column_right .tab_container_2").width(w_tab_container_2);
	}
}


//Lay kich thuoc phan vung lam viec cua trinh duyet
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

//Lay toa do x,y của thanh cuộn (tọa độ điểm phía trên)
function getScrollXY() {
	var scrOfX = 0, scrOfY = 0;
	if( typeof( window.pageYOffset ) == 'number' ) {
	  //Netscape compliant
	  scrOfY = window.pageYOffset;
	  scrOfX = window.pageXOffset;
	} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
	  //DOM compliant
	  scrOfY = document.body.scrollTop;
	  scrOfX = document.body.scrollLeft;
	} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
	  //IE6 standards compliant mode
	  scrOfY = document.documentElement.scrollTop;
	  scrOfX = document.documentElement.scrollLeft;
	}
	return [ scrOfX, scrOfY ];
}

//Check all checkbox
function docheck(value) {
	var checks = document.getElementsByName('chkid[]');
	var boxLength = checks.length;
	if(value == true){
		for(i = 0;i < boxLength;i++)
		checks[i].checked = true;
	}
	else{
		for(i = 0;i < boxLength;i++)
		checks[i].checked = false;
	}
}

//Ẩn hiện danh mục sp ở mục thêm/sửa
function more(id){
	$("#"+id).toggle(200);
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

//Chỉ cho phép nhập số
function noAlpha(obj){
	reg = /[^0-9]/g;
	obj.value =  obj.value.replace(reg,"");
}


//Menu top
navigation.init({
	mainmenuid: "bg_nav", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'nav', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

//Menu sidebar left
navigation.init({
	mainmenuid: "nav_column_left", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'nav-v pro_cate_sidebar_left', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})