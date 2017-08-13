function showLoading(msg,time){
$("#msg").text(msg);
$(".loading-wrapper").show();
if(time>0){
	setTimeout("hideLoading()",time);
}
}
 
function hideLoading(){
$(".loading-wrapper").hide();
}
function jump(url){
window.location.href =url;
}
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?175ececd09f00f4cec1ccf6e498c02ef";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();