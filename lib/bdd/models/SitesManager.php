<?php

class Alt_mon_sites {

    const table_name = 'alt_mon_sites';

    public static function create_bdd_table() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        //TODO : mettre les vrais champs de la table
        $sql = "CREATE TABLE IF NOT EXISTS " . self::table_name . " (
  `sit_id` INT NOT NULL AUTO_INCREMENT,
  `sit_name` TEXT NOT NULL,
  `sit_url` TEXT NOT NULL,
  `sit_date` INT NULL DEFAULT NULL,
  PRIMARY KEY (`sit_id`))
  ENGINE = MYISAM 
  " . $charset_collate . ";";



        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    //creation
    public static function add(Sites $value) {
        global $wpdb;

        $data['sit_date'] = time();
        $data['sit_name'] = $value->sit_name();
        $data['sit_url'] = $value->sit_url();

        $wpdb->insert(self::table_name, $data, $format = null);
        return $wpdb->insert_id;
    }

    //edition
    public static function update(Sites $value) {
        global $wpdb;

        $data['sit_name'] = $value->sit_name();
        $data['sit_url'] = $value->sit_url();
        $data['sit_date'] = $value->sit_date();

        $where['sit_id'] = $value->sit_id();
        $wpdb->update(self::table_name, $data, $where, $format = null, $where_format = null);
    }

    public static function delete_all($date) {
        global $wpdb;
        $packs = $wpdb->get_results($wpdb->prepare("SELECT bro_pro_id FROM  " . self::table_name . " WHERE  `bro_date` <  %d LIMIT 100", $date));
        return $packs;
    }

    //suppression
    public static function delete($id) {
        global $wpdb;
        $where['bro_pro_id'] = $id;
        $wpdb->delete(self::table_name, $where, $where_format = null);
    }

    public static function getList($channel, $date) {
        global $wpdb;
        $sql_limit = "";
        $date = explode('/', $date);
        $new_date = $date[2] . '-' . $date[0] . '-' . $date[1];
        if (isset($_GET['page']) && ($_GET['page'] != 0)) {
            $sql_limit = "LIMIT " . $_GET['page'] * ParPage . "," . ParPage . "";
        }
        $packs = $wpdb->get_results($wpdb->prepare("SELECT * FROM  " . self::table_name . " WHERE  `bro_pro_id_chanel` ='$channel' AND  `bro_date` LIKE  '%s' $sql_limit", $new_date . '%'));
        return $packs;
    }

    public static function get($id) {
        global $wpdb;
        $packs = array();
        $packs = $wpdb->get_results("SELECT * FROM  " . self::table_name . " WHERE  `bro_pro_id` =$id");
        if (isset($packs[0])) {
            return $packs[0];
        }
    }

    public static function GetListSites() {
        global $wpdb;
        $packs = $wpdb->get_results("SELECT * FROM " . self::table_name . " ORDER BY  `sit_name` ");
        return $packs;
    }

    public static function GetByUrl($url) {
        global $wpdb;
        $packs = array();
        $packs = $wpdb->get_results("SELECT * FROM  " . self::table_name . " WHERE  `sit_url` ='$url'");
        if (isset($packs[0])) {
            return $packs[0];
        }
    }

}
