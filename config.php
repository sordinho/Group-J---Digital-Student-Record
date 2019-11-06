<?php
session_start();
require_once("{$_SERVER['DOCUMENT_ROOT']}//site_config.php");
/*
 * This is an autoconfiguration file used to autoload the defined classes
 * @file: config.php
 * @brief: A config file to manage the required classes
 * @author: GroupJ 
 *
 */
//__DIR__
// Cerca e carica il file di classe se non � stato ancora incluso
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.class.php';
});

// Autoload classes in the classes folder
/*function my_autoloader($class) {
    include 'classes/' . $class . '.class.php';
}*/

//spl_autoload_register('my_autoloader');
function initialize_site(csite $site) {
    $site->addHeader("header.php");// Add header
    $site->addFooter("footer.php");// and footer
}
?>
