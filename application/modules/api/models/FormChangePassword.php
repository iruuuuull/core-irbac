<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormChangePassword extends MY_BaseModel {

	public $old_password;
	public $new_password;
	public $conf_password;

	public $_user;

	public function rules() {
		return [
            [
                'field' => 'old_password',
                'rules' => 'required|callback_checkOldPassword',
                'errors' => [
                    'required' => 'Password lama wajib diisi.',
                ],
            ],
            [
                'field' => 'new_password',
                'rules' => 'required|callback_checkNewPassword|min_length[6]',
                'errors' => [
                    'required' => 'Password baru wajib diisi.',
                    'min_length' => 'Password minimal 6 karakter.',
                ],
            ],
            [
                'field' => 'conf_password',
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Password konfirmasi harus sama dengan password baru.',
                ],
            ],
        ];
	}

	public function filters()
	{
	    return [
	        [['old_password', 'new_password', 'conf_password'], 'trim'],
	        [['old_password', 'new_password', 'conf_password'], [$this->security, 'xss_clean']],
	    ];
	}

	public function checkOldPassword() {
		if ($this->_user) {
			$verify = password_verify($this->old_password, $this->_user->password);

			if (!$verify) {
				$this->form_validation->set_message('checkOldPassword', 'Password lama salah.');
				return false;
			}

			return true;
		}
	}

	public function checkNewPassword() {
		if ($this->_user) {
			$verify = password_verify($this->new_password, $this->_user->password);

			if ($verify) {
				$this->form_validation->set_message('checkNewPassword', 'Password baru sama dengan password lama.');
				return false;
			}

			return true;
		}
	}

	public function save()
	{
		$this->_user->password = password_hash($this->new_password, PASSWORD_DEFAULT);

		return $this->_user->save();
	}

}

/* End of file FormChangePassword.php */
/* Location: ./application/modules/api/models/FormChangePassword.php */