<?php
namespace app\helpers;

use app\models\Apply;
use app\models\ExamSite;

class Pdf {

    //生成报名评审表pdf
    public static function createPdfApply($apply_id)
    {
        $apply = Apply::findOne($apply_id);
        $file = \Yii::getAlias("@app") . "/template/apply.pdf";
        $pdfer = new Pdfer($file);
        $pdfer->addText($apply->apply_no, 88, 63);
        $pdfer->addText(substr($apply->create_at, 0, 4), 163, 63);
        $pdfer->addText(substr($apply->create_at, 5, 2), 183, 63);

        $pdfer->addText($apply->name, 40, 75);
        $pdfer->addText($apply->sex, 85, 75);
        $pdfer->addText($apply->nation, 115, 75);
        $pdfer->addText($apply->level, 155, 75);

        $pdfer->addText($apply->pinyin, 35, 88, '');
        $pdfer->addText(substr($apply->birth, 0, 4), 95, 88);
        $pdfer->addText(substr($apply->birth, 5, 2), 112, 88);

        $pdfer->addText(substr($apply->id_number, 0, 1), 41, 103);
        $pdfer->addText(substr($apply->id_number, 1, 1), 46, 103);
        $pdfer->addText(substr($apply->id_number, 2, 1), 51, 103);
        $pdfer->addText(substr($apply->id_number, 3, 1), 56, 103);
        $pdfer->addText(substr($apply->id_number, 4, 1), 61, 103);
        $pdfer->addText(substr($apply->id_number, 5, 1), 66, 103);
        $pdfer->addText(substr($apply->id_number, 6, 1), 71, 103);
        $pdfer->addText(substr($apply->id_number, 7, 1), 76, 103);
        $pdfer->addText(substr($apply->id_number, 8, 1), 81, 103);
        $pdfer->addText(substr($apply->id_number, 9, 1), 86, 103);
        $pdfer->addText(substr($apply->id_number, 10, 1), 91, 103);
        $pdfer->addText(substr($apply->id_number, 11, 1), 96, 103);
        $pdfer->addText(substr($apply->id_number, 12, 1), 101, 103);
        $pdfer->addText(substr($apply->id_number, 13, 1), 106, 103);
        $pdfer->addText(substr($apply->id_number, 14, 1), 111, 103);
        $pdfer->addText(substr($apply->id_number, 15, 1), 116, 103);
        $pdfer->addText(substr($apply->id_number, 16, 1), 121, 103);
        $pdfer->addText(substr($apply->id_number, 17, 1), 126, 103);

        $pdfer->addText($apply->phone, 40, 119);
        $pdfer->addText($apply->adviser, 40, 131);
        $v1 = $v2 = $v3 = $v4 = '';
        if ($apply->lately_credential)
        {
            $arr = explode(',', $apply->lately_credential);
            $v1 = $arr[0];
            $v2 = $arr[1];
            $v3 = $arr[2];
            $v4 = $arr[3];
        }
        $pdfer->addText($v1, 97, 125);
        $pdfer->addText($v2, 114, 125);
        $pdfer->addText($v3, 130, 125);
        $pdfer->addText($v4, 100, 130);

        $pdfer->addText($apply->track_one, 25, 149);
        $pdfer->addText($apply->track_two, 25, 157);
        $pdfer->addText($apply->track_three, 25, 165);
        $pdfer->addText($apply->track_four, 25, 173);


        $str = $apply->apply_no . '_bm';
        $saveFileName = \Yii::getAlias("@app") . "/file/apply/{$str}.pdf";
        $pdfer->export($saveFileName);
        self::pdfpng($saveFileName, \Yii::getAlias("@app") . "/file/applyimg/{$str}.png");
        return $str;

    }

    //生成准考证PDF
    public static function createPdfExam($apply_id)
    {
        $apply = Apply::findOne($apply_id);
        $file = \Yii::getAlias("@app") . "/template/exam.pdf";

        $pdfer = new Pdfer($file);
        $pdfer->addText($apply->name, 50, 58);
        $pdfer->addText($apply->apply_no, 50, 69);
        $pdfer->addText($apply->domain, 50, 80);

        $exam_site1 = ExamSite::findOne($apply->exam_site_id1);
        if (!$exam_site1)
        {
            return '';
        }
        if ($apply->domain != '基本乐科')
        {
            $pdfer->addText($apply->level, 50, 91); //报考级别列
            $pdfer->addText($exam_site1->room, 111, 91);

            $pdfer->addText(substr($exam_site1->exam_time, 5, 2), 55, 102);
            $pdfer->addText(substr($exam_site1->exam_time, 8, 2), 70, 102);
            $pdfer->addText(substr($exam_site1->exam_time, 11, 5), 102, 102);
        }else
        {
            //乐科级别
            $pdfer->addText($apply->level, 50, 113);
            $pdfer->addText($exam_site1->room, 111, 113);

            $pdfer->addText(substr($exam_site1->exam_time, 5, 2), 55, 124);
            $pdfer->addText(substr($exam_site1->exam_time, 8, 2), 70, 124);
            $pdfer->addText(substr($exam_site1->exam_time, 11, 5), 102, 124);

            if ($apply->is_continuous) //如果基本乐科连考
            {
                $exam_site2 = ExamSite::findOne($apply->exam_site_id2);
                if ($exam_site2)
                {
                    //乐科级别2
                    $pdfer->addText($apply->continuous_level, 50, 135);
                    $pdfer->addText($exam_site2->room, 111, 135);

                    $pdfer->addText(substr($exam_site2->exam_time, 5, 2), 55, 146);
                    $pdfer->addText(substr($exam_site2->exam_time, 8, 2), 70, 146);
                    $pdfer->addText(substr($exam_site2->exam_time, 11, 5), 102, 146);
                }
            }

        }

        $pdfer->addText($exam_site1->address, 50, 158);

        $str = $apply->apply_no . '_kz';
        $saveFileName = \Yii::getAlias("@app") . "/file/exam/{$str}.pdf";
        $pdfer->export($saveFileName);
        self::pdfpng($saveFileName, \Yii::getAlias("@app") . "/file/examimg/{$str}.png");
        return $str;
    }

    public static function pdfPng($pdf, $path)
    {
        $im = new \Imagick();
        $im->setCompressionQuality(100);
        $im->setResolution(120, 120);//设置分辨率 值越大分辨率越高
        $im->readImage($pdf);

        $canvas = new \Imagick();
        $imgNum = $im->getNumberImages();
        foreach ($im as $k => $sub) {
            $sub->setImageFormat('png');
            $sub->stripImage();
            $sub->trimImage(0);
            $width  = $sub->getImageWidth() + 10;
            $height = $sub->getImageHeight() + 10;
            if ($k + 1 == $imgNum) {
                $height += 10;
            } //最后添加10的height
            $canvas->newImage($width, $height, new \ImagickPixel('white'));
            $canvas->compositeImage($sub, \Imagick::COMPOSITE_DEFAULT, 5, 5);
        }

        $canvas->resetIterator();
        $canvas->appendImages(true)->writeImage($path);

    }

}