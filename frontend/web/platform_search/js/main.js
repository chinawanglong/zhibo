// JavaScript Document

$(function(){
	
//打开弹出窗口
	$(".btns").click(function(){
		
		var text_tx = $('.search_box .text').val();
		
		if($('.search_box .text').val()==""){
			
				AlertTip("您输入的平台名称不能为空！", 'warning');
				return false;
				
			}else{
	
				$(".bd_wd").css("width", $(document).width());
				$(".bd_wd").css("height", $(document).height());
				$(".bd_wd").show();
				$("#tcwindows").show();
				$(".texts").text(text_tx);
				$("#name2").val(text_tx);
		}
	});
	
 //关闭弹出窗口	
	$(".close a").click(function(){
		$(this).parents("#tcwindows,#tcwindows1").hide();
		$(".bd_wd").hide();	
	});
	
	
	$(".contbox dl dd a").click(function(){
	
		$(".contbox dl dd a").removeClass("activ");	
		$(this).addClass("activ");	
			
	});
	$(".nav_tab a").click(function(){
	
		$(".nav_tab a").removeClass("activ");	
		$(this).addClass("activ");	
	});

    $(".foot1 span#timebox").html(show());
	
    //加入收藏夹
	$('#keleyi').addFavorite(document.title,location.href);

    //随机数字
    number1();
    setInterval("number1()", 3000);
    number2();
    setInterval("number2()", 3000);
    number3();
    setInterval("number3()", 3000);
});

jQuery.fn.addFavorite = function(l, h) {
	return this.click(function() {
	  var t = jQuery(this);
	  if(jQuery.browser.msie) {
	     window.external.addFavorite(h, l);
	  } else if (jQuery.browser.mozilla || jQuery.browser.opera) {
	     t.attr("rel", "sidebar");
	     t.attr("title", l);
	     t.attr("href", h);
	  } else {
	    alert("请使用Ctrl+D将本页加入收藏夹！");
	  }
	});
};	
	
		
	
var cout1 = 7794;
function number1() {
		var spannumber = document.getElementById("number1");
		var date = new Date();
		var second = date.getSeconds();
		var minutes = date.getMinutes();
		var hours = date.getHours();
		var number = cout1 + 24 * 60 * hours + 60 * minutes + second + parseInt(Math.random() * 3);
	
		spannumber.innerHTML = number;
		var html = "";
		for (var i = 0; i < String(number).length; i++) {
			var temp = String(number).substr(i, 1);
			html += "<b>" + temp + "</b>";
		}
		$(".num_show1").html(html);
}		
	
var cout2 = 75895;
function number2() {
		var spannumber = document.getElementById("number2");
		var date = new Date();
		var second = date.getSeconds();
		var minutes = date.getMinutes();
		var hours = date.getHours();
		var number = cout2 + 24 * 60 * hours + 60 * minutes + second + parseInt(Math.random() * 3);
	
		spannumber.innerHTML = number;
		var html = "";
		for (var i = 0; i < String(number).length; i++) {
			var temp = String(number).substr(i, 1);
			html += "<b>" + temp + "</b>";
		}
		$(".num_show2").html(html);
}		

var cout3 = 61476;
function number3() {
		var spannumber = document.getElementById("number3");
		var date = new Date();
		var second = date.getSeconds();
		var minutes = date.getMinutes();
		var hours = date.getHours();
		var number = cout3 + 24 * 60 * hours + 60 * minutes + second + parseInt(Math.random() * 3);
	
		spannumber.innerHTML = number;
		var html = "";
		for (var i = 0; i < String(number).length; i++) {
			var temp = String(number).substr(i, 1);
			html += "<b>" + temp + "</b>";
		}
		$(".num_show3").html(html);
}		

function show(){
   var mydate = new Date();
   var str = "" + mydate.getFullYear() + "-";
   str += (mydate.getMonth()+1) + "-";
   str += mydate.getDate();
   return str;
}




















	