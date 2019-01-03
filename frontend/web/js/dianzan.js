function DianZaner() {

}

DianZaner.prototype.createDianZaner = function (obj, Num) {
    var self = this;
    var that=obj;
    var Iconnum =Math.floor(Math.random() * Num) +1;
    var iconhtml = document.createDocumentFragment();
    var colorArr=["#F3486C","#FF63CB","#6393FF","#FF637B","#FFB863"]
    for (var i = 0; i < Iconnum; i++) {
        var icon = document.createElement("i");
        icon.className = "icon iconfont icon-aixin icon" + Num + i;
//            var R = Math.floor(Math.random() * 256);
//            var G = Math.floor(Math.random() * 256);
//            var B = Math.floor(Math.random() * 256);
        var Font = Math.floor(Math.random() * 11) + 30;
        var Color=Math.floor(Math.random() *(colorArr.length))
        icon.style.color =colorArr[Color];
        icon.style.fontSize = Font + "px";
        icon.style.left = "50px";
        icon.style.top = "30px";
        iconhtml.appendChild(icon);

    }
    that.appendChild(iconhtml);
    setTimeout(function () {
        self.animotion();
    })
}

DianZaner.prototype.animotion = function () {
    var self = this;
    var animateObj = document.getElementsByClassName("icon-aixin");
    if(arrLength(animateObj)>=100){
        return;
    }else{
        for (var k = 0; k < animateObj.length; k++) {
            var Y = Math.floor(Math.random() * (-100)) + (-150);
            var X = Math.floor(Math.random() * (600)) - 300;
            var Time = parseFloat(Math.random() * 1.5 + 1.5);
            var classname = animateObj[k].className;
            if (classname.indexOf("active") == -1) {
                animateObj[k].style.opacity=0;
                animateObj[k].style.filter = 'alpha(opacity:0)';
                animateObj[k].style.transition="all linear "+Time+"s ";
                animateObj[k].style.left=X+"px";
                animateObj[k].style.top=Y+"px";
                animateObj[k].className += " active";
                // self.animate(animateObj[k], {left: X + "px", top: Y + "px", opacity: 0},Time*1000, function () {
                //
                // })
            }


        }
    }


}

DianZaner.prototype.removeIcon = function () {
    var animateObj = document.getElementsByClassName("icon-aixin");
    var self = this;
    for (var k = 0; k <animateObj.length; k++) {
        var Opacity = self.getStyle(animateObj[k])["opacity"];
        if (Opacity <= 0.03) {
            var parentObj=animateObj[k].parentNode;
            parentObj.removeChild(animateObj[k]);
            k--;
        }
    }
}
DianZaner.prototype.getStyle = function (obj) {
    if (obj.currentStyle) {
        return obj.currentStyle;
    } else {
        return getComputedStyle(obj, null);
    }
}
// DianZaner.prototype.animate = function (obj, json, speed, callback) {
//     clearInterval(obj.timer);
//     var that = this;
//
//     if (speed == 0) {
//         var speedinit =200;
//     } else {
//         var speedinit = speed / 20;
//     }
//     var oldJson = {};
//     var Finish = {};
//     for (var attr in json) {
//         if (attr == 'opacity') {
//             oldJson[attr] = parseFloat(that.getStyle(obj)[attr]);
//             Finish[attr] = false;
//         } else {
//             oldJson[attr] = parseFloat(that.getStyle(obj)[attr]) + "px";
//             Finish[attr] = false;
//         }
//     }
//     obj.timer = setInterval(function () {
//
//         for (var attr in json) {
//
//             if (attr == 'opacity') {
//                 obj.style.filter = 'alpha(opacity:' + parseFloat(that.getStyle(obj)[attr]) * 100 + ((parseFloat(json[attr]) - parseFloat(oldJson[attr])) / speedinit) * 100 + ')';
//                 obj.style.opacity = (parseFloat(that.getStyle(obj)[attr]) * 100 + ((parseFloat(json[attr]) - parseFloat(oldJson[attr])) / speedinit) * 100) / 100;
//             } else {
//                 obj.style[attr] = parseFloat(that.getStyle(obj)[attr]) + ((parseFloat(json[attr]) - parseFloat(oldJson[attr])) / speedinit) + "px";
//             };
// //
//             for (var style in oldJson) {
//                 if (parseFloat(json[style]) - parseFloat(oldJson[style]) >= 0) {
//                     if (parseFloat(that.getStyle(obj)[attr]) >= parseFloat(json[style])) {
//                         if (Finish[style] == false) {
//                             Finish[style] = true;
//                         }
//
//                     }
//                 } else {
//                     if (parseFloat(json[style]) - parseFloat(oldJson[style]) < 0) {
//                         if (parseFloat(that.getStyle(obj)[attr]) <= parseFloat(json[style])) {
//                             if (Finish[style] == false) {
//                                 Finish[style] = true;
//                             }
//                         }
//                     }
//                 }
//
//             }
//             var Finishlength=0;
//             for(var k in Finish){
//                 if(Finish[k]==true){
//                     Finishlength++
//                 }
//             }
//             if(Finishlength==arrLength(json)){
//                 clearInterval(obj.timer);
//                 if(callback){
//                     callback();
//                 }
//             }
//         }
//     }, 10);
// }
function arrLength(obj) {
    if (typeof obj == "string") {
        return obj.length;
    } else if (typeof obj == "object") {
        var n = 0;
        for (var i in obj) {
            n++;
        }
        return n;
    }
    return false;
}
