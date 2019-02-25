<?php


namespace app\components;

use \Yii;
use yii\log\Logger;
use yii\log\FileTarget;
use yii\base\InvalidConfigException;

class ProfileFileTarget extends FileTarget
{
    public $max_sql_time = 500;
    public $max_php_time = 200;
    public $max_fun_time = 100;

    public function export()
    {
        //日志格式化开始 junping
        $stack = [];
        $sql_profile = '';
        $php_profile = '';
        $fun_profile = '';

        $sql_total = 0;
        $sql_total_time = 0.0;

        $action = Yii::$app->requestedAction->getUniqueId();

        foreach ($this->messages as $message) {
            if ($message[1] == Logger::LEVEL_PROFILE_BEGIN) {
                array_push($stack, $message);
            }
            if ($message[1] == Logger::LEVEL_PROFILE_END) {
                $endtime = $message[3];
                $msg = array_pop($stack);
                $begintime = $msg[3];
                $elapsedtime = number_format($endtime - $begintime, 3) * 1000;
                if ($message[2] == 'yii\db\Command::query') {
                    $sql_total += 1;
                    $sql_total_time += floatval($elapsedtime);
                    if ($elapsedtime > $this->max_sql_time) {
                        $sql_profile .= $message[0] . " ({$elapsedtime}ms)\n";
                    }
                } else if ($message[2] == 'app\function') {
                    if ($elapsedtime > $this->max_fun_time) {
                        $fun_profile .= $message[0] . " ({$elapsedtime}ms)\n";
                    }
                } else if ($message[2] == 'app\web\index') {
                    if ($elapsedtime < $this->max_php_time) {
                        continue;
                    }
                    $php_profile = $message[0] . " " . $action . ", total time: {$elapsedtime}ms, sql time: {$sql_total_time}ms, sql count: {$sql_total} slow sql(>{$this->max_sql_time}ms)\n";
                }
            }
        }

        if (!$php_profile) {
            return;
        }


        $text = date('Y-m-d H:i:s', microtime(true)) . " " . $php_profile . $sql_profile . $fun_profile . "\n";
        //日志格式化结束 junping

        if (($fp = @fopen($this->logFile, 'a')) === false) {
            throw new InvalidConfigException("Unable to append to log file: {$this->logFile}");
        }
        @flock($fp, LOCK_EX);
        @fwrite($fp, $text);
        @flock($fp, LOCK_UN);
        @fclose($fp);
    }
}