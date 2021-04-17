<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends MY_Model {

	public $tableName = 'tbl_cuti';
	public $soft_delete = false;
	public $blameable = true;
	protected $timestamps = true;

	public $datatable_columns = ['tbl_cuti.id', 'tbl_cuti.tanggal_mulai', 'tbl_cuti.tanggal_akhir', 'tbl_cuti.is_cancel', 'tbl_cuti.status', 'tbl_cuti.attachment', 
		'tbl_cuti.created_at', 'tbl_cuti.note', 'tbl_cuti.user_id', 'tbl_cuti_verifikasi.status AS status_verif', 'tbl_cuti_verifikasi.urutan'];
	public $datatable_search = ['tbl_cuti.tanggal_mulai', 'tbl_cuti.tanggal_akhir', 'tbl_cuti.status', 
		'tbl_cuti.attachment', 'tbl_cuti.created_at'];
	public $datatable_order = ['tbl_cuti.id' => 'asc'];

	const STATUS_MENUNGGU = 0;
	const STATUS_PROSES = 1;
	const STATUS_SETUJU = 2;
	const STATUS_TOLAK = 3;
	const STATUS_BATAL = 4;

	// Tipe cuti yg diambil
	const JENIS_LAST = 1;
	const JENIS_CURRENT = 2;

	public $id_user;
	public $hr_id;
	public $verifikator_id;
	public $nip_user;

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/tipecuti',
			'transaksi/userdetail',
			'transaksi/cutiverifikasi',
		]);
	}

	protected function _get_datatables_query()
    {
    	$this->db->select($this->datatable_columns);
        $this->db->from($this->tableName);
        $this->db->join('tbl_cuti_verifikasi', 'tbl_cuti_verifikasi.cuti_id = tbl_cuti.id', 'left');
        $this->db->group_by('tbl_cuti.id');
 
        if ($this->id_user) {
        	$this->db->where([
        		'tbl_cuti.user_id' => $this->id_user
        	]);
        }

        if ($this->hr_id || $this->verifikator_id) {
        	$this->db->where('tbl_cuti.status !=', self::STATUS_BATAL);

	        if ($this->hr_id && $this->verifikator_id) {
	        	$this->db->where([
	        		'tbl_cuti.hr' => $this->hr_id
	        	]);

	        	$this->db->or_where([
	        		'tbl_cuti_verifikasi.verifikator_id' => $this->verifikator_id
	        	]);
	        } else {
	        	if ($this->hr_id) {
		        	$this->db->where([
		        		'tbl_cuti.hr' => $this->hr_id
		        	]);
		        }

	        	if ($this->verifikator_id) {
	        		$this->db->where([
		        		'tbl_cuti_verifikasi.verifikator_id' => $this->verifikator_id
		        	]);
	        	}
	        }
        }
 
        if ($this->nip_user) {
        	$this->db->join('tbl_user_detail', 'tbl_user_detail.user_id = tbl_cuti.user_id');
        	$this->db->where([
        		'tbl_user_detail.nik' => $this->nip_user
        	]);
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

	public function tipeCuti()
	{
		return $this->hasOne('tipecuti', 'id', 'type_taken');
	}

	public function tipeCutiMain()
	{
		return $this->hasOne('tipecuti', 'id', 'cuti_type');
	}

	public function user()
	{
		return $this->hasOne('user', 'id', 'user_id');
	}

	public function cutiVerifikasi()
	{
		return $this->hasMany('cutiverifikasi', 'cuti_id', 'id');
	}

	public function getStatus()
	{
		return [
			self::STATUS_MENUNGGU => 'Menunggu',
			self::STATUS_PROSES => 'Diproses',
			self::STATUS_SETUJU => 'Disetujui',
			self::STATUS_TOLAK => 'Ditolak',
			self::STATUS_BATAL => 'Dibatalkan'
		];
	}

	public function getStatusValue()
	{
		$status = $this->getStatus();

		if (isset($this->status)) {
			return !empty($status[$this->status]) ? $status[$this->status] : null;
		}

		return null;
	}

	public static function getTypeTaken($dropdown = false)
	{
		$types = [
			self::JENIS_LAST => 'Last Year Leave',
			self::JENIS_CURRENT => 'Current Year Leave',
		];

		if ($dropdown === true) {
			$types = ['' => '- Pilih -'] + $types;
		}

		return $types;
	}

	public function getTypeTakenValue()
	{
		$types = self::getTypeTaken();

		if (isset($this->type_taken)) {
			return !empty($types[$this->type_taken]) ? $types[$this->type_taken] : null;
		}

		return null;
	}

	public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => $this->session->userdata('identity')->id
        ];
    }

    public function setVerifikator($cuti_id = null)
    {
    	$cuti = null;

    	if (empty($cuti_id) && !empty($this->id)) {
    		$cuti = $this;
    	} elseif (!empty($cuti_id)) {
    		$cuti = $this->findOne($cuti_id);
    	}

    	if ($cuti) {
    		# GET RULE OF CUTI
    		$rule = [];
    		$tipe_cuti = $cuti->tipeCuti;
    		if ($tipe_cuti) {
    			$rule = json_decode($tipe_cuti->rule, true);
    		}

    		# GET VERIFICATOR
    		$verifikators = $this->getVerifikator($cuti->user_id, $rule);

    		# SAVE VERIFICATION
    		if ($verifikators) {
    			$verifikator_set = 0;

    			foreach ($verifikators as $key => $verifikator) {
		    		$verifikasi = new Cutiverifikasi;
		    		$verifikasi->cuti_id = $cuti->id;
		    		$verifikasi->verifikator_id = $verifikator->user_id;
		    		$verifikasi->urutan = ($key + 1);

		    		if ($verifikasi->save()) {
		    			$trigger_notif = Notifikasi::sendNotif(
							$verifikator->user_id,
							"Pengajuan permohonan cuti {$this->session->userdata('detail_identity')->nik}",
							site_url('/cuti/verifikasi')
						);

						$verifikator_set++;
		    		}
    			}

    			if (count($verifikators) === $verifikator_set) {
    				$cuti->status = self::STATUS_PROSES;
    				return $cuti->save();
    			}
    		}
    	}

    	return false;
    }

    public function getVerifikator($user_id, $rules)
    {
    	$verifikators = [];

    	foreach ($rules as $key => $rule) {
	    	if ($rule == Tipecuti::VERIF_ATASAN) {
	    		$verifikator = (new Userdetail)->getAtasan($user_id);
	    		if ($verifikator) {
	    			$verifikators[] = $verifikator;
	    		}

	    	} elseif ($rule == Tipecuti::VERIF_HEAD) {
	    		$verifikator = (new Userdetail)->getHead($user_id);
	    		if ($verifikator) {
	    			$verifikators[] = $verifikator;
	    		}
    		} elseif ($rule == Tipecuti::VERIF_HRD) {
				$verifikator = (new Userdetail)->getHr($user_id);
				if ($verifikator) {
	    			$verifikators[] = $verifikator;
				}

			} elseif ($rule == Tipecuti::VERIF_CEO) {
				$verifikator = (new Userdetail)->getCeo($user_id);
				if ($verifikator) {
	    			$verifikators[] = $verifikator;
				}

			}
    	}

    	return $verifikators;
    }

    public function decreaseQuota($id)
    {
    	$model = $this->findOne($id);

    	if ($model) {
    		# Tidak dilakukan pengurangan untuk jenis cuti tertentu
    		if ($model->tipeCuti->is_limited != 1) {
    			return true;
    		}

    		# Diff day
			$amount = diffWorkDay($model->tanggal_mulai, $model->tanggal_akhir);
    		$userdetail = $model->user->userDetail;

    		$userdetail->last_leave -= $amount;
    		
    		if ($userdetail->last_leave < 0) {
    			$userdetail->current_leave += $userdetail->last_leave;
    			$userdetail->last_leave = 0;
    		}

    		return $userdetail->save();
    	}

    	return false;
    }

    public function increaseQuota($id)
    {
    	$model = $this->findOne($id);

    	if ($model) {
    		$pembatalan = $model->pembatalanCuti;

    		# Tidak dilakukan pengurangan untuk jenis cuti tertentu
    		if ($model->tipeCuti->is_limited != 1) {
    			return true;
    		}

    		# Diff day
			$amount = diffWorkDay($pembatalan->mulai_batal, $pembatalan->akhir_batal);
    		$userdetail = $model->user->userDetail;

    		$userdetail->current_leave += $amount;
    		
    		if ($userdetail->current_leave > 12) {
    			$userdetail->last_leave = $userdetail->current_leave - 12;
    			$userdetail->current_leave = 12;
    		}

    		return $userdetail->save();
    	}

    	return false;
    }

    public function pembatalanCuti()
    {
    	return $this->hasOne('cutipembatalan', 'cuti_id', 'id');
    }

}

/* End of file Cuti.php */
/* Location: ./application/models/transaksi/Cuti.php */