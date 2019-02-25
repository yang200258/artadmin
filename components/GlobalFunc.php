<?php

namespace app\components;

use yii\web\Response;

trait GlobalFunc
{

    public function json($data = null, $msg = '', $errorCode = 0)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $json = [
            'error' => $errorCode,
            'msg' => $msg,
            'data' => $data
        ];
        return $json;
    }


    public function ok($msg, $errorCode = 0)
    {
        return $this->json(null, $msg, $errorCode);
    }


    public function error($msg = '', $errorCode = 1)
    {
        return $this->json(null, $msg, $errorCode);
    }

    /**
     * @param $price
     * @return string
     * 将INTEGER型的价格转换成以元为单位的字符串
     */
    public function priceFormat($price)
    {
        return (string)floatval($price / 100);
    }


}
