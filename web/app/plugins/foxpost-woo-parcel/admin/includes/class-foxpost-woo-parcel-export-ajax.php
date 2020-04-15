<?php

namespace Foxpost_Woo_Parcel\Admin\Includes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Foxpost_Woo_Parcel_Export_Ajax
 */
class Foxpost_Woo_Parcel_Export_Ajax {

    /**
     * Download export file action.
     *
     * @since 1.0.0
     */
    public function export_download()
    {
        $ids     = isset($_GET['ids']) ? $_GET['ids'] : array();
        $ids = array_map('intval', $ids);

        $settings = $this->make_settings();

        $filename = Foxpost_Woo_Parcel_Export_Data_Provider::build_file($ids, 100, '');

        $download_name = $this->_make_filename($settings['export_filename']);

        $this->send_header(Foxpost_Woo_Parcel_Export_Format::FORMAT_XLSX, $download_name);
        $this->_send_contents_delete_file($filename);
    }

    /**
     * @since 1.1.0
     */
    public function generate_stickers()
    {
        $ids = isset($_GET['ids']) ? $_GET['ids'] : array();
        $ids = array_map('intval', $ids);

        $foxpostApiManager = new Foxpost_Woo_Parcel_Foxpost_Api_Manager();
        /** @var \Foxpost\FoxpostApi\AttachmentItem $result */
        $result = $foxpostApiManager->createStickers($ids);

        if (is_object($result)) {
            $pdf = $result->getContent(true);
            if ($pdf) {
                $download_name = apply_filters('foxpost_woo_parcel_export_download_sticker_get_filename', 'cimke.pdf');
                $this->send_header(Foxpost_Woo_Parcel_Export_Format::FORMAT_PDF, $download_name);
                echo $pdf;
                exit;
            }
        }
        echo 'No pdf';
        exit;
    }

    /**
     * @param string $filename
     *
     * @since 1.0.0
     */
    private function _send_contents_delete_file($filename)
    {
        if (!empty($filename)) {
            readfile($filename);
            unlink($filename);
        }
    }

    /**
     * @param string $mask
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function _make_filename($mask)
    {
        $time = apply_filters('foxpost_woo_parcel_export_ajax_make_filename_current_time', current_time('timestamp'));

        $subst = apply_filters('foxpost_woo_parcel_make_filename_replacements', array(
            '%d' => date('d', $time),
            '%m' => date('m', $time),
            '%y' => date('Y', $time),
            '%h' => date('H', $time),
            '%i' => date('i', $time),
            '%s' => date('s', $time),
        ));

        return apply_filters('foxpost_woo_parcel_export_ajax_make_filename', strtr($mask, $subst));
    }

    /**
     * @param array $data
     *
     * @since 1.0.0
     *
     * @return array
     */
    protected function make_settings()
    {
        return array(
            'export_filename'  => 'orders-%y-%m-%d-%h-%i-%s.xls',
        );
    }

    /**
     * @since 1.0.0
     *
     * @return array
     */
    protected function sanitize_settings($data)
    {
        $data['export_filename'] = preg_replace('/[^a-zA-Z1-9\%\.-]/', '', $data['export_filename']);
        $data['export_filename'] = trim(preg_replace('/\.+/', '.', $data['export_filename']));

        return $data;
    }

    /**
     * @param array $data
     * @param string $key
     *
     * @since 1.0.0
     *
     * @return void
     */
    protected function sanitize_setting_array_item(&$data, $key)
    {
        if (!is_array($data[$key])) {
            $data[$key] = array();
        }
        foreach ($data[$key] as $i => $shipping_method) {
            $shipping_method = sanitize_key(trim($shipping_method));
            if (empty($shipping_method)) {
                unset($data[$key][$i]);
            }
        }
    }

    /**
     * Sends header.
     *
     * @param string $format
     * @param string $download_name
     *
     * @since 1.0.0
     */
    public function send_header($format, $download_name = '')
    {
        self::kill_buffers();
        switch ($format) {
            case Foxpost_Woo_Parcel_Export_Format::FORMAT_XLSX:
                if (empty($download_name)) {
                    $download_name = 'orders.xlsx';
                }
                header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                break;
            case Foxpost_Woo_Parcel_Export_Format::FORMAT_XLS:
                if (empty($download_name)) {
                    $download_name = 'orders.xls';
                }
                header('Content-type: application/vnd.ms-excel; charset=utf-8');
                break;
            case Foxpost_Woo_Parcel_Export_Format::FORMAT_CSV:
                if (empty($download_name)) {
                    $download_name = 'orders.csv';
                }
                header('Content-type: text/csv');
                break;
            case Foxpost_Woo_Parcel_Export_Format::FORMAT_PDF:
                if (empty($download_name)) {
                    $download_name = 'cimke.pdf';
                }
                header('Content-type: application/pdf');
                break;
        }
        header('Content-Disposition: attachment; filename="' . $download_name . '"');
    }

    /**
     * Clean buffers.
     *
     * @since 1.0.0
     */
    public static function kill_buffers()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
    }
}
