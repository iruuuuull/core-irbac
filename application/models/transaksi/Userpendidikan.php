<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpendidikan extends MY_Model {

	public $tableName = 'tbl_user_pendidikan';
	public $datatable_columns = ['m_pendidikan_id', 'nama_sekolah','user_id','tahun_masuk','tahun_keluar','lokasi','jurusan','tbl_user_pendidikan.id','ijazah'];
 	public $datatable_search = ['nama_sekolah'];
 	public $datatable_order = ['tbl_user_pendidikan.id' => 'asc'];
	public $soft_delete = false;

	public $user_id;

	public function getAllwithID($params = ''){
 		$query = $this->db->select([
 						'tbl_user_pendidikan.id',
 						'm_pendidikan_id',
						'nama_sekolah',
						'tahun_masuk',
						'tahun_keluar',
						'lokasi',
						'tbl_m_pendidikan.label',
						'tbl_m_pendidikan.kode_pendidikan',
						'jurusan',
						'nilai_akhir',
						'ijazah',
 					])
 					->join('tbl_m_pendidikan', 'tbl_m_pendidikan.id = tbl_user_pendidikan.M_pendidikan_id')
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
        		->select($this->datatable_columns);

 		if ($this->user_id) {
        	$this->db->where(['tbl_user_pendidikan.user_id' => $this->user_id])
        	->join('tbl_m_pendidikan', 'tbl_m_pendidikan.id = tbl_user_pendidikan.M_pendidikan_id');
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
