<?php

namespace Foxpost_Woo_Parcel\Admin\Includes;

use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Shipping_Method;
use PHPExcel;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Foxpost_Woo_Parcel_Export_Data_Provider
 */
class Foxpost_Woo_Parcel_Export_Data_Provider {

    /**
     * @param $settings
     * @param $make_mode
     * @param int $limit
     * @param string $filename
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function build_file(
        $order_ids,
        $limit = 100,
        $filename = ''
    ) {
        $filename = ( !empty($filename) ? $filename : self::temp_name(sys_get_temp_dir(), 'xlsx') );

        $formatter = self::init_formatter();

        $formatter->generateHeader();
        $count = 0;
        foreach ($order_ids as $order_id) {
            if ($count >= $limit) {
                break;
            }
            $order_id = apply_filters('foxpost_woo_parcel_order_export_started', $order_id);
            if (!$order_id) {
                continue;
            }
            $column = self::fetch_order_data($order_id, $formatter->getHeaderValues());
            if (count($column) < 1) {
                continue;
            }

            $column = apply_filters('foxpost_woo_parcel_fetch_order_row', $column, $order_id);
            if ($column) {
                $formatter->generateRow($column);
                do_action('foxpost_woo_parcel_order_row_exported', $column, $order_id);

                update_post_meta($order_id, 'foxpost_order_exported_xls', date('Y-m-d H:i:s'));
            }
            ++ $count;
        }

        if ($count) {
            $_SESSION['foxpost_order_export_xls_success_message'] = sprintf(Foxpost_Woo_Parcel::__('Successfully exported to XLS orders count is: %d'), $count);
        }

        $formatter->finishTable($filename);

        return $filename;
    }

    /**
     * @since 1.0.0
     *
     * @return Foxpost_Woo_Parcel_Export_Format_Xls
     */
    private static function init_formatter()
    {
        ini_set('memory_limit', '512M');

        require_once dirname(plugin_dir_path(__DIR__))
                     . '/includes/PHPExcel.php';

        return new Foxpost_Woo_Parcel_Export_Format_Xls(new PHPExcel());
    }

    /**
     * @param array $settings
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function sql_get_order_ids($settings)
    {
        global $wpdb;

        $order_items_where = '';
        if (!empty($settings['shipping_methods'])) {
            $zone_values = array();
            /** @var $settings string[][] */
            foreach ($settings['shipping_methods'] as $value) {
                $zone_values[] = "(shipping_itemmeta.meta_value LIKE '$value%') ";
            }

            $ship_where = array();
            if (!empty($zone_values)) {
                $zone_values  = implode(" OR ", $zone_values);
                $ship_where[] = " (shipping_itemmeta.meta_key='method_id' AND ( $zone_values ) ) ";
            }

            $ship_where = implode(' OR ', $ship_where);

            $order_items_where .= " AND orders.ID IN (SELECT order_shippings.order_id FROM {$wpdb->prefix}woocommerce_order_items as order_shippings
						LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS shipping_itemmeta ON shipping_itemmeta.order_item_id = order_shippings.order_item_id
						WHERE order_shippings.order_item_type='shipping' AND $ship_where )";
        }

        $where = array(1);
        self::apply_order_filters_to_sql($where, $settings);
        $where     = apply_filters('foxpost_woo_parcel_sql_get_order_ids_where', $where, $settings);
        $order_sql = implode(' AND ', $where);

        $order_types = array("'shop_order'");

        $order_types = implode(',', apply_filters('foxpost_woo_parcel_sql_order_types', $order_types));

        $sql = 'SELECT ' . apply_filters('foxpost_woo_parcel_sql_get_order_ids_fields', 'ID AS order_id') . " FROM {$wpdb->posts} AS orders
			WHERE orders.post_type in ( $order_types) AND $order_sql $order_items_where";

        return $sql;
    }

    /**
     * @param array $arr_values
     *
     * @since 1.0.0
     *
     * @return string
     */
    private static function sql_subset($arr_values)
    {
        $values = array();
        foreach ($arr_values as $s) {
            $values[] = "'$s'";
        }

        return implode(',', $values);
    }

    /**
     * @param array $where
     * @param array $settings
     *
     * @since 1.0.0
     */
    private static function apply_order_filters_to_sql(&$where, $settings)
    {
        if ($settings['statuses']) {
            $values = self::sql_subset($settings['statuses']);
            if ($values) {
                $where[] = "orders.post_status in ($values)";
            }
        }

        foreach (self::get_date_range($settings, true) as $date) {
            $where[] = 'orders.post_date' . $date;
        }

        $where[] = "orders.post_status NOT in ('auto-draft','trash')";
    }

    /**
     * @param string $folder
     * @param string $prefix
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function temp_name($folder, $prefix)
    {
        $filename = @tempnam($folder, $prefix);
        if (!$filename) {
            $tmp_folder = dirname(dirname(__DIR__)) . '/tmp';
            foreach (glob($tmp_folder . '/*') as $f) {
                if (time() - filemtime($f) > DAY_IN_SECONDS) {
                    unlink($f);
                }
            }
            $filename = tempnam($tmp_folder, $prefix);
        }

        return $filename;
    }

    /**
     * @param array $settings
     * @param bool $is_for_sql
     * @param bool $use_timestamps
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function get_date_range($settings, $is_for_sql, $use_timestamps = false)
    {
        $result = array();

        if (!empty($settings['from_date']) || !empty($settings['to_date'])) {
            if ($settings['from_date']) {
                $from_date = strtotime($settings['from_date']);
                if ($from_date !== false) {
                    $from_date = date('Y-m-d', $from_date) . ' 00:00:00';
                    if ($is_for_sql) {
                        if ($use_timestamps) {
                            $from_date = mysql2date('U', $from_date);
                        }
                        $from_date = sprintf(">='%s'", $from_date);
                    }
                    $result['from_date'] = $from_date;
                }
            }

            if ($settings['to_date']) {
                $to_date = strtotime($settings['to_date']);
                if ($to_date !== false) {
                    $to_date = date('Y-m-d', $to_date) . ' 23:59:59';
                    if ($is_for_sql) {
                        if ($use_timestamps) {
                            $to_date = mysql2date('U', $to_date);
                        }
                        $to_date = sprintf("<='%s'", $to_date);
                    }
                    $result['to_date'] = $to_date;
                }
            }
        }

        return $result;
    }

    /**
     * @param int $order_id
     * @param array $columnName
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function fetch_order_data($order_id, array $columnName)
    {
        global $wpdb;

        static $apt_options;
        static $home_delivery_options;

        if (null === $apt_options) {
            $apt_options = get_option('woocommerce_foxpost_woo_parcel_apt_shipping_settings', array());
        }
        if (null === $home_delivery_options) {
            $home_delivery_options = get_option('woocommerce_foxpost_woo_parcel_home_delivery_shipping_settings', array());
        }

        $row        = array();
        $order_meta = array();
        $recs       = $wpdb->get_results(sprintf("SELECT meta_value,meta_key FROM {$wpdb->postmeta} WHERE post_id=%d", $order_id));
        foreach ($recs as $rec) {
            $order_meta[$rec->meta_key] = $rec->meta_value;
        }

        $order = new \WC_Order($order_id);

        $shipping_methods = $order->get_items('shipping');
        $shipping_method  = reset($shipping_methods);
        $shipping_method  = !empty($shipping_method) ? $shipping_method['method_id'] : '';

        if (Foxpost_Woo_Parcel_Shipping_Method::isMethodAptShippingMethod($shipping_method)) {
            $shipping_method_type = 'apt';
            $default_parcel_size  = isset($apt_options['default_parcel_size']) ? $apt_options['default_parcel_size'] : Foxpost_Woo_Parcel_Shipping_Method::getDefaultParcelSize();
            $sender_own_data      = isset($apt_options['seller_own_data']) ? $apt_options['seller_own_data'] : '';
        } elseif (Foxpost_Woo_Parcel_Shipping_Method::isMethodHomeDeliveryShipingMethod($shipping_method)) {
            $shipping_method_type = 'hd';
            $default_parcel_size  = isset($home_delivery_options['default_parcel_size']) ? $home_delivery_options['default_parcel_size'] : '';
            $sender_own_data      = isset($home_delivery_options['seller_own_data']) ? $home_delivery_options['seller_own_data'] : '';
        } else {
            return $row;
        }

        foreach ($columnName as $field => $label) {

            if ($field === 'recipient_phone') {
                $row[$field] = $order_meta['_billing_phone'];

            } elseif ($field === 'recipient_city') {
                $row[$field] = $order_meta['_shipping_city'];

            } elseif ($field === 'recipient_zipcode') {
                $row[$field] = $order_meta['_shipping_postcode'];

            } elseif ($field === 'recipient_address') {
                $row[$field] = implode(', ', array_filter(array(
                    $order_meta['_shipping_address_1'],
                    $order_meta['_shipping_address_2']
                )));

            } elseif ($field === 'recipient_email') {
                $row[$field] = $order_meta['_billing_email'];

            } elseif ($field === 'destination') {
                if ($shipping_method_type === 'apt') {
                    $row[$field] = $order_meta['foxpost_woo_parcel_apt_id'];
                }

            } elseif ($field === 'recipient_name') {
                $row[$field] = trim($order_meta['_shipping_first_name'] . ' ' . $order_meta['_shipping_last_name']);

            } elseif ($field === 'recipient_remark') {

                $row[$field] = mb_substr($order->get_customer_note(), 0, Foxpost_Woo_Parcel::SELLER_OWN_DATA_MAX_LENGTH);

            } elseif ($field === 'parcel_size_id') {
                $row[$field]          = $default_parcel_size;
                $selected_parcel_size = get_post_meta($order_id, 'foxpost_woo_parcel_selected_parcel_size', true);
                if ($selected_parcel_size) {
                    $row[$field] = strtolower($selected_parcel_size);
                }

            } elseif ($field === 'sender_own_data') {

                $row[$field] = mb_substr($sender_own_data, 0, Foxpost_Woo_Parcel::SELLER_OWN_DATA_MAX_LENGTH);

            } elseif ($field === 'shipping_method_title') {
                $row[$field] = $order->get_shipping_method();

            } elseif ($field === 'shipping_method') {

                $row[$field] = $shipping_method;
            } elseif ($field === 'api_external_id') {

                $row[$field] = $order_id;
            } elseif ($field === 'cash_on_delivery_money') {

                if ('cod' === $order->get_payment_method()) {
                    $row[$field] = (int) $order->get_total();
                }

            } elseif ($field === 'unique_barcode') {
                $unique_barcode = get_post_meta($order_id, 'foxpost_woo_parcel_unique_barcode', true);

                if ($unique_barcode) {
                    $row[$field] = $unique_barcode;
                }
            } elseif ($field === 'reference_code') {
                $reference_code = get_post_meta($order_id, 'foxpost_woo_parcel_reference_code', true);
                if ($reference_code) {
                    $row[$field] = $reference_code;
                }
            }

            if (!isset($row[$field])) {
                $row[$field] = '';
            }
            if (!is_scalar($row[$field])) {
                $row[$field] = json_encode($row[$field]);
            }
        }

        return apply_filters('foxpost_woo_parcel_fetch_order_data', $row);
    }
}