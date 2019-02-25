<?php
namespace app\modules\mini\controllers;


use common\components\ImageUpload;
use app\models\Image;

class UploadController extends Controller
{
    /**
     * 上传图片，目前微信通过 wx.uploadFile(OBJECT) 接口仅支持一次上传一张图片
     * 该函数拷贝自 \shop\controllers\ImageController::actionUpload
     */
    public function actionIndex()
    {
        $destDir = \Yii::getAlias('@root') . '/uimage';
        $getUrl = \Yii::$app->request->post('url', 0); // 如果传递了getUrl参数,则返回值里带上Url
        $upload = ImageUpload::save($destDir)['image'];

        if ($upload['error']){
            return $this->error($upload['data'], $upload['error']);
        }

        $imgMd5 = $upload['data'];
        $image = Image::findOne(['md5' => $imgMd5]);
        if (!$image)
        {
            $image = new Image();
            $image->md5 = $imgMd5;
            if (!$image->save(false)){
                return $this->error('图片保存失败，请重新上传！');
            }
        }
        if ($getUrl){
            $data = ["id" => "{$image->id}", "url" => $image->getAbsoluteUrl()];
            return $this->json($data);
        }
        return $this->json($image->id."");
    }
    protected function log($message, $id=0)
    {
        if ($id)
        {
            $file = \Yii::getAlias("@app") . "/runtime/logs/console/" . str_replace("/","_",$this->id) ."_{$this->action->id}_{$id}.log";
        }
        else
        {
            $file = \Yii::getAlias("@app") . "/runtime/logs/console/" . str_replace("/","_",$this->id) ."_{$this->action->id}.log";
        }
        file_put_contents($file, date("Y-m-d H:i:s") . ":" . $message . "\n", FILE_APPEND);
    }
}