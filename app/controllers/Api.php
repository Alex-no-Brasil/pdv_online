<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('pos_model');
		$this->load->model('lojas_model');
		$this->load->model('transferenciaestoque_model');
		$this->load->model('products_model');
		$this->load->model('relatorioestoque_model');
		$this->load->model('relatorioestoquelojas_model');
		$this->load->model('produtosedicoes_model');
		$this->load->model('produtosedicoeslojas_model');
	}

	public function listaLojas()
	{

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						$arrLojas = $this->lojas_model->getAllLojas();
						$this->respostaWS(true, 'Sucesso', $arrLojas);
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function transferenciaEstoque()
	{
		
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						if (isset($post['arrTransferencias'])) {
							
							$msg = "";

							foreach ($post['arrTransferencias'] as $arrTransferencia) {

								if (isset($arrTransferencia['cod_produto']) && isset($arrTransferencia['cod_loja_origem']) && isset($arrTransferencia['cod_loja_destino']) && isset($arrTransferencia['qtd_atual_loja_origem']) && isset($arrTransferencia['qtd_transferir']) && isset($arrTransferencia['nome_usuario_solicitante'])) {

									$arrDest = $this->lojas_model->getLojaByCod($arrTransferencia['cod_loja_destino']);

									if ($arrDest) {

										$dados = [
											'cod_produto' => $arrTransferencia['cod_produto'],
											'cod_loja_origem' => $arrTransferencia['cod_loja_origem'],
											'cod_loja_destino' => $arrTransferencia['cod_loja_destino'],
											'qtd_atual_loja_origem' => $arrTransferencia['qtd_atual_loja_origem'],
											'qtd_transferir' => $arrTransferencia['qtd_transferir'],
											'nome_usuario_solicitante' => $arrTransferencia['nome_usuario_solicitante'],
											'data_solicitacao' => date('Ymdhis')
										];

										if ($this->transferenciaestoque_model->add($dados)) {

											$valorAtual = (float)$arrTransferencia['qtd_atual_loja_origem'] - (float)$arrTransferencia['qtd_transferir'];
											$msg .= "<br>SUCESSO! Origem: Loja {$arrTransferencia['cod_loja_origem']} &rarr; Destino: {$arrDest->tipo} {$arrTransferencia['cod_loja_destino']} - PRODUTO: {$arrTransferencia['code']} - QTD TRANSFERIDA: {$arrTransferencia['qtd_transferir']} - QTD FINAL EM ESTOQUE: {$valorAtual}";
										} else {
											$msg .= "<br>ERRO! Origem: Loja {$arrTransferencia['cod_loja_origem']} &rarr; Destino: {$arrDest->tipo} {$arrTransferencia['cod_loja_destino']} - PRODUTO: {$arrTransferencia['code']} - QTD TRANSFERIDA:" . $arrTransferencia['qtd_transferir'];
																			
										}
									} else {
										$msg .= "<br>ERRO! Destino inválido! - Origem: Loja {$arrTransferencia['cod_loja_origem']} &rarr; Destino: {$arrTransferencia['cod_loja_destino']} - PRODUTO: {$arrTransferencia['code']} - QTD TRANSFERIDA:" . $arrTransferencia['qtd_transferir'];
										
									}
								} else {
									$msg .= "<br>ERRO! Origem: Loja {$arrTransferencia['cod_loja_origem']} &rarr; Destino: {$arrTransferencia['cod_loja_destino']} - PRODUTO: {$arrTransferencia['code']} - QTD TRANSFERIDA:" . $arrTransferencia['qtd_transferir'];
								
								}
							}
							
							$this->respostaWS(true, $msg);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function excluirTransferenciaEstoque()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['id_transferencia'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						if ($this->transferenciaestoque_model->getBy('id', "id = {$post['id_transferencia']} AND cod_loja_origem = '{$post['cod_loja_origem']}' AND status = " . STATUS_TRANSFERENCIA_PENDENTE)) {

							if ($this->transferenciaestoque_model->delete($post['id_transferencia'])) {


								$this->respostaWS(true, 'Solicitação de transferência excluída com sucesso');
							} else {

								$msgErro = 'Pedido negado (Erro:008)';
								_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
								$this->respostaWS(false, $msgErro);
							}
						} else {

							$msgErro = 'Pedido negado (Erro:006)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function cancelarTransferenciaEstoque()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['id_transferencia'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {


						if ($this->transferenciaestoque_model->getBy('id', "id = {$post['id_transferencia']} AND cod_loja_origem = '{$post['cod_loja_origem']}' AND status = " . STATUS_TRANSFERENCIA_PENDENTE)) {

							if ($this->transferenciaestoque_model->edt($post['id_transferencia'], ['status' => STATUS_TRANSFERENCIA_CANCELADA])) {

								$this->respostaWS(true, 'Solicitação de transferência cancelada com sucesso');
							} else {

								$msgErro = 'Pedido negado (Erro:008)';
								_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
								$this->respostaWS(false, $msgErro);
							}
						} else {

							$msgErro = 'Pedido negado (Erro:006)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function editarQtdTransferenciaEstoque()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['id_transferencia']) && isset($post['qtd_transferir']) && isset($post['qtd_atual_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						if ($arrTransferenciaAnterior = $this->transferenciaestoque_model->getBy('*', "id = {$post['id_transferencia']} AND cod_loja_origem = '{$post['cod_loja_origem']}' AND status = " . STATUS_TRANSFERENCIA_PENDENTE)) {

							if ($this->transferenciaestoque_model->edt($post['id_transferencia'], ['qtd_atual_loja_origem' => $post['qtd_atual_loja_origem'], 'qtd_transferir' => $post['qtd_transferir']])) {

								$this->respostaWS(true, 'Solicitação de transferência editada com sucesso', $arrTransferenciaAnterior);
							} else {

								$msgErro = 'Pedido negado (Erro:008)';
								_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
								$this->respostaWS(false, $msgErro);
							}
						} else {

							$msgErro = 'Pedido negado (Erro:006)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function getRegistroTransferenciaEstoque()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['id_transferencia'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						$arrTransferencia = $this->transferenciaestoque_model->getBy('*', "id = {$post['id_transferencia']} AND cod_loja_origem = '{$post['cod_loja_origem']}'");
						if ($arrTransferencia) {

							$this->respostaWS(true, 'Registro gerado com sucesso', $arrTransferencia);
						} else {

							$msgErro = 'Pedido negado (Erro:006)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function listaTransferenciasEstoque()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['qtd_por_pg'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						if ($this->lojas_model->getLojaByCod($post['cod_loja_origem'])) {

							$ENVIADAS_arrTransferenciasPendentes = $this->transferenciaestoque_model->getTransferencias("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "cod_loja_origem = '{$post['cod_loja_origem']}' AND status = " . STATUS_TRANSFERENCIA_PENDENTE, false, 0, "data_solicitacao DESC");
							$ENVIADAS_arrTransferenciasConfirmadasCanceladas = $this->transferenciaestoque_model->getTransferencias("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "cod_loja_origem = '{$post['cod_loja_origem']}' AND status != " . STATUS_TRANSFERENCIA_PENDENTE, $post['qtd_por_pg'], 0, "data_solicitacao DESC");


							$RECEBIDAS_arrTransferenciasPendentes = $this->transferenciaestoque_model->getTransferencias("transferencia_estoque.*, tec_products.id AS id_produto, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "cod_loja_destino = '{$post['cod_loja_origem']}' AND status = " . STATUS_TRANSFERENCIA_PENDENTE, false, 0, "data_solicitacao DESC");
							$RECEBIDAS_arrTransferenciasConfirmadasCanceladas = $this->transferenciaestoque_model->getTransferencias("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "cod_loja_destino = '{$post['cod_loja_origem']}' AND status != " . STATUS_TRANSFERENCIA_PENDENTE, $post['qtd_por_pg'], 0, "data_solicitacao DESC");

							$arrTotais = [

								'enviadas' => [

									'pendentes' => count($ENVIADAS_arrTransferenciasPendentes),
									'confirmadas_canceladas' => $this->transferenciaestoque_model->getTotais("cod_loja_origem = '{$post['cod_loja_origem']}' AND status != " . STATUS_TRANSFERENCIA_PENDENTE)


								],

								'recebidas' => [
									'pendentes' => count($RECEBIDAS_arrTransferenciasPendentes),
									'confirmadas_canceladas' => $this->transferenciaestoque_model->getTotais("cod_loja_destino = '{$post['cod_loja_origem']}' AND status != " . STATUS_TRANSFERENCIA_PENDENTE)

								]

							];



							$this->respostaWS(true, 'Sucesso', ['arrTotais' => $arrTotais, 'enviadas' => ['pendentes' => $ENVIADAS_arrTransferenciasPendentes, 'outras' => $ENVIADAS_arrTransferenciasConfirmadasCanceladas], 'recebidas' => ['pendentes' => $RECEBIDAS_arrTransferenciasPendentes, 'outras' => $RECEBIDAS_arrTransferenciasConfirmadasCanceladas]]);
						} else {

							$msgErro = 'Pedido negado (Erro:006)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function listaTransferenciasEstoque_maisRecebidas()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						$sqlPesquisa = (isset($post['arrPesquisa']['pesquisa'])) ? " AND (tec_transferencia_estoque.cod_produto LIKE '%{$post['arrPesquisa']['pesquisa']}%' OR tec_products.name LIKE '%{$post['arrPesquisa']['pesquisa']}%')" : '';

						$RECEBIDAS_arrTransferenciasConfirmadasCanceladas = $this->transferenciaestoque_model->getTransferencias(
							"transferencia_estoque.*, 
								CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo",
							"cod_loja_destino = '{$post['cod_loja_origem']}' $sqlPesquisa AND status != " . STATUS_TRANSFERENCIA_PENDENTE,
							$post['arrPesquisa']['qtd_por_pg'],
							$post['arrPesquisa']['start'],
							"data_solicitacao DESC"
						);

						$this->respostaWS(true, 'Sucesso', $RECEBIDAS_arrTransferenciasConfirmadasCanceladas);
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function listaTransferenciasEstoque_maisEnviadas()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						$sqlPesquisa = (isset($post['arrPesquisa']['pesquisa'])) ? " AND (tec_transferencia_estoque.cod_produto LIKE '%{$post['arrPesquisa']['pesquisa']}%' OR tec_products.name LIKE '%{$post['arrPesquisa']['pesquisa']}%')" : '';

						$ENVIADAS_arrTransferenciasConfirmadasCanceladas = $this->transferenciaestoque_model->getTransferencias(
							"transferencia_estoque.*, 
								CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo",
							"cod_loja_origem = '{$post['cod_loja_origem']}' $sqlPesquisa AND status != " . STATUS_TRANSFERENCIA_PENDENTE,
							$post['arrPesquisa']['qtd_por_pg'],
							$post['arrPesquisa']['start'],
							"data_solicitacao DESC"
						);

						$this->respostaWS(true, 'Sucesso', $ENVIADAS_arrTransferenciasConfirmadasCanceladas);
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function getTrasferenciasEstoquePendentes()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						$sqlIds = (isset($post['arrTransferencias'])) ? "AND transferencia_estoque.id IN (" . implode(',', $post['arrTransferencias']) . ")" : "";

						$arrTransferenciasPendentes = $this->transferenciaestoque_model->getTransferencias("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "cod_loja_destino = '{$post['cod_loja_origem']}' $sqlIds AND status = " . STATUS_TRANSFERENCIA_PENDENTE, false, 0, "data_solicitacao DESC");
						$this->respostaWS(true, 'Sucesso', $arrTransferenciasPendentes);
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function confirmarTransferenciaEstoqueComErro()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['id_transferencia']) && isset($post['dadosUpdate'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						if ($arrTransf = $this->transferenciaestoque_model->getBy('*', "id = {$post['id_transferencia']} AND cod_loja_destino = '{$post['cod_loja_origem']}' AND status = " . STATUS_TRANSFERENCIA_PENDENTE)) {

							$d = new DateTime($post['dadosUpdate']['data_confirmacao']);

							$post['dadosUpdate']['obs'] = 'Transferência aprovada com ERRO. Quantidade informada: ' . $arrTransf->qtd_transferir . ". Quantidade corrigida: " . $post['dadosUpdate']['qtd_transferir'] . ". Usuário que solicitante: {$arrTransf->nome_usuario_solicitante}. Usuário que confirmou: {$post['dadosUpdate']['nome_usuario_confirmacao']} . Data da confirmação: {$d->format('d/m/Y H:i:s')}";

							if ($this->transferenciaestoque_model->edt($post['id_transferencia'], $post['dadosUpdate'])) {

								$this->respostaWS(true, 'Solicitação de transferência confirmada com sucesso!');
							} else {

								$msgErro = 'Pedido negado (Erro:008)';
								_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
								$this->respostaWS(false, $msgErro);
							}
						} else {

							$msgErro = 'Pedido negado (Erro:006)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function corrigirTransferenciaEstoqueComErro()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['arrTransferencias'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {


						foreach ($post['arrTransferencias'] as $arrTransferencia) {

							$arrTransferencia['dadosUpdate']['qtd_erro'] = null;
							$this->transferenciaestoque_model->edt($arrTransferencia['id'], $arrTransferencia['dadosUpdate']);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function getTransferenciaEstoqueComErro()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						if ($arrTransfs = $this->transferenciaestoque_model->get('*', "cod_loja_origem = '{$post['cod_loja_origem']}' AND status = " . STATUS_TRANSFERENCIA_CONFIRMADA . " AND qtd_erro IS NOT NULL")) {

							$this->respostaWS(true, 'Sucesso', $arrTransfs);
						} else {

							$this->respostaWS(false, 'Nada encontrado!');
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function getTotalTrasferenciasEstoqueRecebidasPendentes()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						$totalTransf = $this->transferenciaestoque_model->getTotalRecebidasByLoja($post['cod_loja_origem'], STATUS_TRANSFERENCIA_PENDENTE);
						$this->respostaWS(true, 'Sucesso', $totalTransf);
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function getSolicitacaoRelatorioEstoque()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						$arrRelatoriosNaoRealizados = $this->relatorioestoque_model->getRelatoriosNaoRealizadosByLoja($post['cod_loja_origem']);
						$this->respostaWS(true, 'Sucesso ', $arrRelatoriosNaoRealizados);
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function enviaRelatorioEstoque()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['id_relatorio']) && isset($post['json_envio'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						if ($this->relatorioestoque_model->getRelatorioById($post['id_relatorio'])) {

							if ($this->relatorioestoquelojas_model->getRelatorioBy("id_relatorio_estoque = {$post['id_relatorio']} AND cod_loja = '{$post['cod_loja_origem']}'") === FALSE) {

								$dados = [
									'id_relatorio_estoque' => $post['id_relatorio'],
									'cod_loja' => $post['cod_loja_origem'],
									'json_envio' => $post['json_envio']
								];

								if ($this->relatorioestoquelojas_model->addRelatorio($dados)) {

									$this->respostaWS(true, 'Relatório enviado com sucesso!');
								} else {
									$this->respostaWS(false, 'ERRO - Não foi possível enviar relatório de estoque');
								}
							} else {

								$this->respostaWS(true, 'Sucesso - Relatório já enviado');
							}
						} else {
							$msgErro = 'Pedido negado (Erro:005)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function aprovarTransferenciaEstoque()
	{

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['arrTransferencias'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						$arrUpdates = [];

						if (is_array($post['arrTransferencias'])) {
							foreach ($post['arrTransferencias'] as $arr) {

								if ($this->transferenciaestoque_model->getBy('id', "id = {$arr['arrTransferenciaPendente']['id']} AND cod_loja_destino = '{$post['cod_loja_origem']}' AND status = " . STATUS_TRANSFERENCIA_PENDENTE)) {

									if ($this->transferenciaestoque_model->edt($arr['arrTransferenciaPendente']['id'], $arr['arrDadosUpdate'])) {

										$arrUpdates[] = $arr;
									}
								}
							}

							$this->respostaWS(true, 'Solicitação de transferência confirmada com sucesso', $arrUpdates);
						} else {


							$msgErro = 'Pedido negado (Erro:005)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function getSolicitacaoEdicaoProduto()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

                        $limit = 30;
                        
                        if (isset($post['limit'])) {
                            $limit = intval($post['limit']);
                        }
                        
						$arrEdicoesNaoRealizadas = $this->produtosedicoes_model->getProdutosEdicoesNaoRealizadosByLoja($post['cod_loja_origem'], $limit);
						$this->respostaWS(true, 'Sucesso ', $arrEdicoesNaoRealizadas);
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
	}

	public function enviaConfirmacaoEdicaoProduto()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			if ($post) {

				if (isset($post['token']) && isset($post['cod_loja_origem']) && isset($post['id_produtos_edicoes'])) {

					if ($this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token'])) {

						if ($this->produtosedicoes_model->getProdutoEdicaoById($post['id_produtos_edicoes'])) {

							if ($this->produtosedicoeslojas_model->getProdutosEdicoesBy("id_produtos_edicoes = {$post['id_produtos_edicoes']} AND cod_loja = '{$post['cod_loja_origem']}'") === FALSE) {

								$dados = [
									'id_produtos_edicoes' => $post['id_produtos_edicoes'],
									'cod_loja' => $post['cod_loja_origem'],									
								];

								if ($this->produtosedicoeslojas_model->addProdutoEdicao($dados)) {

									$this->respostaWS(true, 'Produto Editado com sucesso!');
								} else {
									$this->respostaWS(false, 'ERRO - Não foi possível enviar editar Produto');
								}
							} else {

								$this->respostaWS(true, 'Sucesso - Produto já editado');
							}
						} else {
							$msgErro = 'Pedido negado (Erro:005)';
							_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
							$this->respostaWS(false, $msgErro);
						}
					} else {

						$msgErro = 'Pedido negado (Erro:001)';
						_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
						$this->respostaWS(false, $msgErro);
					}
				} else {

					$msgErro = 'Pedido negado (Erro:002)';
					_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
					$this->respostaWS(false, $msgErro);
				}
			} else {

				$this->respostaWS(false, 'Pedido negado (Erro:003)');

				$msgErro = 'Pedido negado (Erro:003)';
				_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
				$this->respostaWS(false, $msgErro);
			}
		} else {

			$msgErro = 'Pedido negado (Erro:004)';
			_logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, $msgErro);
			$this->respostaWS(false, $msgErro);
		}
    }
	
    public function syncSellers() {

        $post = $this->input->post();

        $this->auth($post);

        $this->load->model('sellers_model');

        $rows = $this->sellers_model->getByLoja($post['cod_loja_origem']);

        $dados = [];

        foreach ($rows as $row) {
            $dados[] = $row;
        }

        $this->respostaWS(true, 'Sucesso', $dados);
    }

    public function syncCardsTax() {

        $post = $this->input->post();

        $this->auth($post);

        $this->load->model('cards_model');

        $rows = $this->cards_model->getAllTax();

        $dados = [];

        foreach ($rows as $row) {
            $dados[] = $row;
        }

        $this->respostaWS(true, 'Sucesso', $dados);
    }

    public function syncSale() {

        $post = $this->input->post();

        $this->auth($post);

        if (empty($post['sale']) || empty($post['items'])) {
            $this->respostaWS(false, 'Post inválido', $post);
        }

        $this->load->model('pos_model');
        $this->load->model('sales_model');
        $this->load->model('customers_model');

        $post['sale']['cod_loja'] = $post['cod_loja_origem'];
        $post['sale']['unique_key'] = $post['cod_loja_origem'] . '-' . $post['sale']['id'];

        $post['sale']['local_id'] = $post['sale']['id'];

        $exists = $this->sales_model->getByUniqueKey($post['sale']['unique_key']);

        if ($exists) {
            
            $this->sales_model->setStatus($post['cod_loja_origem'], $post['sale']['id'], $post['sale']['status']);
            
            $this->respostaWS(true, 'Sucesso', $post['sale']['status']);
        }

        if ($exists) {
            log_message('debug', 'Duplicate unique_key ' . $post['sale']['unique_key']);

            $this->respostaWS(true, 'Sucesso', 'Já existe');
        }

        unset($post['sale']['id']);
        unset($post['sale']['sync_time']);

        foreach ($post['items'] as &$item) {
            unset($item['id']);
            $item['cod_loja'] = $post['cod_loja_origem'];
            $item['seller_id'] = $post['sale']['seller_id'];
        }

        if (!empty($post['customer']['id'])) {
            $post['customer']['local_id'] = $post['customer']['id'];
            $post['customer']['cod_loja'] = $post['cod_loja_origem'];

            unset($post['customer']['id']);

            $customer = $this->customers_model->getCustomerByLocalId($post['customer']['local_id'], $post['cod_loja_origem']);

            if ($customer) {
                $customer_id = $customer->id;
            } else {
                $customer_id = $this->customers_model->addCustomer($post['customer']);
            }

            $post['sale']['customer_id'] = $customer_id;
        }

        $save = $this->pos_model->addSale($post['sale'], $post['items']);

        if (!$save) {
            $this->respostaWS(false, 'Erro ao salvar');
        }

        $this->respostaWS(true, 'Sucesso', $save);
    }
    
    public function syncSaleCanceled() {

        $post = $this->input->post();

        $this->auth($post);

        if (empty($post['id'])) {
            $this->respostaWS(false, 'Post inválido', $post);
        }
        
        $this->load->model('sales_model');
        
        $unique_key = $post['cod_loja_origem'] . '-' . $post['id'];
        
        $sale = $this->sales_model->getByUniqueKey($unique_key);
        
        $this->respostaWS(true, 'Sucesso', $sale);
    }

    public function syncEstoque() {

        $post = $this->input->post();

        $this->auth($post);

        if (empty($post['code']) || empty($post['ean']) || !isset($post['quantity'])) {
            $this->respostaWS(false, 'Post inválido', $post);
        }

        $this->products_model->addEstoqueLoja($post['code'], $post['ean'], $post['cod_loja_origem'], $post['quantity']);

        $this->respostaWS(true, 'Sucesso', []);
    }

    public function relatorioEstoque() {

        $post = $this->input->post();

        $this->auth($post);

        $this->load->helper('relatorio_estoque');

        $foto = isset($post['foto']);
        
        $data = [];

        if ($post['item'] === 'thead') {
            $data = relatorio_estoque_thead($foto);
        }

        if ($post['item'] === 'tbody') {
            $date_start = date('Y-m-d', $post['start'] / 1000);
            $date_end = date('Y-m-d', $post['end'] / 1000);

            $data = relatorio_estoque_data($date_start, $date_end, 20, [], $foto);
        }

        $this->respostaWS(true, 'Sucesso', $data);
    }

    public function ajusteEstoque() {

        $post = $this->input->post();

        $this->auth($post);

        $this->load->model('ajusteestoque_model');

        $rows = $this->ajusteestoque_model->getForSync($post['cod_loja_origem']);

        $this->respostaWS(true, 'Sucesso', $rows);
    }
    
    public function confirmaAjusteEstoque() {

        $post = $this->input->post();

        $this->auth($post);

        if (empty($post['id'])) {
            $this->respostaWS(false, 'Post inválido', $post);
        }
        
        $this->load->model('ajusteestoque_model');

        $rows = $this->ajusteestoque_model->confirmaAjuste($post['id'], $post['cod_loja_origem']);

        $this->respostaWS(true, 'Sucesso', $rows);
    }
    
    public function alteracaoPreco() {
        
        $post = $this->input->post();

        $this->auth($post);

        $this->load->model('alteracaopreco_model');
        
        $rows = $this->alteracaopreco_model->alteracoes_loja($post['cod_loja_origem']);
        
        $this->respostaWS(true, 'Sucesso', $rows);
    }
    
    public function confirmaAlteracaoPreco() {
        
        $post = $this->input->post();

        $this->auth($post);

        $this->load->model('alteracaopreco_model');
        
        $this->alteracaopreco_model->confirma_alteracao_loja($post['cod_alteracao'], $post['cod_loja_origem']);
        
        $this->respostaWS(true, 'Sucesso', []);
    }
    
    public function onlinePedidos() {
        
        $post = $this->input->post();

        $this->auth($post);
        
        if ($post['cod_loja_origem'] !== 'ONLINE') {
            $this->respostaWS(false, 'Loja negada', $post);
        }
        
        $this->load->model('pedidoonline_model');
        
        $this->load->library('datatables');

        $pedido = $this->db->dbprefix('online_pedidos');

        $cliente = $this->db->dbprefix('online_pedido_clientes');

        $entrega = $this->db->dbprefix('online_pedido_entregas');

        $this->datatables->select("$pedido.id, $pedido.externalId, $pedido.externalCreated,"
                . "$cliente.name as cliente, $entrega.name as entrega, $pedido.totalItems, $pedido.totalAmount,"
                . "$pedido.status, $entrega.service, $pedido.origem, $pedido.sellerName, $pedido.paymentType,"
                . "$pedido.confirmaPacote,$pedido.confirmaEnvio", FALSE);

        $this->datatables->from($pedido)
                ->join($cliente, "$cliente.pedidoId=$pedido.id", 'LEFT')
                ->join($entrega, "$entrega.pedidoId=$pedido.id", 'LEFT');

        $date_start = date('Y-m-d', $this->input->post('start') / 1000);
        $date_end = date('Y-m-d', $this->input->post('end') / 1000);

        $this->datatables->where("$pedido.externalCreated >=", "$date_start 00:00:00");
        $this->datatables->where("$pedido.externalCreated <=", "$date_end 23:59:59");

        $this->datatables->add_column("Actions", "$1", "$pedido.id");

        $this->datatables->unset_column("$pedido.id");

        echo $this->datatables->generate();
    }

    public function onlinePedidoDetalhes() {
        
        $post = $this->input->post();

        $this->auth($post);
        
        if ($post['cod_loja_origem'] !== 'ONLINE') {
            $this->respostaWS(false, 'Loja negada', $post);
        }
        
        $this->load->model('pedidoonline_model');
        
        $pedidoId = $post['id'];
                
        $pedido = $items = $this->pedidoonline_model->select($pedidoId);

        $items = $this->pedidoonline_model->selectItems($pedidoId);

        $cliente = $this->pedidoonline_model->selectCliente($pedidoId);

        $entrega = $this->pedidoonline_model->selectEntrega($pedidoId);

        $dados = [
            'pedido' => $pedido,
            'items' => $items,
            'cliente' => $cliente,
            'entrega' => $entrega
        ];
        
        echo json_encode($dados);
    }
    
    public function onlineConfirmaPacote() {
        
        $post = $this->input->post();

        $this->auth($post);
        
        if ($post['cod_loja_origem'] !== 'ONLINE') {
            $this->respostaWS(false, 'Loja negada', $post);
        }
        
        $this->load->model('pedidoonline_model');
        
        $dados = ['confirmaPacote' => time()];
        
        $this->pedidoonline_model->update($post['id'], $dados);
    }
    
    private function auth($post) {

        if (empty($post)) {
            $this->respostaWS(false, 'Post vazio');
        }

        if (empty($post['token']) || empty($post['cod_loja_origem'])) {
            $this->respostaWS(false, 'Sem token ou cod_loja');
        }

        $loja = $this->lojas_model->getLojaByCodAndToken($post['cod_loja_origem'], $post['token']);

        if (!$loja) {
            $this->respostaWS(false, 'Loja não encontrada');
        }
    }

    public function transferenciaRecebidasLoja() {
        
        $post = $this->input->post();

        $this->auth($post);
        
        $start = $this->input->post('iDisplayStart');
        $length = $this->input->post('iDisplayLength');
            
        $where = "cod_loja_destino = '{$post['cod_loja_origem']}'";
        
        $search = $this->input->post('sSearch');
                
        if (strlen($search) > 1) {
            $where .= " AND cod_produto LIKE '$search%'";
        }
        
        $where2 = " $where AND status = " . STATUS_TRANSFERENCIA_PENDENTE;
            
        $rows = $this->transferenciaestoque_model->getTransferencias("transferencia_estoque.*, tec_products.name as pname, transferencia_estoque.id as tid", $where2, $length, $start, "data_solicitacao DESC");

        $total_pend = count($rows);
        
        if ($total_pend < $length) {
            
            $length = $length - $total_pend;
            
            $where3 = " $where AND status = " . STATUS_TRANSFERENCIA_CONFIRMADA;
            
            $confirmadas = $this->transferenciaestoque_model->getTransferencias("transferencia_estoque.*, tec_products.name as pname, transferencia_estoque.id as tid", $where3, $length, $start, "data_solicitacao DESC");
            
            $rows = array_merge($rows, $confirmadas);
        }
        
        $total = $this->transferenciaestoque_model->getTotais($where);
        
        $map_lojas = [];
        
        $lojas = $this->lojas_model->getAllLojas();
        
        foreach($lojas as $loja) {
            $map_lojas[$loja->cod] = $loja->nome;
        }
        
        $aaData = [];
        
        foreach ($rows as $row) {
            $aaData[] = [
                $row->data_solicitacao,
                $row->cod_produto,
                $row->pname,
                $map_lojas[$row->cod_loja_origem],
                $map_lojas[$row->cod_loja_destino],
                ($row->status == STATUS_TRANSFERENCIA_CONFIRMADA) ? $row->qtd_atual_loja_destino : "-",
                $row->qtd_transferir,
                ($row->status == STATUS_TRANSFERENCIA_CONFIRMADA) ? $row->qtd_atual_loja_destino + $row->qtd_transferir: "-",
                $row->status,
                $row->tid
            ];
        }
        
        $out = [
            'sEcho' => intval($this->input->post('sEcho')),
            'iTotalRecords' => $total,
            'iTotalDisplayRecords' => $total,
            'aaData' => $aaData
        ];

        echo json_encode($out);
    }

    public function transferenciaEnviadasLoja() {
        
        $post = $this->input->post();

        $this->auth($post);
        
        $start = $this->input->post('iDisplayStart');
        $length = $this->input->post('iDisplayLength');
            
        $where = "cod_loja_origem = '{$post['cod_loja_origem']}'";
        
        $search = $this->input->post('sSearch');
                
        if (strlen($search) > 1) {
            $where .= " AND cod_produto LIKE '$search%'";
        }
        
        $rows = $this->transferenciaestoque_model->getTransferencias("transferencia_estoque.*, tec_products.name as pname, transferencia_estoque.id as tid", $where, $length, $start, "data_solicitacao DESC");

        $total = $this->transferenciaestoque_model->getTotais($where);
        
        $map_lojas = [];
        
        $lojas = $this->lojas_model->getAllLojas();
        
        foreach($lojas as $loja) {
            $map_lojas[$loja->cod] = $loja->nome;
        }
        
        $aaData = [];
        
        foreach ($rows as $row) {
            $aaData[] = [
                $row->data_solicitacao,
                $row->cod_produto,
                $row->pname,
                $map_lojas[$row->cod_loja_origem],
                $map_lojas[$row->cod_loja_destino],
                ($row->status == STATUS_TRANSFERENCIA_CONFIRMADA) ? $row->qtd_atual_loja_origem : "-",
                $row->qtd_transferir,
                ($row->status == STATUS_TRANSFERENCIA_CONFIRMADA) ? $row->qtd_atual_loja_origem - $row->qtd_transferir: "-",
                $row->status,
                $row->tid
            ];
        }
        
        $out = [
            'sEcho' => intval($this->input->post('sEcho')),
            'iTotalRecords' => $total,
            'iTotalDisplayRecords' => $total,
            'aaData' => $aaData
        ];

        echo json_encode($out);
    }

    private function respostaWS($__success = true, $__message = null, $_dados = array()) {

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
                array(
                    'sucesso' => $__success,
                    'mensagem' => $__message,
                    'dados' => $_dados
                )
        );

        exit;
    }

}
