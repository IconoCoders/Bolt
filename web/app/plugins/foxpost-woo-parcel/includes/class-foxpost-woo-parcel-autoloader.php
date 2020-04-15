<?php
namespace Foxpost_Woo_Parcel\Includes;

if (!defined('ABSPATH')) {
    die;
}

/**
 * Class Foxpost_Woo_Parcel_Autoloader
 */
class Foxpost_Woo_Parcel_Autoloader {
    /**
     * @var string
     *
     * @since 1.0.0
     */
    private $plugin_namespace = 'Foxpost_Woo_Parcel';
    /**
     * @var string
     *
     * @since 1.0.0
     */
    private $plugin_path;

    /**
     * Foxpost_Woo_Parcel_Autoloader constructor.
     *
     * @since 1.0.0
     *
     * @param string $plugin_path
     */
    public function __construct($plugin_path)
    {
        $this->plugin_path = $plugin_path;

        $this->register();
    }

    /**
     * Unregister autoloader.
     *
     * @since 1.0.0
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'autoloader'));
    }

    /**
     * Register autoloader.
     *
     * @since 1.0.0
     *
     * @param bool $prepend
     */
    public function register($prepend = true)
    {
        spl_autoload_register(array($this, 'autoloader'), true, $prepend);
    }

    /**
     * @param string $class
     *
     * @since 1.0.0
     */
    public function autoloader($class)
    {
        $path_parts = explode('\\', $class);

        if (count($path_parts) <= 1) {
            return;
        }

        $start = array_values($path_parts)[0];

        if ($start !== $this->plugin_namespace) {
            return;
        }

        $file = $this->plugin_path;

        $end = array_pop($path_parts);

        foreach ($path_parts as $part) {
            if ($part !== $this->plugin_namespace) {
                preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $part, $matches);

                $parts = $matches[0];

                $part = implode('-', $parts);

                $file .= strtolower($part) . '/';
            }
        }

        foreach (
            array(
                'class-',
                'abstract-',
                ''
            ) as $file_name_start
        ) {
            preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $end, $matches);

            $end = $matches[0];

            $end = implode('-', $end);

            $file_ending = $file_name_start . strtolower($end) . '.php';

            if (is_file($file . $file_ending)) {
                include_once( $file . $file_ending );

                return;
            }
        }
    }
}
