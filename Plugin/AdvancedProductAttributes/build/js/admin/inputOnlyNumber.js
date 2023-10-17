$(document).ready(function(){
	$(".number-only").keyup(function(){
        reg = /[^0-9]/g;
        val = $(this).val();
        $(this).val(val.replace(reg,""));
    }).keypress(function(){
        reg = /[^0-9]/g;
        val = $(this).val();
        $(this).val(val.replace(reg,""));
    });
});