<?php
/*
 * This is a class file used to manage and render the content of a single page
 * @file: cpage.class.php
 * @brief: A class file to manage the content of the page
 * @author: GroupJ 
 *
 */
require_once($_SERVER['DOCUMENT_ROOT']."//site_config.php");

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
        echo "<div class='container' id='content'>";
        echo "<H1>{$this->title}</H1>";
        echo $this->content;
        //echo "</div>";
        echo "</div>";//<div id=main>
    }
    
    public function setContent($content){
        $this->content = $content;
    }
}
?>