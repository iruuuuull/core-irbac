<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Model {

	public $tableName = 'tbl_user';
	public $datatable_columns = ['tbl_user.id', 'username', 'email', 'status', 'tbl_user.created_at'];
	public $datatable_search = ['username', 'email', 'status'];
    public $blameable = true;
    public $timestamps = true;

	const AKUN_AKTIF = 1; // status user/akun
    const AKUN_TIDAK_AKTIF = 0; // status user/akun

    const ID_CEO = 100;

    public function __construct()
    {
    	parent::__construct();
    	$this->load->model([
    		'master/usergroup',
    		'master/group',
            'master/unit',
            'transaksi/userdetail',
    	]);
    }

	public function findByUsername($username)
	{
		return $this->get(['username' => $username]);
	}

	public function getUserGroups($user_id)
	{
		return $this->usergroup->getAll(['user_id' => $user_id]);
	}

	protected function _get_datatables_query()
    {
        $user = $this->session->userdata('identity');

        $groups = $this->group->getGroups($this->session->userdata('group_id'));
        $units = $this->unit->getUnitByUserGroup($user->id, true);

        $this->db->from($this->tableName);
        $this->db->select($this->datatable_columns);

        # Menampilkan list user dengan group anakannya
        $this->db->join('tbl_group_user', 'tbl_group_user.user_id = tbl_user.id', 'left');
        $this->db->group_start();
            $this->db->where_in('tbl_group_user.group_id', $groups);
            $this->db->or_where(['tbl_group_user.group_id' => null]);
        $this->db->group_end();

        # Menampilkan list user berdasarkan unit
        $this->db->join('tbl_user_detail', 'tbl_user_detail.user_id = tbl_user.id', 'left');

        $this->db->group_start();
            $this->db->where_in('tbl_user_detail.unit_id', $units);
            $this->db->or_where(['tbl_user_detail.unit_id' => null]);
            $this->db->or_where(['tbl_user.created_by' => $user->id]);
        $this->db->group_end();

        $this->db->group_by('tbl_user.username');

        if ($this->soft_delete === true) {
            $this->db->where(['tbl_user.deleted_at' => null]);
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

    public function getUserGroup()
    {
        return $this->hasMany('usergroup', 'user_id', 'id');
    }

    public function userDetail()
    {
        return $this->hasOne('userdetail', 'user_id', 'id');
    }

    public function isHasGroup($group)
    {
        return !empty($this->getUserGroup()->where(['group_id' => $group])->get()->row());
    }

    public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => $this->session->userdata('identity')->id
        ];
    }

}
