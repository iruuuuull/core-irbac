<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpengalaman extends MY_Model {

	public $tableName = 'tbl_user_pengalaman';
	public $datatable_columns = ['tbl_user_pengalaman.user_id', 'nama_perusahaan', 'tanggal_mulai', 'tanggal_selesai', 'jabatan', 'bagian'];
	public $datatable_search = ['nama_perusahaan', 'tanggal_mulai', 'tanggal_selesai', 'jabatan', 'bagian'];

	/**
	 * [Id relasi user untuk select berdasarkan id user]
	 * @var [int]
	 */
	public $user_id;

	protected function _get_datatables_query()
    {
        $this->db->from($this->tableName);

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

/* End of file Userpengalaman.php */
/* Location: ./application/models/transaksi/Userpengalaman.php */