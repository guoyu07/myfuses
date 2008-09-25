<?php
require_once "myfuses/util/security/MyFusesSecurityManager.class.php";

abstract class MyFusesAbstractSecurityManager 
    implements MyFusesSecurityManager {

    /**
     * Security Manager listeners
     *
     * @var array
     */
    private $securityListeners = array();

    private static $instance;
    
    public function isAuthenticated() {
        
        $credential = $this->getCredential();
        
        return $credential->isAuthenticated();
    }

    public function isAuthorized(){
        foreach( $this->getSecutiyListeners() as $listener ){
            $listener->authorizationPerformed();
        }
    }

    /**
     * Add one Autentication Listener
     *
     * @param AuthenticationListener $listener
     */
    public function addSecutiyListener( MyFusesSecuriyListener $listener ) {
        $this->securityListeners[] = $listener;
    }

    /**
     * Return all authentication listeners
     *
     * @return array Array of AuthenticationListeners
     */
    public function getSecutiyListeners() {
        return $this->securityListeners;
    }

    /**
     * Return new Basic Security Manager instance
     *
     * @return MyFusesSecurityManager
     */
    public static function getInstance() {
        if( is_null( self::$instance ) ) {
            require_once "myfuses/util/security/MyFusesBasicSecurityManager.class.php";
            self::$instance = new MyFusesBasicSecurityManager();
        }
        
        return self::$instance;
    }

} 