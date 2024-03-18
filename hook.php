<?php

use Dropdown as GlpiDropdown;
use GlpiPlugin\Btdestek\BtdestekTools;
use GlpiPlugin\Btdestek\SubSolutionType;

// Define dropdown relations
function plugin_btdestek_getDatabaseRelations()
{
	if (Plugin::isPluginActive("btdestek")) {
		return [
			"glpi_plugin_btdestek_subsolutiontypes" =>
				["glpi_plugin_btdestek_subsolutiontypes" => "id"]
		];
	} else {
		return [];
	}
}

// Define Dropdown tables to be manage in GLPI :
/**
 * @return array
 */
function plugin_btdestek_getDropdown(): array
{
	return [
		SubSolutionType::class => __('Sub Solution Types', 'btdestek')
	];
}

/**
 * @param $itemtype
 * @return array
 */
function plugin_btdestek_getAddSearchOptionsNew($itemtype): array
{
	return [];
}

/**
 * Plugin install process
 *
 * @return boolean
 */
function plugin_btdestek_install()
{
	global $DB;
	ProfileRight::addProfileRights(['btdestek:read']);

	$default_charset = DBConnection::getDefaultCharset();
	$default_collation = DBConnection::getDefaultCollation();
	$default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();

	if (!$DB->tableExists(PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME)) {
		$query = "CREATE TABLE `" . PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME . "` (
                  `id` int $default_key_sign NOT NULL auto_increment,
                  `name` varchar(255) default NULL,
                  `comment` text,
                PRIMARY KEY  (`id`),
                KEY `name` (`name`)
               ) ENGINE=InnoDB DEFAULT CHARSET=$default_charset COLLATE=$default_collation ROW_FORMAT=DYNAMIC;";

		$DB->doQuery($query) or die("error creating " . PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME . " " . $DB->error());

		$query = "INSERT INTO `" . PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME . "`
                       (`id`, `name`, `comment`)
                VALUES (1, 'Alt Çözüm Türü 1', 'Test amaçlıdır'),
                       (2, 'Alt Çözüm Türü 2', 'Test amaçlıdır'),
                       (3, 'Alt Çözüm Türü 3', 'Test amaçlıdır')";

		$DB->doQuery($query) or die("error populate " . PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME . " " . $DB->error());
	}
	if (!$DB->tableExists(PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME)) {
		$query = "CREATE TABLE `" . PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME . "` (
                  `id` int $default_key_sign NOT NULL auto_increment,
                  `tickets_id` int $default_key_sign NOT NULL,
                  `users_id` int $default_key_sign NOT NULL,
                  `solutions_id` int $default_key_sign NOT NULL,
                  `plugin_btdestek_subsolutiontypes_id` int $default_key_sign,
                  `subsolutiontypes_id` int $default_key_sign,
                	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  								`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`tickets_id`) REFERENCES glpi_tickets(id),
								FOREIGN KEY (`users_id`) REFERENCES glpi_users(id)
               ) ENGINE=InnoDB DEFAULT CHARSET=$default_charset COLLATE=$default_collation ROW_FORMAT=DYNAMIC;";

		$DB->doQuery($query) or die("error populate " . PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME . " " . $DB->error());
	}

//	HTTP Title Edit
	$oldTitle = '<title>{{ title }} - GLPI</title>';
	$newTitle = '<title>{{ title }} - BT Destek</title>';
	$headHtmlTwig = '../templates/layout/parts/head.html.twig';
	BtdestekTools::plugin_btdestek_changeFileContent($oldTitle, $newTitle, $headHtmlTwig);
	BtdestekTools::plugin_btdestek_fileCopyChange('misc/iso_default.scss', GLPI_ROOT . '/css/palettes/iso_default.scss', false);
	BtdestekTools::plugin_btdestek_fileCopyChange('misc/iso_red.scss', GLPI_ROOT . '/css/palettes/iso_red.scss', false);
	BtdestekTools::changeThemePalette();

	return true;
}

/**
 * Plugin uninstall process
 *
 * @return boolean
 */
function plugin_btdestek_uninstall()
{
	global $DB;

	$config = new Config();
	$config->deleteConfigurationValues('plugin:Btdestek', ['configuration' => false]);

	// Current version tables
	if ($DB->tableExists(PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME)) {
		$query = "DROP TABLE `" . PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME . "`";
		$DB->doQuery($query) or die("error deleting " . PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME);
	}
	if ($DB->tableExists(PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME)) {
		$query = "DROP TABLE `" . PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME . "`;";
		$DB->doQuery($query) or die("error deleting " . PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME);
	}
	return true;
}

function plugin_btdestek_AssignToTicket($types)
{
//	$types[TicketSubSolution::class] = "TicketSubSolution";
	return $types;
}

// Check to add to status page
function plugin_btdestek_Status($param)
{
	// Do checks (no check for btdestek)
	$ok = true;
	echo "btdestek plugin: btdestek";
	if ($ok) {
		echo "_OK";
	} else {
		echo "_PROBLEM";
		$param['ok'] = false;
	}
	echo "\n";
	return $param;
}

function plugin_btdestek_display_login()
{
//	T4 - Login Sayfası Logo Düzenlemesi
	echo "<style>";
	echo "body .page-anonymous .glpi-logo,";
	echo ".page-anonymous .glpi-logo {";
	echo "  height: 69px !important;";
	echo "  width: 250px !important;";
	echo "  background-size: 250px 69px !important;";
	echo "  --logo: url(\"../plugins/btdestek/pics/BT_Destek_Logo_Yeni.png\") !important;";
	echo "}";
	echo "body > div.page-anonymous > div > div > div.card.card-md > div > form > div > div.col-md-5 > div.card-header.mb-4 {";
	echo "display:none !important";
	echo "}";
	echo "body .page-anonymous a.copyright {";
	echo "display:none !important";
	echo "}";
	echo "</style>";
	echo "<script>";
	echo "$('#login_name').on('keypress', function(event) {";
	echo "  const regexLogin = new RegExp('^[a-zA-Z]+$');";
	echo "  const keyLogin = String.fromCharCode(!event.charCode ? event.which : event.charCode);";
	echo "  if (!regexLogin.test(keyLogin)) {";
	echo "    event.preventDefault();";
	echo "    return false;";
	echo "  }";
	echo "});";
	echo "$('#login_name').bind('paste', function() {";
	echo "  setTimeout(function() {";
	echo "    let valueLogin = $('#login_name').val();";
	echo "    let updated = valueLogin.replace(/[^A-Za-z]/g, '');";
	echo "    $('#login_name').val(updated);";
	echo "  });";
	echo "});";
	echo "</script>";
}
