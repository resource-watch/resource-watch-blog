jQuery('.wpsm-content').addClass('wpsm-content-hide')
jQuery('.wpsm-show, .wpsm-hide').removeClass('wpsm-content-hide')
jQuery('.wpsm-show').on('click', function(e) {
  jQuery(this).next('.wpsm-content').removeClass('wpsm-content-hide');
  jQuery(this).addClass('wpsm-content-hide');
  e.preventDefault();
});
jQuery('.wpsm-hide').on('click', function(e) {
  var wpsm = jQuery(this).parent('.wpsm-content');
  wpsm.addClass('wpsm-content-hide');
  wpsm.prev('.wpsm-show').removeClass('wpsm-content-hide');
  e.preventDefault();
});