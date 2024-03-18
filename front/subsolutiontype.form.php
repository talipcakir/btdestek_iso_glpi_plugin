<?php

use GlpiPlugin\Btdestek\SubSolutionType;

include ('../../../inc/includes.php');

Plugin::load('btdestek', true);

$dropdown = new SubSolutionType();
include (GLPI_ROOT . "/front/dropdown.common.form.php");
