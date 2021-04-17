<?php

class CommonHook
{

	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model([
			'notifikasi'
		]);
	}

	public function setNotification()
	{
		$session = $this->CI->session->userdata();
		$notifikasi = [
			'all' => 0,
			'new' => 0,
			'list' => []
		];

		if (!$this->CI->helpers->isGuest() && $session['status_login'] === 'loggedIn') {
			$notifikasis = $this->CI->notifikasi->find()
							->where(['to' => $session['identity']->id])
							->order_by('id', 'desc')
							->limit(10)
							->get()->result();

			$count_all = $this->CI->notifikasi->find()->where(['to' => $session['identity']->id])->count_all_results();
			$count_new = $this->CI->notifikasi->find()->where(['to' => $session['identity']->id, 'is_read' => 0])->count_all_results();

			if ($notifikasis) {
				$notifikasi = [
					'all' => $count_all,
					'new' => $count_new,
					'list' => $notifikasis
				];
			}

			$this->CI->session->set_userdata('notifikasi', $notifikasi);
		}
	}
}
