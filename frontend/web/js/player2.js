aodianPlayer({
    container:'flash-container',//播放器容器ID，必要参数
    url:'rtmp://12852.lssplay.aodianyun.com/yulitest/stream',//控制台开通的APP rtmp地址，必要参数
    player:{
        name:'jwplayer',//播放器名称，必要参数
        /* 以下为可选参数*/
        width: '100%',//播放器宽度，可用数字、百分比等
        height: '100%',//播放器高度，可用数字、百分比等
        title: '美林云直播',//视频标题
        image: 'http://www.yuliwangluo.com/public/img/logo.png',//视频封面图片地址
        autostart: true,//是否自动播放，默认为false
        repeat: true,//播放完后是否自动重头播放，默认为false
        stretching: 'exactfit',//视频尺寸，uniform：播放器默认的视频尺寸|exactfit：撑满播放器的尺寸|fill：撑满网页的尺寸|none：视频的原始尺寸
        bufferlength: '1'//视频缓冲时间，默认为3秒
    }
});

/* 以下为JW Player支持的事件 */
/* lssHandle.pPlay();//暂停、继续播放 */
/* lssHandle.pStop();//停止播放 */
/* alert(lssHandle.pStatus());//播放状态，BUFFERING：加载中、PLAYING：正在播放、PAUSED：暂停、IDLE：停止 */
/* alert(lssHandle.pCurrent());//获取播放进度 */