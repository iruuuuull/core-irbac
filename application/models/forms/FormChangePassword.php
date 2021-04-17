<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormChangePassword extends MY_BaseModel {

	public $old_password;
	public $new_password;
	public $retype_password;

	public function rules()
    {
        return [
            [
                'field' => 'old_password',
                'rules' => 'required|callback_validatePassword',
                'errors' => [
                    'required' => 'Password lama wajib diisi',
                ],
            ],
            [
                'field' => 'new_password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password baru wajib diisi',
                    'min_length' => 'Mininal karakter password baru adalah 6',
                ],
            ],
            [
                'field' => 'retype_password',
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Ketik ulang password baru',
                    'matches' => 'Password konfirmasi tidak sama dengan password baru',
                ],
            ],
        ];
    }

    public function validatePassword()
    {
    	$user = $this->session->userdata('identity');

    	if (!$user || !password_verify($this->old_password, $user->password)) {
            $this->form_validation->set_message('validatePassword', 'Password lama salah.');
            return false;
        } else {
            return true;
        }
    }

}

/* End of file FormChangePassword.php */
/* Location: ./application/models/forms/FormChangePassword.php */