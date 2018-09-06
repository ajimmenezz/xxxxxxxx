var page = require('webpage').create();
page.open('http://siccob.com.mx', function(status) {
  console.log("Status: " + status);
  if(status === "success") {
    page.render('storage/example.png');
  }
  phantom.exit();
});