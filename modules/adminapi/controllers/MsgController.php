<?php

namespace app\modules\adminapi\controllers;


use app\models\Image;
use app\models\Msg;
use app\models\MsgCategory;
use app\models\MsgContent;
use yii\db\Expression;

class MsgController extends Controller
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
        $this->init_page();

        $request = \Yii::$app->request;
        $cid = $request->post('cid');
        $title = $request->post('title');
        $status = $request->post('status');
        $start_time = $request->post('start_time');
        $end_time = $request->post('end_time');

        $model = Msg::find()
            ->andFilterWhere(['cid' => $cid])
            ->andFilterWhere(['title' => $title])
            ->andFilterWhere(['status' => $status])
            ->andFilterWhere(['>=', 'create_at', $start_time])
            ->andFilterWhere(['<=', 'create_at', $end_time ? $end_time . ' 23:59:59': '']);

        $total = $model->count();
        $list = $model->orderBy('create_at desc')->offset($this->offset)->limit($this->limit)->asArray()->all();
        foreach ($list as &$one)
        {
            $one['category_name'] = MsgCategory::find()->select('name')->where(['id' => $one['cid']])->scalar();
            $one['cover_url'] = $one['cover_id'] ? Image::getAbsoluteUrlById($one['cover_id']) : '';
        }
        return $this->json(['list' => $list, 'page' => $this->page($total)]);
    }

    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $cid = $request->post('cid');
        $title = $request->post('title');
        $status = $request->post('status');
        $cover_id = $request->post('cover_id', 0);
        $intro = $request->post('intro');
        $content = $request->post('content');

        if (!$cid)
        {
            return $this->error('请选择信息分类');
        }

        if (!$title)
        {
            return $this->error('请输入标题');
        }

        if (!$intro)
        {
            return $this->error('请输入引言');
        }

        if (!$content)
        {
            return $this->error('请输入内容');
        }

        $msg_content = new MsgContent();
        $msg_content->content = new Expression("COMPRESS(:content)", [':content' => $content]);
        $msg_content->save(false);

        $msg = new Msg();
        $msg->cid = $cid;
        $msg->title = $title;
        $msg->cover_id = $cover_id ? $cover_id : 0;
        $msg->intro = $intro;
        $msg->content_id = $msg_content->id;
        $msg->status = $status;
        $msg->create_at = date("Y-m-d H:i:s",time());
        $msg->save(false);

        return $this->ok('创建成功');
    }

    public function actionEdit()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $request = \Yii::$app->request;
        $cid = $request->post('cid');
        $title = $request->post('title');
        $status = $request->post('status');  //1=已发布2=草稿
        $cover_id = $request->post('cover_id');
        $intro = $request->post('intro');
        $content = $request->post('content');

        if ($status == 1)
        {
            if (!$cid)
            {
                return $this->error('请选择信息分类');
            }

            if (!$title)
            {
                return $this->error('请输入标题');
            }

            if (!$intro)
            {
                return $this->error('请输入引言');
            }

            if (!$content)
            {
                return $this->error('请输入内容');
            }
        }

        $msg = Msg::findOne($id);
        if (!$msg)
        {
            return $this->error('信息不存在');
        }

        $msg_content = MsgContent::findOne($msg->content_id);
        $msg_content->content = new Expression("COMPRESS(:content)", [':content' => $content]);
        $msg_content->save(false);

        $msg->cid = $cid;
        $msg->title = $title;
        $msg->cover_id = $cover_id;
        $msg->intro = $intro;
        $msg->status = $status;
        $msg->save(false);

        return $this->ok('修改成功');
    }

    public function actionDetail()
    {
        $id = \Yii::$app->request->post('id');
        $msg = Msg::find()->where(['id' => $id])->asArray()->one();
        if (!$msg)
        {
            return $this->error('信息不存在');
        }
        $msg['content'] = MsgContent::find()->select('UNCOMPRESS(content) content')->where(['id' => $msg['content_id']])->scalar();
        return $this->json($msg);
    }

    public function actionDelete()
    {
        $ids = \Yii::$app->request->post('id');
        if (!$ids)
        {
            return $this->error('参数错误');
        }

        $msg_content_ids = Msg::find()->select('content_id')->where(['id' => $ids])->column();
        if ($msg_content_ids)
        {
            MsgContent::deleteAll(['id' => $msg_content_ids]);
        }

        Msg::deleteAll(['id' => $ids]);
        return $this->ok('删除成功');
    }
}
