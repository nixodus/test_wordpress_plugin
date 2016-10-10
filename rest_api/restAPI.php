<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class RestAPI
{

    private $link = '';

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function AddValues($value1 = "", $value2 = "")
    {

        $vars = 'value1=' . $value1 . '&value2=' . $value2;

        $ch = curl_init($this->link . '/addvalues' . '?' . $vars);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = json_decode(curl_exec($ch), 1);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode != 200) {
            return false;
        } else {
            return $response;
        }
    }
}
