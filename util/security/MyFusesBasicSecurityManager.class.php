<?php
require_once "myfuses/util/security/MyFusesAbstractSecurityManager.class.php";

class MyFusesBasicSecurityManager extends MyFusesAbstractSecurityManager {
    
    private $credential;
    
    public function createCredential() {
        if( !isset( $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] ) ) {
            $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] = 
                new MyFusesBasicCredential();    
        }
        else {
            $credential = $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ];
            if( $credential->isExpired() ) {
                $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ] = 
                    new MyFusesBasicCredential();
            }
        }
    }
    
    /**
     * Return registered credential
     *
     * @return MyFusesCredential
     */
    public function getCredential() {
        return $_SESSION[ 'MYFUSES_SECURITY' ][ 'CREDENTIAL' ];
    }
    
    public function setCredential( MyFusesCredential $credential ) {
        $this->credential = $credential;
    }
    
}