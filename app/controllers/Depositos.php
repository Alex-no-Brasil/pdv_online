<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Depositos extends MY_Controller
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
		$this->load->model('products_model');
		$this->load->model('depositoproduto_model');
		$this->load->model('depositoprodutoentradas_model');
	}

	function index()
	{

		$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = "Depósitos";
		$bc = array(array('link' => '#', 'page' => "Depósitos"));
		$meta = array('page_title' => "Depósitos", 'bc' => $bc);
		$this->page_construct('depositos/index', $this->data, $meta);
	}

	public function get_depositos()
	{

		$this->load->library('datatables');
		$this->datatables
			->select("id, cod, nome, obs")
			->from("lojas")
			->where("tipo = 'DEPOSITO'")
			->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='" . site_url('depositos/editar/$1') . "' class='tip btn btn-warning btn-xs' title='Editar Depósito'><i class='fa fa-edit'></i></a</div></div>", "id")
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
				'tipo' => 'DEPOSITO'
			);
		}

		if ($this->form_validation->run() == true && $cid = $this->lojas_model->addLoja($data)) {

			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status' => 'success', 'msg' =>  'Depósito Adicionado com sucesso!', 'id' => $cid, 'val' => $data['cod']));
				die();
			}

			$this->session->set_flashdata('message', 'Depósito Adicionado com sucesso');
			redirect("depositos");
		} else {

			if ($this->input->is_ajax_request()) {
				echo json_encode(array('status' => 'failed', 'msg' => validation_errors()));
				die();
			}

			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			$this->data['page_title'] = 'Adicionar Depósito';
			$bc = array(array('link' => site_url('depositos'), 'page' => 'Depósitos'), array('link' => '#', 'page' => 'Adicionar Depósito'));
			$meta = array('page_title' => 'Adicionar Depósito', 'bc' => $bc);
			$this->page_construct('depositos/adicionar', $this->data, $meta);
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

			$arr = $this->lojas_model->getLojaBy("id != $id AND cod = '" . $this->input->post('cod') . "' AND tipo = 'DEPOSITO'");

			if ($arr === FALSE) {

				if ($this->lojas_model->updateLoja($id, $data)) {

					$this->session->set_flashdata('message', 'Depósito Editado com sucesso!');
					redirect("depositos");
				} else {

					$this->data['deposito'] = $this->lojas_model->getLojaById($id);
					$this->data['error'] = 'Não foi possível';
					$this->data['page_title'] = 'Editar Depósito';
					$bc = array(array('link' => site_url('depositos'), 'page' => 'Depósitos'), array('link' => '#', 'page' => 'Editar Depósito'));
					$meta = array('page_title' => 'Editar Depósito', 'bc' => $bc);
					$this->page_construct('depositos/editar', $this->data, $meta);
				}
			} else {

				$this->data['deposito'] = $this->lojas_model->getLojaById($id);
				$this->data['error'] = 'Código já existente';
				$this->data['page_title'] = 'Editar Depósito';
				$bc = array(array('link' => site_url('depositos'), 'page' => 'Depósitos'), array('link' => '#', 'page' => 'Editar Depósito'));
				$meta = array('page_title' => 'Editar Depósito', 'bc' => $bc);
				$this->page_construct('depositos/editar', $this->data, $meta);
			}
		} else {

			$this->data['deposito'] = $this->lojas_model->getLojaById($id);
			$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			$this->data['page_title'] = 'Editar Depósito';
			$bc = array(array('link' => site_url('depositos'), 'page' => 'Depósitos'), array('link' => '#', 'page' => 'Editar Depósito'));
			$meta = array('page_title' => 'Editar Depósito', 'bc' => $bc);
			$this->page_construct('depositos/editar', $this->data, $meta);
		}
	}

	function excluir($id = NULL)
	{

		if ($this->input->get('id')) {
			$id = $this->input->get('id', TRUE);
		}

		if (!$this->Admin) {
			$this->session->set_flashdata('error', lang("access_denied"));
			redirect('pos');
		}

		if ($this->lojas_model->deleteLoja($id)) {
			$this->session->set_flashdata('message', 'Depósito Excluído com sucesso!');
			redirect("depositos");
		}
	}

	function estoque($is = NULL)
	{
		if (!$this->Admin) {
			$this->session->set_flashdata('error', $this->lang->line('access_denied'));
			redirect('pos');
		}

		if ($this->input->get('id')) {
			$id = $this->input->get('id', TRUE);



			$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			$this->data['page_title'] = "Depósitos";
			$bc = array(array('link' => '#', 'page' => "Depósitos"));
			$meta = array('page_title' => "Depósitos", 'bc' => $bc);
			$this->page_construct('depositos/estoque', $this->data, $meta);
		} else {
			redirect('depositos');
		}
	}

	public function get_estoque_depositos()
	{

		$arrEstoques = $this->depositoproduto_model->getEstoqueByCod($this->input->post('cod_produto'));
		$arrDepositos = $this->lojas_model->getAllLojasDepositos();

		foreach ($arrEstoques as $arrE) {

			$arrDepositos[$arrE->cod]->produto = $arrE;
		}

		echo json_encode($arrDepositos);
	}

	public function edtestoque()
	{

		if ($this->input->post()) {

			$msg = "";
			foreach ($this->input->post() as $strestoque => $estoque) {
				
				if ($strestoque != '0') {

					$arrEstoque = explode("-", $strestoque);

					$dados = [
						'cod_loja' =>  str_replace("++", " ", $arrEstoque[1]),
						'cod_produto' => $arrEstoque[2],
						'qtd' =>$estoque

					];

					

					if ($this->depositoproduto_model->updateEstoqueDeposito($dados)) {

						$msg .= "<br>SUCESSO!  DEPÓSITO {$dados['cod_loja']} - PRODUTO: {$dados['cod_produto']} - QTD: {$dados['qtd']}";
					} else {

						$msg .= "<br>ERRO!  DEPÓSITO {$dados['cod_loja']} - PRODUTO: {$dados['cod_produto']} - QTD: {$dados['qtd']}";
					}
				}
			}

			$this->session->set_flashdata('message', "Verifique abaixo se o estoque foi alterado com sucesso: $msg");
			redirect('products');
		} else {

			$this->session->set_flashdata('message', "Estoque alterado com sucesso!");
			redirect('products');
		}
	}


	function getDepositos()
	{

		if (!$this->Admin  && $this->data['user_group'] !== 'adm') {
			$this->session->set_flashdata('error', lang('access_denied'));
			redirect('pos');
		}

		$arrDep = $this->lojas_model->getAllLojasDepositos();
		echo json_encode($arrDep);
	}

	function entradaestoque()
	{

		if (!$this->Admin) {
			$this->session->set_flashdata('error', lang('access_denied'));
			redirect('pos');
		}


		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();
			$erro = false;
			$arrErros = "";

			foreach ($post['arrProdutos'] as $arrProduto) {

				$arrProtudoEntrar = $this->products_model->getProductByCode($arrProduto['cod_produto']);

				if ($arrProtudoEntrar) {

					if ($this->lojas_model->getLojaBy("cod = {$post['cod_deposito_destino']} AND tipo = 'DEPOSITO'")) {

						$arrEstoqueDeposito = $this->depositoproduto_model->getBy("cod_produto = '{$arrProduto['cod_produto']}' AND cod_loja = '{$post['cod_deposito_destino']}'");
						$qtdAtualDeposito = ($arrEstoqueDeposito) ? $arrEstoqueDeposito->qtd : 0;
						$qtdFinalDeposito = $arrProduto['qtd_entrar'] + $qtdAtualDeposito;

						$dados = [
							'cod_loja' =>  $post['cod_deposito_destino'],
							'cod_produto' => $arrProduto['cod_produto'],
							'qtd' => $qtdFinalDeposito

						];

						$dadosAdd =  [
							'cod_loja' =>  $post['cod_deposito_destino'],
							'cod_produto' => $arrProduto['cod_produto'],
							'qtd' => $qtdFinalDeposito,
							'data' => isset($post['data']) ? $post['data'] : date('Ymd')

						];

						if ($this->depositoproduto_model->updateEstoqueDeposito($dados) && $this->depositoprodutoentradas_model->add($dadosAdd)) {

							$arrErros .= "&bull;<b>SUCESSO!</b>PRODUTO: {$arrProduto['cod_produto']} -  Entrada realizada com sucesso!.<br>";
						} else {
							$erro = true;
							$arrErros .= "&bull; <font color='red'>&bull; <b>ERRO!</b>PRODUTO: {$arrProduto['cod_produto']} -  Não foi possivel realizar a entrada desse produto. Tente novamente mais tarde.</font><br>";
						}
					} else {

						$erro = true;
						$arrErros .= "&bull; <font color='red'>&bull; <b>ERRO!</b>PRODUTO: {$arrProduto['cod_produto']} - Depósito inválido ou não existente ({$post['cod_deposito_destino']})</font><br>";
					}
				} else {

					$erro = true;
					$arrErros .= "&bull; <font color='red'>&bull; <b>ERRO!</b>PRODUTO: {$arrProduto['cod_produto']} - Produto inválido ou não existente</font><br>";
				}
			}

			if (!$erro) {
				$this->session->set_flashdata('message', $arrErros);
				redirect('depositos/entradas');
			} else {

				$this->session->set_flashdata('error', $arrErros);
				redirect('depositos/entradas');
			}
		}
	}

	function entradas()
	{

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = 'Entradas nos Depósitos';
		$bc = array(array('link' => site_url('depositos'), 'page' => 'Depósitos'), array('link' => '#', 'page' => 'Entradas'));
		$meta = array('page_title' => 'Entradas', 'bc' => $bc);
		$this->page_construct('depositos/entradas', $this->data, $meta);
	}

	public function get_entradas()
	{

		$this->load->library('datatables');
		$this->datatables
			->select("id, data, cod_loja, cod_produto, qtd")
			->from("deposito_produto_entradas")
			->add_column("Actions", "<div class='text-center'><a href='" . site_url('depositos/excluirentrada/$1') . "' onClick=\"return confirm('Deseja realmente excluir essa entrada?. Clique em OK para confirmar')\" class='tip btn btn-danger btn-xs' title='Excluir Depósito'><i class='fa fa-trash-o'></i></a></div></div>", "id")
			->unset_column('id');

		echo $this->datatables->generate();
	}

	function excluirentrada($id = NULL)
	{

		if ($this->input->get('id')) {
			$id = $this->input->get('id', TRUE);
		}

		if (!$this->Admin) {
			$this->session->set_flashdata('error', lang("access_denied"));
			redirect('pos');
		}

		$arrEntrada = $this->depositoprodutoentradas_model->getById($id);

		if ($arrEntrada) {

			$qtd_recebida = $arrEntrada->qtd;
			$cod_loja = $arrEntrada->cod_loja;
			$cod_produto = $arrEntrada->cod_produto;

			$arrProduto = $this->depositoproduto_model->getBy("cod_loja ={$cod_loja} AND cod_produto = {$cod_produto}");

			if ($arrProduto) {

				if ($this->depositoprodutoentradas_model->delete($id)) {


					$dados = [
						'cod_loja' =>  $cod_loja,
						'cod_produto' => $cod_produto,
						'qtd' => $arrProduto->qtd - $qtd_recebida

					];

					if ($this->depositoproduto_model->updateEstoqueDeposito($dados)) {

						$this->session->set_flashdata('message', 'Entrada Excluída com sucesso!');
						redirect("depositos/entradas");
					}
				}
			}
		}
	}
}
