<?php


interface Application {

	/**
     * Default applicatication name
     * 
     * @var string
     * @static 
     * @final
     */
    const DEFAULT_APPLICATION_NAME = "default";
	
    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     */
    public function isDefault();
    
    /**
     * Set if the application is default or not
     * 
     * @param boolean $default
     */
    public function setDefault( $default );
    
	/**
     * Returns the application name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Sets the application name
     *
     * @param string $name
     */
    public function setName( $name );
	
	/**
     * Returns the application path
     *
     * @return string
     */
    public function getPath();
    
    /**
     * Sets the application path
     *
     * @param string $path
     */
    public function setPath( $path );
	
	/**
	 * Return the parsed path.
	 * 
	 * @return string
	 */
	public function getParsedPath();
	
	public function getParsedApplicationFile();
	
	/**
     * Returns if the application is started or not
     * 
     * @return boolean
     */
    public function isStarted();
    
    /**
     * Set if the application is started or not
     * 
     * @param boolean $value
     */
    public function setStarted( $started );
	
    public function getStartTime();
    
	/**
	 * Will fire the onApplicationStart event
	 */
	public function fireApplicationStart();
	
	/**
	 * Will fire the onPreProcess event
	 */
	public function firePreProcess();
	
	/**
	 * Will fire the onPostProcess event
	 */
	public function firePostProcess();
	
	
	// PROPERTIES DIFINED IN myfuses.xml
	
	/**
     * Return application locale
     *
     * @return string
     */
    public function getLocale();
    
    /**
     * Set application locale
     *
     * @param string $locale
     */
    public function setLocale( $locale );
    
    /**
     * Return if the degug is alowed
     *
     * @return boolean
     */
    public function isDebugAllowed();
    
    /**
     * Set application debug flag
     *
     * @param boolean $debug
     */
    public function setDebug( $debug );
	
	/**
     * Return the fuseaction variable
     * 
     * @return string
     * @access public 
     */
    public function getFuseactionVariable();
    
    /**
     * Set the fusaction variable
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setFuseactionVariable( $fuseactionVariable );
    
    /**
     * Return the default fuseaction
     * 
     * @return string
     * @access public 
     */
    public function getDefaultFuseaction();
    
    /**
     * Set the defautl fuseaction
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setDefaultFuseaction( $defaultFuseaction );
    
    /**
     * Return precedence form or url
     * 
     * @return string
     * @access public 
     */
    public function getPrecedenceFormOrUrl();
    
    /**
     * Set precedence form or url
     * 
     * @param string $precedenceFormOrUrl
     * @access public
     */
    public function setPrecedenceFormOrUrl( $precedenceFormOrUrl );
    
    /**
     * Return the application mode
     * 
     * @return string
     * @access public 
     */
    public function getMode();
    
    /**
     * Set the application mode
     * 
     * @param string $mode
     * @access public
     */
    public function setMode( $mode );
    
    /**
     * Return the fusebox sctricMode
     * 
     * @return boolean
     * @access public 
     */
    public function isStrictMode();
    
    /**
     * Set the fusebox strictMode
     * 
     * @param boolean $strictMode
     * @access public
     */
    public function setStrictMode( $strictMode );
    
    /**
     * Return application password
     * 
     * @return string
     * @access public
     */
    public function getPassword();
    
    /**
     * Set the application password
     * 
     * @param $password
     * @access public
     */
    public function setPassword( $password );
    
    /**
     * Return if application must be parsed with comments
     * 
     * @return boolean
     */
    public function isParsedWithComments();
    
    /**
     * Set if application must be parsed with comments
     *
     * @param boolean $parsedWithComments
     */
    public function setParsedWithComments( $parsedWithComments );
    
    /**
     * Return if application is using conditional parse
     * 
     * @return boolean
     */
    public function isConditionalParse();
    
    /**
     * Set if application is using conditional parse
     * 
     * @param boolean $conditionalParse
     */
    public function setConditionalParse( $conditionalParse );
	
    /**
     * Return if the tools application is allowed
     *
     * @return boolean
     */
    public function isToolsAllowed();
    
    /**
     * Set application tools flag
     *
     * @param boolean $tools
     */
    public function setTools( $tools );
    
    public function setParameter( $name, $value );
    
	// END PROPERTIES DIFINED IN myfuses.xml
	
}

abstract class AbstractApplication implements Application {
	
	/**
     * Default application flag
     *
     * @var boolean
     */
    private $default = false;
	
    /**
     * Application started state
     * 
     * @var boolean
     */
    private $started = false;
    
	/**
     * Application name
     * 
     * @var string
	 */
	private $name;
	
	/**
     * Application path
     * 
     * @var string
	 */
	private $path;
	
	private $startTime;
	
	// PROPERTIES DIFINED IN myfuses.xml
	
	/**
     * Application locale. English locale is seted by default.
     *
     * @var string
     */
    private $locale = "en";
	
    /**
     * Application debug flag
     *
     * @var boolean
     */
    private $debug = false;
    
	/**
     * Fuseaction variable
     * 
     * @var string
     */
    private $fuseactionVariable = "fuseaction";

    /**
     * Default fuseaction
     * 
     * @var string
     */
    private $defaultFuseaction;
    
    /**
     * Precedence form or url
     * 
     * @var string
     * @deprecated 
     */
    private $precedenceFormOrUrl;
    
    /**
     * Execution mode
     * 
     * @var string
     */
    private $mode;
    
    /**
     * Fusebox strictMode
     * 
     * @var boolean
     */
    private $strictMode = false;
    
    /**
     * Appliaction password
     * 
     * @var string
     */
    private $password;
    
    /**
     * Flag that indicates that the application 
     * must be parsed with comments
     * 
     * @var boolean
     */
    private $parsedWithComments;
    
    /**
     * Flag that indicates that the application 
     * must be parsed using conditional method
     * 
     * @var boolean
     * @deprecated
     */
    private $conditionalParse;
    
    /**
     * Flag that indicates that the application 
     * has lexicon allowed
     * 
     * @var boolean
     * @deprecated
     */
    private $lexiconAllowed;
    
    /**
     * Flag that indicates that the application 
     * has lexicon allowed
     * 
     * @var boolean
     * @deprecated
     */
    private $badGrammarIgnored;
    
    /**
     * Flag that indicates that the application 
     * use assertions
     * 
     * @var boolean
     */
    private $assertionsUsed;
    
    /**
     * Application script language
     * 
     * @var string
     */
    private $scriptLanguage = "php5";
    
    /**
     * Application script file delimiter
     * 
     * @var string
     */
    private $scriptFileDelimiter = "php";
    
    /**
     * Application masked file delimiters
     * 
     * @var array
     */
    private $maskedFileDelimiters;
    
    /**
     * Application character encoding
     * 
     * @var string
     */
    private $characterEncoding = "UTF-8";
	
    // END PROPERTIES DIFINED IN myfuses.xml
    
	/**
	 * Default constructor
	 */
	public function __construct() {
		$this->startTime = time();
	}
	
    /**
     * Returns if the application is default or not
     * 
     * @return boolean
     */
    public function isDefault(){
        return $this->default;
    }
    
    /**
     * Set if the application is default or not
     * 
     * @param boolean $default
     */
    public function setDefault( $default ) {
        $this->default = $default;
    }
	
    /**
     * Returns the application name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Sets the application name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }
	
    /**
     * Returns the application path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Sets the application path
     *
     * @param string $path
     */
    public function setPath( $path ) {
    	$this->path = MyFusesFileHandler::sanitizePath( $path );
    }
	
    public function getParsedPath() {
    	return MyFusesFileHandler::sanitizePath( 
    	   MyFuses::getInstance()->getRootParsedPath() . 
    	   $this->getName() );
    }
    
    public function getParsedApplicationFile() { 
    	return $this->getParsedPath() . $this->getName() . 
    	   MyFuses::getInstance()->getStoredApplicationExtension();
    }
    
    /**
     * Returns if the application is started or not
     * 
     * @return boolean
     */
    public function isStarted() {
    	return $this->started;
    }
    
    /**
     * Set if the application is started or not
     * 
     * @param boolean $started
     */
    public function setStarted( $started ) {
    	$this->started = $started;
    }
    
    public function getStartTime() {
        return $this->startTime;    
    }
    
    /**
     * Will fire the onApplicationStart event
     */
    public function fireApplicationStart() {
        // fire some action
    }
    
    /**
     * Will fire the onPreProcess event
     */
    public function firePreProcess() {
    	// fire some action
    }
    
    /**
     * Will fire the onPostProcess event
     */
    public function firePostProcess() {
    	// fire some action
    }
    
    /**
     * Application tools flag
     *
     * @var boolean
     */
    private $tools = false;
    
    // PROPERTIES DIFINED IN myfuses.xml
    
    /**
     * Return application locale
     *
     * @return string
     */
    public function getLocale() {
        return $this->locale;
    }
    
    /**
     * Set application locale
     *
     * @param string $locale
     */
    public function setLocale( $locale ) {
        $this->locale = $locale;
    }
    
    /**
     * Return if the degug is alowed
     *
     * @return boolean
     */
    public function isDebugAllowed() {
        return $this->debug;
    }
    
    /**
     * Set application debug flag
     *
     * @param boolean $debug
     */
    public function setDebug( $debug ) {
        if( is_bool( $debug ) ) {
            $this->debug = $debug;    
        }
        else {
            if( $debug == "true" ) {
                $this->debug = true;
            }
            else {
                $this->debug = false;
            }
        }
    }
    
    /**
     * Return the fuseaction variable
     * 
     * @return string
     * @access public 
     */
    public function getFuseactionVariable() {
        return $this->fuseactionVariable;
    }
    
    /**
     * Set the fusaction variable
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setFuseactionVariable( $fuseactionVariable ) {
        $this->fuseactionVariable = $fuseactionVariable;
    }
    
    /**
     * Return the default fuseaction
     * 
     * @return string
     * @access public 
     */
    public function getDefaultFuseaction() {
        return $this->defaultFuseaction;
    }
    
    /**
     * Set the defautl fuseaction
     * 
     * @param string $fuseactionVariable
     * @access public
     */
    public function setDefaultFuseaction( $defaultFuseaction ) {
        $this->defaultFuseaction = $defaultFuseaction;
    }
    
    /**
     * Return precedence form or url
     * 
     * @return string
     * @access public 
     */
    public function getPrecedenceFormOrUrl() {
        return $this->precedenceFormOrUrl;
    }
    
    /**
     * Set precedence form or url
     * 
     * @param string $precedenceFormOrUrl
     * @access public
     */
    public function setPrecedenceFormOrUrl( $precedenceFormOrUrl ) {
        $this->precedenceFormOrUrl = $precedenceFormOrUrl;
    }
    
    /**
     * Return the application mode
     * 
     * @return string
     * @access public 
     */
    public function getMode() {
        return $this->mode;
    }
    
    /**
     * Set the application mode
     * 
     * @param string $mode
     * @access public
     */
    public function setMode( $mode ) {
        $this->mode = $mode;
    }
    
    /**
     * Return the fusebox sctricMode
     * 
     * @return boolean
     * @access public 
     */
    public function isStrictMode() {
        return $this->strictMode;
    }
    
    /**
     * Set the fusebox strictMode
     * 
     * @param boolean $strictMode
     * @access public
     */
    public function setStrictMode( $strictMode ) {
        if( is_bool( $strictMode ) ) {
            $this->strictMode = $strictMode;    
        }
        else {
            if( $strictMode == "true" ) {
                $this->strictMode = true;
            }
            else {
                $this->strictMode = false;
            }
        }       
        
    }    
    
    /**
     * Return application password
     * 
     * @return string
     * @access public
     */
    public function getPassword() {
        return $this->password;
    }
    
    /**
     * Set the application password
     * 
     * @param $password
     * @access public
     */
    public function setPassword( $password ) {
        $this->password = $password;
    }
    
    /**
     * Return if application must be parsed with comments
     * 
     * @return boolean
     */
    public function isParsedWithComments() {
        return $this->parsedWithComments;
    }
    
    /**
     * Set if application must be parsed with comments
     *
     * @param boolean $parsedWithComments
     */
    public function setParsedWithComments( $parsedWithComments ) {
        if( is_bool( $parsedWithComments ) ) {
            $this->parsedWithComments = $parsedWithComments;    
        }
        else {
            if( $parsedWithComments == "true" ) {
                $this->parsedWithComments = true;
            }
            else {
                $this->parsedWithComments = false;
            }
        }
    }
    
    /**
     * Return if application is using conditional parse
     * 
     * @return boolean
     */
    public function isConditionalParse() {
        return $this->conditionalParse;
    }
    
    /**
     * Set if application is using conditional parse
     * 
     * @param boolean $conditionalParse
     */
    public function setConditionalParse( $conditionalParse ) {
        if( is_bool( $conditionalParse ) ) {
            $this->conditionalParse = (boolean) $conditionalParse;    
        }
        else {
            if( $conditionalParse == "true" ) {
                $this->conditionalParse = true;
            }
            else {
                $this->conditionalParse = false;
            }
        }
    }
    
    public function isLexiconAllowed() {
        return $this->lexiconAllowed;
    }
    
    public function setLexiconAllowed( $lexiconAllowed ) {
        if( is_bool( $lexiconAllowed ) ) {
            $this->lexiconAllowed = (boolean) $lexiconAllowed;    
        }
        else {
            if( $lexiconAllowed == "true" ) {
                $this->lexiconAllowed = true;
            }
            else {
                $this->lexiconAllowed = false;
            }
        }
    }
    
    public function isBadGrammarIgnored() {
        return $this->badGrammarIgnored;
    }
    
    public function setBadGrammarIgnored( $badGrammarIgnored ) {
        if( is_bool( $badGrammarIgnored ) ) {
            $this->badGrammarIgnored = (boolean) $badGrammarIgnored;    
        }
        else {
            if( $badGrammarIgnored == "true" ) {
                $this->badGrammarIgnored = true;
            }
            else {
                $this->badGrammarIgnored = false;
            }
        }
    }

    public function isAssertionsUsed() {
        return $this->assertionsUsed;
    }
    
    public function setAssertionsUsed( $assertionsUsed ) {
        if( is_bool( $assertionsUsed ) ) {
            $this->assertionsUsed = (boolean) $assertionsUsed;    
        }
        else {
            if( $assertionsUsed == "true" ) {
                $this->assertionsUsed = true;
            }
            else {
                $this->assertionsUsed = false;
            }
        }
    }
    
    public function getScriptLanguage() {
        return $this->scriptLanguage;
    }

    public function setScriptLanguage( $scriptLanguage ) {
        $this->scriptLanguage = $scriptLanguage;
    }
    
    
    public function getScriptFileDelimiter() {
        return $this->scriptFileDelimiter;
    }
    
    public function setScriptFileDelimiter( $scriptFileDelimiter ) {
        $this->scriptFileDelimiter = $scriptFileDelimiter;
    }
    
    public function getMaskedFileDelimiters() {
        return $this->maskedFileDelimiters;
    }
    
    public function setMaskedFileDelimiters( $maskedFileDelimiters ) {
        return $this->maskedFileDelimiters = explode( ",", $maskedFileDelimiters );
    }
    
    public function getCharacterEncoding() {
        return $this->characterEncoding;
    }
    
    public function setCharacterEncoding( $characterEncoding ) {
        $this->characterEncoding = strtoupper( $characterEncoding );
    }
    
    /**
     * Return if the tools application is allowed
     *
     * @return boolean
     */
    public function isToolsAllowed(){
        return $this->tools; 
    }
    
    /**
     * Set application tools flag
     *
     * @param boolean $tools
     */
    public function setTools( $tools ) {
        if( is_bool( $tools ) ) {
            $this->tools = $tools;    
        }
        else {
            if( $tools == "true" ) {
                $this->tools = true;
            }
            else {
                $this->tools = false;
            }
        }
    }
    
    public function setParameter( $name, $value ) {
    	
        $applicationParameters = array(
            "fuseactionVariable" => "setFuseactionVariable",
            "defaultFuseaction" => "setDefaultFuseaction",
            "precedenceFormOrUrl" => "setPrecedenceFormOrUrl",
            "debug" => "setDebug",
            "tools" => "setTools",
            "mode" => "setMode",
            "strictMode" => "setStrictMode",
            "password" => "setPassword",
            "parseWithComments" => "setParsedWithComments",
            "conditionalParse" => "setConditionalParse",
            "allowLexicon" => "setLexiconAllowed",
            "ignoreBadGrammar" => "setBadGrammarIgnored",
            "useAssertions" => "setAssertionsUsed",
            "scriptLanguage" => "setScriptLanguage",
            "scriptFileDelimiter" => "setScriptFileDelimiter",
            "maskedFileDelimiters" => "setMaskedFileDelimiters",
            "characterEncoding" => "setCharacterEncoding"
        );
        
        
        // putting into $application
        if( isset( $applicationParameters[ $name ] ) ) {
            $this->$applicationParameters[ $name ]( $value );
        }
         
    }
    
    // END PROPERTIES DIFINED IN myfuses.xml
    
}

class BasicApplication extends AbstractApplication {
	
}