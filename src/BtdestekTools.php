<?php

namespace GlpiPlugin\Btdestek;

class BtdestekTools
{

	/**
	 * @return void
	 */
	static function changeThemePalette(): void
	{
		global $DB;

		if(file_exists(GLPI_ROOT . '/css/palettes/iso_red.scss')) {
			$DB->update(
				'glpi_configs',
				[
					'value' => 'iso_red'
				],
				[
					'name' => 'palette'
				]
			);
		}
	}

	/**
	 * @return array
	 */
	static function getSubSolutionDropdownTypes(): array
	{
		global $DB;
		$query = 'SELECT * FROM ' . PLUGIN_BTDESTEK_SUBSOLUTIONTYPES_TABLE_NAME;
		$request = $DB->request($query);
		$list = [];

		if (!count($request))
			return $list;

		$list[0] = '-----';
		foreach ($request as $data) {
			$list[SubSolutionType::getTypeName()][$data['id']] = $data['name'];
		}
		return $list;
	}

	/**
	 * @param $oldTitle
	 * @param $newTitle
	 * @param $path_to_file
	 * @return void
	 */
	static function plugin_btdestek_changeFileContent($oldTitle, $newTitle, $path_to_file): void
	{
		if (file_exists($path_to_file)) {
			$file_contents = file_get_contents($path_to_file);
			if ($file_contents && stripos($file_contents, $oldTitle)) {
				$file_contents = str_replace($oldTitle, $newTitle, $file_contents);
				file_put_contents($path_to_file, $file_contents);
			}
		}
	}

	/**
	 * @param $source
	 * @param $target
	 * @param bool $changeFile
	 * @return void
	 */
	static function plugin_btdestek_fileCopyChange($source, $target, bool $changeFile = false): void
	{
		if (file_exists($source) && file_exists($target) && $changeFile) {
			rename($target, $target . '.bak');
			copy($source, $target);
		} else if (file_exists($source) && !file_exists($target) && !$changeFile) {
			copy($source, $target);
		}
	}
}
