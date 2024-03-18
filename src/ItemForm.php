<?php

namespace GlpiPlugin\Btdestek;

use Dropdown;
use Html;
use Ticket;

class ItemForm
{

	/**
	 * @param $params
	 * @return void
	 */
	static public function beforeAddUpdateITILSolution($params): void
	{
		global $DB;

		$tickets_id = $_POST['items_id'] ?? $params->fields['items_id'];
		$solutions_id = $_POST['plugin_btdestek_solutions_id'] ?? $params->input['plugin_btdestek_solutions_id'];
		$plugin_btdestek_subsolutiontypes_id = $_POST['subsolutiontypes'] ?? $params->input['subsolutiontypes'];
		$subsolutiontypes_id = $_POST['subsolutiontypes'] ?? $params->input['subsolutiontypes'];
		$users_id = $_POST['plugin_btdestek_users_id'] ?? $params->fields['users_id'];

		if ($subsolutiontypes_id || $plugin_btdestek_subsolutiontypes_id) {
			$DB->updateOrInsert(
				PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME,
				[
					'tickets_id' => $tickets_id,
					'users_id' => $users_id,
					'solutions_id' => $solutions_id,
					'plugin_btdestek_subsolutiontypes_id' => $subsolutiontypes_id ?? $plugin_btdestek_subsolutiontypes_id,
					'subsolutiontypes_id' => $subsolutiontypes_id ?? $plugin_btdestek_subsolutiontypes_id,
				],
				[
					'tickets_id' => $tickets_id,
					'solutions_id' => $solutions_id
				]
			);
		}
	}

	/**
	 * @param array $params Array with "item" and "options" keys
	 * @return void
	 */
	static public function postItemForm(array $params): void
	{
		$itilSolutions = $params['item'];
		$out = '';

		if ($itilSolutions::getType() === "ITILSolution") {
			$tickets_id = null;
			$users_id = $_SESSION['glpiID'];

			if (isset(($params['options'])['item']))
				$tickets = ($params['options'])['item'];
			if (isset($tickets->fields['id']))
				$tickets_id = $tickets->fields['id'];

			$solutions_id = $itilSolutions->fields['id'] ?? TicketSubSolution::getITILSolutionId();
			$out .= '<input type="hidden" name="plugin_btdestek_solutions_id" id="plugin_btdestek_solutions_id" value="' . $solutions_id . '" />';

			$ticketSubSolutionType = TicketSubSolution::getTicketSubSolutionType($tickets_id, $solutions_id) ?? 0;

			$subSolutionTypes = BtdestekTools::getSubSolutionDropdownTypes();
			if (isset($users_id))
				$out .= '<input type="hidden" name="plugin_btdestek_users_id" id="plugin_btdestek_users_id" value="' . $users_id . '" />';

			$out .= "<tr><td>";
			Dropdown::showFromArray("subsolutiontypes", $subSolutionTypes,
				['value' => $ticketSubSolutionType]);
			$out .= "<br></td></tr>";
		}
		echo $out;
	}

}
