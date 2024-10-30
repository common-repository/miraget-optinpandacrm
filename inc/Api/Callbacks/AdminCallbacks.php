<?php namespace Miraget\Api\Callbacks ;
/**
 * @package  Miraget Generator
 */ 

class AdminCallbacks 
{
 
    public $file ;

    public function calls( $file ){

        $this->file = $file ;
        $this->addJsFile() ;
        $geInfo = new \Miraget\Api\Callbacks\GeneraInfo() ;
        

        return function() use ($file,$geInfo ) {

           require_once MIRG_PLUGIN_PATH .  "/templates/$file.php"  ;
 

           $class = 'Miraget' . ucfirst( $file ) . 'Template' ;
           $cls =  new $class() ;
            
           echo '<script>' ;
           echo $geInfo->renderGenralInfo() ;
           echo $cls->render() ;
           echo 'render();</script>' ;

        };
    }
    public function addJsFile() {

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue')) ;
        
    }
    public function enqueue(){
        
        $currentFile = $this->getCurrentPage() ;
        
        wp_enqueue_script( 'mypluinscriptsza', MIRG_PLUGIN_URL .'assets/js/app.js' ) ;
        
        wp_enqueue_script( 'mypluinscript', MIRG_PLUGIN_URL . "assets/js/oipandamira.js",'',time() ) ;
        

    } 
    private function getCurrentPage(){

        $currentScreen = get_current_screen();

        if( $currentScreen->id ) {
            
            $fileArr = explode ('_',$currentScreen->id) ;

            return end($fileArr); 
        }      
    }
 
 
}