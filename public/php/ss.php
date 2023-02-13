<?php 

$googleApiKey = "AIzaSyAGHe16YsdipwTLmI7_iMWQ4tdmWHXKNn8";
$site_url = "https://pinday.co.id";

$googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$site_url&screenshot=true&strategy=desktop&key=$googleApiKey");
$googlePagespeedData = json_decode($googlePagespeedData, true);
$screenshot = $googlePagespeedData['lighthouseResult']['audits']['final-screenshot']['details']['data'];
$screenshot_data = $screenshot;
list($type, $screenshot_data) = explode(';', $screenshot_data);
list(, $screenshot_data)      = explode(',', $screenshot_data);
$screenshot_data = base64_decode($screenshot_data);
$file_name = 'output'.date('YmdHis').'.png';
$rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
$output_file = $rootDir.'/public/php/ss/'.$file_name;
file_put_contents($output_file, $screenshot_data);


?>
