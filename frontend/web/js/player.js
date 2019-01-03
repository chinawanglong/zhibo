var objectPlayer=new aodianPlayer({
    container:'flash-container',//播放器容器ID，必要参数
    rtmpUrl: 'rtmp://12852.lssplay.aodianyun.com/yulitest/stream',//控制台开通的APP rtmp地址，必要参数
    hlsUrl: 'http://12852.hlsplay.aodianyun.com/yulitest/stream.m3u8',//控制台开通的APP hls地址，必要参数
    /* 以下为可选参数*/
    width: '100%',//播放器宽度，可用数字、百分比等
    height: '100%',//播放器高度，可用数字、百分比等
    autostart: true,//是否自动播放，默认为false
    bufferlength: '1',//视频缓冲时间，默认为3秒。hls不支持！手机端不支持
    maxbufferlength: '1',//最大视频缓冲时间，默认为2秒。hls不支持！手机端不支持
    stretching: '1',//设置全屏模式,1代表按比例撑满至全屏,2代表铺满全屏,3代表视频原始大小,默认值为1。hls初始设置不支持，手机端不支持
    controlbardisplay: 'enable',//是否显示控制栏，值为：disable、enable默认为disable。
    adveDeAddr: 'http://demo.meilingzhibo.com/images/playercover.jpg',//封面图片链接
    //adveWidth: '100%',//封面图宽度
    //adveHeight: '100%',//封面图高度
    adveReAddr: 'http://www.meilingzhibo.com'//封面图点击链接
});
/* rtmpUrl与hlsUrl同时存在时播放器优先加载rtmp*/
/* 以下为Aodian Player支持的事件 */
/* objectPlayer.startPlay();//播放 */
/* objectPlayer.pausePlay();//暂停 */
/* objectPlayer.stopPlay();//停止 hls不支持*/
/* objectPlayer.closeConnect();//断开连接 */
/* objectPlayer.setMute(true);//静音或恢复音量，参数为true|false */
/* objectPlayer.setVolume(volume);//设置音量，参数为0-100数字 */
/* objectPlayer.setFullScreenMode(1);//设置全屏模式,1代表按比例撑满至全屏,2代表铺满全屏,3代表视频原始大小,默认值为1。手机不支持 */