<?php
/**
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

/**
 * The main class for rendering of view scripts
 */
class Phiew_View
{
    protected $_dirname;
    protected $_data;
    protected static $_helpers;
    
    /**
     * Constructor
     * 
     * @param $dirname string Location for view script folder
     */
    public function __construct($dirname = null)
    {
        $this->setDirname($dirname);
    }
    
    /**
     * Check input for bad characters
     * 
     * @param $string string
     * @return string
     */
    protected function _getSafePath($string)
    {
        $allowedChars = array('/', '.', '-', '_');
        
        if (!empty($string) 
            && ctype_alnum(str_replace($allowedChars, '', $string))
            && strpos($string, '..') === false
        ) {
            return $string;
        }
        else
        {
            trigger_error('Bad characters used in path: ' . $string, E_USER_ERROR);
            return null;
        }
    }
    
    /**
     * Set folder for view scripts
     * 
     * @param $dirname string
     */
    public function setDirname($dirname)
    {
        $this->_dirname = rtrim($this->_getSafePath($dirname), '/');
    }
    
    /**
     * Generate full file path for view script
     * 
     * @param $view string
     * @return string
     */
    protected function _getFilename($view)
    {
        return $this->_getSafePath($this->_dirname) 
               . '/' . $this->_getSafePath($view) . '.phtml';
    }
    
    /**
     * Set data
     * 
     * @param $name string
     * @param $value mixed
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * Get data
     * 
     * @param $name string
     * @return mixed
     */
    public function __get($name) 
    {
        if (array_key_exists($name, $this->_data)) 
        {
            return $this->_data[$name];
        }
    }
    
    /**
     * Clear data
     */
    public function clearData()
    {
        $this->_data = array();
    }
    
    /**
     * Output view script
     * 
     * @param $view string
     * @param $data array
     * @return boolean
     */
    public function render($view, $data = array())
    {
        $filename = $this->_getFilename($view);
        
        if (is_readable($filename))
        {
            foreach ($data as $key => $value)
            {
                $this->__set($key, $value);
            }

            return (bool) include $filename;
        }
        else
        {
            trigger_error('Could not find view script: ' . $filename, E_USER_ERROR);
            return false;
        }
    }
    
    /**
     * Get output from view script as string
     * 
     * @param $view string
     * @param $data array
     * @return string
     */
    public function capture($view, $data = array())
    {
        ob_start();
        $this->render($view, $data);
        return ob_get_clean();
    }
    
    /**
     * Escape html characters
     *
     * @param string $string
     */
    public function escape($string)
    {
        return htmlspecialchars($string);
    }
    
    /**
     * Check if one of the view helpers have that missing function
     */
    public function __call($function, $args)
    {
        // No bullshitting, m'kay?
        if (!ctype_alnum($function))
        {
            trigger_error('That\'s not a pretty name for a function, is it?', E_USER_ERROR);
        }
        
        // Make sure we have that view helper loaded
        if (!isset(self::$_helpers[$function]))
        {
            $classname = ucfirst($function);
            require_once dirname(__FILE__) . "//View//Helper//$classname.php";
            self::$_helpers[$function] = eval("return new Phiew_View_Helper_$classname();");
        }
        
        // Call view helper
        $helper = self::$_helpers[$function];
        if (is_callable(array($helper, $function)))
        {
            return call_user_func_array(array($helper, $function), $args);
        }
        else
        {
            trigger_error("Could not load view helper \"$function\"", E_USER_ERROR);
        }
    }
}
