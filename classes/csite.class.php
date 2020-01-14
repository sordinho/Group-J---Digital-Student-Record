<?php
/*
 * This is a class file
 * @file: csite.class.php
 * @brief: A class file to manage the render of the site
 * @author: GroupJ 
 *
 */
//require_once($_SERVER['DOCUMENT_ROOT']."//site_config.php");
require_once(__DIR__."/../site_config.php");
define('APP_RAN', '');//Per verificare se un file � incluso!
class csite{
    
    private $headers;
    private $footers;
    private $page;
    
    public function __construct(){
        
        $this->headers = array();
        $this->footers = array();
        
    }
    
    public function __destruct(){
        
    //Clean up here!    
        
    }
    
    public function render(){
        
        foreach($this->headers as $header){
            $h_path = FULL_PATH.$header;
            include $h_path;
        }
        /*echo '<div id="main">
                <div id="nav">';
            //include("menu.php");
        echo '</div>';*/
        $this->page->render();
        //echo '<div id="extra">';
        //include("testblabla.php");
        //echo '</div>';

        foreach($this->footers as $footer){
            include FULL_PATH.$footer;
        }
    }
    
    // Methods used to add different headers/footer
    public function addHeader($file){
        $this->headers[] = $file;
    }
    
    public function addFooter($file){
        $this->footers[] = $file;
    }
    
    public function setPage($page){
        $this->page = $page;
    }

    public function getDefaultSideContent(){
        include('sidecontent.php');
    }
}
?>