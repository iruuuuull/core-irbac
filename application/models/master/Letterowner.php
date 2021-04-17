<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Letterowner extends MY_Model {

	public $tableName = 'tbl_m_letter_owner';
	public $datatable_columns = ['owner_letter_id','desc','last_letter_no','year'];
	public $datatable_search = ['owner_letter_id','desc', 'last_letter_no'];
	public $soft_delete = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'master/kodesurat',
        ]);
    }

    public function getListOwners($owners = [], $dropdown = false)
    {
        if (empty($owners)) {
            $owners = $this->getAll();
        }

        if ($dropdown) {
            $list_owner = ['' => '- Pilih Owner -'];
        } else {
            $list_owner = [];
        }

        foreach ($owners as $key => $owner) {
            $list_owner[$owner->owner_letter_id] = $owner->desc;
        }

        return $list_owner;
    }


     public function getLetter($unit_id , $trigger = false){

        $code = $this->kodesurat->get(['unit_id' => $unit_id]);
        if($code){
            $list_owner = $this->get(['owner_letter_id' => $code->owner_letter_id]);
            $where = array('owner_letter_id' =>  $list_owner->owner_letter_id);
        }

        if($trigger === false){            
            if(date('Y') > $list_owner->year){
                       
                $data['last_letter_no'] = 0;
                $data['year'] = date('Y');
                $this->db->update($this->tableName, $data, $where);
                        
                $owner = $this->get(['owner_letter_id' => $code->owner_letter_id]);
                 return $owner;  
            }else{
                return $list_owner; 
            }        
            
        }else{
            $data['last_letter_no'] = $list_owner->last_letter_no + 1;
            $this->db->update($this->tableName, $data , $where);
        }
        
         return $list_owner;  


    }


}
