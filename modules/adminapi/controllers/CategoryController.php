<?php

namespace app\modules\adminapi\controllers;


use app\models\Msg;
use app\models\MsgCategory;

//信息分类
class CategoryController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }

        if (!$this->admin->msg)
        {
            $this->throwForbidden();
        }
        return true;
    }

    public function actionList()
    {
        $category = MsgCategory::find()->asArray()->all();
        return $this->json($category);
    }

    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $name = $request->post('name');

        if (!$name)
        {
            return $this->error('分类名称不能为空');
        }
        $category = new MsgCategory();
        $category->name = $name;
        $category->save(false);

        return $this->ok('创建成功');
    }

    public function actionEdit()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $name = $request->post('name');

        if (!$name)
        {
            return $this->error('分类名称不能为空');
        }
        if (in_array($id,[1,2,3,4,5]))
        {
            return $this->error('该分类名称不能修改');
        }
        $category = MsgCategory::findOne($id);
        if (!$category)
        {
            return $this->error('该分类不存在');
        }
        $category->name = $name;
        $category->save(false);

        return $this->ok('编辑成功');
    }

    public function actionDelete()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        if (in_array($id,[1,2,3,4,5]))
        {
            return $this->error('该分类名称不能删除');
        }
        $msg = Msg::findOne(['cid' => $id]);
        if ($msg)
        {
            return $this->error('存在关联文章，不得删除');
        }

        MsgCategory::deleteAll(['id' => $id]);

        return $this->ok('删除成功');
    }
}
