<?php

namespace app\helpers;

use setasign\Fpdi\Tcpdf\Fpdi;

class Pdfer
{
    protected $pdf;
    public function __construct($pdf_file, $language = 'zh')
    {
        $this->pdf = new Fpdi();
        $l['a_meta_charset'] = 'UTF-8';
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_language'] = $language;
        $this->pdf->setLanguageArray($l);
//        $this->pdf->AddPage();
//        $this->pdf->setSourceFile($pdf_file);
//        $tplId = $this->pdf->importPage(1);
//        $this->pdf->useTemplate($tplId);
    }

    //插入文字
    public function addText($text, $x, $y, $font='stsongstdlight', $w = 60, $h=0) {
        $this->pdf->SetXY($x, $y);
        if ($font)
        {
            $this->pdf->SetFont($font, '', 14);
        }
        $this->pdf->Cell($w, $h, $text, 0, $ln=0, 'L', 0, '', 0, false, 'T', 'C');
    }

    //保存文件
    public function export($filename) {
        $this->pdf->Output($filename, 'F');
    }

}