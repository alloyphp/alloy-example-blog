<?php
namespace Alloy\View\Template;
use Alloy\View\Template;

/**
 * View content block class to create and hold content for blocks
 *
 * @package Alloy
 * @link http://alloyframework.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
class Block extends Template
{
    // Setup
    protected $_name;
    protected $_default;

    // Closures to make up block content
    protected $_closures = array();


    /**
     * Create new named content block
     */
    public function __construct($name, $defaultContent = null)
    {
        $this->_name = $name;

        if(null != $defaultContent) {
            $this->ensureClosure($defaultContent);
            $this->_default = $defaultContent;
        }
    }


    /**
     * Block content
     * Static global content blocks that can be set and used across views and layouts
     * 
     * @param string $name Block name
     * @param closure $closure Closure or anonymous function for block to execute and display
     */
    public function content($closure = null)
    {
        // Getter
        if(null === $closure) {
            $content = "";
            if($closures = $this->_closures) {
                // Execute all closure callbacks
                ob_start();
                    foreach($closures as $closure) {
                        echo $closure();
                    }
                $content = ob_get_clean();
            } elseif($this->_default) {
                $default = $this->_default;
                ob_start();
                    echo $default();
                $content = ob_get_clean();
            }
            
            // Return content
            return $content;
        }

        // Setter
        $this->ensureClosure($closure);
        $this->_closures = array($closure);
        return $this;
    }


    /**
     * Append content to block (end of block stack)
     * 
     * @param closure $closure Closure or anonymous function for block to execute and display
     */
    public function append($closure)
    {
        $this->_closures[] = $closure;
        return $this;
    }


    /**
     * Prepend content to block (beginning of block stack)
     * 
     * @param closure $closure Closure or anonymous function for block to execute and display
     */
    public function prepend($closure)
    {
        array_unshift($this->_closures, $closure);
        return $this;
    }


    /**
     * Ensure parameter is a closure
     * Private for now to ensure it will not be relied upon in the API - this may change
     */
    private function ensureClosure($closure)
    {
        if(is_array($closure) || is_string($closure) || !is_callable($closure)) {
            throw new \InvalidArgumentException("Block content expected a closure, given (" . gettype($closure) . ")");
        }
    }
}