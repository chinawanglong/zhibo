<?php

/**
 * Description of KindEditor
 *
 * @author Qinn Pan <Pan JingKui, pjkui@qq.com>
 * @link http://www.pjkui.com
 * @QQ 714428042
 * @date 2015-3-4

 */

namespace backend\widgets\kindeditor;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\InputWidget;

class KindEditor extends InputWidget {

    /**执行id**/
    public $id;
    /**提交name**/
    public $name;
    /**表单项目值**/
    public $value;
    //配置选项，参阅KindEditor官网文档(定制菜单等)
    public $clientOptions = [];
    //定义编辑器的类型，
    //默认为textEditor;
    //uploadButton：自定义上传按钮
    //dialog:弹窗
    //colorpicker:取色器
    //file-manager浏览服务器
    //image-dialog 上传图片
    //multiImageDialog批量上传图片
    //fileDialog 文件上传
    public $editorType;
    //默认配置
    protected $_options;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        $this->name=!empty($this->name)?$this->name:$this->id;
        $this->_options = [
            'allowFileManager'=>true,
            'fileManagerJson' => Yii::$app->urlManager->createUrl(["site/kupload",'action'=>'fileManagerJson']),
            'uploadJson' => Yii::$app->urlManager->createUrl(["site/kupload",'action'=>'uploadJson']),
            'formatUploadUrl'=>'false',
            'urlType'=>'domain',
            'filterMode'=> false,
            'width' => '100%',
            'height' => '400',
        ];
        $this->clientOptions = ArrayHelper::merge($this->_options, $this->clientOptions);
        parent::init();
    }

    public function run() {
        $this->registerClientScript();
        if ($this->hasModel()) {
            switch ($this->editorType) {
                case 'uploadButton':
                    return Html::activeInput('text', $this->model, $this->attribute, ['id' => $this->id, 'readonly' => "readonly"]) . '<input type="button" id="uploadButton" value="Upload" />';

                    break;
                case 'colorpicker':
                    return Html::activeInput('text', $this->model, $this->attribute, ['id' => $this->id]) . '<input type="button" id="colorpicker" value="打开取色器" />';

                    break;
                case 'file-manager':
                    return Html::activeInput('text', $this->model, $this->attribute, ['id' => $this->id]) . '<input type="button" id="filemanager" value="浏览服务器" />';

                    break;
                case 'image-dialog':
                    return Html::activeInput('text', $this->model, $this->attribute, ['id' => $this->id]) . '<input type="button" id="imageBtn" value="选择图片" />';

                    break;
                case 'file-dialog':
                    return Html::activeInput('text', $this->model, $this->attribute, ['id' => $this->id]) . '<input type="button" id="insertfile" value="选择文件" />';

                    break;

                default:
                    return Html::activeTextarea($this->model, $this->attribute, ['id' => $this->id]);
                    break;
            }
        } else {
            switch ($this->editorType) {
                case 'uploadButton':
                    return Html::input('text', $this->id, $this->value, ['id' => $this->id, 'readonly' => "readonly"]) . '<input type="button" id="uploadButton" value="Upload" />';
                    break;
                case 'colorpicker':
                    return Html::input('text', $this->id, $this->value, ['id' => $this->id]) . '<input type="button" id="colorpicker" value="打开取色器" />';
                    break;
                case 'file-manager':
                    return Html::input('text', $this->id, $this->value, ['id' => $this->id]) . '<input type="button" id="filemanager" value="浏览服务器" />';
                    break;
                case 'image-dialog':
                    return Html::input('text', $this->id, $this->value, ['id' => $this->id]) . '<input type="button" id="imageBtn" value="选择图片" />';
                    break;
                case 'file-dialog':
                    return Html::input('text', $this->id, $this->value, ['id' => $this->id,'name'=>$this->name]) . '<input type="button" id="insertfile" value="选择文件" />';
                    break;

                default:
                    return Html::textarea($this->name, $this->value, ['id' => $this->id]);
                    break;
            }
        }
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript() {
        //UEditorAsset::register($this->view);
        KindEditorAsset::register($this->view);
        $clientOptions = Json::encode($this->clientOptions);

        $fileManagerJson = Yii::$app->urlManager->createUrl(["site/kupload",'action'=>'fileManagerJson']);
        $uploadJson = Yii::$app->urlManager->createUrl(["site/kupload",'action'=>'uploadJson']);
        switch ($this->editorType) {
            case 'uploadButton':
                $url =Yii::$app->urlManager->createUrl(["site/kupload",'action'=>'uploadJson']);

                $script = <<<EOT
                             KindEditor.ready(function(K) {
				var uploadbutton = K.uploadbutton({
					button : K('#uploadButton')[0],
					fieldName : 'imgFile',
                                        url : '{$url}',
					afterUpload : function(data) {
						if (data.error === 0) {
							var url = K.formatUrl(data.url, 'absolute');
							K('#{$this->id}').val(url);
						} else {
							alert(data.message);
						}
					},
					afterError : function(str) {
						alert('自定义错误信息: ' + str);
					}
				});
				uploadbutton.fileBox.change(function(e) {
					uploadbutton.submit();
				});
			});
EOT;

                break;
            case 'colorpicker':
                $script = <<<EOT
                            KindEditor.ready(function(K) {
				var colorpicker;
				K('#colorpicker').bind('click', function(e) {
					e.stopPropagation();
					if (colorpicker) {
						colorpicker.remove();
						colorpicker = null;
						return;
					}
					var colorpickerPos = K('#colorpicker').pos();
					colorpicker = K.colorpicker({
						x : colorpickerPos.x,
						y : colorpickerPos.y + K('#colorpicker').height(),
						z : 19811214,
						selectedColor : 'default',
						noColor : '无颜色',
						click : function(color) {
							K('#{$this->id}').val(color);
							colorpicker.remove();
							colorpicker = null;
						}
					});
				});
				K(document).click(function() {
					if (colorpicker) {
						colorpicker.remove();
						colorpicker = null;
					}
				});
			});
EOT;

                break;
            case 'file-manager':
                $script = <<<EOT
                           KindEditor.ready(function(K) {
				var editor = K.editor({
                                      
					fileManagerJson : '{$fileManagerJson}'
				});
				K('#filemanager').click(function() {
					editor.loadPlugin('filemanager', function() {
						editor.plugin.filemanagerDialog({
							viewType : 'VIEW',
							dirName : 'image',
							clickFn : function(url, title) {
								K('#{$this->id}').val(url);
								editor.hideDialog();
							}
						});
					});
				});
			});
EOT;

                break;
            case 'image-dialog':
                $script = <<<EOT
                          KindEditor.ready(function(K) {
				var editor = K.editor({
					allowFileManager : true,
                                        "uploadJson":"{$uploadJson}",
                                         "fileManagerJson":"{$fileManagerJson}",
                                        
				});
				K('#imageBtn').click(function() {
					editor.loadPlugin('image', function() {
						editor.plugin.imageDialog({
							imageUrl : K('#{$this->id}').val(),
							clickFn : function(url, title, width, height, border, align) {
								K('#{$this->id}').val(url);
								editor.hideDialog();
							}
						});
					});
				});
			});
EOT;

                break;
            case 'file-dialog':
                $script = <<<EOT
                          KindEditor.ready(function(K) {
				var editor = K.editor({
					allowFileManager : true,
                                        "uploadJson":"{$uploadJson}",
                                         "fileManagerJson":"{$fileManagerJson}",
                                        
				});
				K('#insertfile').click(function() {
					editor.loadPlugin('insertfile', function() {
						editor.plugin.fileDialog({
							fileUrl : K('#{$this->id}').val(),
							clickFn : function(url, title) {
								K('#{$this->id}').val(url);
								editor.hideDialog();
							}
						});
					});
				});
			});
EOT;

                break;
            default:
                $script = "KindEditor.ready(function(K) {
	K.create('#" . $this->id . "', " . $clientOptions . ");
});";
                break;
        }

        $pluginjs=<<<PLUGIN
              ;

              function random(min,max){
                 return Math.floor(min+Math.random()*(max-min));
              }
              function randomstr(length){
                  if(!length){
                      length=8;
                  }
                  var chars='ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
                  var str = '';
　　               for (i = 0; i < length; i++) {
　　　　                str += chars.charAt(random(0,chars.length));
　　               }
　　              return str;
              }
              function extend(des, src, override){
                 if(src instanceof Array){
                       for(var i = 0, len = src.length; i < len; i++)
                        extend(des, src[i], override);
                 }
                 for( var i in src){
                        if(override || !(i in des)){
                           des[i] = src[i];
                        }
                 }
                 return des;
              }
              /**插入播放器**/
              function insertplayer(editor,width,height,flashvars,videos,params){
                  var  editorhtml=editor.html();
                  var  playerfile="<"+"script type='text/javascript' src='"+editor.options.pluginsPath+'ckplayer/ckplayer/ckplayer.js'+"'></"+"script>";
                  var width_=width?width:'100%';
                  var height_=height?height:'250';

                  var contentid=randomstr(5);
                  var playerid="ckplayer_"+contentid;
                  var flashvars_={
                      f:'',
                      i:'',
                      c:0,
                      wh:'4:3',
                      loaded:'loadedHandler'
                  };
                  var videos_=[];
                  var params_={};
                  if(flashvars){
                      flashvars_=extend(flashvars,flashvars_);
                  }
                  if(videos){
                      videos_=extend(videos,videos_);
                  }
                  if(params){
                      params_=extend(params,params_);
                  }
                  var s='&nbsp;&nbsp;<div class="ckplayer_wrap ke-mediafile">';
                  if(!editorhtml.match("ckplayer.js")){
                      s+=playerfile;
                  }
                  var videojs='<div class="ckvedio_wrap"><div id="'+contentid+'" class="ckvedio_content"></div><'+'script type="text/javascript" id="ckplayerjs">CKobject.embed("'+editor.options.pluginsPath+'ckplayer/ckplayer/ckplayer.swf","'+contentid+'","'+playerid+'","'+width_+'","'+height_+'",false,'+JSON.stringify(flashvars_)+','+JSON.stringify(videos_)+','+JSON.stringify(params_)+');</script'+'></div>';
                  videojs=s+videojs;
                  videojs+="</div>&nbsp;&nbsp;";
                  editor.insertHtml(videojs);
                  return videojs;
              }

              KindEditor.lang({
				    mediafile : '新音视频1'
			   });
              KindEditor.plugin('mediafile', function(K) {
                  var editor = this, name = 'mediafile';
                  // 点击图标时执行
                  editor.clickToolbar(name, function() {
                        var dialogbody='<div style="padding:20px" id="newmediauploadarea"><div class="ke-dialog-row"><label for="FileUrl" style="width:60px">URL</label><input class="ke-input-text" type="text" id="FileUrl" name="url" value="" style="width:160px"> &nbsp; <input type="button" id="FileUpload" value="上传">&nbsp; &nbsp;<input type="button" id="Media_Manager" value="空间"></div><div class="ke-dialog-row"><label for="FilePoster" style="width:60px">缩略图</label><input type="text" id="FilePoster" class="ke-input-text" name="poster"> &nbsp; <input type="button" id="PosterUpload" value="上传">&nbsp;&nbsp;<input type="button" id="FilePoster_Manager" value="空间"></div><div class="ke-dialog-row"><label for="FileWidth" style="width:60px">宽度</label><input type="text" id="FileWidth" class="ke-input-text ke-input-number" name="width" maxlength="4"></div><div class="ke-dialog-row"><label for="FileHeight" style="width:60px">高度</label><input type="text" id="FileHeight" class="ke-input-text ke-input-number" name="height" maxlength="4"></div><div class="ke-dialog-row"><label for="FileAutostart">自动播放</label><input type="checkbox" id="FileAutostart" name="autostart" value="0" onclick="this.value=parseInt(this.value)?0:1"></div><div id="newmedialoading" style="z-index:1000000;text-align:center;width:100%;position:absolute;top:0;left:0;bottom:0;line-height:275px;background:rgba(255,255,255,.5);display:none">正在上传......</div></div>';
                        var dialog = K.dialog({
                          width : 500,
                          title : '新视频音频',
                          body : dialogbody,
                          closeBtn : {
                            name : '关闭',
                            click : function(e) {
                                 dialog.remove();
                            }
                          },
                          yesBtn : {
                            name : '确定',
                            click : function(e) {
                                 var url=K('#FileUrl').val();
                                 var posterurl=K('#FilePoster').val();
                                 var width=K('#FileWidth').val();
                                 var height=K('#FileHeight').val();
                                 var autostart=parseInt(K('#FileAutostart').val());
                                 var flashvars={f:url,i:posterurl,p:autostart};
                                 var videos=[url];
                                 insertplayer(editor,width,height,flashvars,videos);
                                 dialog.remove();
                            }
                          },
                          noBtn : {
                            name : '取消',
                            click : function(e) {
                                  dialog.remove();
                            }
                          }
                        });

                        var fileuploadbutton=K.uploadbutton({
                              button : K('#FileUpload')[0],
                              fieldName : 'imgFile',
                              url : K.addParam(editor.uploadJson,'dir=media'),
					            afterUpload : function(data) {

					                K('#newmedialoading').hide();
						            if (data.error === 0) {
							             var url = K.formatUrl(data.url, '');
							             K('#FileUrl').val(url);
						            }else {
							             alert(data.message);
						            }
					            },
                              afterError : function(str) {
						          alert('自定义错误信息: ' + str);
					            }
                           });
                           fileuploadbutton.fileBox.change(function(e) {
                               K('#newmedialoading').show();
					             fileuploadbutton.submit();
				             });
				             K('#Media_Manager').click(function() {
					             editor.loadPlugin('filemanager', function() {
						           editor.plugin.filemanagerDialog({
							         viewType : 'LIST',
							         dirName : 'media',
							         clickFn : function(url, title) {
							             url=K.formatUrl(url,'absolute');
							             url=K.formatUrl(url,'domain');
								         K('#FileUrl').val(url);
								         editor.hideDialog();
							         }
						           });
					             });
				             });
				              var posteruploadbutton=K.uploadbutton({
                               button : K('#PosterUpload')[0],
                               fieldName : 'imgFile',
                               url : K.addParam(editor.uploadJson,'dir=image'),
					             afterUpload : function(data) {
					                K('#newmedialoading').hide();
						            if (data.error === 0) {
							           var url = K.formatUrl(data.url, '');
							           K('#FilePoster').val(url);
						            }else {
							           alert(data.message);
						            }
					             },
                               afterError : function(str) {
						            alert('自定义错误信息: ' + str);
					             }
                             });
                             posteruploadbutton.fileBox.change(function(e) {
                                K('#newmedialoading').show();
					              posteruploadbutton.submit();
				               });
				               K('#FilePoster_Manager').click(function() {
					             editor.loadPlugin('filemanager', function() {
						           editor.plugin.filemanagerDialog({
							          viewType : 'VIEW',
							          dirName : 'image',
							          clickFn : function(url, title) {
							             url=K.formatUrl(url,'absolute');
							             url=K.formatUrl(url,'domain');
								         K('#FilePoster').val(url);
								         editor.hideDialog();
							          }
						           });
					             });
				               });
                      /*clickToolbar*/
                  });
              });
PLUGIN;
        $this->view->registerJs($pluginjs, View::POS_READY,'kindeditorpluginjs');
        $this->view->registerJs($script, View::POS_READY);
    }

}

?>
