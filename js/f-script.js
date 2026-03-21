function SavedataByAjaxRequest(data, method) {
    return jQuery.ajax({
        url: frontendajax.ajaxurl,
        type: method,
        data: data,
        cache: false
    });
}

function lfbErrorCheck(){
    var termaccept = true;
    if(jQuery('.term_accept').length){
        var termaccept = false;
        var numItems = jQuery('.term_accept').length;
        jQuery('.term_accept').css("outline", "2px solid #f50808");

        jQuery("input:checkbox[class=term_accept]:checked").each(function () {
                --numItems;
            jQuery('#'+jQuery(this).attr("id")).css("outline", "none");
            if(numItems==false){
                termaccept = true;
            }
            });
    }

     return termaccept;
}



jQuery(document).ready(function(){
// Disable native browser validation to use custom messages
jQuery('form.lead-form-front').attr('novalidate', true);

var dateToday = new Date();


jQuery('input*[name^="date_"]').datepicker({
    dateFormat: "mm/dd/yy",
    showOtherMonths: true,
    selectOtherMonths: true,
    autoclose: true,
    changeMonth: true,
    changeYear: true,
    gotoCurrent: true,
    yearRange:  (dateToday.getFullYear()-200) +":" + (dateToday.getFullYear()+50),
});

    jQuery('input*[name^="dob_"]').datepicker({
            dateFormat: "mm/dd/yy",
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            gotoCurrent: true,
            yearRange:  (dateToday.getFullYear()-100) +":" + (dateToday.getFullYear()),
        });
});

 var CaptchaCallback = function(){  
     var recaptcha = jQuery(".g-recaptcha").attr('data-sitekey'); 
      jQuery('.g-recaptcha').each(function(){
        grecaptcha.render(this,{
            'sitekey' : recaptcha,
            'callback' : correctCaptcha,
            });
      })
  };

 var correctCaptcha = function(response) {
 };
 function lfb_upload_button(newthis){
    $id = jQuery(newthis).attr('filetext');
    $var = jQuery(newthis).val();

    $newValue = $var.replace("C:\\fakepath\\", "");
    
     jQuery("."+$id).val($newValue);
   //jQuery("."+$id).val($var);
}
/*
 *Save form data from front-end
 */
 // inser form data
function lfbInserForm(element,form_id,uploaddata=''){
            var this_form_data = element.serialize();
            if(uploaddata!=''){
            this_form_data = this_form_data + '&' + uploaddata;
            } 
           var  lfbFormData = { fdata : this_form_data,
                                 action :  'Save_Form_Data',
                                 _wpnonce: frontendajax._wpnonce
                                };
        SavedataByAjaxRequest(lfbFormData, 'POST').success(function(response) {
            element.find('#loading_image').hide();
            if (jQuery.trim(response) == 'invalidcaptcha') {
                element.find(".leadform-show-message-form-"+form_id).append("<div class='error'><p>Invalid Captcha</p></div>");
                if (typeof grecaptcha !== "undefined") { grecaptcha.reset(); }
                element.find('input[type=submit]').prop('disabled', false);

            } else if (jQuery.trim(response) == 'inserted') {
                var redirect = jQuery(".successmsg_"+form_id).attr('redirect');
                element.siblings(".successmsg_"+form_id).css('display','block');
                element.hide();
                if (typeof grecaptcha !== "undefined") {
                    grecaptcha.reset();
                }
                if (jQuery.trim(redirect) !== '') {
                    window.location.href = redirect;
                }

            } else if (jQuery.trim(response) === 'INVAILD') {
                element.find(".leadform-show-message-form-"+form_id).append("<div class='error'><p>Invalid Data!</p></div>");
                element.find('input[type=submit]').prop('disabled', false);

            } else {
                element.find('input[type=submit]').prop('disabled', false);
            }
        }).fail(function() {
            element.find('#loading_image').hide();
            element.find('input[type=submit]').prop('disabled', false);
        });
    }

//captcha validation check
function lfbCaptchaCheck(element,form_id){
        var captcha_res = element.find(".g-recaptcha-response").val();
    form_data = "captcha_res="+captcha_res+"&action=verifyFormCaptcha";
    SavedataByAjaxRequest(form_data, 'POST').success(function(response) {
    element.find('#loading_image').hide();
        if (jQuery.trim(response) == 'Yes') {
        // if(element.find('.upload-type').length){
        //  lfbfileUpload(element,form_id);
        // }else{
         lfbInserForm(element,form_id);
        //}
         } else {
          element.find(".leadform-show-message-form-"+form_id).append("<div class='error'><p>Invalid Captcha</p></div>");
          grecaptcha.reset();
        }
    });
}


// required field validation with custom messages
function lfbValidateRequiredFields(element) {
    var hasError = false;
    var requiredMsg = (frontendajax && frontendajax.required_msg) ? frontendajax.required_msg : 'The field is required.';
    var errorMsg   = (frontendajax && frontendajax.error_msg)    ? frontendajax.error_msg    : 'One or more fields have an error. Please check and try again.';

    // remove previous validation messages
    element.find('.lfb-field-error').remove();
    element.find('.lfb-general-error-box').remove();

    // validate each required field
    element.find('input[required], textarea[required], select[required]').each(function() {
        var field     = jQuery(this);
        var fieldType = (field.attr('type') || '').toLowerCase();
        var container = field.closest('.lf-field');

        if (fieldType === 'radio') {
            var radioName = field.attr('name');
            if (!element.find('input[name="' + radioName + '"]:checked').length) {
                if (!container.find('.lfb-field-error').length) {
                    container.append('<p class="lfb-field-error">' + requiredMsg + '</p>');
                    hasError = true;
                }
            }
        } else {
            var val = jQuery.trim(field.val());
            if (!val) {
                container.append('<p class="lfb-field-error">' + requiredMsg + '</p>');
                hasError = true;
            }
        }
    });

    if (hasError) {
        element.find('.lf-form-panel').after('<div class="lfb-general-error-box"><p>' + errorMsg + '</p></div>');
    }

    return !hasError;
}

// form submit
jQuery(document).on('submit', "form.lead-form-front", function(event) {

    if (!lfbValidateRequiredFields(jQuery(this))) {
        return false;
    }
    if(!lfbErrorCheck()){
      return false;
    }
    event.preventDefault();
    var element = jQuery(this);
    element.find('input[type=submit]').prop('disabled', true);
    var form_id = element.find(".hidden_field").val();   
    var captcha_status = element.find(".this_form_captcha_status").val();
    
    element.find('#loading_image').show();
    element.find(".leadform-show-message-form-"+form_id).empty();

    if (captcha_status == 'disable') {
        lfbInserForm(element, form_id);
    } else {
        lfbCaptchaCheck(element, form_id);
    }
});

// required-field-function
jQuery(function(){
    var requiredCheckboxes = jQuery('.lead-form-front :checkbox[required]');
    requiredCheckboxes.change(function(){
        if(requiredCheckboxes.is(':checked')) {
            requiredCheckboxes.removeAttr('required');
        }
        else {
            requiredCheckboxes.attr('required', 'required');
        }
    });
});