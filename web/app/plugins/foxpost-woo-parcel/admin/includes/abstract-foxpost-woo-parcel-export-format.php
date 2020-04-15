<?php

namespace Foxpost_Woo_Parcel\Admin\Includes;

use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
use PHPExcel;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Foxpost_Woo_Parcel_Export_Format
 */
class Foxpost_Woo_Parcel_Export_Format {

    /**
     * Format xlsx.
     *
     * @since 1.0.0
     */
    const FORMAT_XLSX = 'XLSX';
    /**
     * Format xls.
     *
     * @since 1.0.0
     */
    const FORMAT_XLS = 'XLS';
    /**
     * Format csv.
     *
     * @since 1.0.0
     */
    const FORMAT_CSV = 'CSV';
    /**
     * Format pdf.
     *
     * @since 1.0.0
     */
    const FORMAT_PDF = 'PDF';
    /**
     * PHPExcel object.
     *
     * @var PHPExcel
     *
     * @since 1.0.0
     */
    protected $objPHPExcel;
    /**
     * Header values.
     *
     * @var array
     *
     * @since 1.0.0
     */
    protected $headerValues = array();

    /**
     * Foxpost_Woo_Parcel_Export_Format constructor.
     *
     * @param PHPExcel $objPHPExcel PHPExcel object.
     *
     * @since 1.0.0
     */
    public function __construct($objPHPExcel)
    {
        $this->objPHPExcel = $objPHPExcel;
        $this->init();
    }

    /**
     * Init.
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function init()
    {
        $this->headerValues = array(
            'recipient_name'            => Foxpost_Woo_Parcel::__('Címzett neve'),
            'recipient_phone'           => Foxpost_Woo_Parcel::__('Címzett telefonszáma'),
            'recipient_email'           => Foxpost_Woo_Parcel::__('Címzett email címe'),
            'destination'               => Foxpost_Woo_Parcel::__('Átvételi automata'),
            'recipient_city'            => Foxpost_Woo_Parcel::__('Település'),
            'recipient_zipcode'         => Foxpost_Woo_Parcel::__('Irányítószám'),
            'recipient_address'         => Foxpost_Woo_Parcel::__('Utca, házszám'),
            'cash_on_delivery_money'    => Foxpost_Woo_Parcel::__('Utánvételi összeg'),
            'parcel_size_id'            => Foxpost_Woo_Parcel::__('Csomag mérete'),
            'recipient_remark'          => Foxpost_Woo_Parcel::__('Futár információk'),
            'sender_own_data'           => Foxpost_Woo_Parcel::__('Saját adatok'),
            'extra_service_label_print' => Foxpost_Woo_Parcel::__('Címkenyomtatás'),
            'extra_service_fragile'     => Foxpost_Woo_Parcel::__('Törékeny'),
            'unique_barcode'            => Foxpost_Woo_Parcel::__('Egyedi vonalkód'),
            'reference_code'            => Foxpost_Woo_Parcel::__('Referencia kód'),
        );
    }

    /**
     * Get header values.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function getHeaderValues()
    {
        return $this->headerValues;
    }
}