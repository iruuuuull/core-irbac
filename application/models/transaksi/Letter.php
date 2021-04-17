<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Letter extends MY_Model {

	public $tableName = 'tbl_letter_reg';
	public $datatable_columns = ['tbl_letter_reg.id','tbl_letter_reg.unit_id' ,'letter_date','letter_no','letter_from','letter_to','desc','attachment','is_attach','tbl_letter_reg.created_by','nama_depan','nama_tengah','nama_belakang'];
 	public $datatable_search = ['letter_from','nama_depan'];
	public $soft_delete = false;

    public $unit_id;
    public $user_id;
    public $owner_letter_id;


	protected function _get_datatables_query()
    {
        $this->db->from($this->tableName)
        		->select($this->datatable_columns)
                ->join('tbl_user_detail', 'tbl_user_detail.user_id = tbl_letter_reg.created_by')
                ->order_by('tbl_letter_reg.id','DESC');
                
 		if($this->unit_id){       
            if ($this->user_id) {
                    $this->db->where([
                        'tbl_letter_reg.unit_id' => $this->unit_id,
                        'tbl_letter_reg.created_by' => $this->user_id
                    ]);
                }else{
                    $this->db->where([
                        'tbl_letter_reg.unit_id' => $this->unit_id
                    ]);
              }
        }

        if($this->owner_letter_id){
            if ($this->user_id) {
                $this->db->where([
                    'owner_letter_id' => $this->owner_letter_id,
                    'tbl_letter_reg.created_by' => $this->user_id
                ]);
            }elseif($this->unit_id){
                $this->db->where([
                    'tbl_letter_reg.unit_id' => $this->unit_id,
                ]);
            }else{
                $this->db->where([
                        'owner_letter_id' => $this->owner_letter_id
                ]);
            }
        }


        
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

}

/* End of file modelName.php */
/* Location: ./application/models/modelName.php */
