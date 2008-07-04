<?php
/**
 * MyFuses i18n Context class - MyFusesI18nContext.class.php
 *
 * Utility that controls i18n state.
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
 * The Original Code is Candango Fusebox Implementation part .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2005 - 2006.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link       http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Id:MyFusesI18nContext.class.php 521 2008-06-25 12:43:32Z piraz $
 */

/**
 * MyFuses i18n Context class - MyFusesI18nContext.class.php
 *
 * Utility that controls i18n state.
 *
 * @category   i18n
 * @package    myfuses.util.i18n
 * @author     Flavio Goncalves Garcia <flavio.garcia@candango.com>
 * @copyright  Copyright (c) 2006 - 2008 Candango Opensource Group
 * @link http://www.candango.org/myfuses
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    SVN: $Revision:521 $
 * @since      Revision 514
 */
class MyFusesI18nContext {
    
    private static $timeStamp;
    
    public static function markTimeStamp() {
        self::$timeStamp = time();
    }
    
    
    public static function getTimeStamp(){
        return self::$timeStamp;
    }
    
    public static function setLocale() {
        
        $locale = MyFuses::getApplication()->getLocale();
        
        putenv( "LANG=" . $locale );
        
        putenv( "LANG=" . "pt_BR" );
        
        setlocale( LC_ALL, $locale );
        
    }
    
    public static function checkFiles() {
        
    }
    
    public static function loadFiles() {
        
        $application = MyFuses::getApplication();
        
        MyFuses::getInstance()->createApplicationPath( $application );
        
        $path = MyFusesFileHandler::sanitizePath( 
                $application->getParsedPath() . 'i18n' );
                
        if( !file_exists( $path ) ) {
            mkdir( $path, 0777, true );
            chmod( $path, 0777 );
        }
        
        $i18nPath = MyFusesFileHandler::sanitizePath( 
            MyFuses::MYFUSES_ROOT_PATH . "i18n" );
        $it = new RecursiveDirectoryIterator( $i18nPath );
        
        $exps = array();
        
        foreach ( new RecursiveIteratorIterator($it, 1) as $child ) {
            if( $child->isDir() ) {
                $localePath = MyFusesFileHandler::sanitizePath( 
                    $child->getPath() . DIRECTORY_SEPARATOR . 
                    $child->getFileName() );
                $locale = $child->getFileName();
                if( $localePath != $i18nPath ) {
                    if( file_exists( $localePath . "expression.xml" ) ) {
                        $expressions = self::loadFile( $localePath . 
                            "expression.xml" );
                        foreach( $expressions as $expression ){
                            if( strtolower( $expression->getName() ) === 
                                'expression' ) {
                                $name = "";
                                foreach( $expression->attributes() as $attr ) {
                                    if( strtolower( $attr->getName() ) === 
                                        'name' ) {
                                        $exps[ $locale ][ "" . $attr ] = "" . 
                                            $expression;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        self::storeFiles( $exps );
        
    }
    
    private static function loadFile( $file ) {
        try {
            // FIXME put no warning modifier in SimpleXMLElement call
            return @new SimpleXMLElement( file_get_contents( $file ) ); 
        }
        catch ( Exception $e ) {
            // FIXME handle error
            echo "<b>" . $this->getApplication()->
                getCompleteFile() . "<b><br>";
            die( $e->getMessage() );    
        }
    }
    
    private static function storeFiles( $exps ) {
        $path = MyFusesFileHandler::sanitizePath( 
            MyFuses::getApplication()->getParsedPath() . 'i18n' );
        foreach( $exps as $locale => $expressions ) {
            $strOut = self::getFileComments( $locale );
            $strOut .= self::getFileHeaders( $locale );
            $strOut .= self::getExpressions( $locale, $expressions );
            
            $pathI18n = MyFusesFileHandler::sanitizePath( $path . $locale );
            
            if( !file_exists( $pathI18n ) ) {
                mkdir( $pathI18n, 0777, true );
                chmod( $pathI18n, 0777 );
            }
            
            $pathI18n = MyFusesFileHandler::sanitizePath( $pathI18n . 
                "LC_MESSAGES" );
            
            if( !file_exists( $pathI18n ) ) {
                mkdir( $pathI18n, 0777, true );
                chmod( $pathI18n, 0777 );
            }
            
            $fileI18n = $pathI18n . "myfuses.po";
            
            MyFusesFileHandler::writeFile( $fileI18n, $strOut );
            
            exec( 'msgfmt ' . $fileI18n . ' -o ' . $pathI18n . 'myfuses.mo' );
            
        }
        
    }
    
    private static function getFileComments( $locale ) {
        $strOut = "# " . MyFuses::getApplication()->getName() . " " . $locale . " i18n expressions file.\n";
        $strOut .= "# Copyright (C) YEAR THE PACKAGE'S COPYRIGHT HOLDER\n";
        $strOut .= "# This file is distributed under the same license as the PACKAGE package.\n";
        $strOut .= "# FIRST AUTHOR <EMAIL@ADDRESS>, YEAR.\n";
        $strOut .= "#\n";
        $strOut .= "#, fuzzy\n";
        $strOut .= "msgid \"\"\n";
        $strOut .= "msgstr \"\"\n";
        return $strOut;
    }
    
    private static function getFileHeaders( $locale ) {
        $strOut = "\"Project-Id-Version: PACKAGE VERSION\\n\"\n";
        $strOut .= "\"Report-Msgid-Bugs-To: \\n\"\n";
        $strOut .= "\"POT-Creation-Date: 2008-06-16 09:54-0300\\n\"\n";
        $strOut .= "\"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n\"\n";
        $strOut .= "\"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n\"\n";
        $strOut .= "\"Language-Team: LANGUAGE <LL@li.org>\\n\"\n";
        $strOut .= "\"MIME-Version: 1.0\\n\"\n";
        $strOut .= "\"Content-Type: text/plain; charset=UTF-8\\n\"\n";
        $strOut .= "\"Content-Transfer-Encoding: 8bit\\n\"\n\n";
        
        return $strOut;
    }
    
    private static function getExpressions( $locale, $expressions ) {
        $strOut = "";
        
        foreach( $expressions as $key => $expression ) {
            $strOut .= "#: expression " . $key . "\n";
            $strOut .= "msgid \"" . $key . "\"\n";
            $strOut .= "msgstr \"" . $expression . "\"\n\n";    
        }
        
        return $strOut;
    }
    
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */