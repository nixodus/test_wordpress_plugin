<?php
/*
Plugin Name: RESTful example
Plugin URI: 
Description: RESTful example
Version: 1.0
Author: Nicholas
Author URI: 
*/

define('REST_API__PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once(REST_API__PLUGIN_DIR . 'restAPI.php');


if (!function_exists('restApiPluginCalculateForm')) {
    function restApiPluginCalculateForm($value1 = "", $value2 = "", $result = "", $errorValue1 = "", $errorValue2 = "")
    {

        echo '<H1>Add 2 values:</H1>';

        if ($result) {
            echo '<H3>Value 1 + Value 2 = ' . $result . '</H3>';
        }

        echo '<form action="?action=calculate" method="post">';
        echo '<p>';
        echo 'Value 1 <br/>';
        if ($errorValue1) {
            echo '<span style="color: red;">Error: ' . $errorValue1 . '</span>';
        }
        echo '<input type="text" name="value1"  value="' . esc_attr($value1) . '"  />';
        echo '</p>';
        echo 'Value 2 <br/>';
        if ($errorValue2) {
            echo '<span style="color: red;">Error: ' . $errorValue2 . '</span>';
        }
        echo '<input type="text" name="value2"  value="' . esc_attr($value2) . '"  />';
        echo '</p>';
        echo '<p><input type="submit" name="submitted" value="Calculate"></p>';
        echo '</form>';
    }
}

if (!function_exists('restApiPluginErrorConnection')) {
    function restApiPluginErrorConnection()
    {
        echo '<H3><span style="color: red;">Error: API connection </span></H3>';
    }
}

if (!function_exists('restApiPluginShortcode')) {
    function restApiPluginShortcode($inputData)
    {
        $value1 = "";
        $value2 = "";
        $error1 = "";
        $error2 = "";
        $resultAPI = false;

        
        if (!isset($inputData['api_link'])) {
            $inputData['api_link'] = false;
        }
        if (!isset($_GET['action'])) {
            $action = false;
        }  else {
            $action = trim($_GET['action']);
        }
        
        ob_start();

        if ($action == 'calculate') {
            $api = new RestAPI(esc_url($inputData['api_link']));

            if (isset($_POST['value1'])) {
                $value1 = trim($_POST['value1']);
            }
            if (isset($_POST['value1'])) {
                $value2 = trim($_POST['value2']);
            }

            $resultAPI = $api->AddValues($value1, $value2);
            if (isset($resultAPI['errorValue1'])) {
                $error1 = $resultAPI['errorValue1'];
            }
            if (isset($resultAPI['errorValue2'])) {
                $error2 = $resultAPI['errorValue2'];
            }

            if ($resultAPI) {
                restApiPluginCalculateForm($value1, $value2, $resultAPI['sum'], $error1, $error2);
            } else {
                restApiPluginErrorConnection();
                restApiPluginCalculateForm($value1, $value2);
            }
        } else {
            restApiPluginCalculateForm($value1, $value2);
        }


        return ob_get_clean();

    }
}

add_shortcode('sitepoint_rest_api_webtools', 'restApiPluginShortcode');
