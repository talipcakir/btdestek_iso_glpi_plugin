<?php

namespace GlpiPlugin\Btdestek;

use CommonDBTM;
use CommonGLPI;
use Log;

// Class of the defined type
class TicketSubSolution extends CommonDBTM
{
	/**
	 * @param $nb
	 * @return string
	 */
	static function getTypeName($nb = 0): string
	{
		return __('Ticket Sub Solutions Type', 'btdestek');
	}

	/**
	 * @return array
	 */
	static function getAll(): array
	{
		global $DB;
		$query = 'SELECT * FROM ' . PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME;
		$request = $DB->request($query);
		$list = array();

		if (!count($request))
			return $list;

		foreach ($request as $data) {
			$list[] = $data;
		}

		return $list;
	}

	/**
	 * @return int
	 */
	static function getITILSolutionId(): int
	{
		global $DB;

		return count($DB->request([
				'SELECT' => 'id',
				'FROM' => 'glpi_itilsolutions',
			])) + 1;
	}

	/**
	 * @param $tickets_id
	 * @param $solutions_id
	 * @return int
	 */
	static function getTicketSubSolutionType($tickets_id, $solutions_id): int
	{
		global $DB;

		$subsolutiontypes_id = 0;
		$result = $DB->request([
			'SELECT' => ["subsolutiontypes_id"],
			'FROM' => PLUGIN_BTDESTEK_TICKETSUBSOLUTIONS_TABLE_NAME,
			'WHERE' => [
				'tickets_id' => $tickets_id,
				'solutions_id' => $solutions_id
			]
		]);

		if (count($result) === 1) {
			foreach ($result as $data) {
				$subsolutiontypes_id = $data['subsolutiontypes_id'];
			}
		}

		return $subsolutiontypes_id;
	}

}
