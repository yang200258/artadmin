<?php

namespace app\modules\adminapi\controllers;


use app\components\GlobalFunc;
use app\models\Admin;

class Controller extends \yii\rest\Controller
{
    use GlobalFunc;
    public $enableCsrfValidation = false;

    protected $pn;
    protected $limit;
    protected $offset;
    public $admin;
    protected $authConfigs=[];

    public function init()
    {
        parent::init();
        date_default_timezone_set('PRC');
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * 登录态维护
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }

        \Yii::$app->user->enableSession = false; // 不需要session
        \Yii::$app->user->loginUrl = null;       // 登录失败不用跳回登录页面
        \Yii::$app->user->identityClass = 'app\models\Admin';

        // 部分控制器不需要验证身份
        if (in_array($this->id, ['login', 'image', 'download', 'file']))
        {
            return true;
        }
        if (in_array($this->id, ['download']))
        {
            $token = \Yii::$app->request->get('token');
        }else
        {
            $token = \Yii::$app->request->post('token');
        }
        if (!$token)
        {
            $resp = \Yii::$app->response;
            $resp->data = $this->error('登录失败，请重新登录');
            $resp->send();
            \Yii::$app->end();
        }
        $this->admin = \Yii::$app->user->loginByAccessToken($token);
        if (!$this->admin)
        {
            $resp = \Yii::$app->response;
            $resp->data = $this->error('登录失败，请重新登录', 401);
            $resp->send();
            \Yii::$app->end();
        }

        return true;
    }

    public function throwForbidden(){
        $resp = \Yii::$app->response;
        $resp->data = $this->error('您的账号无此权限！如需进行此操作，请联系管理员');
        $resp->send();
        \Yii::$app->end();
    }

    public function init_page()
    {
        $request = \Yii::$app->request;
        $pn = intval($request->post('pn'));
        if ($pn <= 0)
        {
            $pn = 1;
        }
        $this->pn = $pn;

        $limit = intval($request->post('limit'));
        $this->limit = $limit ? $limit : 50;
        $this->offset = ($pn - 1) * $this->limit;
    }

    public function page($total)
    {
        $page = [];
        $page['total'] = intval($total);
        $page['total_page'] = ceil($total / $this->limit);
        $page['limit'] = intval($this->limit);
        $page['pn'] = intval($this->pn);
        $page['is_end'] = ($this->pn >=  $page['total_page']) ? true : false;
        return $page;
    }

}
