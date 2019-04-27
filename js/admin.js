jQuery(document).ready(function(){
   bindEvents();
   jQuery(document).on("input","#subscription-list .search",function(){
      if(jQuery(this).val().length > 3) {
        var _string = jQuery(this).val();
        jQuery.ajax({
            url : ajax_url,
            type : 'post',
            data : {
                search : _string,
                action: "search"
            },
            success : function( response ) {
                jQuery(".subscription-list").replaceWith(response);
                bindEvents();
            }
        });
      }
   });
});

function bindEvents() {
    jQuery(".subscription-list .unsubscribe").click(function(e){
      e.preventDefault();
      var _id = jQuery(this).data("id");
      jQuery.ajax({
            url : ajax_url,
            type : 'post',
            data : {
                id : _id,
                action: "unsubscribe"
            },
            success : function( response ) {
                window.location.reload();
            }
        });
   });
}