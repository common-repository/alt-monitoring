<?php

class Alt_mon_sites_meta {

    const table_name = 'alt_mon_sites_meta';

    public static function create_bdd_table() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

//TODO : mettre les vrais champs de la table
        $sql = "CREATE TABLE IF NOT EXISTS " . self::table_name . " (
  `sitm_id` INT NOT NULL AUTO_INCREMENT,
  `sitm_sit_id` INT NOT NULL,
  `sitm_key` text NOT NULL,
  `sitm_value` text NULL DEFAULT NULL,
  `sitm_infos` text NULL DEFAULT NULL,
  PRIMARY KEY (`sitm_id`))
  ENGINE = MYISAM 
  " . $charset_collate . ";";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

//creation
    public static function add(Sites_meta $value) {
        global $wpdb;

        if ($value->sitm_sit_id() == '0' && $value->sitm_key() == 'plugin-info') {
            $where['sitm_sit_id'] = $value->sitm_sit_id();
            $where['sitm_key'] = $value->sitm_key();
            $where['sitm_value'] = $value->sitm_value();
            $wpdb->delete(self::table_name, $where, $where_format = null);
        }
        $data['sitm_sit_id'] = $value->sitm_sit_id();
        $data['sitm_key'] = $value->sitm_key();
        $data['sitm_value'] = $value->sitm_value();
        $data['sitm_infos'] = $value->sitm_infos();

        $wpdb->insert(self::table_name, $data, $format = null);
        return $wpdb->insert_id;
    }

//edition
    public static function update(Sites_meta $value) {
        global $wpdb;

        $data['sitm_sit_id'] = $value->sitm_sit_id();
        $data['sitm_key'] = $value->sitm_key();
        $data['sitm_value'] = $value->sitm_value();
        $data['sitm_infos'] = $value->sitm_infos();

        $where['sitm_id'] = $value->sitm_id();
        $wpdb->update(self::table_name, $data, $where, $format = null, $where_format = null);
    }

//suppression
    public static function delete($id) {
        global $wpdb;
        $where['sitm_id'] = $id;
        $wpdb->delete(self::table_name, $where, $where_format = null);
    }

    public static function drop_meta($id) {
        global $wpdb;
        $where['sitm_sit_id'] = $id;
        $wpdb->delete(self::table_name, $where, $where_format = null);
    }

    public static function get($id) {
        global $wpdb;
        $packs = array();
        $packs = $wpdb->get_results("SELECT * FROM  " . self::table_name . " WHERE  `sitm_id` =$id");
        if (isset($packs[0])) {
            return $packs[0];
        }
    }

    public static function GetVersion($site, $plugin) {
        global $wpdb;
        $packs = $wpdb->get_results("SELECT * FROM  " . self::table_name . " WHERE sitm_sit_id='$site' AND sitm_value ='$plugin' ORDER BY  sitm_key,sitm_value ");
        if (isset($packs[0])) {
            return $packs[0];
        }
    }

    public static function GetListPlugins() {
        global $wpdb;
        $packs = $wpdb->get_results("SELECT  * FROM  " . self::table_name . " WHERE sitm_sit_id=0 AND sitm_key='plugin-info' ORDER BY sitm_id ASC, sitm_value ASC  ");
        return $packs;
    }

}
