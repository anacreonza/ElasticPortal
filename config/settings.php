<?php

$all = new stdClass();
$all->name = "all";
$all->nicename = "All Titles";

$beeld = new stdClass();
$beeld->name = "beeld";
$beeld->nicename = "Beeld";

$citypress = new stdClass();
$citypress->name = "citypress";
$citypress->nicename = "City Press";

$dailysun = new stdClass();
$dailysun->name = "dailysun";
$dailysun->nicename = "Daily Sun";

$dieburger = new stdClass();
$dieburger->name = "dieburger";
$dieburger->nicename = "Die Burger";

$rapport = new stdClass();
$rapport->name = "rapport";
$rapport->nicename = "Rapport";

$witnessmedia = new stdClass();
$witnessmedia->name = "witnessmedia";
$witnessmedia->nicename = "WitnessMedia";

return [
    'publications' => [
        $all,
        $beeld,
        $citypress,
        $dailysun,
        $dieburger,
        $rapport,
        $witnessmedia
    ]
];
?>