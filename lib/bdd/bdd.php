<?php

/**
 * Gestion des tables propres au plugin et des requètes qui vont avec :)
 */
class ALT_monitoring_Bdd {

    public static function run() {
        self::require_models();
        self::require_class();
    }


    protected static function require_models() {
        //Require des différents modèles (1 modèle par table)
        require_once( dirname(__FILE__) . '/models/SitesManager.php' );
        require_once( dirname(__FILE__) . '/models/Sites_metaManager.php' );
    }

    protected static function require_class() {
        //Require des différents class (1 modèle par table)
        require_once( dirname(__FILE__) . '/models/Sites.php' );
        require_once( dirname(__FILE__) . '/models/Sites_meta.php' );
    }

    public static function create_bdd_tables() {
        //Création des différentes tables
        Alt_mon_sites::create_bdd_table();
        Alt_mon_sites_meta::create_bdd_table();
    }

}

ALT_monitoring_Bdd::run();

