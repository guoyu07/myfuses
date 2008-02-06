<?php
/**
 * MyFuses  - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
 * 
 * PHP version 5
 * 
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * This product includes software developed by the Fusebox Corporation 
 * (http://www.fusebox.org/).
 * 
 * The Original Code is Fuses "a Candango implementation of Fusebox Corporation 
 * Fusebox" part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2006 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   controller
 * @package    myfuses
 * @author     Flávio Gonçalves Garcia <flavio.garcia@candango.org>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id$
 */
define( "MYFUSES_ROOT_PATH", dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

require_once MYFUSES_ROOT_PATH . "exception/MyFusesException.class.php";

try {
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "core/Application.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "core/ICacheable.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "core/IParseable.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "engine/AbstractMyFusesLoader.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "engine/loaders/XmlMyFusesLoader.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "process/FuseRequest.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "process/MyFusesLifecycle.class.php" );
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "process/MyFusesDebugger.class.php" );
    
    MyFuses::includeCoreFile( MyFuses::MYFUSES_ROOT_PATH . 
        "util/file/MyFusesFileHandler.class.php" );
}
catch( MyFusesMissingCoreFileException $mfmcfe ) {
    $mfmcfe->breakProcess();
}

/**
 * MyFuses  - MyFuses.class.php
 * 
 * This is MyFuses a Candango Opensource Group a implementation of Fusebox 
 * Corporation Fusebox framework. The MyFuses is used as Iflux Framework 
 * Main Controller.
 * 
 * PHP version 5
 *
 * @category   controller
 * @package    myfuses
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2006 - 2006 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision$
 * @since      Revision 17
 */
class MyFuses {
    
    /**
     * The MyFuses root path constant
     * 
     * @static
     * @final
     */
    const MYFUSES_ROOT_PATH = MYFUSES_ROOT_PATH;
    
    /**
     * Unique instance to be created in process. MyFuses is implemmented using
     * the singleton pattern.
     *
     * @var MyFuses
     */
    protected static $instance;
    
    /**
     * Array of registered applications
     * 
     * @var array
     */
    protected $applications = array();
    
    /**
     * 
     * 
     * @var FuseRequest
     */
    private $request;
    
    /**
     * Instance Lifecycle
     * 
     * @var MyFusesLifecycle
     */
    private $lifecycle;
    
    /**
     * Default debugger
     * 
     * @var MyFusesDebugger
     */
    private $debugger;
    
    private $parsedPath;
    
    private $applicationClass = "Application";
    
    
    const MODE_DEVELOPMENT = "development";
    const MODE_PRODUCTION = "production";
    
    /**
     * MyFuses constructor
     *
     * @param MyFusesLoader $loader
     * @param string $applicationName
     */
    protected function __construct() {
        $this->debugger = new MyFusesDebugger();
        $this->setParsedPath( MyFuses::MYFUSES_ROOT_PATH . "parsed" . 
            DIRECTORY_SEPARATOR );
    }
    
    public function getParsedPath() {
        return $this->parsedPath;
    }
    
    protected function setParsedPath( $parsedPath ) {
        $this->parsedPath = $parsedPath;
    }
    
    protected function getApplicationClass() {
        return $this->applicationClass;
    }
    
    protected function setApplicationClass( $appClass ) {
        $this->applicationClass = $appClass;
    }
    
    public function createApplication( 
        $appName = Application::DEFAULT_APPLICATION_NAME, 
        $appReference = null ) {
            
        $appClass = $this->getApplicationClass();
            
        $application = new $appClass( $appName );
        
        if( !is_null( $appReference ) ) {
            if( isset( $appReference[ 'path' ] ) ) {
                $application->setPath( $appReference[ 'path' ] );
            }
            if( isset( $appReference[ 'file' ] ) ) {
                $application->setFile( $appReference[ 'file' ] );
            }
            
        }
        else {
            $application->setPath( dirname( $_SERVER[ 'SCRIPT_FILENAME' ] ) );    
        }
        
        // setting parsed path
        $application->setParsedPath( $this->getParsedPath() . 
            $application->getName() . DIRECTORY_SEPARATOR ) ;
        
        $this->addApplication( $application );
        
        return $application;
    }
    
    /**
     * Returns an existing application
     *
     * @param string $name
     * @return Application
     */
    public function &getApplication( 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        return $this->applications[ $name ];
    }
    
    /**
     * Returns an array of registered applications
     *
     * @return array
     */
    public function &getApplications() {
        return $this->applications;
    }
    
    public function addApplication( Application $application ) {
        
        if( count( $this->applications ) == 0 ) {
            $application->setDefault( true );
        }

        $application->setController( $this );
        
        $this->applications[ $application->getName() ] = $application;
        
        $application->setController( $this );
        
        if( Application::DEFAULT_APPLICATION_NAME != $application->getName() ) {
            if( $application->isDefault() ) {
                if( isset( $this->applications[ 
                    Application::DEFAULT_APPLICATION_NAME ] ) ) {
                    $this->applications[ 
                    Application::DEFAULT_APPLICATION_NAME ]->setDefault( 
                        false );
                }
                $this->applications[ Application::DEFAULT_APPLICATION_NAME ] =
                    &$this->applications[ $application->getName() ];
            }
                
        }
    }
    
    /**
     * Sets the application $name
     * 
     * @param string
     */
    public function setApplicationName( $value, 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        return $this->applications[ $name ]->setName( $value );
    }
    
    /**
     * Returns the application $path
     * 
     * @return string
     */
    public function getApplicationPath( 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        $this->applications[ $name ]->getPath();
    }
    
    /**
     * Sets the application path
     * 
     * @param string $name
     */
    public function setApplicationPath( $value, 
        $name = Application::DEFAULT_APPLICATION_NAME ) {
        $this->applications[ $name ]->setPath( $value );
    }
    
    
    /**
     * Loads all applications registered
     */
    private function loadApplications() {
         foreach( $this->applications as $key => $application ) {
             if( $key != Application::DEFAULT_APPLICATION_NAME ) {
                 $this->loadApplication( $application );
             }
             
         }
    }
    
    protected function loadApplication( Application $application ) {
        $application->getLoader()->loadApplication();
    }
    
    protected function createRequest() {
        $this->request = new FuseRequest();
    }
    
    public function getCurrentPhase() {
        return $this->lifecycle->getPhase();
    }
    
    public function setCurrentPhase( $phase ) {
        $this->lifecycle->setPhase( $phase );
    }
    
    public function &getCurrentCircuit() {
        return $this->lifecycle->getAction()->getCircuit();
    }
    
    public function &getCurrentAction() {
        return $this->lifecycle->getAction();
    }
    
    public function setCurrentAction( $fuseaction ) {
        list( $appName, $cName, $aName ) = explode( ".", $fuseaction );
        $this->lifecycle->setAction( $this->getApplication( $appName )->
            getCircuit( $cName )->getAction( $aName ) );
    }
    
    public function setCurrentProperties( $phase, $fuseaction ) {
        $this->setCurrentPhase( $phase );
        $this->setCurrentAction( $fuseaction );
    }
    
    /**
     * Returns the current request
     * 
     * @return FuseRequest
     */
    public function &getRequest() {
        return $this->request;
    }
    
    protected function storeApplication( Application $application ) {
        $strStore = "";
        
        if( $application->mustParse() ) {
            if( !file_exists( $application->getParsedPath() ) ) {
                mkdir( $application->getParsedPath(), 0777, true );
             
                $path = explode( DIRECTORY_SEPARATOR, 
                    substr( $application->getParsedPath(), 0, 
                    strlen( $application->getParsedPath() ) - 1 ) );
             
                while( $this->getParsedPath() != ( 
                    implode( DIRECTORY_SEPARATOR, $path ) . 
                    DIRECTORY_SEPARATOR ) ) {
                    chmod( implode( DIRECTORY_SEPARATOR, $path ), 
                        0777 );
                    $path = array_slice( $path, 0, count( $path ) - 1 );
                }
            } 
            $strStore = $application->getCachedCode();
         
            $fileName = $application->getCompleteCacheFile();
        
            MyFusesFileHandler::writeFile( $fileName, "<?php\n" . 
	            $strStore );
        }
         
        
    }
    
    /**
     * Sotore all myfuses applications
     */
    protected function storeApplications() {
        foreach( $this->applications as $index => $application ) {
            if( $index != Application::DEFAULT_APPLICATION_NAME ) {
                $this->storeApplication( $application );
            }
        }
    }
    
    /**
     * This method parse the request and write the genereted 
     * string in one file
     */
    public function parseRequest() {
        $this->lifecycle = new MyFusesLifecycle();
        
        $circuit = $this->request->getAction()->getCircuit();
        
        $controllerName = $circuit->getApplication()->getControllerClass();
        
        $application = $circuit->getApplication();
        
        $path = $this->request->getApplication()->getParsedPath() .
	        $this->request->getCircuitName() . DIRECTORY_SEPARATOR;
        
        $fileName = $path . $this->request->getActionName() . ".action.php" ;
        
        // TODO handle file parse
        if( !is_file( $fileName ) || $circuit->isModified() ) {
            $fuseQueue = $this->request->getFuseQueue();
            
            $myFusesString = $controllerName . "::getInstance()";
        
	        $actionString = "\"" . $this->request->getApplication()->getName() . 
	            "." . $this->request->getCircuitName() . 
	            "." . $this->request->getActionName() . "\"";
            
            $strParse = "";
	        
            $strParse .= $myFusesString . "->setCurrentProperties( \"" . 
		        MyFusesLifecycle::PRE_PROCESS_PHASE . "\", "  . 
                $actionString . " );\n\n";
	        
            // parsing pre process plugins
            if( count( $application->getPlugins( 
                Plugin::PRE_PROCESS_PHASE ) ) ) {
                $pluginsStr = $controllerName . "::getApplication( \"" . 
                    $application->getName() . "\" )->getPlugins(" .
                    " \"" . Plugin::PRE_PROCESS_PHASE . "\" )";
                $strParse .= "foreach( " . $pluginsStr . " as \$plugin ) {\n";
                $strParse .= "\t\$plugin->run();\n}\n\n";
            }
            //end parsing pre process plugins
            
            foreach( $fuseQueue->getPreProcessQueue() as $parseable ) {
                $strParse .= $parseable->getParsedCode( 
                    $this->request->getApplication()->isParsedWithComments(), 
	                0 );
	        }
	
	        foreach( $fuseQueue->getProcessQueue() as $parseable ) {
	            $strParse .= $parseable->getParsedCode( 
                    $this->request->getApplication()->isParsedWithComments(),
                    0 );
	        }
	        
	        $strParse .= $myFusesString . "->setCurrentProperties( \"" . 
		        MyFusesLifecycle::POST_PROCESS_PHASE . "\", "  . 
                $actionString . " );\n\n";
	        
            $selector = true;
                
            foreach( $fuseQueue->getPostProcessQueue() as $parseable ) {
	            
                
	            $strParse .= $parseable->getParsedCode( 
                    $this->request->getApplication()->isParsedWithComments(), 
                    0 );
	        }
	        
	        // parsing post process plugins
            if( count( $application->getPlugins( 
                Plugin::POST_PROCESS_PHASE ) ) ) {
                $strParse .= $myFusesString . "->setCurrentProperties( \"" . 
                        MyFusesLifecycle::POST_PROCESS_PHASE . "\", "  . 
                        $actionString . " );\n\n";
                $pluginsStr = $controllerName . "::getApplication( \"" . 
                    $application->getName() . "\" )->getPlugins(" .
                    " \"" . Plugin::POST_PROCESS_PHASE . "\" )";
                $strParse .= "foreach( " . $pluginsStr . " as \$plugin ) {\n";
                $strParse .= "\t\$plugin->run();\n}\n\n";
            }
            //end parsing post process plugins
	        
	        // resolving #valriable#'s 
	        $strParse = preg_replace( 
                "@([#])([\$?\w+][\:\:\w+\(\)]*([\-\>\w+\(?\)?]|" .
                "[\[\'\w+\'\]])*)([#])@", "\" . $2 . \"" , $strParse );
            
	        // sanitizing " "'s    
            $strParse = 
                str_replace( array( " \"\" .", ". \"\" " ), "", $strParse );
	        $strParse = 
                str_replace( array( ". \"\";" ), ";", $strParse );
            if( !file_exists( $path ) ) {
	            mkdir( $path );
	            chmod( $path, 0777 );
	        }
	        
	        $fp = fopen( $fileName,"w" );
	         
	        if ( !flock($fp,LOCK_EX) ) {
	            die("Could not get exclusive lock to Parsed File file");
	        }
	
	        if ( !fwrite($fp, "<?php\n" . $strParse) ) {
	            var_dump( "deu pau 2!!!" );
	        }
	        flock($fp,LOCK_UN);
	        fclose($fp);
	        chmod( $fileName, 0777 );
        
        }
        include $fileName;
    }
    
    /**
     * Process the user request
     */
    public function doProcess() {
        try {
            // initilizing application if necessary
            $this->loadApplications();
            
            $this->createRequest();
            
            // storing all applications if necessary
            $this->storeApplications();
            
            $this->parseRequest();
            
        }
        catch( MyFusesException $mfe ) {
            $mfe->breakProcess();
        }
        
    }
    
    
    /**
     * Returns one instance of MyFuses. Only one instance is creted per process.
     * MyFuses is implemmented using the singleton pattern.
     * 
     * @return MyFuses
     * @static 
     */
     public static function &getInstance() {
        
        if( is_null( self::$instance ) ) {
            self::$instance = new MyFuses();
        }
        
        return self::$instance;
    }
    
    public static function getXfa( $name ) {
        return self::getInstance()->getRequest()->getAction()->getXfa( $name );
    }
    
    public static function getSelfPath() {
        
        $self = "http://" . $_SERVER[ 'HTTP_HOST' ];
        
        $self .= "/";
        
        if( substr( $self, -1 ) == "/" ) {
            $self = substr( $self, 0, strlen( $self ) - 1 );
        }
        
        $self .= dirname( $_SERVER[ 'PHP_SELF' ] );
        
        if( substr( $self, -1 ) != "/" ) {
            $self .= "/";
        }
        
        return $self;
    }
    
    public static function getSelf() {
        $self = "http://" . $_SERVER[ 'HTTP_HOST' ];
        
        if( substr( $self, -1 ) != "/" ) {
            $self .= "/";
        }
        
        if( isset( $_SERVER[ 'REDIRECT_STATUS' ] ) ) {
            $self1 = dirname( $_SERVER[ 'SCRIPT_NAME' ] );
            if( substr( $self1, -1 ) != "/" ) {
                $self1 .= "/";
            }
        }
        else {
            $self1 = $_SERVER[ 'SCRIPT_NAME' ];
        }
        
        if( substr( $self1, 0, 1 ) == "/" ) {
            $self1 = substr( $self1, 1, strlen( $self1 ) );
        }
        
        $self .= $self1;
        
        return $self;
    }
    
    public static function getMySelf( $showFuseactionVariable=true ) {
        if( isset( $_SERVER[ 'REDIRECT_STATUS' ] ) ) {
            $mySelf = self::getSelf();
            if( $showFuseactionVariable ) {
                $mySelf .= self::getInstance()->getRequest()->
                    getApplication()->getFuseactionVariable() . "/";    
            }
        }
        else {
            $mySelf = self::getSelf() . "?";
        
	        $mySelf .= self::getInstance()->getRequest()->
	            getApplication()->getFuseactionVariable();
	        $mySelf .= "=" ;    
        }
        
        return $mySelf;
    }
    
    public static function getMySelfXfa( $xfaName, $initQuery = false, 
        $showFuseactionVariable=true ) {
        if( isset( $_SERVER[ 'REDIRECT_STATUS' ] ) ) {
            $link = self::getMySelf( $showFuseactionVariable ) . 
                implode( "/", explode( ".", self::getXfa( $xfaName ) ) );
            if( $initQuery ) {
                $link .= "?";
            }
        }
        else {
            $link = self::getMySelf() . self::getXfa( $xfaName );
            if( $initQuery ) {
                $link .= "&";
            }    
        }
        return $link;
    }
    
    /**
     * Includes core files.<br>
     * Throws IFBExeption when <code>file doesn't exists</code>.
     * In truth this method use require_once insted include_once.
     * Process must break if some core file doesn't exists.
     * 
     * @param $file
     * @return void
     */
    public static function includeCoreFile( $file ) {
        if ( file_exists( $file ) ) {
            require_once $file;
        }
        else {
            throw new MyFusesMissingCoreFileException( $file );
        }
    }
    
    public static function sendToUrl( $url ) {
        if( !headers_sent() ) {
            header( "Location: " . $url );
        }
	    else {
	        echo '<script type="text/javascript">';
	        echo 'window.location.href="'.$url.'";';
	        echo '</script>';
	        echo '<noscript>';
	        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
	        echo '</noscript>';
	        die();
	    }
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */