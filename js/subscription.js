jQuery(document).ready(function(){
   jQuery(".subscription-form .submit").click(function(e){
      e.preventDefault();
      var _can_submit = true;
      jQuery(".subscription-form .input-field").each(function(){
          jQuery(this).removeClass("validate-error");
          if(!validate_input(jQuery(this))) {
              jQuery(this).addClass("validate-error");
              _can_submit = false;
          }
      });
      jQuery(".subscription-form .input-field").focus(function(){
          jQuery(this).removeClass("validate-error");
      });
      if(_can_submit) {
        jQuery.ajax({
            url : ajax_url,
            type : 'post',
            data : {
                first_name : jQuery("#first_name").val(),
                email : jQuery("#email").val(),
                action: "create_subscription"
            },
            success : function( response ) {
                jQuery(".subscription-status").show();
                jQuery(".subscription-status").html("Susbscribed Successfully");
                jQuery(".subscription-form .input-field").val("");
                setTimeout(function(){ jQuery(".subscription-status").hide(); }, 3000);
            }
        });
      }
   });
});

function validate_input(_this) {
    var validate = true;
    var _type = _this.data("type");
    if(_type === "email") {
        var regex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if (!regex.test(_this.val())) {
            validate = false;
        }
    }
    if(_type === "text") {
        var regex = /^[a-zA-Z\s]+$/;
        if (!regex.test(_this.val())) {
            validate = false;
        }
    }
    return validate;
}