<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
 
$database = new OssnDatabase;
//regenerate .htaccess file
ossn_generate_server_config('apache');

/**
 * Update processed updates in database so user cannot upgrade again and again.
 *
 * can we make this script short? 
 */
$upgrade_json = array_merge(ossn_get_upgraded_files(), array(
		$upgrade
));
$upgrade_json = json_encode($upgrade_json);

$update           = array();
$update['table']  = 'ossn_site_settings';
$update['names']  = array(
		'value'
);
$update['values'] = array(
		$upgrade_json
);
$update['wheres'] = array(
		"name='upgrades'"
);

$upgrade = str_replace('.php', '', $upgrade);
if($database->update($update) && ossn_update_db_version('3.2')) {
		ossn_trigger_message(ossn_print('upgrade:success', array(
				$upgrade
		)), 'success');
} else {
		ossn_trigger_message(ossn_print('upgrade:failed', array(
				$upgrade
		)), 'error');
}
