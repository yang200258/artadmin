<?php
namespace app\helpers;

class Excel {

    public static function Export($headers, $data, $fileName = 'simple')
    {
		require_once(dirname(__FILE__) . "/../components/PHPExcel/PHPExcel.php");
		$def_columns = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");

		$objSheet = $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(28);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);

        #header
		foreach($headers as $hkey => $hval) {
            $cell = $def_columns[$hkey].'1';
            $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimension($def_columns[$hkey])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setWrapText(true);
            $objSheet->setCellValue($cell, $hval);
		}

		#data to body
		$i = 2;
		foreach($data as $key => $val) {
			$j = 0;
			foreach($val as $k => $v) {
                $column = $def_columns[$j++];
				$cell = $column.($i);
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setWrapText(true);
                $objSheet->setCellValue($cell, $v);
			}
			$i++;
		}

		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objPHPExcel->setActiveSheetIndex(0);

//        $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=\"{$fileName}.xlsx\"");
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

    /**
     * @param array $headers 表标题
     * @param array $data 表内容
     * @param string $title 表头
     * @param int $col 表头占几行,0为不展示表头
     * @param string $fileName
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public static function ExportPro($headers, $data, $title = '', $col = 0, $fileName = 'simple')
    {
		require_once(dirname(__FILE__) . "/../components/PHPExcel/PHPExcel.php");
		$def_columns = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");

		$objSheet = $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(28);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
        $line = 1;
        if (!empty($title) && $col > 0) {
            $objPHPExcel->getActiveSheet()->mergeCells('A1:'. $def_columns[count($headers) - 1] . $col);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',$title);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $line += $col;
        }

        #header
		foreach($headers as $hkey => $hval) {
            $cell = $def_columns[$hkey] . $line;
            $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objPHPExcel->getActiveSheet()->getColumnDimension($def_columns[$hkey])->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setWrapText(true);
            $objSheet->setCellValue($cell, $hval);
		}
        $line ++;


        #data to body
		foreach($data as $key => $val) {
			$j = 0;
			foreach($val as $k => $v) {
                $column = $def_columns[$j++];
				$cell = $column.($line);
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setWrapText(true);
                // 动态设置列宽
                if (mb_strlen($v) > 6) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                } else {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(10);
                }
                $objSheet->setCellValue($cell, $v);
			}
            $line++;
		}

		$objPHPExcel->getActiveSheet()->setTitle($fileName);
		$objPHPExcel->setActiveSheetIndex(0);

		// 设置边框
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'allborders' => array( //设置全部边框
                    'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
                ),

            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle( 'A1:' . $def_columns[count($headers) - 1] . ($line - 1))->applyFromArray($styleThinBlackBorderOutline);
//        $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setWrapText(true);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=\"{$fileName}.xlsx\"");
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

	public static function Import($file, $columns)
    {
		require_once(dirname(__FILE__) . "/../components/PHPExcel/PHPExcel/IOFactory.php");
		require_once(dirname(__FILE__) . "/../components/PHPExcel/PHPExcel.php");
		$def_columns = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];


		$objPHPExcel = \PHPExcel_IOFactory::load($file);
		$objSheet = $objPHPExcel->setActiveSheetIndex(0);

		$highestRow = $objSheet->getHighestRow(); // 取得总行数
		$highestColumm = $columns ? $def_columns[$columns-1] : $objSheet->getHighestColumn(); // 取得总列数
 
		/** 循环读取每个单元格的数据 */
		$final = [];
		for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
		  $dataset = [];
		  if(empty($objSheet->getCell('A'.$row)->getValue()))
			  continue;
		  for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
			$dataset[] = $objSheet->getCell($column.$row)->getValue();
		  }
		  $final[] = $dataset;
		}

		return $final;
	}
}