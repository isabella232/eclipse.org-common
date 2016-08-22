// main.js
(function($, document) {
  
  $(window).on("load", function() {
    if (window.location.hash && $(window.location.hash).hasClass("tab-pane")) {
      window.scrollTo(0, 0);
      setTimeout(function() {
        window.scrollTo(0, 0);
      }, 1);
    }
  });
  
  $(document).ready(function() {

    var href_hash = window.location.hash;
    // Add a class if right column is non-existant.
    if ($("#rightcolumn").length == 0) {
      $("#midcolumn").attr("class", "no-right-sidebar");
      if (href_hash) {
        window.location.hash = href_hash;
      }
    }
    // add a class if left column is non-existant.
    if ($("#leftcol").length == 0) {
      $("#midcolumn").attr("class", "no-left-nav");
      if (href_hash) {
        window.location.hash = href_hash;
      }
    }

    $('#showalltabs').click(function(){
      $('.tabs li').each(function(i,t){
        $(this).removeClass('active');
      });
      $('.tab-pane').each(function(i,t){
        $(this).addClass('active');
      });
    });
    
    href_hash && $('ul.nav a[href="' + href_hash + '"]').tab('show');
    
    
    // Donate Ads
    
    // If the page loads and the recognition checkbox is already checked
    if ($('input.recognition-checkbox').is(':checked')) {
      $('.recognition-fields').slideDown(300);
    }
    
    // If the recognition checkbox is clicked
    $('input.recognition-checkbox').click(function(){
        if($(this).prop("checked") == true){
          $('.recognition-fields').slideDown(300);
        }
        else if($(this).prop("checked") == false){
          $('.recognition-fields').slideUp(300);
        }
    });

    // When the user click on a pre-defined donation amount
    $('.btn-square').click(function() {
      $('.btn-square, .amount-body, .highlight-amount-body').removeClass('active');
      $(this).addClass('active');
      $('input[name=amount]').val($(this).val());
    });

    // When the user click in the custom donation amount field
    $('input[name=amount]').click(function() {
      $('input[name=amount]').bind("keyup change", function(e) {
          $('.btn-square').removeClass('active');
      });
    });
    
    /**
     * Disable the Payment radio depending 
     * if Paypal or Bitcoin is selected
     */
    function disablePaymentRadio() {
      var payment_type = $('input[name=type]:radio:checked').val();
      if (payment_type === "paypal") {
        $('input[name=subscription]').attr("disabled",false);
      }
      else{
        $('#subscription_default').prop('checked',true);
        $('input[name=subscription]').attr("disabled",true);
      }
    }
    
    // Disable the Bitcoin radio if the page loads and it is selected.
    disablePaymentRadio();
    
    // Make changes when the user chooses either Paypal or Bitcoin
    $('input[name=type]:radio').change(function(e){
      disablePaymentRadio();
    });

    $(".num-only").keydown(function(e) {
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl+A, Command+A
        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
      }
    });

    /**
     * Create or Update a cookie
     * 
     * @param string name - Name of the cookie
     * @param string value - Value of the cookie
     * @param string path - Path of the cookie
     */
    function createCookie(name, value, path) {
      document.cookie = name+"=" + escape(value) + "; path=" + path + ";";
    }
  
    /**
     * Fetch a specific cookie based on the name
     * 
     * @param string name - Name of the cookie
     * 
     * @return string
     */
    function fetchCookie(name) {
      
       var cookie_value = "";
       var current_cookie = "";
       var name_and_equal = name + "=";
       var all_cookies = document.cookie.split(";");
       var number_of_cookies = all_cookies.length;
       
       for(var i = 0; i < number_of_cookies; i++) {
         current_cookie = all_cookies[i].trim();
         if (current_cookie.indexOf(name_and_equal) == 0) {
           cookie_value = current_cookie.substring(name_and_equal.length, current_cookie.length);
           break;
         }
       }
       
       return cookie_value;
    }
    
    $('.btn-donate-close').click(function () {
      
      // The cookie name based on what has been set in the settings
      var cookie_name = eclipse_org_common.settings.cookies_class.name;

      // The JSON decoded value of the cookie 
      // fetched based on the cookie_name variable
      var cookie = jQuery.parseJSON(unescape(fetchCookie(cookie_name)));
      
      // Set the path
      var path = "/";

      // Set the banner as NOT visible
      cookie.donation_banner.value.visible = 0;
      
      // Make a string out of the object
      cookie = JSON.stringify(cookie);

      // Create the cookie
      createCookie(cookie_name, cookie, path);

      // Make the banner slide up
      $('.donate-ad').slideUp(300);
    });
    
  });
  
  // This code will prevent unexpected menu close when
  // using some components (like accordion, forms, etc).
  $(document).on("click", ".yamm .dropdown-menu", function(e) {
    e.stopPropagation()
  });

  // scroll button.
  $(window).on("load resize scroll", function(){
    if ($(window).width() < 1270){
      $('.scrollup').hide();
      return false;
    }
    if ($(this).scrollTop() > 100) {
      $('.scrollup').fadeIn();
    } else {
      $('.scrollup').fadeOut();
    }
  });

  // scroll back to the top of the page.
  $('.scrollup').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 600);
    return false;
  });
  
  $('.nav-tabs a').click(function (e) {
    $(this).tab('show');
    history.pushState({}, "", this.href);
    $('.alert:not(.stay-visible)').remove();
  });
  
  $("a[data-tab-destination]").on('click', function() {
    var tab = $(this).attr('data-tab-destination');
    $("#"+tab).click();
  });
  
  $('.solstice-collapse').click(function(){
    $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
  });
})(jQuery, document);