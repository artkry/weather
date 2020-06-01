<?php
require_once __DIR__ . '/vendor/autoload.php';

// Путь к файлу ключа сервисного аккаунта

$googleAccountKeyFilePath = __DIR__.'/assets/quickstart-1590997547876-edb5b462b428.json';
putenv( 'GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath );
$client = new Google_Client();
$client->useApplicationDefaultCredentials();

$client->addScope( 'https://www.googleapis.com/auth/spreadsheets' );

$service = new Google_Service_Sheets( $client );
$spreadsheetId = '1Qil_LjMdMTUBcCUoGI9zfLW6NXLFZny57G5FcgDyr40';

//get the weather data
$apiKey = "5f490afc77f9bd26e8e4d6acaf2a0dbd";
$cityID = "1489425";
$apiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityID . "&lang=ru&units=metric&APPID=" . $apiKey;

$crequest = curl_init();

curl_setopt($crequest, CURLOPT_HEADER, 0);
curl_setopt($crequest, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($crequest, CURLOPT_URL, $apiUrl);
curl_setopt($crequest, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($crequest, CURLOPT_VERBOSE, 0);
curl_setopt($crequest, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($crequest);

curl_close($crequest);
$data = json_decode($response);
$currentTime = time();


$values = [
    ["Город:", $data->name],
    ["Макс. температура", $data->main->temp_max],
    ["Мин. температура", $data->main->temp_min],
    ["Скорость ветра(м/c):", $data->wind->speed],
    ["Влажность:", $data->main->humidity],
];
$body = new Google_Service_Sheets_ValueRange( [ 'values' => $values ] );

$options = array( 'valueInputOption' => 'RAW' );

$service->spreadsheets_values->update( $spreadsheetId, 'Artem!A13', $body, $options );


?>
