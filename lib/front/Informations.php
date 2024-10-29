<?php

$alt_monitoring = get_option('alt_monitoring');

if (isset($_GET['mdp'])) {

    if ($_GET['mdp'] == $alt_monitoring['mdp']) {
        global $wp_version;
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();

        $syncho['core']['title'] = 'core';
        $syncho['core']['version'] = $wp_version;
        $syncho['core']['infos'] = '||Coeur de WordPress';
        foreach ($all_plugins as $plugin_slug => $plugin) {
            $plugin_base = basename(
                    $plugin_slug, // Get the key which holds the folder/file name
                    '.php' // Strip away the .php part
            );
            $syncho[$plugin['Name']]['title'] = $plugin['Name'];
            $syncho[$plugin['Name']]['version'] = $plugin['Version'];
            $syncho[$plugin['Name']]['infos'] = $plugin_base . '|' . $plugin['PluginURI'] . '|' . $plugin['Description'];
        }
        $encode_json = json_encode($syncho);
        echo $encode_json;
    }
}



