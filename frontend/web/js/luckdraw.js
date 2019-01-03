var data = [1, 2, 3, 4, 5, 6,7,8,9,10,11,12];
var Mobile=false;
var flag=true;
$(function(){
	var $hand = $('.hand');
	$hand.click(function(){
		
		//if(Mobile==false){$('.rotateMobile').show(); return;}
		
		data = data[Math.floor(Math.random()*data.length)];
		if(data==2||data==4||data==8||data==9||data==11){
			data=data;
		}
		else{
			
			data=9;
			
		}
		switch(data){
			case 1:
				rotateFunc(1,75,'恭喜你抽中了100元话费');
				break;
			case 2:
				if (flag==true){
					rotateFunc(2,105,'恭喜你抽中了机器人服务体验');
				}
				break;
			case 3:
				
				rotateFunc(3,135,'恭喜你抽中了苹果6Plus');
				break;
			case 4:
				if (flag==true){
				rotateFunc(4,165,'恭喜你抽中了10元话费');
				}
				break;
			case 5:
				rotateFunc(5,195,'恭喜你抽中迪士尼门票两张');
				break;
			case 6:
				rotateFunc(6,225,'恭喜你抽中了苹果IPad Pro');
				break;
			case 7:
				rotateFunc(7,255,'恭喜你抽中了20元话费');
				break;
			case 8:
				if (flag==true){
				rotateFunc(8,285,'恭喜你抽中了美女助理服务体验');
				}
				break;
			case 9:
				if (flag==true){
				rotateFunc(9,315,'未中奖');
				}
				break;
			case 10:
				rotateFunc(10,345,'恭喜你抽中了50元话费');
				break;
			case 11:
				if (flag==true){
					rotateFunc(11,375,'恭喜你抽中了分析师一对一');
				}
				break;
			case 12:
				rotateFunc(12,405,'恭喜你抽中了苹果7一部');
				break;
		}
	});

	var rotateFunc = function(awards,angle,text){
		switch(awards){
			case 1:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				break;
			case 2:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				flag=false;
				break;
			case 3:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				break;
			case 4:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				flag=false;
				break;
			case 5:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				break;
			case 6:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				break;
			case 7:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				break;
			case 8:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				flag=false;
				break;
			case 9:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				flag=false;
				break;
			case 10:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				break;
			case 11:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				flag=false;
				break;
			case 12:
				$hand.rotate({
					angle: 0,
					duration: 5000,
					animateTo: angle + 1020,
					callback: function(){
						alert(text);
					}
				});
				break;
		}
		
		$.get("/handle/GetLuckDraw.asp",{ac:"GetLuck",Tel:$("#rmobile").val(),RID:iRoomID,test:text},function(){
		},"script");

	};
});