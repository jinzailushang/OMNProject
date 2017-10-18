<?php

class myBarcode {

    /**
     * @version 绘制条形码，根据传入有无文件名判断是否产生条码文件
     * @copyright (c) 2013-09-24, cwall 
     * @param array $data       条码内容、条码规格数组
     * @param string $filename  条码文件地址
     */
    public function drawBarcode($arr, $filename = '', $is_str = TRUE) {
        $data = empty($arr['data']) ? 'testBarcode' : $arr['data'];
        $codebar = empty($arr['codebar']) ? 'BCGcode128' : $arr['codebar'];

        define('MYBARCODE_ROOT', dirname(__FILE__) . '/');

        // Including all required classes
        require_once(MYBARCODE_ROOT . 'barcodegen/class/BCGFontFile.php');
        require_once(MYBARCODE_ROOT . 'barcodegen/class/BCGColor.php');
        require_once(MYBARCODE_ROOT . 'barcodegen/class/BCGDrawing.php');

        // 条形码像素宽度
        $barcode_width_int = 1;
        // 条形码像素高度
        $barcode_height_int = 30;
        // 条形码像素高度
        $barcode_font_size = 10;

        // Including the barcode technology
        require_once(MYBARCODE_ROOT . 'barcodegen/class/' . $codebar . '.barcode.php');

        // Loading Font
        $font = $is_str ? new BCGFontFile(MYBARCODE_ROOT . 'barcodegen/font/Arial.ttf', $barcode_font_size) : 0;

        // The arguments are R, G, B for color.
        $color_black = new BCGColor(0, 0, 0);
        $color_white = new BCGColor(255, 255, 255);

        $drawException = null;
        try {
            $code = new $codebar(); //实例化对应的编码格式
            $code->setScale($barcode_width_int); // Resolution
            $code->setThickness($barcode_height_int); // Thickness
            $code->setForegroundColor($color_black); // Color of bars
            $code->setBackgroundColor($color_white); // Color of spaces
            $code->setFont($font); // Font (or 0)
            $code->parse($data);
        } catch (Exception $exception) {
            $drawException = $exception;
        }

        /* Here is the list of the arguments
          1 - Filename (empty : display on screen)
          2 - Background color */
        $drawing = new BCGDrawing($filename, $color_white);
        if ($drawException) {
            $drawing->drawException($drawException);
        } else {
            $drawing->setBarcode($code);
            $drawing->draw();
        }

        // Header that says it is an image (remove it if you save the barcode to a file)
        if (empty($filename)) {
            header('Content-Type: image/png');
        }
        // Draw (or save) the image into PNG format.
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
    }

}
