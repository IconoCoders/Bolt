<?php

namespace Foxpost_Woo_Parcel\Admin\Includes;

use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_RichText;
use PHPExcel_Settings;
use PHPExcel_Style_Alignment;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Foxpost_Woo_Parcel_Export_Format_Xls
 */
class Foxpost_Woo_Parcel_Export_Format_Xls extends Foxpost_Woo_Parcel_Export_Format {
    /**
     * @var int
     *
     * @since 1.0.0
     */
    protected $currentRow = 1;

    /**
     * Foxpost_Woo_Parcel_Export_Format_Xls constructor.
     *
     * @since 1.0.0
     *
     * @param PHPExcel $objPHPExcel
     */
    public function __construct($objPHPExcel)
    {
        parent::__construct($objPHPExcel);

        if (!class_exists('ZipArchive')) {
            PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        }
    }

    /**
     * @param array $columnData
     *
     * @since 1.0.0
     */
    public function generateRow($columnData)
    {
        $column = 'A';
        ++ $this->currentRow;

        foreach ($this->headerValues as $key => $headerValue) {
            $cellData = isset($columnData[$key]) ? $columnData[$key] : 'sssssss';
            // for +1234567 we set positive number format to keep plus sign in cell
            if (preg_match('#^\+(\d+)#', $cellData)) {
                $this->objPHPExcel->getActiveSheet()->getStyle($column . $this->currentRow)->getNumberFormat()->setFormatCode('+0;');
            }
            $this->objPHPExcel->setActiveSheetIndex(0)
                              ->setCellValue($column . $this->currentRow, $cellData);

            $this->objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);

            ++ $column;
        }
    }

    /**
     * Generates mass import parcel sample file.
     *
     * @since 1.0.0
     */
    public function generateHeader()
    {
        $column = 'A';

        foreach ($this->headerValues as $key => $headerValue) {

            $cell = $this->objPHPExcel->setActiveSheetIndex(0)->getCell($column . $this->currentRow);
            self::setHeaderCellValue($headerValue, null, $cell);

            $this->objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);

            ++ $column;
        }

        $this->objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(false);
        $this->objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(60);

        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $this->objPHPExcel->getActiveSheet()->getStyle('A1:' . $column . '1')->applyFromArray($style);
        $this->objPHPExcel->getActiveSheet()->getStyle('A1:' . $column . '1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->objPHPExcel->getActiveSheet()->getStyle('A1:' . $column . '1')->getAlignment()->setWrapText(true);

        $this->objPHPExcel->getActiveSheet()->setTitle(Foxpost_Woo_Parcel::__('Export data'));
    }

    /**
     * @param string $outputFile
     *
     * @since 1.0.0
     */
    public function finishTable($outputFile)
    {
        global $woocommerce;

        $site_name = get_bloginfo('name');
        $site_url  = get_bloginfo('wpurl');

        $this->objPHPExcel->getProperties()->setCreator('Woocommerce V' . $woocommerce->version)
                          ->setLastModifiedBy($site_name)
                          ->setTitle($site_url)
                          ->setSubject('Csomagvarazslo.hu')
                          ->setDescription('Példa fájl a Csomagvarazslo.hu tömeges csomag import funkcióhoz')
                          ->setKeywords('Foxpost oreder export')
                          ->setCategory('Sample file');

        $this->objPHPExcel->createSheet(1);

        self::setHeaderCellValue(Foxpost_Woo_Parcel::__('Site name'), null, $this->objPHPExcel->setActiveSheetIndex(1)->getCell('A1'));
        self::setHeaderCellValue(Foxpost_Woo_Parcel::__('Site URL'), null, $this->objPHPExcel->setActiveSheetIndex(1)->getCell('B1'));
        self::setHeaderCellValue(Foxpost_Woo_Parcel::__('Webshop engine'), null, $this->objPHPExcel->setActiveSheetIndex(1)->getCell('C1'));

        $this->objPHPExcel->setActiveSheetIndex(1)->setCellValue('A2', $site_name);
        $this->objPHPExcel->setActiveSheetIndex(1)->setCellValue('B2', $site_url);
        $this->objPHPExcel->setActiveSheetIndex(1)->setCellValue('C2', sprintf(Foxpost_Woo_Parcel::__('WooCommerce V%s'), $woocommerce->version));

        $this->objPHPExcel->getActiveSheet()->setTitle(Foxpost_Woo_Parcel::__('Webshop engine data'));

        $this->objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save($outputFile);
    }

    /**
     * Sets and format header cell values
     *
     * @param string $headerValue
     * @param string $headerExtraValue
     * @param PHPExcel_Cell $cell
     *
     * @since 1.0.0
     */
    private static function setHeaderCellValue($headerValue, $headerExtraValue, $cell)
    {
        if (empty($headerExtraValue)) {
            $cell->setValue($headerValue);

            return;
        }
        $objRichText = new PHPExcel_RichText();
        $objRichText->createText($headerValue);

        $objBold = $objRichText->createTextRun(PHP_EOL . $headerExtraValue);
        $objBold->getFont()->setBold(true);

        $cell->setValue($objRichText);
    }

}