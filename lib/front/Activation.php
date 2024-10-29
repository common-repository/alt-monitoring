<?php

$alt_monitoring = get_option('alt_monitoring');

if (isset($_GET['mdp'])) {

    if ($_GET['mdp'] == $alt_monitoring['mdp']) {


        $site['sit_name'] = $_POST['blogname'];
        $site['sit_url'] = $_POST['siteurl'];

        $site = new Sites($site);
        $return = Alt_mon_sites::GetByUrl($_POST['siteurl']);

        if (!isset($return)) {
            $site_add = Alt_mon_sites::Add($site);
            echo $site_add;
        } else {
            echo $return->sit_id;
        }
    } else {
        echo'Erreur: mot de passe incorect';
    }
}