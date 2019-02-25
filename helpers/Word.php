<?php
namespace app\helpers;

use PhpOffice\PhpWord\TemplateProcessor;

class Word {

    public static function getLatitudeAndLongitude($address)
    {

        $templateProcessor = new TemplateProcessor('static/jianding.docx');
        $templateProcessor->setValue('ketiname','测试题目');
        $templateProcessor->setValue('ketifuzeren','测试题目');
        $templateProcessor->setValue('suozaidanwei','测试题目');
        $templateProcessor->setValue('tianbiaoriqi','测试题目');

        //保存文件
        $templateProcessor->saveAs('static/jianding1.docx');
    }
}