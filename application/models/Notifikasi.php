<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi extends MY_Model {

	public $tableName = 'tbl_notifikasi';
	public $soft_delete = false;
	public $timestamps = true;
	public $blameable = true;
	const UPDATED_AT = null;

	public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'triggered_by',
            'updatedByAttribute' => null,
            'value' => $this->session->userdata('identity')->id
        ];
    }

    public function userTrigger()
    {
    	return $this->hasOne('user', 'triggered_by', 'id');
    }

    public function userSent()
    {
    	return $this->hasOne('user', 'to', 'id');
    }

    static function sendNotif($to, $content, $url = '', $priority = 'label-info')
    {
    	$model = new self;
    	$model->to = $to;
    	$model->content = $content;
    	$model->redirect_url = $url;
    	$model->priority = $priority;

    	return $model->save();
    }

}

/* End of file Notifikasi.php */
/* Location: ./application/models/Notifikasi.php */