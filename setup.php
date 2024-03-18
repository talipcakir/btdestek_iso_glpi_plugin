<?php

use Glpi\Plugin\Hooks;
use GlpiPlugin\Btdestek\ItemForm;
use GlpiPlugin\Btdestek\SubSolutionType;
use GlpiPlugin\Btdestek\TicketSubSolution;

const PLUGIN_BTDESTEK_VERSION = '0.0.1';
const PLUGIN_BTDESTEK_MIN_GLPI = '10.0.0';
const PLUGIN_BTDESTEK_MAX_GLPI = '11.0.99';
const PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME = 'glpi_plugin_btdestek_ticketsubsolutions';
const PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME = 'glpi_plugin_btdestek_subsolutiontypes';

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_btdestek()
{
	global $PLUGIN_HOOKS, $CFG_GLPI;

	// Add specific files to add to the header : javascript or css
	$PLUGIN_HOOKS[Hooks::ADD_JAVASCRIPT]['btdestek'] = 'btdestek.js';
	$PLUGIN_HOOKS[Hooks::ADD_CSS]['btdestek'] = 'btdestek.css';

	// Params : plugin name - string type - ID - Array of attributes
	Plugin::registerClass(SubSolutionType::class);
	Plugin::registerClass(TicketSubSolution::class);

	// Item action event // See define.php for defined ITEM_TYPE
	$PLUGIN_HOOKS['assign_to_ticket']['btdestek'] = 1;

//	$PLUGIN_HOOKS[Hooks::POST_INIT]['btdestek'] = 'plugin_btdestek_postInit';

	$PLUGIN_HOOKS['status']['btdestek'] = 'plugin_btdestek_Status';

	// CSRF compliance : All actions must be done via POST and forms closed by Html::closeForm();
	$PLUGIN_HOOKS[Hooks::CSRF_COMPLIANT]['btdestek'] = true;

	$PLUGIN_HOOKS[Hooks::DISPLAY_LOGIN]['btdestek'] = "plugin_btdestek_display_login";

	$PLUGIN_HOOKS[Hooks::PRE_ITEM_ADD]['btdestek'] = [
		'ITILSolution' => [ItemForm::class, 'beforeAddUpdateITILSolution']
	];
	$PLUGIN_HOOKS[Hooks::PRE_ITEM_UPDATE]['btdestek'] = [
		'ITILSolution' => [ItemForm::class, 'beforeAddUpdateITILSolution']
	];

	$PLUGIN_HOOKS[Hooks::POST_ITEM_FORM]['btdestek'] = [ItemForm::class, 'postItemForm'];

	// dictionaries
	$CFG_GLPI['languages'] = [
		'en_GB' => ['English', 'en_GB.mo', 'en-GB', 'en', 'english', 2],
		'tr_TR' => ['Türkçe', 'tr_TR.mo', 'tr', 'tr', 'turkish', 2]
	];
}

/**
 * Get the name and the version of the plugin
 *
 * REQUIRED
 *
 * @return array
 */
function plugin_version_btdestek(): array
{
	return [
		'name' => 'BT Destek - İSO',
		'version' => PLUGIN_BTDESTEK_VERSION,
		'author' => 'Talip ÇAKIR',
		'license' => 'GPLv2+',
		'homepage' => 'https://github.com/pluginsGLPI/btdestek',
		'requirements' => [
			'glpi' => [
				'min' => PLUGIN_BTDESTEK_MIN_GLPI,
				'max' => PLUGIN_BTDESTEK_MAX_GLPI,
			]
		]
	];
}

/**
 * Check pre-requisites before install
 *
 * OPTIONAL, but recommended
 *
 * @return boolean
 */
function plugin_btdestek_check_prerequisites(): bool
{
	return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_btdestek_check_config($verbose = false): bool
{
	if (true) {
		return true;
	}
}

/**
 * @return array {\Plugin.OPTION_AUTOINSTALL_DISABLED: true}
 */
function plugin_btdestek_options(): array
{
	return [
		Plugin::OPTION_AUTOINSTALL_DISABLED => true,
	];
}
