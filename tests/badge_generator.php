<?php

$cloverXml = __DIR__ . '/../reports/clover.xml';
$template = __DIR__ . '/badge/coverage_template.svg';
$badge = __DIR__ . '/badge/coverage.svg';

if (!file_exists($cloverXml)) {
    echo "No coverage file found: $cloverXml" . PHP_EOL;
    exit;
}

$xml = new SimpleXMLElement(file_get_contents($cloverXml));
$metrics = $xml->xpath('//metrics');
$totalElements = 0;
$checkedElements = 0;

foreach ($metrics as $metric) {
    $totalElements += (int)$metric['elements'];
    $checkedElements += (int)$metric['coveredelements'];
}
$coverage = round(($checkedElements / $totalElements) * 100);

$color = 'ff0000';
if ($coverage >= 50) $color = 'd80000';
if ($coverage >= 60) $color = 'c4a600';
if ($coverage >= 70) $color = 'b9d117';
if ($coverage >= 80) $color = '7ad117';
if ($coverage >= 90) $color = '34D058';

$darkeningFactor = 1.2;
$R = sprintf("%02X", floor(hexdec(substr($color, 0, 2)) / $darkeningFactor));
$G = sprintf("%02X", floor(hexdec(substr($color, 2, 2)) / $darkeningFactor));
$B = sprintf("%02X", floor(hexdec(substr($color, 4, 2)) / $darkeningFactor));

$values = [
    'coverage' => $coverage,
    'colorStart' => $color,
    'colorStop' => $R . $G . $B,
];


$svg = file_get_contents($template);
foreach ($values as $search => $replace) {
    $search = '{{' . $search . '}}';
    $svg = str_replace($search, $replace, $svg);
}
file_put_contents($badge, $svg);
