
   

//$('.multi-field-wrapper').each(function() {
//    var $wrapper = $('.multi-fields', this);
//    $(".add-field", $(this)).click(function(e) {
//        $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
//    });
//    $('.multi-field .remove-field', $wrapper).click(function() {
//        if ($('.multi-field', $wrapper).length > 1)
//            $(this).parent('.multi-field').remove();
//    });
//});

//Set-Top Box

$(".add-field").click(function (e) {
    var $wrapper = $('.multi-field-wrapper');
    $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
});
$(".multi-field .remove-field").click(function () {
    var $wrapper = $('.multi-field-wrapper');
    if ($('.multi-field', $wrapper).length > 1)
        $(this).parent('.multi-field').remove();
});

//Smart Card
$(".add-fieldsc").click(function (e) {
    var $wrapper = $('.multi-field-wrappersc');
    $('.multi-fieldsc:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
});
$(".multi-fieldsc .remove-fieldsc").click(function () {
    var $wrapper = $('.multi-field-wrappersc');
    if ($('.multi-fieldsc', $wrapper).length > 1)
        $(this).parent('.multi-fieldsc').remove();
});


//Contact
$(".add-fieldcon").click(function (e) {
    var $wrapper = $('.multi-field-wrappercon');
    var count = $wrapper.find('input').length;
//    count = (count - 1);
//    count = (count < 0)? 0:count;
    //s$('.multi-fieldcon:first-child', $wrapper).clone(false).appendTo($wrapper).find('input').attr('name','phone_no['+count+']').val('').focus();
    var html = '<div class="multi-fieldscon">';
        html += '<div class="multi-fieldcon" style="padding-bottom: 10px">';                                     
        html += '<div class="form-group">';						
        html += '<div class="input-group margin-bottom-sm">';
        html += '<span class="input-group-addon"><i class="fa fa-list"></i></span>';
        html += '<input type="text" class="form-control" name="phone_no[]">';
        html += '</div>';
        html += '</div>';
        html += '<button type="button" class="remove-fieldcon btn btn-danger btn-xs">Remove</button>';
        html += '</div>';
        html += '</div>';
        
    $(".multi-field-wrappercon").append(html);

});
$(".multi-fieldcon .remove-fieldcon").click(function () {
    var $wrapper = $('.multi-field-wrappercon');
    if ($('.multi-fieldcon', $wrapper).length > 1)
        $(this).parent('.multi-fieldcon').remove();
});

//Billing
$(".add-fieldbill").click(function (e) {
    var $wrapper = $('.multi-field-wrapperbill');
    $('.multi-fieldbill:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
});
$(".multi-fieldbill .remove-fieldbill").click(function () {
    var $wrapper = $('.multi-field-wrapperbill');
    if ($('.multi-fieldbill', $wrapper).length > 1)
        $(this).parent('.multi-fieldbill').remove();
});