<?php
namespace Alloy;

/**
 * Response Class
 *
 * Contians response body, status, headers, etc.
 *
 * @package Alloy
 * @link http://alloyframework.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
class Response
{
    protected $_status = 200;
    protected $_content = "";
    protected $_encoding = "UTF-8";
    protected $_contentType = "text/html";
    protected $_protocol = "HTTP/1.1";
    protected $_headers = array();
    
    
    /**
     * Constructor Function
     */
    public function __construct($status = 200, $content = "")
    {
        $this->_status = $status;
        $this->_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'http';
        $this->_content = $content;
    }
    
    
    /**
     * Set HTTP header
     * 
     * @param string $type HTTP header type
     * @param string $content header content/value
     */
    public function header($type, $content)
    {
        // normalize headers ... not really needed
        for ($tmp = explode("-", $type), $i=0;$i<count($tmp);$i++) {
            $tmp[$i] = ucfirst($tmp[$i]);
        }
        
        $type = implode("-", $tmp);
        if ($type == 'Content-Type') {
            if (preg_match('/^(.*);\w*charset\w*=\w*(.*)/', $content, $matches)) {
                $this->_contentType = $matches[1];
                $this->_encoding = $matches[2];
            } else {
                $this->_contentType = $content;
            }
        } else {
            $this->_headers[$type] = $content;
        }
    }
    
    
    /**
     * Set HTTP status to return
     * 
     * @param int $status HTTP status code
     */
    public function status($status = null)
    {
        if(null === $status) {
            return $this->_status;
        }
        $this->_status = $status;
    }
    
    
    /**
     * Set HTTP encoding to use
     * 
     * @param string $encoding Charset encoding to use
     */
    public function encoding($encoding = null)
    {
        if(null === $encoding) {
            return $this->_encoding;
        }
        $this->_encoding = $encoding;
    }
    
    
    /**
     * Set HTTP response body
     * 
     * @param string $content Content
     */
    public function content($content = null)
    {
        $this->_content = $content;
    }
    public function appendContent($content)
    {
        $this->_content .= $content;
    }
    
    
    /**
     * Set HTTP content type
     * 
     * @param string $contentType Content-type for response
     */
    public function contentType($contentType = null)
    {
        if(null == $contentType) {
            return $this->_contentType;
        }
        $this->_contentType = $contentType;
    }
    
    
    /**
     * Clear any previously set HTTP headers
     */
    public function clearHeaders()
    {
        $this->_headers = array();
    }
    
    
    /**
     * Clear any previously set HTTP redirects
     */
    public function clearRedirects()
    {
        if(isset($this->_headers['Location'])) {
            unset($this->_headers['Location']);
        }
    }
    
    
    /**
     * See if the response has any redirects set
     * 
     * @return boolean
     */
    public function hasRedirects()
    {
        return isset($this->_headers['Location']);
    }
    
    
    /**
     * See if the response has any redirects set
     * 
     * @param string $location URL
     * @param int $status HTTP status code for redirect (3xx)
     */
    public function redirect($location, $status = 301)
    {
        $this->status($status);
        $this->header('Location', $location);
    }
    
    
    /**
     * Send HTTP status header
     */
    protected function sendStatus()
    {
        $responses = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            226 => 'IM Used',
            
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => 'Reserved',
            307 => 'Temporary Redirect',
            
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            510 => 'Not Extended'
        );
        
        $statusText = "";
        if(isset($responses[$this->_status])) {
            $statusText = $responses[$this->_status];
        }
        
        // Send HTTP Header
        header($this->_protocol . " " . $this->_status . " " . $statusText);
    }
    
    
    /**
     * Send all set HTTP headers
     */
    public function sendHeaders()
    {
        if(isset($this->_contentType)) {
            header('Content-Type: '.$this->_contentType."; charset=".$this->_encoding);
            /*
            if(isset($this->_encoding)) {
                header('Content-Type: '.$this->_contentType."; charset=".$this->_encoding);
            } else {
                header('Content-Type: '.$this->_contentType);
            }
            */
        }
        
        // Send all headers
        foreach($this->_headers as $key => $value) {
            if(!is_null($value)) {
                header($key . ": " . $value);
            }
        }
    }
    
    
    /**
     * Send HTTP body content
     */
    protected function sendBody()
    {
        echo $this->_content;
    }
    
    
    /**
     * Send full HTTP response
     */
    public function send()
    {
        if(session_id()) {
            session_write_close();
        }
        $this->sendStatus();
        $this->sendHeaders();
        $this->sendBody();
    }
}