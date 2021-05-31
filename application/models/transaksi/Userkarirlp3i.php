<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userkarirlp3i extends MY_Model {

	public $tableName = 'tbl_user_karir_lp3i';
	public $datatable_columns = ['tanggal_sk','tbl_user_karir_lp3i.id','tbl_m_unit.sbu_id' ,'user_id','tanggal_berakhir','tbl_m_grade.golongan','job_title','nama_unit','department'];
 	public $datatable_search = ['tbl_m_grade.golongan', 'job_title'];
 	public $datatable_order = ['tbl_user_karir_lp3i.id' => 'asc'];
	public $soft_delete = false;

	public function getAllwithID($params = ''){
 		$query = $this->db->select([
 						'tbl_user_karir_lp3i.id',
 						'tbl_user_karir_lp3i.unit_id',
 						'tbl_user_karir_lp3i.department_id',
 						'tanggal_sk',
						'tanggal_berakhir',
						'grade_id',
                        'file_sk',
						'Job_title',
						'tbl_m_unit.sbu_id',
 					])
 					->join('tbl_m_grade', 'tbl_m_grade.id = tbl_user_karir_lp3i.grade_id')
 					->join('tbl_m_department', 'tbl_m_department.id = tbl_user_karir_lp3i.department_id')
 					->join('tbl_m_unit', 'tbl_m_unit.id = tbl_user_karir_lp3i.unit_id')
					->where($params)
					->get($this->tableName);

 		return $query;
 	}

 	public function	getData($columns='', $table = ''){
		if($columns != null){
			$this->db->select($columns);
		}else{
			$this->db->select('*');
		}

 		$query = $this->db->get($table);

 		return $query;
 	}

 	public function deletedata($where,$table){
		$this->db->where($where);
		$this->db->delete($table);
	}

	protected function _get_datatables_query()
    {
        $this->db->from($this->tableName)
        		->select($this->datatable_columns)
        		->join('tbl_m_grade', 'tbl_m_grade.id = tbl_user_karir_lp3i.grade_id')
 				->join('tbl_m_department', 'tbl_m_department.id = tbl_user_karir_lp3i.department_id')
 				->join('tbl_m_unit', 'tbl_m_unit.id = tbl_user_karir_lp3i.unit_id');

        if ($this->user_id) {
        	$this->db->where(['user_id' => $this->user_id]);
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
