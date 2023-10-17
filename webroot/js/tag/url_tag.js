/*
 * @description: Duoc goi o trang tag khi load ajax
 * 
 * @author: Hoang Tuan Anh
 * 
 */
$(window).resize(function() {
	resize2();
});
$(document).ready(function(){
	resize2();
	
	$("#backTop a").click(function(){
		var pos = $(".tab_container").offset();
		$('body,html').animate({
			scrollTop: pos.top
		}, 500);
		return false;
	})
})


function resize2(){
	content_width = $("#content").width()-20;
	
	//.box_product  -- San pham
	box_width = 180;
	if(content_width>=1038){	
		//Hien thi 6sp
		box_width = Math.floor(content_width/6);
	};
	if(content_width>=865 && content_width<1038){
		//Hien thi 5 sp
		box_width = Math.floor(content_width/5);
	};
	if(content_width>=692 && content_width<865){
		//Hien thi 4 sp
		box_width = Math.floor(content_width/4);
	};
	if(content_width>=346 && content_width<692){
		//Hien thi 3 sp
		box_width = Math.floor(content_width/3);
	};
	if(content_width<346){
		//Hien thi 2 sp
		box_width = Math.floor(content_width/2);
	};
	$(".box_product").width(box_width-6);
		
	//Bai viet
	if(content_width>=910){
		//Hien thi 3 tin
		box_post = Math.floor(content_width/3);
	}
	if(content_width>=570 && content_width<910){
		//Hien thi 2 tin
		box_post = Math.floor(content_width/2);
	}
	if(content_width<570){
		//Hien thi 1 tin
		box_post = content_width;
	}
	$(".box_post").css('width',box_post-6);
	
	// .box_post_parent
	box_post_parent = 270;
	if(content_width>=312){
		//Hien thi 3 bai viet
		box_post_parent = Math.floor(content_width/2);
	};
	$(".box_post_parent").width(box_post_parent-10);
	// end .box_post_parent

}