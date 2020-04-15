<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Class Foxpost_Woo_Parcel_Autoloader
 */
class FoxpostApiAutoloader {
    /**
     * @var string
     */
    private $plugin_namespace = 'Foxpost\FoxpostApi';

    /**
     * @var string
     *
     */
    private $autoload_path;

    /**
     * Foxpost_Woo_Parcel_Autoloader constructor.
     *
     * @param string $autoload_path
     */
    public function __construct($autoload_path)
    {
        $this->autoload_path = $autoload_path;

        $this->register();
    }

    /**
     * Unregister autoloader.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'autoloader'));
    }

    /**
     * Register autoloader.
     *
     * @param bool $prepend
     *
     * @throws \Exception
     */
    public function register($prepend = true)
    {
        spl_autoload_register(array($this, 'autoloader'), true, $prepend);
    }

    /**
     * Autoload a class identified by name
     *
     * @param string $class Name of the object to load
     *
     * @return bool
     */
    public function autoloader($class)
    {
        if (
            class_exists($class, false)
            || stripos($class, $this->plugin_namespace) !== 0
        ) {
            return false;
        }

        $pClassFilePath = $this->autoload_path . str_replace('Foxpost\FoxpostApi\\', '', $class) . '.php';

        if (( file_exists($pClassFilePath) === false ) || ( is_readable($pClassFilePath) === false )) {
            // Can't load
            return false;
        }

        require $pClassFilePath;
    }
}
