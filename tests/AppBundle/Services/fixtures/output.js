document.writeln('<div style="position: fixed; right:0; bottom: 0; border: 0"><iframe id="MyHotelRankingTest" style="background:blue" src="about:blank" width="100" height="100" align="left" scrolling="no" marginheight="0" marginwidth="0" frameborder="0">Your browser does not suppor iframes</iframe></div>');
var hotelRanking = document.getElementById('MyHotelRankingTest').contentWindow.document;
hotelRanking.open();
hotelRanking.write('<html><head></head><title></title></head><body style="text-align: center; position: absolute; top:30%; left: 10%"><b style="color:white; padding-left:20%; padding-bottom: 30%;">Rank: 55%</b></body></html>');
hotelRanking.close();