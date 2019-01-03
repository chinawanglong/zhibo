/**
 * Created by Administrator on 2016/2/27.
 */
$(function(){
    try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}

    var url = window.location;
    var element = $('ul.nav.nav-list a').filter(function() {
        if(this.href == url  || url.href.indexOf(this.href) == 0){
            return 1;
        }
        else if($(this).attr('cid')){
            return $(this).attr('cid')==window.requestcontrollerid;
        }
        else{
            return 0;
        }
    });
    if(element){
        element.addClass('active_nav');
        ace.toggleli(element);
    }

})