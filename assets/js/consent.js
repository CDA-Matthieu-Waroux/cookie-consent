function cookie() {

  var cookieAcceptButton = $('.cookie__accept'),
      cookieDeclineButton = $('.cookie__decline'),
      cookieAlert = $('.cookie');

  cookieAcceptButton.on('click', function() {
    cookieAlert.fadeOut('slow');
  });
  
  cookieDeclineButton.on('click', function() {
    cookieAlert.fadeOut('slow');
  });
  
}

$(function() {
  cookie();
});