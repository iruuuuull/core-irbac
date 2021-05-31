<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserSertifikasi extends MY_Model {

	public $tableName = 'tbl_user_sertifikasi';
	public $datatable_columns = ['id','nama_pelatihan','user_id', 'penyelenggara','lokasi','tanggal_mulai','tanggal_selesai'];
 	public $datatable_search = ['nama_pelatihan', 'penyelenggara'];
	public $soft_delete = false;

	public $user_id;


	protected function _get_datatables_query()
    {
        $this->db->from($this->tableName)
            ->select($this->datatable_columns);

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
