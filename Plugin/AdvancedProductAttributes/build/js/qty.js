jQuery(document).ready(function(){
    jQuery('.btn-number').on( 'click', function(e) {
        e.preventDefault();
        var fieldName = jQuery(this).attr('data-field');
        var type      = jQuery(this).attr('data-type');
        var input = jQuery("input[name='"+fieldName+"']");
        var currentVal = parseInt(input.val() , 10);
        if (!isNaN(currentVal)) {
            if(type == 'minus') {
                if(currentVal > input.attr('minlength')) {
                    input.val(currentVal - 1).change();
                }
            } else if(type == 'plus') {
                input.val(currentVal + 1).change();
            }
        } else {
            input.val(0);
        }
    });
});