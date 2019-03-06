<?php

namespace app\helpers;


use app\models\Image;

class FetchImage
{
    public static function fetchImage($imageurl)
    {
        $imageurl = htmlspecialchars_decode($imageurl);
        $content = self::curl_multi_get($imageurl);
        $productImgDir = \Yii::getAlias('@app') . '/uimage';
        $file_extension = 'jpg';
        $filename = md5($content);
        $md5Dir = '';
        for ($i = 0; $i < 4; $i++)
        {
            $md5Dir .= substr($filename, $i * 2, 2) . '/';
        }
        $dir = $productImgDir . '/' . $md5Dir;
        if(!is_dir($dir))
        {
            if (!@mkdir($dir, 0777, true))
            {
                return [];
            }
        }
        $file = $dir . $filename . '.' . $file_extension;
        if(!is_file($file))
        {
            file_put_contents($file,$content);
        }
        $image = Image::find()->where(['md5' => $filename])->limit(1)->one();
        if(!$image)
        {
            $image = new Image();
            $image->md5 = $filename;
            $image->save(false);
        }

        return $image->md5;
    }
    public static function curl_multi_get($url)
    {
        $mh = curl_multi_init();

        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)');
        curl_setopt($conn, CURLOPT_HEADER ,0);
        curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($conn,CURLOPT_RETURNTRANSFER,true);  // 设置不将爬取代码写到浏览器，而是转化为字符串
        curl_multi_add_handle ($mh,$conn);


        do {
            curl_multi_exec($mh,$active);
        } while ($active);
        $data= curl_multi_getcontent($conn);
        curl_multi_remove_handle($mh,$conn);
        curl_close($conn);
        curl_multi_close($mh);
        return $data;
    }

}