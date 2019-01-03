<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-31
 * Time: 下午2:58
 */
namespace backend\components;

use yii\web\HttpException as Exception;

class Upload
{

    public  function upload($path,$baseurl,$originName,$usename=false,$type="img"){

        $return=array('error'=>0,'msg'=>'',"src"=>'');
        try{
            $root = rtrim($path,"/");
            $baseurl=rtrim($baseurl,"/");
            $files=$_FILES[$originName];
            $folder = date('Ymd');
            $pre = rand(999,9999).time();
            if(!$files){
                throw new Exception("文件不存在");
            }
            if($files['error']){
                switch($files['error']){
                    case 1:
                        throw new Exception("上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值");
                    case 2:
                        throw new Exception("上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值");
                    case 3:
                        throw new Exception("文件只有部分被上传");
                    case 4:
                        throw new Exception("没有文件被上传");
                }
            }
            $pathinfo=pathinfo($files['name']);
            if($usename){
                $newName = $pre.$pathinfo['basename'];
            }
            else{
                $newName = $pre.'.'.$pathinfo['extension'];
            }
            if($files['size'] > 2000000){
                throw new Exception("上传的文件太大");
            }

            if(!is_dir($root."/".$folder))
            {
                if(!mkdir($root."/".$folder, 0777, true)){
                    throw new Exception('创建目录失败...');
                }else{
                }
            }


            if(move_uploaded_file($files['tmp_name'],$root."/".$folder."/".$newName))
            {
                $return['src']=rtrim($baseurl,"/")."/".$folder."/".$newName;
            }
            else{
                throw new Exception("上传失败！");
            }
            return $return;
        }
        catch(Exception $e){
            $return['error']=1;
            $return['msg']=$e->getMessage();
            return $return;
        }
    }
}