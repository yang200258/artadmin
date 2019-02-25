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