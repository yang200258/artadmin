<?php

namespace app\modules\mini\controllers;

use yii\base\Exception;
use yii\web\Controller;
use yii\web\HttpException;

class ErrorController extends Controller
{
    public function actionIndex()
    {

        if (($exception = \Yii::$app->errorHandler->exception) === null)
        {
            return '';
        }

        if ($exception instanceof HttpException)
        {
            $code = $exception->statusCode;
        } else
        {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception)
        {
            $name = $exception->getName();
        } else
        {
            $name = \Yii::t('yii', 'Error');
        }
        if ($code)
        {
            $name .= " (#$code)";
        }

//        if ($exception instanceof UserException) {
//            $message = $exception->getMessage();
//        } else {
            $message = \Yii::t('yii', '抱歉！出现错误了！'); // 统一错误提示语 2016.3.8 yuance
//        }

        $tv = [];
        $tv['name'] = $name;
        $tv['code'] = $code;
        $tv['qihucdn'] = true;
        $tv['message'] = $message;
        $tv['exception'] = $exception;

        if (\Yii::$app->request->isAjax)
        {
            return "$name: $message";
        } else {
            return $this->render('index.twig', $tv);
        }
    }

    public function actionFailure()
    {
        $v = [];
        $v['error'] = 1;
        $request = \Yii::$app->request;
        $v['title'] = $request->get('title', '');
        $v['message'] = $request->get('msg', '');
        $v['code'] = $request->get('code', 200);
        return $this->render('result.twig', $v);
    }

    public function actionThrow()
    {
        throw new HttpException(404, '抱歉！出现错误了！');
    }

    public function actionSuccess()
    {
        $v = [];
        $v['error'] = 0;
        $request = \Yii::$app->request;
        $v['title'] = $request->get('title', '');
        $v['code'] = $request->get('code', 200);
        return $this->render('result.twig', $v);
    }
}