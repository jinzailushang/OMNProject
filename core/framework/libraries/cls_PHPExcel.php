<?php

/**
 * desc Excel处理类
 */
class phpExcelMod {

    const DOWNLOADPATH = '../../../data/download';

    /**
     * @desc 初始化excel文档对象
     * @copyright (c) 2013-05-17, cwall 
     */
    public function iniExcel() {
        require_once('PHPExcel.class.php');

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize' => '2048MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        //ini_set('display_errors','Off');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("admin")
                ->setLastModifiedBy("admin")
                ->setTitle('导出')
                ->setSubject('导出')
                ->setDescription("导出通用格式.")
                ->setKeywords("统一导出")
                ->setCategory("统一导出");
        return $objPHPExcel;
    }

    /**
     * @desc 产生excel文件
     * @copyright (c) 2013-05-17, cwall 
     * @param object $objPHPExcel   excel文档对象
     * @param string $title         文件名称
     */
    public function output($objPHPExcel, $title, $file_path = '') {
        $titlename = $title . ".xls";
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        if (empty($file_path)) {
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename={$titlename}");
            header('Cache-Control: max-age=0');
        } else {
            $down_path = phpExcelMod::DOWNLOADPATH . $file_path;
            // 上传目录不存在，新建文件目录
            if (!file_exists($down_path)) {
                if (!mkdir($down_path, 0777, true)) {
                    /* 创建目录失败 */
                    $this->error('上传目录为只读属性');
                }
            }
            $file = $down_path . '/' . $titlename;
        }

        $objWriter->save(empty($file_path) ? 'php://output' : $file);
    }

    /**
     * @desc 自动转换成excel表头
     * 
     * @author cwall
     * @create date 2013/01/10
     * @return string
     * */
    public function int2excel($val) {
        $str = '';
        $val = (int) $val;
        if ($val >= 0 && $val < 26) {
            $str = chr($val + 65);           // 返回单个字母A-Z
        } else {
            $str = $this->int2excel(($val / 26) - 1) . $this->int2excel($val % 26);
        }

        return $str;
    }

    /**
     * @desc 修改指定单元格格式为有边框
     * @copyright (c) 2013-04-11, cwall 
     * @param object $objPHPExcel 指定excel文档对象
     * @param string $pos   指定单元格坐标例如A1
     */
    private function setExcelBorders($objPHPExcel, $pos) {
        $objStyle = $objPHPExcel->getActiveSheet()->getStyle($pos);
        //设置边框  
        $objBorder = $objStyle->getBorders();
        $objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }

    /**
     * @desc 修改指定单元格格式为有边框下边框
     * @copyright (c) 2013-04-11, cwall 
     * @param object $objPHPExcel 指定excel文档对象
     * @param string $pos   指定单元格坐标例如A1
     */
    private function setExcelBorderBottom($objPHPExcel, $pos) {
        $objStyle = $objPHPExcel->getActiveSheet()->getStyle($pos);
        //设置边框  
        $objBorder = $objStyle->getBorders();
        $objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }

    /**
     * @desc 修改指定单元格字体格式为18号粗体黑色字
     * @copyright (c) 2013-05-16, cwall 
     * @param object $objPHPExcel 指定excel文档对象
     * @param string $pos   指定单元格坐标例如A1
     */
    private function setExcelFont($objPHPExcel, $pos) {
        $objStyle = $objPHPExcel->getActiveSheet()->getStyle($pos);
        $objFont = $objStyle->getFont();
        $objFont->setName('Courier New');
        $objFont->setSize(18);
        $objFont->setBold(true);
        $objFont->setUnderline(PHPExcel_Style_Font::UNDERLINE_NONE);
        $objFont->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
    }

    /**
     * @desc 修改指定单元格字体格式为15号字
     * @copyright (c) 2013-05-16, cwall 
     * @param object $objPHPExcel 指定excel文档对象
     * @param string $pos   指定单元格坐标例如A1
     */
    private function setExcelFontSize($objPHPExcel, $pos) {
        $objStyle = $objPHPExcel->getActiveSheet()->getStyle($pos);
        $objFont = $objStyle->getFont();
        $objFont->setName('Courier New');
        $objFont->setSize(12);
        $objFont->setUnderline(PHPExcel_Style_Font::UNDERLINE_NONE);
        $objFont->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
    }

    /**
     * @desc 修改指定单元格字体格式为粗体
     * @copyright (c) 2013-05-16, cwall 
     * @param object $objPHPExcel 指定excel文档对象
     * @param string $pos   指定单元格坐标例如A1
     */
    private function setExcelBold($objPHPExcel, $pos) {
        $objStyle = $objPHPExcel->getActiveSheet()->getStyle($pos);
        $objFont = $objStyle->getFont();
        $objFont->setName('Courier New');
        $objFont->setBold(true);
        $objFont->setUnderline(PHPExcel_Style_Font::UNDERLINE_NONE);
        $objFont->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
    }

    /**
     * @desc 修改指定单元格水平、竖直居中
     * @copyright (c) 2013-05-16, cwall 
     * @param object $objPHPExcel 指定excel文档对象
     * @param string $pos   指定单元格坐标例如A1
     */
    private function setExcelAlign($objPHPExcel, $pos) {
        $objStyle = $objPHPExcel->getActiveSheet()->getStyle($pos);
        //设置对齐方式  
        $objAlign = $objStyle->getAlignment();
        $objAlign->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objAlign->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }

    /**
     * @desc 设定指定单元格内容为图片(限定长宽为60*60)
     * @copyright (c) 2013-05-16, cwall 
     * @param object $objPHPExcel 指定excel文档对象
     * @param string $pos   指定单元格坐标例如A1
     * @param string $path  指定图片路径
     */
    private function setExcelImage($objPHPExcel, $pos, $path) {
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Photo');
        $objDrawing->setDescription('Photo');
        $objDrawing->setPath($path);
        $objDrawing->setWidth(60);
        $objDrawing->setHeight(60);
        $objDrawing->setCoordinates($pos);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
    }

    /**
     * @version 设置指定单元格为字符串格式
     * @copyright (c) 2014-08-12, cwall 
     * @param object $objPHPExcel   指定excel文档对象
     * @param string $pos           指定单元格坐标例如A1
     */
    private function _setExcelStringFormat($objPHPExcel, $pos) {
        $objStyle = $objPHPExcel->getActiveSheet()->getStyle($pos);
        $objFormat = $objStyle->getNumberFormat();
        $objFormat->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
    }

    /**
     * @desc 将excel制作表格列表页分离
     * @copyright (c) 2013-04-11, cwall 
     * @param object $objPHPExcel   excel对象
     * @param array $titlearr       excel第一行array(key=>val) key list中的关键字 val 第一行标题
     * @param array $widtharr       excel列宽 array(key=>val) key list中的关键字 val 宽度
     * @param array $list           需要导出的数据
     * @param array $typearr        导出列当前格式,当前只处理图片image和非图片
     * @param int   $t_num          开始行数
     * @return int                  当前表格占用到excel的行数
     */
    private function setExcelList($objPHPExcel, $titlearr, $widtharr, $list, $typearr, $basepos = 1) {
        set_time_limit(0);
        $t_num = 0;    // excel从A开始
        $excel_arr = array(); // 导出excel的实际字段数组 
        $pos = 1 + $basepos;
        if (!empty($titlearr)) {
            foreach ($titlearr as $k => $v) {
                $k_str = $this->int2excel($t_num);
                $objPHPExcel->getActiveSheet()->setCellValue($k_str . $basepos, $v);
                $objPHPExcel->getActiveSheet()->getColumnDimension($k_str)->setWidth($widtharr[$k]);
                $this->setExcelBorders($objPHPExcel, $k_str . $basepos);
                $excel_arr[$k_str] = $k;
                $t_num++;
            }
            // 添加一个空title，防止因为一条title造成的缓存数据不推送出问题
            $k_str = $this->int2excel($t_num);
            $objPHPExcel->getActiveSheet()->setCellValue($k_str . $basepos, '');
        }
        if (!empty($list)) {
            foreach ($list as $value) {
                foreach ($excel_arr as $k => $v) {
                    // 添加导出时候带图片
                    if ($typearr[$v] == 'image' && $value[$v]) {
                        if (file_exists($value[$v]) && is_file($value[$v])) {
                            $this->setExcelImage($objPHPExcel, $k . $pos, $value[$v]);
                            $objPHPExcel->getActiveSheet()->getRowDimension($pos)->setRowHeight(60);
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue($k . $pos, $value[$v] ? $value[$v] : '');
                        }
                    } else {
//                        $objPHPExcel->getActiveSheet()->setCellValue($k . $pos, $value[$v] ? $value[$v] : '');
//                        $this->_setExcelStringFormat($objPHPExcel, $k . $pos);
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($k . $pos, $value[$v] ? $value[$v] : '', PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                    $this->setExcelBorders($objPHPExcel, $k . $pos);
                    $this->setExcelAlign($objPHPExcel, $k . $pos);
                }
                $pos++;
            }
        }
        return $pos;
    }

    /**
     * @desc excel表单填写项
     * @param object    $objPHPExcel        excel对象
     * @param array     $bodyarr            excel写入的数据的数组
     * @param int       $t_num              当前数据写入行
     * @return type
     */
    private function setExcelDetail($objPHPExcel, $bodyarr, $pos = 1) {
        $merge_pos = $pos;
        if (is_array($bodyarr)) {
            foreach ($bodyarr as $key => $line) {
                // 合并单元格
                if ($key === 'merge') {
                    if (is_array($line)) {
                        // 遍历所有需要合并区间
                        foreach ($line as $row) {
                            // 多行合并
                            if (is_array($row)) {
                                if (count($row) == 2) {
                                    $str = '';
                                    $doc = '';
                                    // 区间转换成处理模式 $k 合并单元格行数 $val 合并单元格列数
                                    foreach ($row as $k => $val) {
                                        $str .= $doc . ($this->int2excel($val)) . ($merge_pos + $k);
                                        $doc = ':';
                                    }
                                    // 合并转换后区间
                                    $objPHPExcel->getActiveSheet()->mergeCells($str);
                                } elseif (count($row) == 1) {
                                    $ak = array_keys($row);
                                    $split = explode(':', $row[$ak[0]]);
                                    $str = '';
                                    $doc = '';
                                    // 区间转换成处理模式 $k 合并单元格行数 $val 合并单元格列数
                                    foreach ($split as $val) {
                                        $str .= $doc . ($this->int2excel($val)) . ($merge_pos + $ak[0]);
                                        $doc = ':';
                                    }
                                    // 合并转换后区间
                                    $objPHPExcel->getActiveSheet()->mergeCells($str);
                                }
                            }
                        }
                    }
                } elseif (!is_string($key)) {
                    if (is_array($line)) {
                        foreach ($line as $k => $row) {
                            $k_str = $this->int2excel($k);
                            // 设置当前为图片
                            if (strpos($row['type'], 'image') !== false) {
                                $this->setExcelImage($objPHPExcel, $k_str . $pos, $row['value']);
                                $objPHPExcel->getActiveSheet()->getRowDimension($pos)->setRowHeight(60);
                            } else {
//                                $objPHPExcel->getActiveSheet()->setCellValue($k_str . $pos, $row['value']);
                                $objPHPExcel->getActiveSheet()->setCellValueExplicit($k_str . $pos, $row['value'] ? $row['value'] : '', PHPExcel_Cell_DataType::TYPE_STRING);
                                // 设置单元格字体
                                if (strpos($row['type'], 'font') !== false) {
                                    $this->setExcelFont($objPHPExcel, $k_str . $pos);
                                } else {
                                    // 设置单元格字体大小
                                    if (strpos($row['type'], 'size') !== false) {
                                        $this->setExcelFontSize($objPHPExcel, $k_str . $pos);
                                    }
                                    // 设置单元格字体粗体
                                    if (strpos($row['type'], 'bold') !== false) {
                                        $this->setExcelBold($objPHPExcel, $k_str . $pos);
                                    }
                                }
                            }

                            // 设置单元格居中
                            if (strpos($row['type'], 'align') !== false) {
                                $this->setExcelAlign($objPHPExcel, $k_str . $pos);
                            }
                            // 设置单元格外部边框
                            if (strpos($row['type'], 'border') !== false) {
                                $this->setExcelBorders($objPHPExcel, $k_str . $pos);
                            } elseif (strpos($row['type'], 'bottom') !== false) {
                                $this->setExcelBorderBottom($objPHPExcel, $k_str . $pos);
                            }
                        }
                    }
                    $pos++;
                }
            }
        }
        return $pos;
    }

    private function setExcelColumnStyle($objPHPExcel, $ColumnStyle) {

        $objWorksheet = $objPHPExcel->getActiveSheet();
        foreach ($ColumnStyle as $key => $v) {
            $result = $objWorksheet->getDataValidation($v['Column'] . '2')
                    ->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
                    ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
                    ->setAllowBlank(false)
                    ->setShowInputMessage(true)
                    ->setShowErrorMessage(true)
                    ->setShowDropDown(true)
                    ->setErrorTitle($v['ErrorTitle'])
                    ->setError($v['Error'])
                    ->setPromptTitle($v['PromptTitle'])
                    ->setPrompt($v['Prompt'])
                    ->setFormula1('"' . $v['Formula1'] . '"');
        }
    }

    /**
     * @desc 最新接口，系统传入文件名称，活动页名称数组，活动页详细数据数组，产生excel文件导出。  多活动页导出
     * @copyright (c) 2013-05-17, cwall 
     * @param string $filename  文件名称
     * @param array $titlearr   活动页名称数组
     * @param array $sheetarr   活动页内容数组
     * 
     * @version 增加excel保存服务器目录
     * @copyright (c) 2014-03-14, cwall 
     * @param string $file_path 下载目录子目录,为空表示直接下载
     */
    public function expExcelMulSheet($filename, $titlearr, $sheetarr, $file_path = '') {
        $objPHPExcel = $this->iniExcel();
        // 遍历活动页内容数组
        $length = count($sheetarr);
        for ($i = 0; $i < $length; $i++) {
            $pos = 1;
            if ($i > 0) {
                $objPHPExcel->createSheet();
            }
            // 活动页标题
            $sheetname = !empty($titlearr[$i]) ? $titlearr[$i] : $i;
            // 处理当前活动页
            $objPHPExcel->setActiveSheetIndex($i);
            // 设置当前处理的活动页标题
            $objPHPExcel->getActiveSheet()->setTitle($sheetname);
            // 当前活动页内容写入excel
            if (is_array($sheetarr[$i])) {
                foreach ($sheetarr[$i] as $val) {
                    if ($val['sheet'] == 'table') {
                        $pos = $this->setExcelList($objPHPExcel, $val['title'], $val['width'], $val['list'], $val['type'], $pos);
                    } else {
                        $pos = $this->setExcelDetail($objPHPExcel, $val, $pos);
                    }
                }
            }
        }
        $this->output($objPHPExcel, $filename, $file_path);
        if (empty($file_path)) {
            exit;
        }
    }

    /**
     * @desc 最新接口，系统传入文件名称，活动页详细数据数组，产生excel文件导出。  单活动页导出
     * @copyright (c) 2013-05-20, cwall 
     * @param string $filename  文件名称（活动页title名称）
     * @param array $sheetarr   活动页内容数组
     *  
     * @version 增加excel保存服务器目录
     * @copyright (c) 2014-03-14, cwall 
     * @param string $file_path 下载目录子目录,为空表示直接下载
     */
    public function expExcelSingleSheet($filename, $sheetarr, $file_path = '') {
        $objPHPExcel = $this->iniExcel();
        $objPHPExcel->getActiveSheet()->setTitle($filename);
        // 当前活动页内容写入excel
        if (is_array($sheetarr)) {
            $pos = 1;
            foreach ($sheetarr as $val) {
                if ($val['sheet'] == 'table') {
                    $pos = $this->setExcelList($objPHPExcel, $val['title'], $val['width'], $val['list'], $val['type'], $pos);
                } else {
                    $pos = $this->setExcelDetail($objPHPExcel, $val, $pos);
                }
            }
        }
        $this->output($objPHPExcel, $filename, $file_path);
        if (empty($file_path)) {
            exit;
        }
    }

    /**
     * @desc 按照给定excel头，宽度，和数据生成excel
     * @copyright (c) 2013-05-20, cwall 
     * @param string $filename  文件名称（活动页title名称）
     * @param array $listarr    表格数组 title： 表格头  width：表格对应列宽度 list：表格详细信息 type 表格单元格类型
     *  
     * @version 增加excel保存服务器目录
     * @copyright (c) 2014-03-14, cwall 
     * @param string $file_path 下载目录子目录,为空表示直接下载
     */
    public function expExcelList($filename, $listarr, $file_path = '') {
        $objPHPExcel = $this->iniExcel();
        $objPHPExcel->getActiveSheet()->setTitle($filename);
        $this->setExcelList($objPHPExcel, $listarr['title'], $listarr['width'], $listarr['list'], $listarr['type']);
        $this->output($objPHPExcel, $filename, $file_path);
        if (empty($file_path)) {
            exit;
        }
    }

    /**
     * @desc 按照给定excel头，宽度，和数据生成excel
     * @copyright (c) 2013-02-26, cwall 
     * @param string $title     导出文件名称
     * @param array $titlearr   excel第一行array(key=>val) key list中的关键字 val 第一行标题
     * @param array $widtharr   excel列宽 array(key=>val) key list中的关键字 val 宽度
     * @param array $list       需要导出的数据
     * @param array $typearr    导出列当前格式,当前只处理图片image和非图片
     *  
     * @version 增加excel保存服务器目录
     * @copyright (c) 2014-03-14, cwall 
     * @param string $file_path 下载目录子目录,为空表示直接下载
     */
    public function expExcelCommon($title, $titlearr, $widtharr, $list = array(), $typearr = array(), $file_path = '', $ColumnStyle = '') {
        $objPHPExcel = $this->iniExcel();
        if (!empty($ColumnStyle)) {
            $this->setExcelColumnStyle($objPHPExcel, $ColumnStyle);
        }

        $this->setExcelList($objPHPExcel, $titlearr, $widtharr, $list, $typearr);


        $objPHPExcel->getActiveSheet()->setTitle($title);
        $this->output($objPHPExcel, $title, $file_path);
        if (empty($file_path)) {
            exit;
        }
    }

    /**
     * @desc 表单格式excel报表导出
     * @copyright (c) 2013-04-11, cwall 
     * @param string $title     导出文件名称
     * @param array $headarr    表单格式表格前数据
     * @param array $listarr    表单格式表格数据
     * @param array $footarr    表单格式表格后数据
     */
    public function expFormExcelFormat($title, $headarr, $listarr, $footarr) {
        $objPHPExcel = $this->iniExcel();
        $pos = 1;
        $pos = $this->setExcelDetail($objPHPExcel, $headarr, $pos);
        $pos = $this->setExcelList($objPHPExcel, $listarr['titlearr'], $listarr['widtharr'], $listarr['list'], $listarr['typearr'], $pos);
        $pos = $this->setExcelDetail($objPHPExcel, $footarr, $pos);

        $objPHPExcel->getActiveSheet()->setTitle($title);
        $this->output($objPHPExcel, $title);
        exit;
    }

    /**
     * @desc 按照给定excel头，宽度，和数据生成多张的excel
     * @copyright (c) 2013-04-07, jimmy
     * @param string $title     导出文件名称
     * @param array $titlearr   excel第一行array(array(STTITLE=>title,key=>val),array(STTITLE=>title,key=>val)) 
     * 							STTITLE:Sheet的标题	key:list中的关键字	val:第一行标题
     * @param array $widtharr   excel列宽(与Title一致)
     * @param array $list       需要导出的数据,三维数组
     * @param array $typearr    导出列当前格式,当前只处理图片image和非图片
     */
    public function expExcelMulti($title, $titlearr, $widtharr, $list = array(), $typearr = array()) {
        require_once('PHPExcel.class.php');

        ini_set('display_errors', 'Off');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("admin")
                ->setLastModifiedBy("admin")
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription("导出通用格式.")
                ->setKeywords("统一导出")
                ->setCategory("统一导出");

        $tnumber = 0;
        foreach ($titlearr as $value) {
            if ($tnumber > 0)
                $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex($tnumber);

            $objActSheet = $objPHPExcel->getActiveSheet();

            $t_num = 0;    // excel从A开始
            $excel_arr = array(); // 导出excel的实际字段数组
            foreach ($value as $k => $v) {
                if ($k == 'STTITLE')
                    continue;
                $k_str = $this->int2excel($t_num);
                $objActSheet->setCellValue($k_str . 1, $v);
                $objActSheet->getColumnDimension($k_str)->setWidth($widtharr[$tnumber][$k]);
                $excel_arr[$k_str] = $k;
                $t_num++;
            }
            if (!empty($list) && !empty($list[$tnumber])) {
                $tlist = $list[$tnumber];
                $pos = 2;
                //设置当前活动sheet的名称
                $objActSheet->setTitle($value['STTITLE'] . '(' . count($tlist) . '条)');
                foreach ($tlist as $val) {

                    foreach ($excel_arr as $key => $v) {
                        // 添加导出时候带图片
                        if ($typearr[$v] == 'image' && $val[$v] != '') {
                            $objDrawing = new PHPExcel_Worksheet_Drawing();
                            $objDrawing->setName('Photo');
                            $objDrawing->setDescription('Photo');
                            $objDrawing->setPath($val[$v]);
                            $objDrawing->setWidth(60);
                            $objDrawing->setHeight(60);
                            $objDrawing->setCoordinates($key . $pos);
                            $objDrawing->setWorksheet($objActSheet);
                            $objActSheet->getRowDimension($pos)->setRowHeight(60);
                        } else {
                            $objActSheet->setCellValue($key . $pos, $val[$v]);
                        }
                    }
                    $pos++;
                }
            } else {
                $objActSheet->setTitle($value['STTITLE']);
            }

            $tnumber++;
        }

        $objPHPExcel->setActiveSheetIndex(0);
        $titlename = $title . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename={$titlename}");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        exit;
    }
    /**
     * 获取列数
     * @param type $filePath
     * @return int
     */
    public function getHColumn($filePath) {
        require_once ('PHPExcel.class.php');
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize' => '2048MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        $PHPReader = new PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new PHPExcel_Reader_Excel2003XML();
                if (!$PHPReader->canRead($filePath)) {
                    showDialog("读取失败!", '', '', 'error');
                }
            }
        }
        $PHPReader->setReadDataOnly(TRUE);
        $objPHPExcel = $PHPReader->load($filePath);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数
        return $highestColumnIndex;
    }

    /**
     * @desc 获取指定excel文件的数组, 
     * @author cwall
     * @update data 2012/11/22
     * @param string $filePath 上传文件名
     * @param array $titleArray excel标题数组
     * @return array 生成的数组，一维索引下标为系统默认$_importArray的索引下标
     */
    public function excelToArray($filePath, $titleArray, $default_row = '2') {

        require_once ('PHPExcel.class.php');

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize' => '2048MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $PHPReader = new PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new PHPExcel_Reader_Excel2003XML();
                if (!$PHPReader->canRead($filePath)) {
                    showDialog("读取失败!", '', '', 'error');
                }
            }
        }

        $PHPReader->setReadDataOnly(TRUE);
        $objPHPExcel = $PHPReader->load($filePath);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); //总列数	 

        $res_arr = array();
        // 一维数组索引下标
        $key_arr = array_keys($titleArray);
        // 第一行为标题
        for ($row = $default_row; $row <= $highestRow; $row++) {
            $col_arr = array();
            $is_empty = TRUE;             # 筛选全空行
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $row_val = trim(addslashes($objWorksheet->getCellByColumnAndRow($col, $row)->getValue()));

                $col_arr[$key_arr[$col]] = $row_val;
                if (!empty($row_val)) {
                    $is_empty = FALSE;
                }
            }
            if (!$is_empty) {
//                rray_push($res_arr, $col_arr);
                $res_arr[$col_arr[$key_arr[0]] . '_' . $row] = $col_arr;
            }
        }
        return $res_arr;
    }

    /**
     * @version 导出csv版excel列表
     * @copyright (c) 2014-07-11, cwall 
     * @param string $file_name 文件名称（活动页title名称）
     * @param array $list_arr   主要需要title和list
     * @param string $file_path 文件存放目录
     * 
     */
    public function expCSVList($file_name, $list_arr, $file_path) {
        $down_path = phpExcelMod::DOWNLOADPATH . $file_path;
        // 上传目录不存在，新建文件目录
        if (!file_exists($down_path)) {
            if (!mkdir($down_path, 0777, true)) {
                /* 创建目录失败 */
                $this->error('上传目录为只读属性');
            }
        }
        $file_url = $down_path . '/' . $file_name . '.csv';

        $title_arr = $list_arr['title'];
        $list_arr = $list_arr['list'];

        $fp = fopen($file_url, 'w');
        if (!empty($title_arr)) {
            $arr = array();
            foreach ($title_arr as $k => $v) {
                $arr[] = iconv('utf-8', 'gb2312', $v);
            }
            fputcsv($fp, $arr);
            if (!empty($list_arr)) {
                foreach ($list_arr as $val) {
                    $arr = array();
                    foreach ($title_arr as $k => $v) {
                        $arr[$k] = iconv('utf-8', 'gb2312', $val[$k]);
                    }
                    fputcsv($fp, $arr);
                }
            }
        }

        fclose($fp);
        return $file_url;
    }

}

?>