<?php
/*
 * This is a class file used to manage and render the content of a single page
 * @file: cpage.class.php
 * @brief: A class file to manage the content of the page
 * @author: GroupJ 
 *
 */
require_once(__DIR__."/../site_config.php");
class cpage {
    
    private $title;
    private $content;
    
    // Richiamando new cpage('title') creo l'oggetto pagina e setto il "titolo pagina"
    public function __construct($title){
        $this->title = $title;

    }
    
    public function __destruct(){
        //Clean up!
    }
    
    public function render(){
        
        //La funzione render crea il contenuto della pagina in html
        echo "<div class='container min_container mb-5' id='content'>";
        $mtitle = "<div class='text-center mb-5'><h2>{$this->title}</h2></div>";
        echo $mtitle;
        echo $this->content;
        echo "</div>";
        echo "</div>";//<div id=main>
    }
    
    public function setContent($content){
        $this->content = $content;
    }
}
?>