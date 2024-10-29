<?php

/*
  Plugin Name: AlT Monotoring
  Version: 1.0.3
  Plugin URI: http://wordpress.lived.fr/plugins/alt-monitoring/
  Description: AlT Monitoring permet d'avoir une vue d'ensemble sur ses différents sites
  Author URI: http://wordpress.lived.fr/AlTi5
 */


if (!class_exists('AltMonitoring')) {

    class AltMonitoring {

        public static function run() {
            register_activation_hook(__FILE__, array(__CLASS__, 'plugin_activation'));
            register_deactivation_hook(__FILE__, array(__CLASS__, 'on_deactivation'));
            add_action('plugins_loaded', array(__CLASS__, 'plugins_loaded'));
            // add_action('init', array(__CLASS__, 'init'));
        }

        protected static function lib_require() {
            require_once(dirname(__FILE__) . '/lib/bdd/bdd.php');
            require_once(dirname(__FILE__) . '/lib/interface/interface-bo.php');
        }

        public static function plugins_loaded() {
            self::lib_require();
        }

        public static function plugin_activation() {
            self::lib_require();
          
            flush_rewrite_rules();
        }

        public static function on_deactivation() {
            flush_rewrite_rules();
        }

        //   public static function init() {
        // }

        public static function set_option($option, $value) {
            if (get_option($option) !== FALSE) {
                update_option($option, $value);
            } else {
                add_option($option, $value, '', 'no');
            }
        }

    }

    AltMonitoring::run();
}

add_action('send_headers', "AltMonitoring_site_routeur");

function AltMonitoring_site_routeur() {
    $root = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
    $url = str_replace($root, "", $_SERVER['REQUEST_URI']);
    $url = explode('/', $url);
    $url = explode('?', $url[0]);
    if (!empty($url) && $url[0] == 'AltMonitoring') {
        require 'lib/front/Informations.php';
        die();
    }
    if (!empty($url) && $url[0] == 'AltMonitoringActvate') {
        require 'lib/front/Activation.php';
        die();
    }
}
