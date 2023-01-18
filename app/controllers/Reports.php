<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $allowed_groups = ['admin', 'adm', 'estoque'];

        if (!in_array($this->data['user_group'], $allowed_groups)) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->load->library('form_validation');
        $this->load->model('reports_model');
        $this->load->model('relatorioestoque_model');
        $this->load->model('relatorioestoquelojas_model');
        $this->load->model('depositoprodutoentradas_model');
        $this->load->model('depositoproduto_model');
        $this->load->model('products_model');
        $this->load->model('lojas_model');
        $this->load->model('categories_model');
    }

    function daily_sales() {

        $this->load->helper('daterange');

        $meses = [];

        $range = daterange(date('Y-m-01', strtotime("-12 month")), date('Y-m-d'));

        foreach ($range as $day) {
            list($y, $m, $d) = explode("-", $day);
            $meses["$y-$m"] = "$m/$y";
        }

        krsort($meses);

        $this->data['meses'] = $meses;

        $mes = $this->input->get('mes');

        if (!$mes) {
            $mes = date('Y-m');
        }

        $this->data['mes'] = $mes;

        $arrLojas = $this->lojas_model->getAllCod('LOJA');

        $lojas = [];

        $rows = [];

        $sales = $this->reports_model->dailySales($mes);

        foreach ($arrLojas as $codigo) {
            foreach ($sales as $sale) {

                $rows[$sale->dia][$codigo] = [
                    'pecas' => 0,
                    'vendas' => 0,
                    'cad' => 0
                ];
            }

            $lojas[$codigo] = $codigo;
        }

        foreach ($sales as $sale) {

            if (!isset($lojas[$sale->cod_loja])) {
                continue;
            }

            $rows[$sale->dia][$sale->cod_loja]['pecas'] += $sale->pecas;
            $rows[$sale->dia][$sale->cod_loja]['vendas'] += $sale->vendas;
        }

        $customers = $this->reports_model->dailyCustomers($mes);

        foreach ($customers as $row) {

            if (!isset($lojas[$row->cod_loja])) {
                continue;
            }

            $rows[$row->dia][$row->cod_loja]['cad'] += $row->cad;
        }

        $this->data['thead'] = $lojas;

        $this->data['rows'] = $rows;

        $this->data['page_title'] = $this->lang->line("daily_sales");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('daily_sales')));
        $meta = array('page_title' => lang('daily_sales'), 'bc' => $bc);
        $this->page_construct('reports/daily', $this->data, $meta);
    }

    function monthly_sales() {
        $year = date("Y");

        $range = [
            'JANEIRO' => "$year-01",
            'FEVEREIRO' => "$year-02",
            'MARÇO' => "$year-03",
            'ABRIL' => "$year-04",
            'MAIO' => "$year-05",
            'JUNHO' => "$year-06",
            'JULHO' => "$year-07",
            'AGOSTO' => "$year-08",
            'SETEMBRO' => "$year-09",
            'OUTUBRO' => "$year-10",
            'NOVEMBRO' => "$year-11",
            'DEZEMBRO' => "$year-12",
        ];

        $arrLojas = $this->lojas_model->getAllCod('LOJA');

        $lojas = [];

        $rows = [];

        foreach ($range as $month) {

            foreach ($arrLojas as $codigo) {
                $rows[$month][$codigo] = [
                    'pecas' => 0,
                    'vendas' => 0,
                    'cad' => 0
                ];

                $lojas[$codigo] = $codigo;
            }
        }

        $sales = $this->reports_model->monthlySales();

        foreach ($sales as $sale) {

            if (!isset($lojas[$sale->cod_loja])) {
                continue;
            }

            $rows[$sale->month][$sale->cod_loja]['pecas'] += $sale->pecas;
            $rows[$sale->month][$sale->cod_loja]['vendas'] += $sale->vendas;
        }


        $customers = $this->reports_model->monthlyCustomers();

        foreach ($customers as $row) {

            if (!isset($lojas[$row->cod_loja])) {
                continue;
            }

            $rows[$row->month][$row->cod_loja]['cad'] += $row->cad;
        }

        $this->data['thead'] = $lojas;

        $this->data['range'] = array_combine(array_values($range), array_keys($range));

        $this->data['rows'] = $rows;

        $this->data['page_title'] = $this->lang->line("monthly_sales");
        $bc = array(array('link' => '#', 'page' => lang('reports')),
            array('link' => '#', 'page' => lang('monthly_sales')));
        $meta = array('page_title' => lang('monthly_sales'),
            'bc' => $bc);
        $this->page_construct('reports/monthly', $this->data, $meta);
    }

    function index() {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        if ($this->input->post('customer')) {
            $start_date = $this->input->post('start_date') ? $this->input->post('start_date') : NULL;
            $end_date = $this->input->post('end_date') ? $this->input->post('end_date') : NULL;
            $user = $this->input->post('user') ? $this->input->post('user') : NULL;
            $this->data['total_sales'] = $this->reports_model->getTotalSalesforCustomer($this->input->post('customer'), $user, $start_date, $end_date);
            $this->data['total_sales_value'] = $this->reports_model->getTotalSalesValueforCustomer($this->input->post('customer'), $user, $start_date, $end_date);
        }
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['users'] = $this->reports_model->getAllStaff();
        $this->data['page_title'] = $this->lang->line("sales_report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('sales_report')));
        $meta = array('page_title' => lang('sales_report'), 'bc' => $bc);
        $this->page_construct('reports/sales', $this->data, $meta);
    }

    function get_sales() {
        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        //$paid_by = $this->input->get('paid_by') ? $this->input->get('paid_by') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;

        $this->load->library('datatables');
        $this->datatables
                ->select("id, date, customer_name, total, total_tax, total_discount, grand_total, paid, (grand_total-paid) as balance")
                ->from('sales')
                ->unset_column('id');

        if ($customer) {
            $this->datatables->where('customer_id', $customer);
        }
        if ($user) {
            $this->datatables->where('created_by', $user);
        }
        if ($start_date) {
            $this->datatables->where('date >=', $start_date);
        }
        if ($end_date) {
            $this->datatables->where('date <=', $end_date);
        }

        echo $this->datatables->generate();
    }

    function products() {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

        $this->data['products'] = $this->reports_model->getAllProducts();
        $this->data['page_title'] = $this->lang->line("products_report");
        $this->data['page_title'] = $this->lang->line("products_report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('products_report')));
        $meta = array('page_title' => lang('products_report'), 'bc' => $bc);
        $this->page_construct('reports/products', $this->data, $meta);
    }

    function get_products() {
        $product = $this->input->get('product') ? $this->input->get('product') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        //COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity)*".$this->db->dbprefix('products').".cost, 0) as cost,
        $this->load->library('datatables');
        $this->datatables
                ->select($this->db->dbprefix('products') . ".name, " . $this->db->dbprefix('products') . ".code, COALESCE(sum(" . $this->db->dbprefix('sale_items') . ".quantity), 0) as sold, ROUND(COALESCE(((sum(" . $this->db->dbprefix('sale_items') . ".subtotal)*" . $this->db->dbprefix('products') . ".tax)/100), 0), 2) as tax, COALESCE(sum(" . $this->db->dbprefix('sale_items') . ".quantity)*" . $this->db->dbprefix('sale_items') . ".cost, 0) as cost, COALESCE(sum(" . $this->db->dbprefix('sale_items') . ".subtotal), 0) as income,
            ROUND((COALESCE(sum(" . $this->db->dbprefix('sale_items') . ".subtotal), 0)) - COALESCE(sum(" . $this->db->dbprefix('sale_items') . ".quantity)*" . $this->db->dbprefix('sale_items') . ".cost, 0) -COALESCE(((sum(" . $this->db->dbprefix('sale_items') . ".subtotal)*" . $this->db->dbprefix('products') . ".tax)/100), 0), 2)
            as profit", FALSE)
                ->from('sale_items')
                ->join('products', 'sale_items.product_id=products.id', 'left')
                ->join('sales', 'sale_items.sale_id=sales.id', 'left')
                ->group_by('products.id');

        if ($product) {
            $this->datatables->where('products.id', $product);
        }
        if ($start_date) {
            $this->datatables->where('date >=', $start_date);
        }
        if ($end_date) {
            $this->datatables->where('date <=', $end_date);
        }

        echo $this->datatables->generate();
    }

    function profit($income, $cost, $tax) {
        return floatval($income) . " - " . floatval($cost) . " - " . floatval($tax);
    }

    function top_products() {

        $this->data['topProducts'] = $this->reports_model->topProducts();
        $this->data['topProducts1'] = $this->reports_model->topProducts1();
        $this->data['topProducts3'] = $this->reports_model->topProducts3();
        $this->data['topProducts12'] = $this->reports_model->topProducts12();

        $this->data['page_title'] = $this->lang->line("top_products");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('top_products')));
        $meta = array('page_title' => lang('top_products'), 'bc' => $bc);
        $this->page_construct('reports/top', $this->data, $meta);
    }

    function registers() {

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['users'] = $this->reports_model->getAllStaff();
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('registers_report')));
        $meta = array('page_title' => lang('registers_report'), 'bc' => $bc);
        $this->page_construct('reports/registers', $this->data, $meta);
    }

    function get_register_logs() {

        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;

        $this->load->library('datatables');
        $this->datatables
                ->select("date, closed_at, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name, '<br>', " . $this->db->dbprefix('users') . ".email) as user, cash_in_hand, CONCAT(total_cc_slips, ' (', total_cc_slips_submitted, ')') as cc_slips, CONCAT(total_cheques, ' (', total_cheques_submitted, ')') as total_cheques, CONCAT(total_cash, ' (', total_cash_submitted, ')') as total_cash, note", FALSE)
                ->from("registers")
                ->join('users', 'users.id=registers.user_id', 'left');

        if ($user) {
            $this->datatables->where('registers.user_id', $user);
        }
        if ($start_date) {
            $this->datatables->where('date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        echo $this->datatables->generate();
    }

    function payments() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['users'] = $this->reports_model->getAllStaff();
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('payments_report')));
        $meta = array('page_title' => lang('payments_report'), 'bc' => $bc);
        $this->page_construct('reports/payments', $this->data, $meta);
    }

    function get_payments() {
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        $ref = $this->input->get('payment_ref') ? $this->input->get('payment_ref') : NULL;
        $sale_id = $this->input->get('sale_no') ? $this->input->get('sale_no') : NULL;
        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        $paid_by = $this->input->get('paid_by') ? $this->input->get('paid_by') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;

        $this->load->library('datatables');
        $this->datatables
                ->select($this->db->dbprefix('payments') . ".date, " . $this->db->dbprefix('payments') . ".reference as ref, " . $this->db->dbprefix('sales') . ".id as sale_no, paid_by, amount")
                ->from('payments')
                ->join('sales', 'payments.sale_id=sales.id', 'left')
                ->group_by('payments.id');

        if ($user) {
            $this->datatables->where('payments.created_by', $user);
        }
        if ($ref) {
            $this->datatables->where('payments.reference', $ref);
        }
        if ($paid_by) {
            $this->datatables->where('payments.paid_by', $paid_by);
        }
        if ($sale_id) {
            $this->datatables->where('sales.id', $sale_id);
        }
        if ($customer) {
            $this->datatables->where('sales.customer_id', $customer);
        }
        if ($customer) {
            $this->datatables->where('sales.customer_id', $customer);
        }
        if ($start_date) {
            $this->datatables->where($this->db->dbprefix('payments') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        echo $this->datatables->generate();
    }

    function alerts() {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('stock_alert');
        $bc = array(array('link' => '#', 'page' => lang('stock_alert')));
        $meta = array('page_title' => lang('stock_alert'), 'bc' => $bc);
        $this->page_construct('reports/alerts', $this->data, $meta);
    }

    function get_alerts() {

        $this->load->library('datatables');
        $this->datatables->select($this->db->dbprefix('products') . ".id as pid, " . $this->db->dbprefix('products') . ".image as image, " . $this->db->dbprefix('products') . ".code as code, " . $this->db->dbprefix('products') . ".name as pname, type, " . $this->db->dbprefix('categories') . ".name as cname, quantity, alert_quantity, tax, tax_method, cost, price", FALSE)
                ->join('categories', 'categories.id=products.category_id')
                ->from('products')
                ->where('quantity < alert_quantity', NULL, FALSE)
                ->group_by('products.id');
        $this->datatables->add_column("Actions", "<div class='text-center'><a href='#' class='btn btn-xs btn-primary ap tip' data-id='$1' title='" . lang('add_to_purcahse_order') . "'><i class='fa fa-plus'></i></a></div>", "pid");
        $this->datatables->unset_column('pid');
        echo $this->datatables->generate();
    }

    

    

    function get_relatorios_estoque() {
        
        $this->load->helper('relatorio_estoque');

        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $alert = $this->input->get('alert');

        $level = $this->input->get('estoque');

        if (!$level) {
            $level = [];
        }
        
        $foto = $this->input->get('foto');

        $rows = relatorio_estoque_data($date_start, $date_end, $alert, $level, $foto);

        header('Content-Type: application/json');

        echo json_encode($rows);
    }

    function addrelatorioestoque() {

        $this->form_validation->set_rules('obs', "Observações", 'trim|max_length[150]');

        if ($this->input->post('data_range')) {

            $arrData = explode(' - ', $this->input->post('data_range'));
            $dataI = dataDMYToYMD2($arrData[0]);
            $dataF = dataDMYToYMD2($arrData[1]);
        } else {
            $dataI = null;
            $dataF = null;
        }

        if ($this->form_validation->run() == true) {

            $data = array(
                'id_usuario' => $this->session->userdata('user_id'),
                'data' => date('Ymdhis'),
                'obs' => $this->input->post('obs'),
                'data_i' => $dataI,
                'data_f' => $dataF
            );
        }

        if ($this->form_validation->run() == true && $this->relatorioestoque_model->addRelatorio($data)) {

            $this->session->set_flashdata('message', 'Relatório de estoque solicitado com sucesso!');
        } else {
            $erro = (validation_errors()) ? validation_errors() : 'Não foi posível solicitar relatório de estoque. Por favor, tente novamente mais tarde';
            $this->session->set_flashdata('error', validation_errors($erro));
        }

        redirect("reports/estoque");
    }

    function excluirrelatorioestoque($id = NULL) {

        if ($this->input->get('id')) {
            $id = $this->input->get('id', TRUE);
        }

        if ($this->relatorioestoque_model->deleteRelatorio($id)) {
            $this->session->set_flashdata('message', "Relatório Excluido com sucesso!");
        }

        redirect("reports/estoque");
    }

    function exportarrelatorioestoque($id = NULL) {


        if ($this->input->get('id')) {
            $id = $this->input->get('id', TRUE);
        }


        $arrRelatorio = $this->relatorioestoque_model->getRelatorioById($id);
        $arrCabecalhoLojasEstoque = [];
        $arrCabecalhoLojasVendas = [];
        $arrCabecalhoDepositos = [];

        if ($arrRelatorio) {

            $arrRelatoriosEnviadosLojas = $this->relatorioestoquelojas_model->getBy("id_relatorio_estoque = {$id}", false);

            if ($arrRelatoriosEnviadosLojas) {

                $arrTE = $this->depositoprodutoentradas_model->getTotalEntradas();

                $arrTotalEntradas = [];
                foreach ($arrTE as $arr) {

                    $arrTotalEntradas[$arr->name] = $arr->qtd_total_entradas;
                }

                $arrED = $this->depositoproduto_model->getAllEstoque();

                foreach ($this->lojas_model->getAllCod('DEPOSITO') as $cod) {

                    $arrEstoqueDepositos[$cod] = [];
                }

                foreach ($arrED as $arr) {

                    $arrEstoqueDepositos[$arr->cod_loja][$arr->name] = $arr->qtd;
                }


                $arrRelatoriosLojas = [];
                foreach ($arrRelatoriosEnviadosLojas as $arr) {

                    $arrRelatoriosLojas[$arr->cod_loja] = json_decode($arr->json_envio, true);
                }

                $arrProdutos = $this->products_model->getAllProductsNames();

                foreach ($arrProdutos as $pname => $arrP) {


                    $qtdTotalEstoqueLojas = 0;
                    $qtdTotalVendasLojas = 0;

                    foreach ($arrRelatoriosLojas as $codLoja => $arr) {

                        $qtd = (isset($arr[$pname]) && isset($arr[$pname]['qtd_estoque'])) ? $arr[$pname]['qtd_estoque'] : 0;
                        $arrProdutos[$pname]['arrEstoqueLojas']['lojas'][$codLoja] = $qtd;
                        $qtdTotalEstoqueLojas += $qtd;
                        array_push($arrCabecalhoLojasEstoque, $codLoja);

                        $qtdVendas = (isset($arr[$pname]) && isset($arr[$pname]['qtd_vendas'])) ? $arr[$pname]['qtd_vendas'] : 0;
                        $arrProdutos[$pname]['arrVendasLojas']['lojas'][$codLoja] = $qtdVendas;
                        $qtdTotalVendasLojas += $qtdVendas;
                        array_push($arrCabecalhoLojasVendas, $codLoja);
                    }

                    $arrProdutos[$pname]['arrEstoqueLojas']['total'] = $qtdTotalEstoqueLojas;
                    $arrProdutos[$pname]['arrVendasLojas']['total'] = $qtdTotalVendasLojas;

                    $qtdTotalEstoqueDepositos = 0;

                    if ($arrEstoqueDepositos) {
                        foreach ($arrEstoqueDepositos as $codDeposito => $arr) {

                            $qtd = (isset($arr[$pname]) && isset($arr[$pname])) ? $arr[$pname] : 0;
                            $arrProdutos[$pname]['arrEstoqueDepositos']['depositos'][$codDeposito] = $qtd;
                            $qtdTotalEstoqueDepositos += $qtd;
                            array_push($arrCabecalhoDepositos, $codDeposito);
                        }
                    } else {
                        
                    }


                    $totalEntradas = (isset($arrTotalEntradas[$pname])) ? $arrTotalEntradas[$pname] : 0;

                    $arrProdutos[$pname]['arrEstoqueDepositos']['total'] = $qtdTotalEstoqueDepositos;
                    $arrProdutos[$pname]['totalEmEstoque'] = $qtdTotalEstoqueDepositos + $qtdTotalEstoqueLojas;
                    $arrProdutos[$pname]['cohort'] = ($qtdTotalVendasLojas) ? $arrProdutos[$pname]['totalEmEstoque'] / $qtdTotalVendasLojas : 0;
                    $arrProdutos[$pname]['percentual'] = ($arrProdutos[$pname]['totalEmEstoque']) ? $totalEntradas / $arrProdutos[$pname]['totalEmEstoque'] : 0;
                }

                $arrCabecalho = [
                    'CÓDIGO',
                    'DESCRIÇÃO',
                    'VALOR',
                    array_unique($arrCabecalhoLojasEstoque),
                    'TOTAL',
                    array_unique($arrCabecalhoDepositos),
                    'TOTAL',
                    array_unique($arrCabecalhoLojasVendas),
                    'TOTAL',
                    'COHORT',
                    '%'
                ];
            }

            $arrFinal = ['cabecalho' => $arrCabecalho, 'produtos' => $arrProdutos];

            $html = '<table>
                            <thead>
                            <tr>';

            foreach ($arrFinal['cabecalho'] as $desc) {

                if (is_array($desc)) {

                    foreach ($desc as $desc2) {

                        $html .= ' <th>' . $desc2 . '</th>';
                    }
                } else {

                    $html .= ' <th>' . $desc . '</th>';
                }
            }

            $html .= '</tr></thead><tbody>';

            foreach ($arrFinal['produtos'] as $pname => $arrProduto) {
                $html .= '<tr>
                                        <td>' . $arrProduto['name'] . '</td>
                                        <td>' . $arrProduto['descricao'] . '</td>
                                        <td>' . $arrProduto['valor'] . '</td>';

                foreach ($arrProduto['arrEstoqueLojas']['lojas'] as $codLoja => $qtd) {

                    $html .= '<td>' . $qtd . '</td>';
                }

                $html .= '<td>' . $arrProduto['arrEstoqueLojas']['total'] . '</td>';

                foreach ($arrProduto['arrEstoqueDepositos']['depositos'] as $codDeposito => $qtd) {

                    $html .= '<td>' . $qtd . '</td>';
                }

                $html .= '<td>' . $arrProduto['arrEstoqueDepositos']['total'] . '</td>';

                foreach ($arrProduto['arrVendasLojas']['lojas'] as $codLoja => $qtd) {

                    $html .= '<td>' . $qtd . '</td>';
                }

                $html .= '<td>' . $arrProduto['arrVendasLojas']['total'] . '</td>';
                $html .= '<td>' . $arrProduto['cohort'] . '</td>';
                $html .= '<td>' . $arrProduto['percentual'] * 100 . '%</td>';

                $html .= '</tr>';
            }
            $html .= '</tbody>
                        </table>';

            // header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=RelatorioEstoque-" . date('Ymdhis') . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo "\xEF\xBB\xBF"; //UTF-8 BOM

            echo $html;
            exit;
        }
    }

    function exibirrelatorioestoque($id = NULL) {


        if ($this->input->get('id')) {
            $id = $this->input->get('id', TRUE);
        }


        $arrRelatorio = $this->relatorioestoque_model->getRelatorioById($id);
        $arrCabecalhoLojasEstoque = [];
        $arrCabecalhoLojasVendas = [];
        $arrCabecalhoDepositos = [];

        if ($arrRelatorio) {

            $arrRelatoriosEnviadosLojas = $this->relatorioestoquelojas_model->getBy("id_relatorio_estoque = {$id}", false);

            if ($arrRelatoriosEnviadosLojas) {

                $arrTE = $this->depositoprodutoentradas_model->getTotalEntradas();

                $arrTotalEntradas = [];
                foreach ($arrTE as $arr) {

                    $arrTotalEntradas[$arr->name] = $arr->qtd_total_entradas;
                }

                $arrED = $this->depositoproduto_model->getAllEstoque();

                foreach ($this->lojas_model->getAllCod('DEPOSITO') as $cod) {

                    $arrEstoqueDepositos[$cod] = [];
                }

                foreach ($arrED as $arr) {

                    $arrEstoqueDepositos[$arr->cod_loja][$arr->name] = $arr->qtd;
                }


                $arrRelatoriosLojas = [];
                foreach ($arrRelatoriosEnviadosLojas as $arr) {

                    $arrRelatoriosLojas[$arr->cod_loja] = json_decode($arr->json_envio, true);
                }

                $arrProdutos = $this->products_model->getAllProductsNames();
                if ($arrProdutos) {
                    foreach ($arrProdutos as $pname => $arrP) {


                        $qtdTotalEstoqueLojas = 0;
                        $qtdTotalVendasLojas = 0;

                        foreach ($arrRelatoriosLojas as $codLoja => $arr) {

                            $qtd = (isset($arr[$pname]) && isset($arr[$pname]['qtd_estoque'])) ? $arr[$pname]['qtd_estoque'] : 0;
                            $arrProdutos[$pname]['arrEstoqueLojas']['lojas'][$codLoja] = $qtd;
                            $qtdTotalEstoqueLojas += $qtd;
                            array_push($arrCabecalhoLojasEstoque, $codLoja);

                            $qtdVendas = (isset($arr[$pname]) && isset($arr[$pname]['qtd_vendas'])) ? $arr[$pname]['qtd_vendas'] : 0;
                            $arrProdutos[$pname]['arrVendasLojas']['lojas'][$codLoja] = $qtdVendas;
                            $qtdTotalVendasLojas += $qtdVendas;
                            array_push($arrCabecalhoLojasVendas, $codLoja);
                        }

                        $arrProdutos[$pname]['arrEstoqueLojas']['total'] = $qtdTotalEstoqueLojas;
                        $arrProdutos[$pname]['arrVendasLojas']['total'] = $qtdTotalVendasLojas;

                        $qtdTotalEstoqueDepositos = 0;

                        if ($arrEstoqueDepositos) {
                            foreach ($arrEstoqueDepositos as $codDeposito => $arr) {

                                $qtd = (isset($arr[$pname]) && isset($arr[$pname])) ? $arr[$pname] : 0;
                                $arrProdutos[$pname]['arrEstoqueDepositos']['depositos'][$codDeposito] = $qtd;
                                $qtdTotalEstoqueDepositos += $qtd;
                                array_push($arrCabecalhoDepositos, $codDeposito);
                            }
                        } else {
                            
                        }


                        $totalEntradas = (isset($arrTotalEntradas[$pname])) ? $arrTotalEntradas[$pname] : 0;

                        $arrProdutos[$pname]['arrEstoqueDepositos']['total'] = $qtdTotalEstoqueDepositos;
                        $arrProdutos[$pname]['totalEmEstoque'] = $qtdTotalEstoqueDepositos + $qtdTotalEstoqueLojas;
                        $arrProdutos[$pname]['cohort'] = ($qtdTotalVendasLojas) ? $arrProdutos[$pname]['totalEmEstoque'] / $qtdTotalVendasLojas : 0;
                        $arrProdutos[$pname]['percentual'] = ($arrProdutos[$pname]['totalEmEstoque']) ? $totalEntradas / $arrProdutos[$pname]['totalEmEstoque'] : 0;
                    }
                }

                $arrCabecalho = [
                    'CÓDIGO',
                    'DESCRIÇÃO',
                    'VALOR',
                    array_unique($arrCabecalhoLojasEstoque),
                    'TOTAL',
                    array_unique($arrCabecalhoDepositos),
                    'TOTAL',
                    array_unique($arrCabecalhoLojasVendas),
                    'TOTAL',
                    'COHORT',
                    '%'
                ];
            }

            $arrFinal = ['cabecalho' => $arrCabecalho, 'produtos' => $arrProdutos];

            $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['page_title'] = 'Exibir Relatório Estoque';
            $this->data['arrProdutos'] = $arrFinal;
            $this->data['id'] = $id;
            $bc = array(array('link' => site_url('reports/estoque'), 'page' => 'Relatórios de Estoque'), array('link' => '#', 'page' => 'Exibir Relatório'));
            $meta = array('page_title' => 'Exibir Relatório Estoque', 'bc' => $bc);
            $this->page_construct('reports/exibirrelatorioestoque', $this->data, $meta);
        }
    }

    function exportarrelatorioestoque2($id = NULL) {


        if ($this->input->get('id')) {
            $id = $this->input->get('id', TRUE);
        }


        $arrRelatorio = $this->relatorioestoque_model->getRelatorioById($id);
        $arrCabecalhoLojasEstoque = [];
        $arrCabecalhoLojasVendas = [];
        $arrCabecalhoDepositos = [];

        if ($arrRelatorio) {

            $arrRelatoriosEnviadosLojas = $this->relatorioestoquelojas_model->getBy("id_relatorio_estoque = {$id}", false);

            if ($arrRelatoriosEnviadosLojas) {

                $arrTE = $this->depositoprodutoentradas_model->getTotalEntradas();

                $arrTotalEntradas = [];
                foreach ($arrTE as $arr) {

                    $arrTotalEntradas[$arr->name] = $arr->qtd_total_entradas;
                }

                $arrED = $this->depositoproduto_model->getAllEstoque();

                foreach ($this->lojas_model->getAllCod('DEPOSITO') as $cod) {

                    $arrEstoqueDepositos[$cod] = [];
                }

                foreach ($arrED as $arr) {

                    $arrEstoqueDepositos[$arr->cod_loja][$arr->name] = $arr->qtd;
                }


                $arrRelatoriosLojas = [];
                foreach ($arrRelatoriosEnviadosLojas as $arr) {

                    $arrRelatoriosLojas[$arr->cod_loja] = json_decode($arr->json_envio, true);
                }

                $arrProdutos = $this->products_model->getAllProductsNames();
                if ($arrProdutos) {
                    foreach ($arrProdutos as $pname => $arrP) {


                        $qtdTotalEstoqueLojas = 0;
                        $qtdTotalVendasLojas = 0;

                        foreach ($arrRelatoriosLojas as $codLoja => $arr) {

                            $qtd = (isset($arr[$pname]) && isset($arr[$pname]['qtd_estoque'])) ? $arr[$pname]['qtd_estoque'] : 0;
                            $arrProdutos[$pname]['arrEstoqueLojas']['lojas'][$codLoja] = $qtd;
                            $qtdTotalEstoqueLojas += $qtd;
                            array_push($arrCabecalhoLojasEstoque, $codLoja);

                            $qtdVendas = (isset($arr[$pname]) && isset($arr[$pname]['qtd_vendas'])) ? $arr[$pname]['qtd_vendas'] : 0;
                            $arrProdutos[$pname]['arrVendasLojas']['lojas'][$codLoja] = $qtdVendas;
                            $qtdTotalVendasLojas += $qtdVendas;
                            array_push($arrCabecalhoLojasVendas, $codLoja);
                        }

                        $arrProdutos[$pname]['arrEstoqueLojas']['total'] = $qtdTotalEstoqueLojas;
                        $arrProdutos[$pname]['arrVendasLojas']['total'] = $qtdTotalVendasLojas;

                        $qtdTotalEstoqueDepositos = 0;

                        if ($arrEstoqueDepositos) {
                            foreach ($arrEstoqueDepositos as $codDeposito => $arr) {

                                $qtd = (isset($arr[$pname]) && isset($arr[$pname])) ? $arr[$pname] : 0;
                                $arrProdutos[$pname]['arrEstoqueDepositos']['depositos'][$codDeposito] = $qtd;
                                $qtdTotalEstoqueDepositos += $qtd;
                                array_push($arrCabecalhoDepositos, $codDeposito);
                            }
                        } else {
                            
                        }


                        $totalEntradas = (isset($arrTotalEntradas[$pname])) ? $arrTotalEntradas[$pname] : 0;

                        $arrProdutos[$pname]['arrEstoqueDepositos']['total'] = $qtdTotalEstoqueDepositos;
                        $arrProdutos[$pname]['totalEmEstoque'] = $qtdTotalEstoqueDepositos + $qtdTotalEstoqueLojas;
                        $arrProdutos[$pname]['cohort'] = ($qtdTotalVendasLojas) ? $arrProdutos[$pname]['totalEmEstoque'] / $qtdTotalVendasLojas : 0;
                        $arrProdutos[$pname]['percentual'] = ($arrProdutos[$pname]['totalEmEstoque']) ? $totalEntradas / $arrProdutos[$pname]['totalEmEstoque'] : 0;
                    }
                }

                $arrCabecalho = [
                    'CÓDIGO',
                    'DESCRIÇÃO',
                    'VALOR',
                    array_unique($arrCabecalhoLojasEstoque),
                    'TOTAL',
                    array_unique($arrCabecalhoDepositos),
                    'TOTAL',
                    array_unique($arrCabecalhoLojasVendas),
                    'TOTAL',
                    'COHORT',
                    '%'
                ];
            }

            $arrFinal = ['cabecalho' => $arrCabecalho, 'produtos' => $arrProdutos];

            $html = '<table>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="' . count($arrFinal['cabecalho'][3]) . '">
                                        <center>Estoque das Lojas</center>
                                    </td>
                                    <td></td>
                                    <td colspan="' . count($arrFinal['cabecalho'][5]) . '">
                                        <center>Estoque dos Depósitos</center>
                                    </td>
                                    <td></td>
                                    <td colspan="' . count($arrFinal['cabecalho'][7]) . '">
                                        <center>Vendas das Lojas</center>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                <tr>
                                <tr>';

            foreach ($arrFinal['cabecalho'] as $desc) {

                if (is_array($desc)) {

                    foreach ($desc as $desc2) {

                        $html .= ' <th>' . $desc2 . '</th>';
                    }
                } else {

                    $html .= ' <th>' . $desc . '</th>';
                }
            }

            $html .= '</tr>';

            foreach ($arrFinal['produtos'] as $pname => $arrProduto) {
                $html .= '<tr>
                                        <td>' . $arrProduto['name'] . '</td>
                                        <td>' . $arrProduto['descricao'] . '</td>
                                        <td>' . $arrProduto['valor'] . '</td>';

                foreach ($arrProduto['arrEstoqueLojas']['lojas'] as $codLoja => $qtd) {

                    $html .= '<td>' . $qtd . '</td>';
                }

                $html .= '<td>' . $arrProduto['arrEstoqueLojas']['total'] . '</td>';

                foreach ($arrProduto['arrEstoqueDepositos']['depositos'] as $codDeposito => $qtd) {

                    $html .= '<td>' . $qtd . '</td>';
                }

                $html .= '<td>' . $arrProduto['arrEstoqueDepositos']['total'] . '</td>';

                foreach ($arrProduto['arrVendasLojas']['lojas'] as $codLoja => $qtd) {

                    $html .= '<td>' . $qtd . '</td>';
                }

                $html .= '<td>' . $arrProduto['arrVendasLojas']['total'] . '</td>';
                $html .= '<td>' . $arrProduto['cohort'] . '</td>';
                $html .= '<td>' . $arrProduto['percentual'] * 100 . '%</td>';

                $html .= '</tr>';
            }
            $html .= '</tbody>
                        </table>';

            require_once 'vendor/autoload.php';
            //echo "HI";
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString("<table><tr>a</tr></table>");

            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"RelatorioEstoque.xlsx\"");
            header('Cache-Control: max-age=0');
            header("Content-Transfer-Encoding: binary ");
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit();
        }
    }

}
