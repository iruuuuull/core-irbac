<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_BaseModel extends CI_Model {

	/**
	 * [$attributes description]
	 * @var array
	 */
	public $attributes = [];

	/**
     * @var array Validation errors (depends on validator driver)
     */
    protected $_errors;

	/**
     * Returns the list of attribute names.
     * By default, this method returns all public non-static properties of the class.
     * You may override this method to change the default behavior.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
        	if ($property->getDeclaringClass()->getName() !== $class->getName()) {
		    	continue;
			}

            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name => value) to be assigned to the model.
     * @param bool $safeOnly whether the assignments should only be done to the safe attributes.
     */
    public function setAttributes($values)
    {
        if (is_array($values)) {
            $attributes = array_flip($this->attributes());

            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                }
            }

            $this->getAttributes();
        }
    }

    /**
     * Returns attribute values.
     * @param array $names list of attributes whose value needs to be returned.
     * Defaults to null, meaning all attributes listed in [[attributes()]] will be returned.
     * If it is an array, only the attributes in the array will be returned.
     * @param array $except list of attributes whose value should NOT be returned.
     * @return array attribute values (name => value).
     */
    public function getAttributes($names = null, $except = [])
    {
        $this->attributes = [];
        if ($names === null) {
            $names = $this->attributes();
        }

        foreach ($names as $name) {
            $this->attributes[$name] = $this->$name;
        }

        foreach ($except as $name) {
            unset($this->attributes[$name]);
        }

        return $this->attributes;
    }

    /**
     * Returns the filter rules for validation.
     *
     * @return array Filter rules. [[['attr1','attr2'], 'callable'],]
     */
    public function filters()
    {
        return [];
    }

    /**
     * Returns the validation rules for attributes.
     * 
     * @see https://www.codeigniter.com/userguide3/libraries/form_validation.html#rule-reference
     * @return array validation rules. (CodeIgniter Rule Reference)
     */
    public function rules()
    {
        return [];
    }

    /**
     * Filter process
     *
     * @param array $data Attributes
     * @return array Filtered data
     */
    public function filter($data)
    {
        // Get filter rules
        $filters = $this->filters();

        // Filter process with setting check
        if (!empty($filters) && is_array($filters)) {
            
            foreach ($filters as $key => $filter) { 
                
                if (!isset($filter[0]))
                    throw new Exception("No attributes defined in \$filters from " . get_called_class() . " (" . __CLASS__ . ")", 500);

                if (!isset($filter[1]))
                    throw new Exception("No function defined in \$filters from " . get_called_class() . " (" . __CLASS__ . ")", 500);

                list($attributes, $function) = $filter;

                $attributes = (is_array($attributes)) ? $attributes : [$attributes];

                // Filter each attribute
                foreach ($attributes as $key => $attribute) {

                    if (!isset($data[$attribute]))
                        continue;
                    
                    $data[$attribute] = call_user_func($function, $data[$attribute]);
                }
            }
        }
        
        return $data;
    }

	/**
     * Performs the data validation with filters
     * 
     * ORM only performs validation for assigned properties.
     * 
     * @param array Data of attributes
     * @param boolean Return filtered data
     * @return boolean Result
     * @return mixed Data after filter ($returnData is true)
     */
    public function validate($attributes=[], $returnData=false)
    {
        // Data fetched by ORM or input
        $data = ($attributes) ? $attributes : $this->getAttributes();
        // Filter first
        $data = $this->filter($data);
        // ORM re-assign properties
        $this->attributes = (!$attributes) ? $data : $this->getAttributes();
        // Get validation rules from function setting
        $rules = $this->rules();

        // The ORM update will only collect rules with corresponding modified attributes.
        if ($this->_selfCondition = null) {

            $newRules = [];
            foreach ((array) $rules as $key => $rule) {
                if (isset($this->attributes[$rule['field']])) {
                    // Add into new rules for updating
                    $newRules[] = $rule;
                }
            }
            // Replace with mapping rules
            $rules = $newRules;
        }

        // Check if has rules
        if (empty($rules))
            return ($returnData) ? $data : true;

        // CodeIgniter form_validation doesn't work with empty array data
        if (empty($data))
            return false;

        // Load CodeIgniter form_validation library for yidas/model namespace, which has no effect on common one
        // get_instance()->load->library('form_validation', null);
        // Get CodeIgniter validator
        $validator = get_instance()->form_validation;
        $validator->reset_validation();
        $validator->set_data($data);
        $validator->set_rules($rules);
        // Run Validate
        $result = $validator->run($this);

        // Result handle
        if ($result===false) {

            $this->_errors = $validator->error_array();
            return false;

        } else {

            return ($returnData) ? $data : true;
        }
    }

    /**
     * Validation - Get error data referenced by last failed Validation
     *
     * @return array 
     */
    public function getErrors()
    {
        return $this->_errors;
    }

}

/* End of file MY_BaseModel.php */
/* Location: ./application/core/MY_BaseModel.php */