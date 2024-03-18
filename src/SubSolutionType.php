<?php

namespace GlpiPlugin\Btdestek;

use CommonDropdown;

class SubSolutionType extends CommonDropdown
{
	/**
	 * @param $nb
	 * @return string
	 */
	static function getTypeName($nb = 0): string
	{
		return __('Sub Solution Types', 'btdestek');
	}
}
