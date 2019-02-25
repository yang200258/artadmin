<?php
namespace app\components;

use yii\helpers\FileHelper;

class ImageUpload
{
    public static function save($rootdir)
    {
        $rootdir = rtrim($rootdir, '/') . '/';
        //判断最大单张图片5M
//        $max_size = 5242880;

        //上传文件赋值给$upload_file
        $upload_files = isset($_FILES) ? $_FILES : [];
        $ret = [];
        foreach ($upload_files as $key=>$upload_file)
        {
            if (empty($upload_file['name']) ||
                empty($upload_file['tmp_name']) ||
                !empty($upload_file['error']) ||
                !is_uploaded_file($upload_file["tmp_name"]) ||
                !is_file($upload_file["tmp_name"]) ||
                !filesize($upload_file["tmp_name"]))
            {
                $ret[$key]['error'] = 1;
                $ret[$key]['data'] = '请正确上传图片';
                return $ret;
            }
            $md5file = md5_file($upload_file['tmp_name']);
            $newName = $md5file . ".jpg";

            $newdir = '';
            for ($i = 0; $i < 4; $i++)
            {
                $newdir .= substr($md5file, $i * 2, 2) . '/';
            }

            //建立文件夹
            if (!is_dir($rootdir . $newdir)) {
                if (!FileHelper::createDirectory($rootdir . $newdir, 0777, true))
                {
                    $ret[$key]['error'] = 1;
                    $ret[$key]['data'] = '创建目录失败';
                    return $ret;
                }
            }
            //设置默认服务端文件名
            $imgfn = $rootdir . $newdir . $newName;

            //上传的图片类型
            $imagetype = ['1' => 'gif', '2' => 'jpeg', '3' => 'png', '15' => 'wbmp'];
            ini_set("memory_limit", '128M');
            $imgsize = @getimagesize($upload_file['tmp_name']);
            if (empty($imgsize)) {
                $ret[$key]['error'] = 1;
                $ret[$key]['data'] = '图片信息不正确';
                return $ret;

            }
            if (empty($imagetype[$imgsize[2]])) {
                $ret[$key]['error'] = 1;
                $ret[$key]['data'] = '图片类型不正确，请上传gif，jpg，png图片。';
                return $ret;
            }
            $upfile = @move_uploaded_file($upload_file["tmp_name"], $imgfn);
            if (!$upfile) {
                $ret[$key]['error'] = 1;
                $ret[$key]['data'] = '图片上传失败,请重试。';
                return $ret;
            }
            $ret[$key]['error'] = 0;
            $ret[$key]['data'] = $md5file;
            $ret[$key]['path'] = '/uimage/' . $newdir . $newName;
            $ret[$key]['name'] = $newName;
            $ret[$key]['width'] = $imgsize[0];
            $ret[$key]['height'] = $imgsize[1];
        }
        return $ret;
    }

    public static function delete($rootdir)
    {
        $md5file = \Yii::$app->request->post('imgmd5', '');
        if (!$md5file)
        {
            $ret = [];
            $ret['error'] = 1;
            $ret['data'] = '图片的MD5不存在';
            return $ret;
        }
        $imgfile = $rootdir;
        for ($i = 0; $i < 4; $i++)
        {
            $imgfile .= substr($md5file, $i * 2, 2) . '/';
        }
        $imgfile .= $md5file . '.jpg';
        if (unlink($imgfile))
        {
            $ret = [];
            $ret['error'] = 0;
            $ret['data'] = '删除成功';
            return $ret;
        }
        else
        {
            $ret = [];
            $ret['error'] = 1;
            $ret['data'] = '删除失败';
            return $ret;
        }
    }

}