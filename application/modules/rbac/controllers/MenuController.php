<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/menu',
			'master/menutype',
			'master/group',
			'master/menugroup',
		]);
	}

	public function actionIndex($id = null)
	{
		$this->layout->title = 'Menu';
		$list_type = $this->menutype->getAll();

		# Set data for form
		$model = null;
		if ($id) {
			$model = $this->menutype->get($id);
		}

		# Doing magic if post
		if ($post = $this->input->post('MenuType')) {
			$post['menu_type'] = str_replace(' ', '-', strtolower($post['title']));

			$save = false;
			if ($model) {
				$save = $this->menutype->update($post, $id);
			} else {
				$save = $this->menutype->insert($post);
			}

			if ($save) {
				$this->session->set_flashdata('success', 'Proses simpan berhasil');
			} else {
				$this->session->set_flashdata('danger', 'Proses simpan gagal');
			}

			redirect('rbac/menu', 'refresh');
		}

		$this->layout->render('index', [
			'list_type' => $list_type,
			'model' => $model
		]);
	}

	public function actionHapus($id)
	{
		$model = $this->menutype->get($id);

		if ($model) {
			if ($this->menutype->delete($id)) {
				$this->session->set_flashdata('warning', 'Proses hapus berhasil');
			} else {
				$this->session->set_flashdata('danger', 'Proses hapus berhasil');
			}
		} else {
			$this->session->set_flashdata('info', 'Data tidak ditemukan');
		}

			redirect('rbac/menu', 'refresh');
	}

	public function actionListMenu($id)
	{
		$menu_type = $this->menutype->get($id);
		$menus = $this->menu->getAllMenu($menu_type->menu_type);
		$listGroup = $this->group->getAll();
		$menu_groups = $this->menugroup->getAll(['menu_id' => $id]);

		if ($post = $this->input->post()) {
			if ($this->menu->saveMenu($post, $id)) {
				$this->session->set_flashdata('success', 'Proses simpan menu berhasil');
			} else {
				$this->session->set_flashdata('error', 'Proses simpan menu gagal');
			}

			redirect("/rbac/menu/list-menu/{$id}", 'refresh');
		}

		$this->layout->title = 'List Menu '. $menu_type->menu_type;
		$this->layout->view_css = '_partial/css_list_menu';
		$this->layout->view_js = '_partial/js_list_menu';
		$this->layout->render('list_menu', [
			'menus' => $menus,
			'listGroup' => $listGroup,
			'menuType' => $menu_type->menu_type,
			'menu_groups' => $menu_groups,
		]);
	}

}

/* End of file MenuController.php */
/* Location: ./application/modules/rbac/controllers/MenuController.php */