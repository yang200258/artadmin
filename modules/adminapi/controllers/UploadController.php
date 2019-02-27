<?php

namespace app\modules\adminapi\controllers;

use app\components\ImageUpload;
use app\models\Image;


class UploadController extends Controller
{
    //上传图片接口
    public function actionIndex()
    {
        $destDir = \Yii::getAlias('@root') . '/uimage';

        $uploads = ImageUpload::save($destDir);
        $ids = [];
        $urls = [];
        foreach ($uploads as $key => $upload)
        {
            if ($upload['error'])
            {
                return $this->error($upload['data'], $upload['error']);
            }
            $imgMd5 = $upload['data'];
            $image = Image::findOne(['md5' => $imgMd5]);
            if (!$image)
            {
                $image = new Image();
                $image->md5 = $imgMd5;
                if (!$image->save(false))
                {
                    return $this->ok('图片保存失败，请重新上传！');
                }
            }
            $ids[] = strval($image->id);
            $urls[] = $image->getAbsoluteUrl();
        }

        $resp = [];
        $resp['id'] = $ids;
        $resp['url'] = $urls;

        return $this->json($resp);
    }
}
