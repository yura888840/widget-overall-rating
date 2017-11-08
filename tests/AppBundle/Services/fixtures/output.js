document.writeln('<div style="position: fixed; top:50px; left:0; width: 100%; bottom: 0; border: 0"><iframe
id="MyHotelRankingTest" src="about:blank" width="300" height="600" align="left" scrolling="no"
marginheight="0" marginwidth="0" frameborder="0">Your browser does not suppor iframes</iframe></div>');
var hotelRanking = document.getElementById('MyHotelRankingTest').contentWindow.document;
hotelRanking.open();
hotelRanking.write('<html><head></head><title></title></head><body>Average rank: 55</body></html>');
hotelRanking.close();