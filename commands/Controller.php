<?php


namespace app\commands;


class Controller extends \yii\console\Controller
{
    public function init()
    {
        parent::init();
        ini_set('memory_limit', '2048M');
        date_default_timezone_set('PRC');
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