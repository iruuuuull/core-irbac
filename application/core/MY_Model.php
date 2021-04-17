<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 * 
 * @category	Libraries
 * @author		Ilham D. Sofyan
 */
class MY_Model extends MY_Orm {

	public $tableName = '';
	public $primaryKey = 'id';

	# Public property for datatable usage
	/**
	 * Field that will be shown on datatables
	 * @var array
	 */
	public $datatable_columns = [];

	/**
	 * Field that will be search-able
	 * @var array
	 */
    public $datatable_search = [];

    /**
     * Field order
     * @var array
     */
    public $datatable_order = ['id' => 'asc']; // default order 

	/**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
	public function get($where = NULL) {
        $this->db->select('*');
        $this->db->from($this->tableName);

        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    (is_array($value)) ? $this->db->where_in($field, $value) : $this->db->where($field, $value);
                    // $this->db->where($field, $value);
                }

            } else {
                $this->db->where($this->primaryKey, $where);
            }
        }

        if ($this->soft_delete === true) {
            $this->db->where(['deleted_at' => null]);
        }

        $result = $this->db->get()->row();
        if ($result) {
            return $result;

        } else {
            return [];
        }
    }

    /**
     * Retrieves record(s) from the database in the form of multiple records
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return array
     */
    public function getAll($where = array(), $limit = null) {
        $this->db->select('*');
        $this->db->from($this->tableName);

        if (!empty($where) && is_array($where)) {
            foreach ($where as $field => $value) {
                // $this->db->where($field, $value);
                (is_array($value)) ? $this->db->where_in($field, $value) : $this->db->where($field, $value);
            }
        }

        if ($this->soft_delete === true) {
            $this->db->where(['deleted_at' => null]);
        }

        if ($limit) {
            $this->db->limit($limit);
        }

        $result = $this->db->get()->result();
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    // public function insert(Array $data) {
    //     if ($this->db->insert($this->tableName, $data)) {
    //         return $this->db->insert_id();
    //     } else {
    //         return false;
    //     }
    // }

    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insertBatch(Array $data) {
        $this->db->insert_batch($this->tableName, $data);
        
        if($this->db->affected_rows() < 1){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    // public function update(Array $data, $where = []) {
    //     if (!is_array($where)) {
    //         $where = [$this->primaryKey => $where];
    //     }

    //     return $this->db->update($this->tableName, $data, $where);
    // }

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($where = []) {
        if (!is_array($where)) {
            $where = [$this->primaryKey => $where];
        }

        if ($this->soft_delete === true) {
            $this->update([
                'deleted_at' => date('YmdHis'),
                'deleted_by' => $this->session->userdata('identity')->id,
            ], $where);
        } else {
            $this->db->delete($this->tableName, $where);
        }

        return $this->db->affected_rows();
    }

    /**
     * @link [http://mfikri.com/artikel/Datatable-serverside-processing-menggunakan-codeigniter.html] [serverside datatable codeigniter]
     * @return [type] [description]
     */
    protected function _get_datatables_query()
    {
        $this->db->from($this->tableName);

        if ($this->soft_delete === true) {
            $this->db->where(['deleted_at' => null]);
        }
 
        $i = 0;
     
        foreach ($this->datatable_search as $item) {
        	# jika datatable mengirimkan pencarian dengan metode POST
            if($_POST['search']['value']) {
                 
                if ($i === 0) {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);

                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if (count($this->datatable_search) - 1 == $i) {
                    $this->db->group_end(); 
                }
            }

            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db->order_by($this->datatable_columns[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

        } elseif(isset($this->datatable_order)) {
            $order = $this->datatable_order;
            $this->db->order_by(key($order), $order[key($order)]);

        }
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();

        if($_POST['length'] != -1) {
        	$this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->queryAll($this->db);

        return $query;
    }
 
    function count_filtered($id = null,$field = null)
    {
        $this->_get_datatables_query();

        if($id != null && !is_array($id)){
            $this->db->where($field, $id);
        } elseif ($id && is_array($id)) {
            $this->db->where($id);
        }

        $query = $this->db->get();

        return $query->num_rows();
    }
 
    public function count_all($id = null,$field = null)
    {
        $this->db->from($this->tableName);

        if($id != null && !is_array($id)){
            $this->db->where($field, $id);
        } elseif ($id && is_array($id)) {
            $this->db->where($id);
        }

        if ($this->soft_delete === true) {
            $this->db->where(['deleted_at' => null]);
        }
        
        $query = $this->db->get();

        return $query->num_rows();
    }

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
        }
    }

    /**
     * Populates the model with input data.
     *
     * This method provides a convenient shortcut for:
     *
     * ```php
     * if (isset($_POST['FormName'])) {
     *     $model->attributes = $_POST['FormName'];
     *     if ($model->save()) {
     *         // handle success
     *     }
     * }
     * ```
     *
     * which, with `load()` can be written as:
     *
     * ```php
     * if ($model->load($_POST) && $model->save()) {
     *     // handle success
     * }
     * ```
     *
     * `load()` gets the `'FormName'` from the model's [[formName()]] method (which you may override), unless the
     * `$formName` parameter is given. If the form name is empty, `load()` populates the model with the whole of `$data`,
     * instead of `$data['FormName']`.
     *
     * Note, that the data being populated is subject to the safety check by [[setAttributes()]].
     *
     * @param array $data the data array to load, typically `$_POST` or `$_GET`.
     * @param string $formName the form name to use to load the data into the model.
     * If not set, [[formName()]] is used.
     * @return bool whether `load()` found the expected form in `$data`.
     */
    // public function load($data, $formName = null)
    // {
    //     $scope = $formName === null ? $this->formName() : $formName;
    //     if ($scope === '' && !empty($data)) {
    //         $this->setAttributes($data);

    //         return true;
    //     } elseif (isset($data[$scope])) {
    //         $this->setAttributes($data[$scope]);

    //         return true;
    //     }

    //     return false;
    // }

    /**
     * Returns the form name that this model class should use.
     *
     * The form name is mainly used by [[\yii\widgets\ActiveForm]] to determine how to name
     * the input fields for the attributes in a model. If the form name is "A" and an attribute
     * name is "b", then the corresponding input name would be "A[b]". If the form name is
     * an empty string, then the input name would be "b".
     *
     * The purpose of the above naming schema is that for forms which contain multiple different models,
     * the attributes of each model are grouped in sub-arrays of the POST-data and it is easier to
     * differentiate between them.
     *
     * By default, this method returns the model class name (without the namespace part)
     * as the form name. You may override it when the model is used in different forms.
     *
     * @return string the form name of this model class.
     * @see load()
     * @throws InvalidConfigException when form is defined with anonymous class and `formName()` method is
     * not overridden.
     */
    public function formName()
    {
        $reflector = new ReflectionClass($this);
        if (PHP_VERSION_ID >= 70000 && $reflector->isAnonymous()) {
            throw new InvalidConfigException('The "formName()" method should be explicitly defined for anonymous models');
        }
        return $reflector->getShortName();
    }

}
