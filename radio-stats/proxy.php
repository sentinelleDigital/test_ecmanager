<?php
// proxy.php - CORS Bypass Script for Radio Dashboard
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['url'])) {
    http_response_code(400);
    die(json_encode(['error' => 'URL parameter is missing']));
}

$url = $_GET['url'];

// Basic validation to ensure we only proxy status-json.xsl 
if (strpos($url, 'status-json.xsl') === false) {
    http_response_code(403);
    die(json_encode(['error' => 'Not allowed']));
}

// Fetch the URL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'RadioStats Dashboard Proxy Bot');

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if(curl_errno($ch)){
    http_response_code(500);
    echo json_encode(['error' => curl_error($ch)]);
} else {
    http_response_code($httpcode);
    echo $response;
}
curl_close($ch);
?>
