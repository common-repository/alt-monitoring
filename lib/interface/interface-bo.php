<?php
    $alt_monitoring = get_option('alt_monitoring');
require_once( dirname(__FILE__) . '/alt-monitoring-edit.php' );
if (isset($alt_monitoring['parent'])) {
    require_once( dirname(__FILE__) . '/alt-monitoring-affiche.php' );
}
