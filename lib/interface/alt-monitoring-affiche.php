<?php
/*
 * Contiendra l'implémentation du panel Back Office de liste de programmes
 */

class ALT_Monitoring_affiche {

    public static function run() {
        add_action('admin_menu', array(__CLASS__, 'add_bo_panels'));
    }

    public static function add_bo_panels() {

        add_submenu_page(
                ALT_Monitoring_edit::menu_id, 'Liste des enfants', 'Liste des enfants', 'manage_options', 'affiche_plugin', array(__CLASS__, 'affiche_plugin')
        );
    }

    public static function handle_current_program_screen() {
        self::affiche_plugin();
    }

    public static function affiche_plugin() {
        global $wp_version;
        $alt_monitoring = get_option('alt_monitoring');
        $les_sites = Alt_mon_sites::GetListSites();
        $les_plugins = Alt_mon_sites_meta::GetListPlugins();

        if (!function_exists('plugins_api')) {
            require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        }
        ?>

        <script>
            jQuery(function ($) {
                $(document).on('click', '.show', function () {
                    var value = $(this).attr("value");
                    $(".class_" + value).toggle();
                    return false;
                });
            });
        </script>
        <h1>Liste des sites :</h1>


        <table>

            <tr>
                <td></td>
                <?php
                foreach ($les_sites as $site) {
                    ?>
                    <td> <a href="<?php echo $site->sit_url ?>"><?php echo $site->sit_name; ?></a></td>
                    <?php
                }
                ?>

            </tr>
            <?php
            foreach ($les_plugins as $plugin) {
                $info_plugin = explode('|', $plugin->sitm_infos);

                if ($plugin->sitm_value == 'core') {
                    $version_latest = $wp_version;
                } else {
                    //prévoir une sauvegarde en base de la version
                    $args = array(
                        'slug' => $info_plugin[0],
                        'fields' => array(
                            'version' => true,
                        )
                    );

                    /** Prepare our query */
                    $call_api = plugins_api('plugin_information', $args);

                    $version_latest = 'Non disponible';
                    if (is_wp_error($call_api)) {
                        $api_error = $call_api->get_error_message();
                    } else {
                        if (!empty($call_api->version)) {
                            $version_latest = $call_api->version;
                        }
                    }
                }
                ?>
                <tr>
                    <td><a href="<?php echo (isset($info_plugin[1])) ? $info_plugin[1] : '' ?>"><?php echo $plugin->sitm_value ?></a><br/>
                        Version : <?php echo $version_latest ?><br/>
                        <div style=" width: 150px; float: left; cursor: pointer;" class="show" value="<?php echo $plugin->sitm_id ?>">Plus d'informations</div>
                        <div style=" width: 150px;  float: left; display: none;" class="class_<?php echo $plugin->sitm_id ?>">SLUG <?php echo $info_plugin[0] ?>    <?php echo (isset($info_plugin[2])) ? $info_plugin[2] : $info_plugin[2] ?></div>
                    </td>
                    <?php
                    foreach ($les_sites as $site) {
                        $plugin_version = '';
                        $plugin_version = Alt_mon_sites_meta::GetVersion($site->sit_id, $plugin->sitm_value);
                        $color = '';
                        if (!isset($plugin_version)) {
                            $color = 'background-color: #2980b9';
                        } else if ($version_latest == 'Non disponible') {
                            $color = 'background-color: #2c3e50';
                        } else if (isset($plugin_version) && $plugin_version->sitm_infos == $version_latest) {
                            $color = 'background-color: #27ae60';
                        } else {
                            $color = 'background-color: #c0392b';
                        }
                        ?>
                        <td style="color: #fff; text-align:center; <?php echo $color ?>">
                            <?php echo (isset($plugin_version)) ? $plugin_version->sitm_infos : 'Plugin non instalé' ?></td>
                            <?php
                        }
                        ?>
                </tr>
                <?php
            }
            ?>
        </table>


        <?php
    }

}

ALT_Monitoring_affiche::run();
