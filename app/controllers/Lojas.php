<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lojas extends MY_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->loggedIn) {
			redirect('login');
		}
		$this->load->library('form_validation');
		$this->load->model('pos_model');
		$this->load->model('lojas_model');
	}

	function index()
	{

		$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = lang('stores');
		$bc = array(array('link' => '#', 'page' => lang('stores')));
		$meta = array('page_title' => lang('stores'), 'bc' => $bc);
		$this->page_construct('lojas/index', $this->data, $meta);
	}

	public function get_lojas()
	{

		$this->load->library('datatables');
		$this->datatables
			->select("id, cod, nome, token, obs")
			->from("lojas")
			->where("tipo = 'LOJA'")
			->add_column("Actions", "<div class='text-center'>"
                . "<div class='btn-group actions'>"
                .   "<a href='" . site_url('lojas/gerarNovoToken/$1') . "' onClick=\"return confirm('Deseja realmente gerar um novo token? Esta loja não conseguirá acesso ao sistema com o token antigo')\" class='tip btn btn-primary btn-xs' title='Gerar novo Token'>"
                .       "<i class='fa fa-lock'></i>"
                .   "</a>"
                    . "<a href='" . site_url('lojas/editar/$1') . "' class='tip btn btn-warning btn-xs' title='" . $this->lang->line("edit_store") . "'>"
                    .   "<i class='fa fa-edit'></i>"
                    . "</a>"
                . "</div>"
                . "</div>", "id")
			->unset_column('id');

		echo $this->datatables->generate();
	}

	function adicionar()
	{

		$this->form_validation->set_rules('cod', "Código", 'trim|required|is_unique[lojas.cod]');
		$this->form_validation->set_rules('nome', "Nome", 'required');

		if ($this->form_validation->run() == true) {

			$data = array(
				'cod' => $this->input->post('cod'),
				'nome' => $this->input->post('nome'),
				'obs' => $this->input->post('obs'),
				'token' => md5($this->input->post('cod') . date('ymdhis'))
			);
		}

		if ($this->form_validation->run() == true && $cid = $this->lojas_model->addLoja($data)) {

			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status' => 'success', 'msg' =>  $this->lang->line("store_added"), 'id' => $cid, 'val' => $data['cod']));
				die();
			}

			$this->session->set_flashdata('message', $this->lang->line("store_added"));
			redirect("lojas");
		} else {

			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status' => 'failed', 'msg' => validation_errors()));
				die();
			}

			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			$this->data['page_title'] = lang('add_store');
			$bc = array(array('link' => site_url('lojas'), 'page' => lang('stores')), array('link' => '#', 'page' => lang('add_store')));
			$meta = array('page_title' => lang('add_store'), 'bc' => $bc);
			$this->page_construct('lojas/adicionar', $this->data, $meta);
		}
	}

	function gerarNovoToken($id = NULL)
	{

		if (!$this->Admin) {
			$this->session->set_flashdata('error', $this->lang->line('access_denied'));
			redirect('pos');
		}

		if ($this->input->get('id')) {
			$id = $this->input->get('id', TRUE);
		}

		$data = ['token' => md5($this->input->post('cod') . date('ymdhis'))];

		if ($this->lojas_model->updateLoja($id, $data)) {

			$this->session->set_flashdata('message', 'Token atualizado com sucesso');
			redirect("lojas");
		} else {
			$this->session->set_flashdata('error', 'Não foi possível atualizar token. Por favor, tente novamente mais tarde');
			redirect('pos');
		}
	}

	function editar($id = NULL)
	{
		if (!$this->Admin) {
			$this->session->set_flashdata('error', $this->lang->line('access_denied'));
			redirect('pos');
		}

		if ($this->input->get('id')) {
			$id = $this->input->get('id', TRUE);
		}

		$this->form_validation->set_rules('cod', 'Código', 'trim|required');
		$this->form_validation->set_rules('nome', 'Nome', 'required');

		if ($this->form_validation->run() == true) {

			$data = array(
				'cod' => $this->input->post('cod'),
				'nome' => $this->input->post('nome'),
				'obs' => $this->input->post('obs'),

			);
		}

		if ($this->form_validation->run() == true) {

			$arr = $this->lojas_model->getLojaBy("id != $id AND cod = '" . $this->input->post('cod') . "' AND tipo = 'LOJA'");

			if ($arr === FALSE) {

				if ($this->lojas_model->updateLoja($id, $data)) {

					$this->session->set_flashdata('message', 'Loja Editada com sucesso!');
					redirect("lojas");
				} else {

					$this->data['loja'] = $this->lojas_model->getLojaById($id);
					$this->data['error'] = 'Não foi possível editar Loja. Por favor tente novamente mais tarde';
					$this->data['page_title'] = 'Editar Loja';
					$bc = array(array('link' => site_url('lojas'), 'page' => 'Lojas'), array('link' => '#', 'page' => 'Editar Loja'));
					$meta = array('page_title' => 'Editar Loja', 'bc' => $bc);
					$this->page_construct('lojas/editar', $this->data, $meta);
				}
			} else {

				$this->data['loja'] = $this->lojas_model->getLojaById($id);
				$this->data['error'] = 'Código já existente';
				$this->data['page_title'] = 'Editar Loja';
				$bc = array(array('link' => site_url('lojas'), 'page' => 'Lojas'), array('link' => '#', 'page' => 'Editar Loja'));
				$meta = array('page_title' => 'Editar Loja', 'bc' => $bc);
				$this->page_construct('lojas/editar', $this->data, $meta);
			}
		} else {

			$this->data['loja'] = $this->lojas_model->getLojaById($id);
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			$this->data['page_title'] = 'Editar Loja';
			$bc = array(array('link' => site_url('lojas'), 'page' => 'Lojas'), array('link' => '#', 'page' => 'Editar Loja'));
			$meta = array('page_title' => 'Editar Loja', 'bc' => $bc);
			$this->page_construct('lojas/editar', $this->data, $meta);
		}
	}

	function excluir($id = NULL)
	{
		if (DEMO) {
			$this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
			redirect('pos');
		}

		if ($this->input->get('id')) {
			$id = $this->input->get('id', TRUE);
		}

		if (!$this->Admin) {
			$this->session->set_flashdata('error', lang("access_denied"));
			redirect('pos');
		}

		if ($this->lojas_model->deleteLoja($id)) {
			$this->session->set_flashdata('message', lang("store_deleted"));
			redirect("lojas");
		}
	}
}
