<?php


class MuxForm{
	
	const METHOD_DELETE = 'delete';
    const METHOD_GET    = 'get';
    const METHOD_POST   = 'post';
    const METHOD_PUT    = 'put';
	
	const ENCTYPE_URLENCODED = 'application/x-www-form-urlencoded';
    const ENCTYPE_MULTIPART  = 'multipart/form-data';
	
	protected $_attribs = array();
	protected $_methods = array('delete', 'get', 'post', 'put');
	protected $_displayGroups = array();
	protected $_elements = array();
	protected $_elementsBelongTo;
	protected $_errorMessages = array();
	protected $_errorsExist = false;
	protected $_order = array();
	protected $_subForms = array();
	protected $_view;
	
	private $formAction;
	private $formMethod;
	private $controllerAction;
	
	
	public function __construct($options = null){
		/*
        if (is_array($options)) {
            $this->setOptions($options);
        } elseif ($options instanceof Zend_Config) {
            $this->setConfig($options);
        }
		*/
		
        // Extensions...
        $this->init();
    }
	
	public function init(){}
	
	public function setAttrib($key, $value){
        $key = (string) $key;
        $this->_attribs[$key] = $value;
        return $this;
    }
	
	public function addAttribs(array $attribs){
        foreach ($attribs as $key => $value) {
            $this->setAttrib($key, $value);
        }
        return $this;
    }
	
	public function getAttrib($key){
        $key = (string) $key;
        if (!isset($this->_attribs[$key])) {
            return null;
        }

        return $this->_attribs[$key];
    }
	
	public function getAttribs(){
        return $this->_attribs;
    }
	
	public function removeAttrib($key){
        if (isset($this->_attribs[$key])) {
            unset($this->_attribs[$key]);
            return true;
        }
        return false;
    }
	
	public function clearAttribs(){
        $this->_attribs = array();
        return $this;
    }
	
	public function setAction($action){
        return $this->setAttrib('action', (string) $action);
    }
	
	public function getAction(){
        $action = $this->getAttrib('action');
        if (null === $action) {
            $action = '';
            $this->setAction($action);
        }
        return $action;
    }
	
	public function setMethod($method){
        $method = strtolower($method);
        if (!in_array($method, $this->_methods)) {
            //require_once 'Zend/Form/Exception.php';
            //throw new Zend_Form_Exception(sprintf('"%s" is an invalid form method', $method));
        }
        $this->setAttrib('method', $method);
        return $this;
    }
	
	public function getMethod(){
        if (null === ($method = $this->getAttrib('method'))) {
            $method = self::METHOD_POST;
            $this->setAttrib('method', $method);
        }
        return strtolower($method);
    }
	
	public function setEnctype($value){
        $this->setAttrib('enctype', $value);
        return $this;
    }
	
	public function getEnctype(){
        if (null === ($enctype = $this->getAttrib('enctype'))) {
            $enctype = self::ENCTYPE_URLENCODED;
            $this->setAttrib('enctype', $enctype);
        }
        return $this->getAttrib('enctype');
    }
	
	public function filterName($value, $allowBrackets = false){
        $charset = '^a-zA-Z0-9_\x7f-\xff';
        if ($allowBrackets) {
            $charset .= '\[\]';
        }
        return preg_replace('/[' . $charset . ']/', '', (string) $value);
    }
	
	//Set form name
	public function setName($name){
        $name = $this->filterName($name);
        if (('0' !== $name) && empty($name)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Invalid name provided; must contain only valid variable characters and be non-empty');
        }

        return $this->setAttrib('name', $name);
    }
	
	/* Set form order */
	public function setOrder($index)
    {
        $this->_formOrder = (int) $index;
        return $this;
    }
	
	/* Get form order*/
	public function getOrder()
    {
        return $this->_formOrder;
    }
	
	/* Add a new element */
	public function addElement($element, $name = null, $options = null)
    {
        if (is_string($element)) {
            if (null === $name) {
                require_once 'Zend/Form/Exception.php';
                throw new Zend_Form_Exception('Elements specified by string must have an accompanying name');
            }

            if (is_array($this->_elementDecorators)) {
                if (null === $options) {
                    $options = array('decorators' => $this->_elementDecorators);
                } elseif ($options instanceof Zend_Config) {
                    $options = $options->toArray();
                }
                if (is_array($options) 
                    && !array_key_exists('decorators', $options)
                ) {
                    $options['decorators'] = $this->_elementDecorators;
                }
            }

            $this->_elements[$name] = $this->createElement($element, $name, $options);
        } elseif ($element instanceof Zend_Form_Element) {
            $prefixPaths              = array();
            $prefixPaths['decorator'] = $this->getPluginLoader('decorator')->getPaths();
            if (!empty($this->_elementPrefixPaths)) {
                $prefixPaths = array_merge($prefixPaths, $this->_elementPrefixPaths);
            }

            if (null === $name) {
                $name = $element->getName();
            }

            $this->_elements[$name] = $element;
            $this->_elements[$name]->addPrefixPaths($prefixPaths);
        }

        $this->_order[$name] = $this->_elements[$name]->getOrder();
        $this->_orderUpdated = true;
        $this->_setElementsBelongTo($name);

        return $this;
    }
	
	
	/* Create an element */
	/*
	public function createElement($type, $name, $options = null)
    {
        if (!is_string($type)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Element type must be a string indicating type');
        }

        if (!is_string($name)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Exception('Element name must be a string');
        }

        $prefixPaths              = array();
        $prefixPaths['decorator'] = $this->getPluginLoader('decorator')->getPaths();
        if (!empty($this->_elementPrefixPaths)) {
            $prefixPaths = array_merge($prefixPaths, $this->_elementPrefixPaths);
        }

        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if ((null === $options) || !is_array($options)) {
            $options = array('prefixPath' => $prefixPaths);
        } elseif (is_array($options)) {
            if (array_key_exists('prefixPath', $options)) {
                $options['prefixPath'] = array_merge($prefixPaths, $options['prefixPath']);
            } else {
                $options['prefixPath'] = $prefixPaths;
            }
        }

        $class = $this->getPluginLoader(self::ELEMENT)->load($type);
        $element = new $class($name, $options);

        return $element;
    }
	
	public function addElements(array $elements)
    {
        foreach ($elements as $key => $spec) {
            $name = null;
            if (!is_numeric($key)) {
                $name = $key;
            }

            if (is_string($spec) || ($spec instanceof Zend_Form_Element)) {
                $this->addElement($spec, $name);
                continue;
            }

            if (is_array($spec)) {
                $argc = count($spec);
                $options = array();
                if (isset($spec['type'])) {
                    $type = $spec['type'];
                    if (isset($spec['name'])) {
                        $name = $spec['name'];
                    }
                    if (isset($spec['options'])) {
                        $options = $spec['options'];
                    }
                    $this->addElement($type, $name, $options);
                } else {
                    switch ($argc) {
                        case 0:
                            continue;
                        case (1 <= $argc):
                            $type = array_shift($spec);
                        case (2 <= $argc):
                            if (null === $name) {
                                $name = array_shift($spec);
                            } else {
                                $options = array_shift($spec);
                            }
                        case (3 <= $argc):
                            if (empty($options)) {
                                $options = array_shift($spec);
                            }
                        default:
                            $this->addElement($type, $name, $options);
                    }
                }
            }
        }
        return $this;
    }
	*/
	
	/*
	public function setAction($action){
        $this->formAction = $action;
    }
	*/
    
    public function setMethod($method){
        $this->formMethod = $method;
    }
	
	public function init(){
		return $this->formAction;
    }
	
	public function createElement($type , $name , $value ){
		return "<input type='hidden' name='$name' value='$value' > \n";	
	}
	
}//$


?>