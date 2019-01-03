//验证手机合法性
function ismobile(mobilenum) {
    var remobile = new RegExp("^[1][34578][0-9]{9}$", "gi");
    if (!remobile.test(mobilenum)) {
        AlertTip("手机号码错误！", 'warning');
        return false;
    }
    return true;
}
function checkForm(objname, objmobile,code,objagree) {
    var remobile = new RegExp("^[1][34578][0-9]{9}$", "gi");
 if (!objname) {
		AlertTip("请输入平台名称！", 'warning');
		$(objname).focus();
		return false;
	}
    if (!objmobile) {
        AlertTip("请填写手机！", 'warning');
        $(objmobile).focus();
        return false;
    }
    if (code == "") {
        AlertTip("请输入验证码！", 'warning');
        return false;
    }
    if (!remobile.test(objmobile.replace(/(^\s+)|(\s+$)/g,""))) {
        AlertTip("手机号码错误！", 'warning');
        $(objmobile).focus();
        return false;
    }
    if (objagree && !$(objagree).is(':checked')) {
        AlertTip("隐私政策必须同意才能完成本次申请！", 'warning');
        return false;
    }
    return true;
}
function AlertTip(str, flag) {
    var dialog = art.dialog({
        title: '提示',
        lock:true,
        content: str,
        icon: flag,
        padding: 20,
        width: 250,
        height: 50,
        zIndex: 10000,
        ok: function () {
            this.close();
        }
    });
}

//同步表单
function tongBuData(userName, mobile, NameValue, mobileValue) {
    document.getElementById(userName).value = NameValue;
    document.getElementById(mobile).value = mobileValue;
}

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}









