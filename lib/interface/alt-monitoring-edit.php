<?php
/*
 * Contiendra l'implémentation du panel Back Office de liste de programmes
 */

class ALT_Monitoring_edit {

    const menu_id = 'ALT_Monitoring_edit';

    public static function run() {
        add_action('admin_menu', array(__CLASS__, 'add_bo_panels'));
    }

    public static function add_bo_panels() {
        add_menu_page(
                'ALT Monitoring', 'ALT Monitoring', 'manage_options', self::menu_id, array(__CLASS__, 'handle_current_program_screen')
        );
    }

    public static function handle_current_program_screen() {
        self::edit_plugin();
    }

    public static function edit_plugin() {

        $alt_monitoring = get_option('alt_monitoring');
        //   $array = TZP_Bdd_Model_Channels::getListAll();
        //  $url = plugins_url("telez-programs");


        if (isset($_POST['enfant'])) {
            if (!isset($alt_monitoring['mdp'])) {
                echo'Il manque le mdp pour activer le site';
            } else if (!isset($alt_monitoring['site_parent'])) {
                echo'Il manque le sire parent pour activer le site';
            } else {
                $site['blogname'] = get_option('blogname');
                $site['siteurl'] = get_option('siteurl');
                $site['mdp'] = $alt_monitoring['mdp'];
                $postdata = http_build_query($site);
                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
                );
                $context = stream_context_create($opts);
                $result = file_get_contents($alt_monitoring['site_parent'] . '/AltMonitoringActvate?mdp=' . $alt_monitoring['mdp'], false, $context);

                if (is_numeric($result)) {
                    echo'Le site a été présenté';
                    $alt_monitoring['enfant'] = $result;
                    AltMonitoring::set_option('Alt_monitoring', $alt_monitoring);
                } else {
                    echo $result;
                }
            }
        }

        if (isset($_POST['parent'])) {
            //active les bases de données
            ALT_monitoring_Bdd::create_bdd_tables();
            $alt_monitoring['parent'] = 'ok';
            $alt_monitoring['site_parent'] = 'http://' . $_SERVER['SERVER_NAME'];
            AltMonitoring::set_option('Alt_monitoring', $alt_monitoring);
        }

        if (isset($_POST['syncho'])) {
            //liste des différents site 
            $liste_site = Alt_mon_sites::GetListSites();

            foreach ($liste_site as $site) {
                $url_ping = $site->sit_url . '/AltMonitoring?mdp=' . $alt_monitoring['mdp'];
                $infos = file_get_contents($url_ping);
                $site_metas = json_decode($infos);

                Alt_mon_sites_meta::drop_meta($site->sit_id);


                foreach ($site_metas as $meta) {
                    $constructeur = array();
                    $constructeur['sitm_sit_id'] = $site->sit_id;
                    if (($meta->title) == 'core') {
                        $constructeur['sitm_key'] = 'core';
                    } else {
                        $constructeur['sitm_key'] = 'plugin';
                    }
                    $constructeur['sitm_value'] = $meta->title;
                    $constructeur['sitm_infos'] = $meta->version;
                    //supprimer le plugin et remplacé le plugin
                    $informations = $constructeur;
                    $informations['sitm_sit_id'] = 0;
                    $informations['sitm_infos'] = $meta->infos;
                    $informations['sitm_key'] = 'plugin-info';
                    $informations = new Sites_meta($informations);
                    Alt_mon_sites_meta::add($informations);

                    $constructeur = new Sites_meta($constructeur);
                    Alt_mon_sites_meta::add($constructeur);
                }

                echo $site->sit_url . ' syncro';
            }
        }



        if (isset($_POST['site_parent'])) {
            if (isset($_POST['mdp'])) {
                $alt_monitoring['mdp'] = md5($_POST['mdp'] . 'altm');
            }
            $alt_monitoring['site_parent'] = $_POST['site_parent'];
            AltMonitoring::set_option('Alt_monitoring', $alt_monitoring);
        }


        if (!isset($alt_monitoring['parent'])) {
            ?>
            <h1>Activer le site parent</h1>
            <form method="POST">
                <input type="hidden" value="1" name="parent">
                <input type="submit" value="activer">
            </form>
        <?php }else
        {
            ?>
                <h1>Lancer une syncho</h1>
        <form method="POST">
            <input type="hidden" value="1" name="syncho">
            <input type="submit" value="syncho">
        </form>
            <?php
        }
        ?>

        <?php
        if (!isset($alt_monitoring['enfant'])) {
            ?>
            <h1>Activer le site enfant</h1>
            <form method="POST">
                <input type="hidden" value="1" name="enfant">
                <input type="submit" value="activer">
            </form>
            <?php
        }
        ?>

        <h1>Parametrage du site</h1>
        <form method="POST">
            <table>
                <tr>
                    <td><label for="site_parent">Site parent</label></td>
                    <td><input type="text" value="<?php echo (isset($alt_monitoring['site_parent'])) ? $alt_monitoring['site_parent'] : '' ?>" id="site_parent" name="site_parent" style=" width: 300px;"/></td>
                </tr>
                <tr>
                    <td><label for="mdp"><?php echo (isset($alt_monitoring['mdp'])) ? 'Mettre à jour le mot de passe' : 'créer un mot de passe' ?></label></td>
                    <td><input type="password" value="" id="mdp" name="mdp"  style=" width: 300px;"/></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Ajouter un mot de passe" style=" width: 200px;"/></td>
                </tr>
            </table>
        </form>


    

      
        <?php
    }

}

ALT_Monitoring_edit::run();
