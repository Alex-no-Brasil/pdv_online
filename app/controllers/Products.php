<?php defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
{

    function __construct()
    {
        parent::__construct();


        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('products_model');
        $this->load->model('lojas_model');
        $this->load->model('transferenciaestoque_model');
        $this->load->model('relatorioestoque_model');
        $this->load->model('relatorioestoquelojas_model');
        $this->load->model('depositoproduto_model');
    }

    function index()
    {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('products');

        $bc = array(array('link' => '#', 'page' => lang('products')));
        $meta = array('page_title' => lang('products'), 'bc' => $bc);
        $this->page_construct('products/index', $this->data, $meta);
    }

    function get_products()
    {

        $this->load->library('datatables');
        
        $prod = $this->db->dbprefix('products');
        $cat = $this->db->dbprefix('categories');
        
        $this->datatables->select("$prod.id, $prod.image, $prod.code, $prod.name, $prod.ean, "
                    . "$cat.name as cname, model, quantity, price, barcode_symbology", FALSE);

        $this->datatables->from($prod)
            ->join($cat, "$cat.id=$prod.category_id", 'LEFT')
            ->edit_column('quantity', '$1', "getEstoquesDepositos($prod.code)")
            ->group_by("$prod.id");

        $this->datatables->add_column("Actions", "<div class='text-center'>"
                . "<div class='btn-group actions'>"
                . "<a href='" . site_url('products/view/$1') . "' title='" . lang("view") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'>"
                .   "<i class='fa fa-file-text-o'></i>"
                . "</a>"
                /*
                . "<a onclick=\"window.open('" . site_url('products/single_barcode/$1') . "', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;\" href='#' title='" . lang('print_barcodes') . "' class='tip btn btn-default btn-xs'>"
                .   "<i class='fa fa-print'></i>"
                . "</a>"
                . "<a onclick=\"window.open('" . site_url('products/single_label/$1') . "', 'pos_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;\" href='#' title='" . lang('print_labels') . "' class='tip btn btn-default btn-xs'><i class='fa fa-print'>"
                .   "</i>"
                . "</a>"
                . "<a id='$4 ($3)' href='" . site_url('products/gen_barcode/$3/$5') . "' title='" . lang("view_barcode") . "' class='barcode tip btn btn-primary btn-xs'>"
                .   "<i class='fa fa-barcode'></i>"
                . "</a>"
                . "<a class='tip image btn btn-primary btn-xs' id='$4 ($3)' href='" . base_url('uploads/$2') . "' title='" . lang("view_image") . "'>"
                .   "<i class='fa fa-picture-o'></i>"
                . "</a> "
                 */
                . "<a href='" . site_url('products/edit/$1') . "' title='" . lang("edit_product") . "' class='tip btn btn-warning btn-xs'>"
                .   "<i class='fa fa-edit'></i>"
                . "</a>"
                . "<a href='" . site_url('products/delete/$1') . "' onClick=\"return confirm('" . lang('alert_x_product') . "')\" title='" . lang("delete_product") . "' class='tip btn btn-danger btn-xs'>"
                .   "<i class='fa fa-trash-o'></i>"
                . "</a>"
                . "<a onclick=\"getEstoqueProduto('$3', '$4')\" href='#' title='Ver Estoque nos Depósitos' class='tip btn btn-default btn-xs'>"
                .   "<i class='fa fa-building'></i>"
                . "</a>"
                . "</div>"
                . "</div>", "$prod.id, $prod.image, $prod.code, $prod.name, quantity");

        $this->datatables->unset_column("$prod.id")->unset_column('barcode_symbology');
        echo $this->datatables->generate();
    }

    function view($id = NULL)
    {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $product = $this->site->getProductByID($id);
        $this->data['product'] = $product;
        $this->data['category'] = $this->site->getCategoryByID($product->category_id);
        $this->data['combo_items'] = $product->type == 'combo' ? $this->products_model->getComboItemsByPID($id) : NULL;
        $this->load->view($this->theme . 'products/view', $this->data);
    }

    function barcode($product_code = NULL)
    {
        if ($this->input->get('code')) {
            $product_code = $this->input->get('code');
        }

        $data['product_details'] = $this->products_model->getProductByCode($product_code);
        $data['img'] = "<img src='" . base_url() . "index.php?products/gen_barcode&code={$product_code}' alt='{$product_code}' />";
        $this->load->view('barcode', $data);
    }

    function product_barcode($product_code = NULL, $bcs = 'code39', $height = 60)
    {
        if ($this->input->get('code')) {
            $product_code = $this->input->get('code');
        }
        return "<img src='" . base_url() . "products/gen_barcode/{$product_code}/{$bcs}/{$height}' alt='{$product_code}' />";
    }

    function gen_barcode($product_code = NULL, $bcs = 'code39', $height = 60, $text = 1)
    {
        $drawText = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $product_code, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $imageResource = Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        return $imageResource;
    }


    function print_barcodes()
    {
        $this->load->library('pagination');

        $per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $config['base_url'] = site_url('products/print_barcodes');
        $config['total_rows'] = $this->products_model->products_count();
        $config['per_page'] = 16;
        $config['num_links'] = 5;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);

        $products = $this->products_model->fetch_products($config['per_page'], $per_page);
        $r = 1;
        $html = "";
        $html .= '<table class="table table-bordered">
        <tbody><tr>';
        foreach ($products as $pr) {
            if ($r != 1) {
                $rw = (bool)($r & 1);
                $html .= $rw ? '</tr><tr>' : '';
            }
            $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 60) . '<br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $pr->price . '</span></td>';
            $r++;
        }
        $html .= '</tr></tbody>
        </table>';

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme . 'products/print_barcodes', $this->data);
    }

    function print_labels()
    {
        $this->load->library('pagination');

        $per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $config['base_url'] = site_url('products/print_labels');
        $config['total_rows'] = $this->products_model->products_count();
        $config['per_page'] = 10;
        $config['num_links'] = 5;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);

        $products = $this->products_model->fetch_products($config['per_page'], $per_page);

        $html = "";

        foreach ($products as $pr) {
            $html .= '<div class="labels"><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 25) . '<br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $pr->price . '</span></div>';
        }

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_labels");
        $this->load->view($this->theme . 'products/print_labels', $this->data);
    }

    function single_barcode($product_id = NULL)
    {

        $product = $this->site->getProductByID($product_id);

        $html = "";
        $html .= '<table class="table table-bordered">
        <tbody><tr>';
        if ($product->quantity > 0) {
            for ($r = 1; $r <= $product->quantity; $r++) {
                if ($r != 1) {
                    $rw = (bool)($r & 1);
                    $html .= $rw ? '</tr><tr>' : '';
                }
                $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 60) . ' <br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $product->price . '</span></td>';
            }
        } else {
            for ($r = 1; $r <= 16; $r++) {
                if ($r != 1) {
                    $rw = (bool)($r & 1);
                    $html .= $rw ? '</tr><tr>' : '';
                }
                $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 60) . ' <br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $product->price . '</span></td>';
            }
        }
        $html .= '</tr></tbody>
        </table>';

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme . 'products/single_barcode', $this->data);
    }

    function single_label($product_id = NULL, $warehouse_id = NULL)
    {

        $product = $this->site->getProductByID($product_id);
        $html = "";
        if ($product->quantity > 0) {
            for ($r = 1; $r <= $product->quantity; $r++) {
                $html .= '<div class="labels"><strong>' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 25) . ' <br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $product->price . '</span></div>';
            }
        } else {
            for ($r = 1; $r <= 10; $r++) {
                $html .= '<div class="labels"><strong>' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 25) . ' <br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $product->price . '</span></div>';
            }
        }
        $this->data['html'] = $html;
        $this->data['page_title'] = lang("barcode_label");
        $this->load->view($this->theme . 'products/single_label', $this->data);
    }


    function add()
    {
        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->form_validation->set_rules('ean', lang("product_code"), 'trim|is_unique[products.ean]|min_length[2]|max_length[50]|required|alpha_numeric');
        $this->form_validation->set_rules('name', lang("product_name"), 'required');
        $this->form_validation->set_rules('category', lang("category"), 'required');
        
        $this->form_validation->set_rules('model', lang("Modelo"), 'required');
        $this->form_validation->set_rules('season', lang("Estação"), 'required');
        
        $this->form_validation->set_rules('price', lang("product_price"), 'required|is_numeric');
        $this->form_validation->set_rules('cost', lang("product_cost"), 'required|is_numeric');
        

        if ($this->form_validation->run() == true) {

            if ($this->duplicated("0")) {
                redirect('products');
            }

            $data = array(
                'type' => $this->input->post('type'),
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'ean' => $this->input->post('ean'),
                'ncm' => $this->input->post('ncm'),
                'category_id' => $this->input->post('category'),
                
                'model' => $this->input->post('model'),
                'material' => $this->input->post('material'),
                'stamp' => $this->input->post('stamp'),
                'manga' => $this->input->post('manga'),
                'season' => $this->input->post('season'),
                
                'price' => $this->input->post('price'),
                'cost' => $this->input->post('cost')
            );
            
            $dataUpdateLojas = $data;
            
            unset($dataUpdateLojas['quantity']);
            
            if ($this->input->post('type') == 'combo') {
                $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity'][$r])) {
                        $items[] = array(
                            'item_code' => $_POST['combo_item_code'][$r],
                            'quantity' => $_POST['combo_item_quantity'][$r]
                        );
                    }
                }
            } else {
                $items = array();
            }
            
            $quantity = 0;
            
            $variants = [];
            
            $vars = $this->input->post('variants');
            
            if ($vars) {
                foreach ($vars as $prop => $rows) {
                    foreach($rows as $i => $val) {
                        $variants[$i][$prop] = $val;
                        
                        if ($prop === 'quantity') {
                            $quantity += intval($val);
                        }
                    }
                }
            }
            
            $data['quantity'] = $quantity;

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] =  array('gif', 'png', 'jpg', 'jpeg');
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/add", 'refresh');
                }

                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $dataUpdateLojas['image'] = $photo;

                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/' . $photo;
                $config['new_image'] = 'uploads/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 110;
                $config['height'] = 110;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    $this->session->set_flashdata('error', $this->image_lib->display_errors());
                    redirect("products/add");
                }
            }
            // $this->tec->print_arrays($data, $items);
        }
        
        if ($this->form_validation->run() == true && $id = $this->products_model->addProduct($data, $items)) {
            adicionaProdutos($id, $dataUpdateLojas);
            $this->products_model->addVariants($id, $variants);
            $this->session->set_flashdata('message', lang("product_added"));
            redirect('products');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['page_title'] = lang('add_product');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('add_product')));
            $meta = array('page_title' => lang('add_product'), 'bc' => $bc);
            $this->page_construct('products/add', $this->data, $meta);
        }
    }

    function edit($id = NULL)
    {               
        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->site->getProductByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("product_code"), 'is_unique[products.code]');
        }
        $this->form_validation->set_rules('ean', lang("product_code"), 'trim|min_length[2]|max_length[50]|required|alpha_numeric');
        $this->form_validation->set_rules('name', lang("product_name"), 'required');
        $this->form_validation->set_rules('category', lang("category"), 'required');
        
        $this->form_validation->set_rules('model', lang("Modelo"), 'required');
        $this->form_validation->set_rules('season', lang("Estação"), 'required');
        
        $this->form_validation->set_rules('price', lang("product_price"), 'required|is_numeric');
        $this->form_validation->set_rules('cost', lang("product_cost"), 'required|is_numeric');

        if ($this->form_validation->run() == true) {

            if ($this->duplicated($id)) {
                redirect('products');
            }

            $data = array(
                'type' => $this->input->post('type'),
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'ean' => $this->input->post('ean'),
                'ncm' => $this->input->post('ncm'),
                'category_id' => $this->input->post('category'),
                
                'model' => $this->input->post('model'),
                'material' => $this->input->post('material'),
                'stamp' => $this->input->post('stamp'),
                    
                'manga' => $this->input->post('manga'),
                'season' => $this->input->post('season'),
                
                'price' => $this->input->post('price'),
                'cost' => $this->input->post('cost'),
            );
            
            $dataUpdateLojas = $data;
            
            unset($dataUpdateLojas['quantity']);

            if ($this->input->post('type') == 'combo') {
                $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity'][$r])) {
                        $items[] = array(
                            'item_code' => $_POST['combo_item_code'][$r],
                            'quantity' => $_POST['combo_item_quantity'][$r]
                        );
                    }
                }
            } else {
                $items = array();
            }

            $quantity = 0;
            
            $variants = [];
            
            $vars = $this->input->post('variants');
            
            if ($vars) {
                foreach ($vars as $prop => $rows) {
                    foreach($rows as $i => $val) {
                        $variants[$i][$prop] = $val;
                        
                        if ($prop === 'quantity') {
                            $quantity += intval($val);
                        }
                    }
                }
            }
            
            $data['quantity'] = $quantity;
                    
            if ($_FILES['userfile']['size'] > 0) {
                
                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = array('gif', 'png', 'jpg', 'jpeg');
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);               
                
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/edit/" . $id);
                }

                $photo = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/' . $photo;
                $config['new_image'] = 'uploads/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 110;
                $config['height'] = 110;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    $this->session->set_flashdata('error', $this->image_lib->display_errors());
                    redirect("products/edit/" . $id);
                }
            } else {
                $photo = NULL;
            }
            
            $dataUpdateLojas['image'] = $pr_details->image;
        }

        if ($this->form_validation->run() == true && $this->products_model->updateProduct($id, $data, $items, $photo)) {
            editaProdutos($id, $dataUpdateLojas);
            
            $this->products_model->addVariants($id, $variants);
            
            $this->session->set_flashdata('message', lang("product_updated"));
            redirect("products");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $product = $this->site->getProductByID($id);
            if ($product->type == 'combo') {
                $combo_items = $this->products_model->getComboItemsByPID($id);
                foreach ($combo_items as $combo_item) {
                    $cpr = $this->site->getProductByID($combo_item->id);
                    $cpr->qty = $combo_item->qty;
                    $items[] = array('id' => $cpr->id, 'row' => $cpr);
                }
                $this->data['items'] = $items;
            }
            $this->data['product'] = $product;
            
            $this->data['categories'] = $this->site->getAllCategories();
            
            $this->data['variants'] = $this->products_model->getVariants($id);
            
            $this->data['page_title'] = lang('edit_product');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_product')));
            $meta = array('page_title' => lang('edit_product'), 'bc' => $bc);
            $this->page_construct('products/edit', $this->data, $meta);
        }
    }

    function duplicated($id) {

        $code = $this->input->post('code');
        $ean = $this->input->post('ean');

        $prod = $this->products_model->getByCode($code);

        if ($prod && $prod->id !== $id) {
            $this->session->set_flashdata('error', "Código do produto duplicado.");
            return true;
        }

        $prod = $this->products_model->getByEan($ean);

        if ($prod && $prod->id !== $id) {
            $this->session->set_flashdata('error', "Código de barras duplicado.");
            return true;
        }

        return false;
    }

    function import()
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect('pos');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '500';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/import");
                }


                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("uploads/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                array_shift($arrResult);

                //$keys = array('id', 'code', 'name', 'cost', 'tax', 'price', 'category');

                $keys = [
                    'id',
                    'code',
                    'name',
                    'category_code',
                    'price',
                    'image',
                    'tax',
                    'cost',
                    'tax_method',
                    'quantity',
                    'barcode_symbology',
                    'type',
                    'details',
                    'alert_quantity',
                ];

                $final = array();
                $erroFormato = false;
                foreach ($arrResult as $key => $value) {
                    if (count($keys) == count($value)) {

                        $final[] = array_combine($keys, $value);
                    } else {

                        $erroFormato = true;
                        break;
                    }
                }

                //var_export(sizeof($final));exit;

                if (sizeof($final) > 1500) {
                    $this->session->set_flashdata('error', lang("more_than_allowed"));
                    redirect("products/import");
                }

                $arrCodes = [];

                if (!$erroFormato) {
                    foreach ($final as $csv_pr) {

                        if (in_array($csv_pr['code'], $arrCodes)) {
                            $this->session->set_flashdata('error', 'Código repetido: ' . $csv_pr['code']);
                            redirect("products/import");
                            break;
                        } else {
                            array_push($arrCodes, $csv_pr['code']);
                        }

                        $csv_pr['id'] = (int)$csv_pr['id'];
                        $csv_pr['price'] = (float)$csv_pr['price'];
                        $csv_pr['category_code'] = (strlen($csv_pr['category_code']) == 1) ? '0' . $csv_pr['category_code'] : $csv_pr['category_code'];
                        $csv_pr['image'] = ($csv_pr['image']) ? $csv_pr['image'] : 'no_image.png';
                        $csv_pr['tax'] = ($csv_pr['tax']) ?  (float)$csv_pr['tax'] : 0;
                        $csv_pr['cost'] = ($csv_pr['cost']) ? (float)$csv_pr['cost'] : 0;
                        $csv_pr['tax_method'] = ($csv_pr['tax_method']) ? (int)$csv_pr['tax_method'] : 1;
                        $csv_pr['quantity'] = ($csv_pr['quantity']) ? (float)$csv_pr['quantity'] : 0;
                        $csv_pr['barcode_symbology'] = ($csv_pr['barcode_symbology']) ? $csv_pr['barcode_symbology'] : 'ean13';
                        $csv_pr['type'] = ($csv_pr['type']) ? $csv_pr['type'] : 'standard';
                        $csv_pr['details'] = ($csv_pr['details']) ?  $csv_pr['details'] : NULL;
                        $csv_pr['alert_quantity'] = ($csv_pr['alert_quantity']) ?  (float)$csv_pr['alert_quantity'] : 0.00;

                        if ($this->products_model->getProductByCode($csv_pr['code'])) {
                            $this->session->set_flashdata('error', 'CÓDIGO JÁ CADASTRADO');
                            redirect("products/import");
                        }
                        if (!is_numeric($csv_pr['tax']) && $csv_pr['tax'] != NULL) {

                            $this->session->set_flashdata('error', lang("check_product_tax") . " (" . $csv_pr['tax'] . "). " . lang("tax_not_numeric"));
                            redirect("products/import");
                        }


                        if (!($category = $this->site->getCategoryByCode($csv_pr['category_code']))) {
                            $this->session->set_flashdata('error', 'CATEGORIA NÃO ENCONTRADA');
                            redirect("products/import");
                        }


                        $data[] = [
                            'id' => $csv_pr['id'],
                            'code' => $csv_pr['code'],
                            'name' => $csv_pr['name'],
                            'category_id' => $category->id,
                            'price' => $csv_pr['price'],
                            'image' => $csv_pr['image'],
                            'tax' => $csv_pr['tax'],
                            'cost' => $csv_pr['cost'],
                            'tax_method' => $csv_pr['tax_method'],
                            'quantity' => $csv_pr['quantity'],
                            'barcode_symbology' => $csv_pr['barcode_symbology'],
                            'type' => $csv_pr['type'],
                            'details' => $csv_pr['details'],
                            'alert_quantity' => $csv_pr['alert_quantity'],

                        ];
                    }
                } else {

                    $this->session->set_flashdata('error', 'Formato inválido');
                    redirect("products/import");
                }
            }
        }

        if ($this->form_validation->run() == true && $this->products_model->add_products($data)) {

            $this->session->set_flashdata('message', lang("products_added"));
            redirect('products');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['page_title'] = lang('import_products');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('import_products')));
            $meta = array('page_title' => lang('import_products'), 'bc' => $bc);
            $this->page_construct('products/import', $this->data, $meta);
        }
    }


    function import_original()
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect('pos');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '500';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/import");
                }


                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("uploads/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                array_shift($arrResult);

                $keys = array('code', 'name', 'cost', 'tax', 'price', 'category');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                if (sizeof($final) > 1001) {
                    $this->session->set_flashdata('error', lang("more_than_allowed"));
                    redirect("products/import");
                }

                foreach ($final as $csv_pr) {
                    if ($this->products_model->getProductByCode($csv_pr['code'])) {
                        $this->session->set_flashdata('error', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("code_already_exist"));
                        redirect("products/import");
                    }
                    if (!is_numeric($csv_pr['tax'])) {
                        $this->session->set_flashdata('error', lang("check_product_tax") . " (" . $csv_pr['tax'] . "). " . lang("tax_not_numeric"));
                        redirect("products/import");
                    }
                    if (!($category = $this->site->getCategoryByCode($csv_pr['category']))) {
                        $this->session->set_flashdata('error', lang("check_category") . " (" . $csv_pr['category'] . "). " . lang("category_x_exist"));
                        redirect("products/import");
                    }
                    $data[] = array(
                        'type' => 'standard',
                        'code' => $csv_pr['code'],
                        'name' => $csv_pr['name'],
                        'cost' => $csv_pr['cost'],
                        'tax' => $csv_pr['tax'],
                        'price' => $csv_pr['price'],
                        'category_id' => $category->id
                    );
                }
                //print_r($data); die();
            }
        }

        if ($this->form_validation->run() == true && $this->products_model->add_products($data)) {

            $this->session->set_flashdata('message', lang("products_added"));
            redirect('products');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['page_title'] = lang('import_products');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('import_products')));
            $meta = array('page_title' => lang('import_products'), 'bc' => $bc);
            $this->page_construct('products/import', $this->data, $meta);
        }
    }


    function delete($id = NULL)
    {
        if (DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        if ($this->products_model->deleteProduct($id)) {
            $this->session->set_flashdata('message', lang("product_deleted"));
            redirect('products');
        }
    }

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);

        $rows = $this->products_model->getProductNames($term);
        if ($rows) {
            foreach ($rows as $row) {
                $row->qty = 1;
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

    function consumirApi($endpoint, $post)
    {

        //The url you wish to send the POST request to
        $url = URL_API . '/' . $endpoint;

        $fields_string = http_build_query($post);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $result = curl_exec($ch);
        return $result;
    }

    function buscaProdutoByCodONome()
    {

        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $code = $this->input->post('pesquisa');

        if ($code != '') {

            $prod = $this->products_model->getByCode($code);

            if ($prod) {
                $prod->quantity = $this->depositoproduto_model->getDepositoEstoque($this->input->post('cod_loja_origem'), $prod->code);
                echo json_encode(['dados' => [$prod]]);
            } else {

                echo json_encode(['dados' => false]);
            }
        } else {
            echo json_encode(['dados' => false]);
        }
    }

    // tRANSFERENCIAS DE ESTOQUE
    function getLojas()
    {

        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $arrLojas = $this->lojas_model->getAllLojas();
        $arr = [];

        foreach ($arrLojas as $arrL) {

            $arr[$arrL->cod] = $arrL;
        }

        $this->session->set_userdata('lojasSessao', $arr);
        echo json_encode(['dados' => $arr]);
    }

    function transferirestoque()
    {

        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $post = $this->input->post();
            $erro = false;
            $arrErros = "";
            $arrDados = [];

            if (!isset($post['arrProdutos'])) {
                
                file_put_contents("/tmp/debug_transferirestoque", print_r($post, true));
                
                $this->session->set_flashdata('error', 'Post inválido');
                
                redirect('products/transferenciaestoque');
            }
            
            foreach ($post['arrProdutos'] as $arrProduto) {

                $arrProtudoTransferir = $this->products_model->getByCode($arrProduto['cod_produto']);

                if ($arrProtudoTransferir) {
                    
                    $qtd_atual_loja_origem = $this->depositoproduto_model->getDepositoEstoque($post['cod_loja_origem'], $arrProtudoTransferir->code);
                    
                    if ($qtd_atual_loja_origem && ($qtd_atual_loja_origem > 0) && $arrProduto['qtd_transferir'] > 0) {

                        if ($arrProduto['qtd_transferir'] <= $qtd_atual_loja_origem) {

                            if ($post['cod_loja_origem'] != $post['cod_loja_destino']) {

                                $arrDados[] = [
                                    'id_produto' => $arrProtudoTransferir->id,
                                    'cod_produto' => $arrProtudoTransferir->code,
                                    'code' => $arrProduto['cod_produto'],
                                    'cod_loja_origem' => $post['cod_loja_origem'],
                                    'cod_loja_destino' => $post['cod_loja_destino'],
                                    'qtd_atual_loja_origem' => $qtd_atual_loja_origem,
                                    'qtd_transferir' => $arrProduto['qtd_transferir'],
                                    'nome_usuario_solicitante' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name')
                                ];
                            } else {

                                $erro = true;
                                $arrErros .= "&bull; PRODUTO: {$arrProduto['cod_produto']} - {$arrProtudoTransferir->name} QTD ENVIADA : {$arrProduto['qtd_transferir']} ESTOQUE ATUAL: {$qtd_atual_loja_origem} - Loja destino inválida";
                                break;
                            }
                        } else {

                            $erro = true;
                            $arrErros .= "&bull; PRODUTO: {$arrProduto['cod_produto']} - {$arrProtudoTransferir->name} QTD ENVIADA : {$arrProduto['qtd_transferir']} ESTOQUE ATUAL: {$qtd_atual_loja_origem} - Quantidade a transferir inválida'<br>";
                        }
                    } else {

                        $erro = true;
                        $arrErros .= "&bull; PRODUTO: {$arrProduto['cod_produto']} - {$arrProtudoTransferir->name} QTD ENVIADA : {$arrProduto['qtd_transferir']} ESTOQUE ATUAL: {$qtd_atual_loja_origem} - Estoque ou quantidade zerado deste produto'<br>";
                    }
                } else {
                    $erro = true;
                    $arrErros .= "&bull; PRODUTO: {$arrProduto['cod_produto']} - Produto inválido ou não existente'<br>";
                }
            }
                        
            if (!$erro) {
                $msgFinal = "<b>VERIFIQUE A BAIXO SE A TRANSFERÊNCIA FOI BEM SUCEDIDA:</b><br><br>";
                $arrTransferenciasEnviar = [];
                foreach ($arrDados as $arrTransferencia) {

                    $qtd_apos_transferencia = (float)$arrTransferencia['qtd_atual_loja_origem'] - (float)$arrTransferencia['qtd_transferir'];
                    $msg = "Origem: " . getTipoLD($arrTransferencia['cod_loja_origem']) . " {$arrTransferencia['cod_loja_origem']} &rarr; Destino: " . getTipoLD($arrTransferencia['cod_loja_destino']) . " {$arrTransferencia['cod_loja_destino']} - PRODUTO: {$arrTransferencia['cod_produto']} - QTD TRANSFERIDA: {$arrTransferencia['qtd_transferir']} - QTD FINAL EM ESTOQUE: {$qtd_apos_transferencia} <br>";


                    if ($this->depositoproduto_model->updateEstoqueDeposito(['cod_loja' => $arrTransferencia['cod_loja_origem'], 'cod_produto' => $arrTransferencia['cod_produto'], 'qtd' => $qtd_apos_transferencia])) {

                        $arrTransferenciasEnviar[] = $arrTransferencia;
                        $msgFinal .= "&bull; <b>SUCESSO!</b> - " . $msg;
                    } else {

                        $msgFinal .= "&bull; <b>ERRO!</b> - " . $msg;
                    }
                }
                $this->excluirProdutosSessao();
                $resposta = json_decode(consumirApi('transferenciaEstoque', ['token' => TOKEN_MESTRE, 'cod_loja_origem' => $post['cod_loja_origem'], 'arrTransferencias' => $arrTransferenciasEnviar]));
                
                if ($resposta->sucesso) {

                    $this->session->set_flashdata('message', "<b>VERIFIQUE A BAIXO SE A TRANSFERÊNCIA FOI BEM SUCEDIDA:</b><br><br>" . $resposta->mensagem . "<br><br>" . "Para acompanhar o histórico de transferência acesse <a href='" . site_url('products/transferenciaestoque') . "'>PRODUTOS > TRANSFERÊNCIAS DE ESTOQUE</a>");
                    $this->excluirProdutosSessao();
                    redirect('products/transferenciaestoque');
                } else {

                    $this->session->set_flashdata('error', $resposta->mensagem);
                    redirect('products/transferenciaestoque');
                }
            } else {

                $this->session->set_flashdata('error', $arrErros);
                redirect('products/transferenciaestoque');
            }
        }
    }

    function transferenciaestoque()
    {


        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->data['page_title'] = 'Transferências de Estoque';

//        $ENVIADAS_arrTransferenciasPendentes = $this->transferenciaestoque_model->getTransferenciasDepositos("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "status = " . STATUS_TRANSFERENCIA_PENDENTE, false, 0, "data_solicitacao DESC");
//        $ENVIADAS_arrTransferenciasConfirmadasCanceladas = $this->transferenciaestoque_model->getTransferenciasDepositos("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "status != " . STATUS_TRANSFERENCIA_PENDENTE, TRANSFERENCIAS_POR_PG, 0, "data_solicitacao DESC");
//
//
//        $RECEBIDAS_arrTransferenciasPendentes = $this->transferenciaestoque_model->getTransferenciasDepositos("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "status = " . STATUS_TRANSFERENCIA_PENDENTE, false, 0, "data_solicitacao DESC", "destino");
//        $RECEBIDAS_arrTransferenciasConfirmadasCanceladas = $this->transferenciaestoque_model->getTransferenciasDepositos("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "status != " . STATUS_TRANSFERENCIA_PENDENTE, TRANSFERENCIAS_POR_PG, 0, "data_solicitacao DESC", "destino");
//
//        $arrTotais = [
//
//            'enviadas' => [
//
//                'pendentes' => count($ENVIADAS_arrTransferenciasPendentes),
//                'confirmadas_canceladas' => $this->transferenciaestoque_model->getTotais("status != " . STATUS_TRANSFERENCIA_PENDENTE)
//
//
//            ],
//
//            'recebidas' => [
//                'pendentes' => count($RECEBIDAS_arrTransferenciasPendentes),
//                'confirmadas_canceladas' => $this->transferenciaestoque_model->getTotais("status != " . STATUS_TRANSFERENCIA_PENDENTE, "destino")
//
//            ]
//
//        ];
//
//
//
//        $this->data['arrTransferencias'] =  json_decode(json_encode(['arrTotais' => $arrTotais, 'enviadas' => ['pendentes' => $ENVIADAS_arrTransferenciasPendentes, 'outras' => $ENVIADAS_arrTransferenciasConfirmadasCanceladas], 'recebidas' => ['pendentes' => $RECEBIDAS_arrTransferenciasPendentes, 'outras' => $RECEBIDAS_arrTransferenciasConfirmadasCanceladas]]));


        $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => 'Transferências de Estoque'));
        $meta = array('page_title' => 'Transferências de Estoque', 'bc' => $bc);
        $this->page_construct('products/transferenciaestoque', $this->data, $meta);
    }

    function lista_transferenciaestoque() {

        $total = $this->transferenciaestoque_model->getTotaisRecebidasDepositos(STATUS_TRANSFERENCIA_PENDENTE);

        $start = $this->input->post('iDisplayStart');
        $length = $this->input->post('iDisplayLength');
            
        $where = "1=1"; //status = " . STATUS_TRANSFERENCIA_PENDENTE;
        
        $order = "data_solicitacao DESC";
        
        $search = $this->input->post('sSearch');
                
        if (strlen($search) > 1) {
            $where .= " AND cod_produto LIKE '$search%'";
        }
        
        $qual = "origem";
        
        $filtro = $this->input->post('filtro');
        
        $rows = [];
        
        if ($filtro === "pendentes") {
            
            $qual = "destino";
            
            $where .= " AND status = " . STATUS_TRANSFERENCIA_PENDENTE;
            
            $total = $this->transferenciaestoque_model->getTotaisDepositos(['status' => STATUS_TRANSFERENCIA_PENDENTE], $qual);
        }
        
        if ($filtro === "recebidas") {
            
            $qual = "destino";
            
            $where .= " AND status = " . STATUS_TRANSFERENCIA_CONFIRMADA;
            
            $total = $this->transferenciaestoque_model->getTotaisDepositos(['status' => STATUS_TRANSFERENCIA_CONFIRMADA], $qual);
        }
        
        if ($filtro === "enviadas") {
            
            $qual = "origem";
            
            $order = "data_solicitacao DESC";
            
            $where1 = " $where AND status = " . STATUS_TRANSFERENCIA_PENDENTE;
           
            $rows1 = $this->transferenciaestoque_model->getTransferenciasDepositos("transferencia_estoque.*, tec_products.name as pname, transferencia_estoque.id as tid", $where1, 1000, $start, $order, $qual);

            $where2 = " $where AND status = " . STATUS_TRANSFERENCIA_CONFIRMADA;
            
            $rows2 = $this->transferenciaestoque_model->getTransferenciasDepositos("transferencia_estoque.*, tec_products.name as pname, transferencia_estoque.id as tid", $where2, $length, $start, $order, $qual);

            $rows = array_merge($rows1, $rows2);
                        
            $total = $this->transferenciaestoque_model->getTotaisDepositos([], $qual);
        }
        
        if (empty($rows)) {
            $rows = $this->transferenciaestoque_model->getTransferenciasDepositos("transferencia_estoque.*, tec_products.name as pname, transferencia_estoque.id as tid", $where, $length, $start, $order, $qual);
        }
        
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
                $row->qtd_atual_loja_origem,
                $map_lojas[$row->cod_loja_destino],
                ($row->status == STATUS_TRANSFERENCIA_CONFIRMADA) ? $row->qtd_atual_loja_destino + $row->qtd_transferir: "",
                $row->qtd_transferir,
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

    function cancelartransferenciaestoque($id = NULL)
    {
        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $arrTransferencia = $this->transferenciaestoque_model->getBy('*', "id = $id");
        
        if ($arrTransferencia) {
            $json = consumirApi('cancelarTransferenciaEstoque', ['token' => TOKEN_MESTRE, 'cod_loja_origem' => $arrTransferencia->cod_loja_origem, 'id_transferencia' => $id]);
            
            $resposta = json_decode($json);
            
            if ($resposta->sucesso) {

                $cod_produto = $arrTransferencia->cod_produto;
                $qtd_transferir = $arrTransferencia->qtd_transferir;
                    
                $where = ['cod_loja' => $arrTransferencia->cod_loja_origem, 'cod_produto' => $cod_produto];
                    
                $deposito = $this->depositoproduto_model->getBy($where);
                
                if ($deposito) {
                    
                    $qtd_atualizada = (int) $deposito->qtd + (int) $qtd_transferir;

                    $dados = $where + ['qtd' => $qtd_atualizada];
                   
                    if ($this->depositoproduto_model->updateEstoqueDeposito($dados)) {
                        $this->session->set_flashdata('message', $resposta->mensagem);
                    } else {
                        $this->session->set_flashdata('error', 'Não foi possível cancelar a solicitação de transferência de estoque. Por favor, tente novamente mais tarde (Erro 001)');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Não foi possível cancelar a solicitação de transferência de estoque. Por favor, tente novamente mais tarde (Erro 002)');
                }
            } else {
                $this->session->set_flashdata('error', 'Não foi possível cancelar a solicitação de transferência de estoque. Verifique se esssa solicitação já foi confirmada e por favor, tente novamente mais tarde (Erro 003)');
            }
        } else {
            $this->session->set_flashdata('error', 'Não foi possível cancelar a solicitação de transferência de estoque. Por favor, tente novamente mais tarde (Erro 004)');
        }
                
        redirect('products/transferenciaestoque');
    }

    function gettransferenciaspendentes()
    {

        echo consumirApi('getTrasferenciasEstoquePendentes', ['token' => TOKEN_MESTRE, 'cod_loja_origem' => CODIGO_LOJA]);
    }

    function confirmartransferenciaspendentes($id)
    {

        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

       // if ($this->input->server('REQUEST_METHOD') == 'POST') {

            //$post = $this->input->post();
            $sqlIds = " transferencia_estoque.id = $id AND ";


            $arrTransferenciasPendentes = $this->transferenciaestoque_model->getTransferenciasDepositos("transferencia_estoque.*, CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo", "$sqlIds status = " . STATUS_TRANSFERENCIA_PENDENTE, false, 0, "data_solicitacao DESC", "destino");


            if ($arrTransferenciasPendentes) {

                $arrDados = [];

                foreach ($arrTransferenciasPendentes as $arr) {

                    $qtdAtual = $this->depositoproduto_model->getDepositoEstoque($arr->cod_loja_destino, $arr->cod_produto);


                    $arrDados[] = [

                        'arrTransferenciaPendente' => $arr,
                        'arrDadosUpdate' => [

                            'qtd_atual_loja_destino' => $qtdAtual,
                            'nome_usuario_confirmacao' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
                            'status' => STATUS_TRANSFERENCIA_CONFIRMADA,
                            'data_confirmacao' => date('Ymdhis')

                        ]
                    ];
                }


                $arrUpdates = [];


                foreach ($arrDados as $arr) {

                    if ($this->transferenciaestoque_model->getBy('id', "id = {$arr['arrTransferenciaPendente']->id} AND status = " . STATUS_TRANSFERENCIA_PENDENTE)) {

                        if ($this->transferenciaestoque_model->edt($arr['arrTransferenciaPendente']->id, $arr['arrDadosUpdate'])) {

                            $arrUpdates[] = $arr;
                        }
                    }
                }


                if ($arrUpdates) {

                    $erro = false;

                    foreach ($arrUpdates as $arr) {

                        $qtdAtual = $this->depositoproduto_model->getDepositoEstoque($arr['arrTransferenciaPendente']->cod_loja_destino, $arr['arrTransferenciaPendente']->cod_produto);

                        $quantity = (int)$qtdAtual + (int)$arr['arrTransferenciaPendente']->qtd_transferir;


                        $dadosUpdade = [
                            'cod_loja' => $arr['arrTransferenciaPendente']->cod_loja_destino,
                            'cod_produto' => $arr['arrTransferenciaPendente']->cod_produto,
                            'qtd' => $quantity
                        ];

                        if (!$this->depositoproduto_model->updateEstoqueDeposito($dadosUpdade)) {

                            $erro = true;
                            break;
                        }
                    }

                    if (!$erro) {
                        $this->session->set_flashdata('message', 'Transferência(s) de estoque confirmada(s) com sucesso!');
                    } else {

                        $this->session->set_flashdata('error', 'Não foi possível confirmar a solicitação de transferência de estoque. Por favor, tente novamente mais tarde (Erro 001)');
                    }
                }
            }
        //}

        redirect('products/transferenciaestoque');
    }

    function getTotalTrasferenciasEstoqueRecebidasPendentes()
    {
        echo  json_encode(['dados' => $this->transferenciaestoque_model->getTotaisRecebidasDepositos(STATUS_TRANSFERENCIA_PENDENTE)]);
    }

    function editarEnvioTransferenciaEstoque()
    {

        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $post = $this->input->post();

            $arrProduto = $this->products_model->getProductByCode($post['cod_produto']);
            $qtdAtualEstoque = $this->depositoproduto_model->getDepositoEstoque($post['cod_loja_origem'], $post['cod_produto']);

            $qtd_trasnferir = (int)$post['qtd_transferir'];
            $qtd_atual = (int)$post['qtd_atual'];
            $id_transferencia = (int)$post['id_transferencia'];
            $qtd_atual_loja_origem = (int)$qtdAtualEstoque + $qtd_atual;

            if ($qtdAtualEstoque && ($qtd_atual_loja_origem > 0) && $qtd_trasnferir > 0) {

                if ($qtd_trasnferir <= $qtd_atual_loja_origem) {

                    $arrResposta = json_decode(
                        consumirApi(
                            'editarQtdTransferenciaEstoque',
                            [
                                'token' => TOKEN_MESTRE,
                                'cod_loja_origem' => $post['cod_loja_origem'],
                                'id_transferencia' => $id_transferencia,
                                'qtd_transferir' =>  $qtd_trasnferir,
                                'qtd_atual_loja_origem' => $qtd_atual_loja_origem
                            ]
                        )
                    );

                    if ($arrResposta->sucesso) {

                        $qtd_final = ((int)$qtdAtualEstoque + $qtd_atual) - $qtd_trasnferir;

                        if ($this->depositoproduto_model->updateEstoqueDeposito(['cod_loja' => $post['cod_loja_origem'], 'cod_produto' => $post['cod_produto'], 'qtd' => $qtd_final])) {

                            $this->session->set_flashdata('message', $arrResposta->mensagem);
                        } else {

                            $this->session->set_flashdata('error', 'Não foi possível editar a solicitação de transferência de estoque. Por favor, tente novamente mais tarde');
                        }
                    } else {

                        $this->session->set_flashdata('error', 'Não foi possível editar a solicitação de transferência de estoque. Verifique se ela não foi confirmada e por favor, tente novamente mais tarde' . $arrResposta->mensagem);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Quantidade inválida');
                }
            } else {

                $this->session->set_flashdata('error', 'Quantidade inválida');
            }
        }

        redirect('products/transferenciaestoque');
    }

    function confirmarTransferenciaEstoqueComErro()
    {

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $post = $this->input->post();

            $qtdAtual = $this->depositoproduto_model->getDepositoEstoque($post['cod_loja_destino'], $post['cod_produto']);

            $qtd_corrigida = (int)$post['qtd_transferir'];
            $qtd_atual = (int)$post['qtd_atual'];
            $id_transferencia = (int)$post['id_transferencia'];

            /*
            $arrResposta = json_decode(
                consumirApi(
                    'confirmarTransferenciaEstoqueComErro',
                    [
                        'token' => TOKEN_MESTRE,
                        'cod_loja_origem' => CODIGO_LOJA,
                        'id_transferencia' => $id_transferencia,
                        'dadosUpdate' => [
                            'qtd_erro' =>  $qtd_atual,
                            'qtd_transferir' =>  $qtd_corrigida,
                            'qtd_atual_loja_destino' => $qtdAtual,
                            'nome_usuario_confirmacao' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
                            'status' => STATUS_TRANSFERENCIA_CONFIRMADA,
                            'data_confirmacao' => date('Ymdhis')
                        ]
                    ]
                )
            );*/

            $dadosUpdate = [
                'qtd_erro' =>  $qtd_atual,
                'qtd_transferir' =>  $qtd_corrigida,
                'qtd_atual_loja_destino' => $qtdAtual,
                'nome_usuario_confirmacao' => $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
                'status' => STATUS_TRANSFERENCIA_CONFIRMADA,
                'data_confirmacao' => date('Ymdhis')
            ];

            if ($arrTransf = $this->transferenciaestoque_model->getBy('*', "id = {$id_transferencia} AND status = " . STATUS_TRANSFERENCIA_PENDENTE)) {

                $d = new DateTime($dadosUpdate['data_confirmacao']);

                $dadosUpdate['obs'] = 'Transferência aprovada com ERRO. Quantidade informada: ' . $arrTransf->qtd_transferir . ". Quantidade corrigida: " . $dadosUpdate['qtd_transferir'] . ". Usuário que solicitante: {$arrTransf->nome_usuario_solicitante}. Usuário que confirmou: {$dadosUpdate['nome_usuario_confirmacao']} . Data da confirmação: {$d->format('d/m/Y H:i:s')}";

                if ($this->transferenciaestoque_model->edt($id_transferencia,  $dadosUpdate)) {

                    $qtd_final = (int)$qtdAtual + $qtd_corrigida;

                    if ($this->depositoproduto_model->updateEstoqueDeposito(['cod_loja' => $post['cod_loja_destino'], 'cod_produto' => $post['cod_produto'], 'qtd' => $qtd_final])) {

                        $this->session->set_flashdata('message', 'Transferência confirmada com sucesso!');
                    } else {

                        $this->session->set_flashdata('error', 'Não foi possível editar a solicitação de transferência de estoque. Por favor, tente novamente mais tarde');
                    }
                }
            } else {

                $this->session->set_flashdata('error', 'Não foi possível editar a solicitação de transferência de estoque. Verifique se ela não foi confirmada e por favor, tente novamente mais tarde. ');
            }
        }

        redirect('products/transferenciaestoque');
    }

    function corrigeEstoqueComErro()
    {

        if (!$this->Admin) {
            exit;
        }

        $arrTransferenciasEstoqueErro = json_decode(
            consumirApi(
                'getTransferenciaEstoqueComErro',
                [
                    'token' => TOKEN_MESTRE,
                    'cod_loja_origem' => CODIGO_LOJA,

                ]
            )
        );

        if ($arrTransferenciasEstoqueErro->dados) {
            $arr = [];
            foreach ($arrTransferenciasEstoqueErro->dados as $arrTransderencia) {

                $arrProduto = $this->products_model->getProductByCode($arrTransderencia->cod_produto);

                if ($arrProduto) {
                    $qtd_atual_loja_origem = (int)$arrProduto->quantity + $arrTransderencia->qtd_erro;
                    $qtd_final = $qtd_atual_loja_origem - $arrTransderencia->qtd_transferir;

                    if ($this->products_model->updateProduct($arrProduto->id, ['quantity' => $qtd_final])) {


                        $arrTransderencia->dadosUpdate = [

                            'qtd_atual_loja_origem' => $qtd_atual_loja_origem,
                            'qtd_erro' => NULL

                        ];

                        $arr[] = $arrTransderencia;
                    }
                }
            }

            consumirApi(
                'corrigirTransferenciaEstoqueComErro',
                [
                    'token' => TOKEN_MESTRE,
                    'cod_loja_origem' => CODIGO_LOJA,
                    'arrTransferencias' => $arr

                ]
            );
        }
    }

    function listaTransferenciasEstoque_maisRecebidas()
    {

        $arrPesquisa = [
            'start' => (int)$this->input->post('start', TRUE),
            'qtd_por_pg' =>  (int)$this->input->post('qtd_por_pg', TRUE),
            'pesquisa' => ($this->input->post('pesquisa', TRUE)) ? $this->input->post('pesquisa', TRUE) : null
        ];


        $sqlPesquisa = (isset($arrPesquisa['pesquisa'])) ? " tec_products.code = '{$arrPesquisa['pesquisa']}' AND " : '';

        $RECEBIDAS_arrTransferenciasConfirmadasCanceladas = $this->transferenciaestoque_model->getTransferenciasDepositos(
            "transferencia_estoque.*, 
								CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo",
            "$sqlPesquisa status != " . STATUS_TRANSFERENCIA_PENDENTE,
            $arrPesquisa['qtd_por_pg'],
            $arrPesquisa['start'],
            "data_solicitacao DESC",
            "destino"
        );


        echo json_encode(['dados' => $RECEBIDAS_arrTransferenciasConfirmadasCanceladas]);
    }

    function listaTransferenciasEstoque_maisEnviadas()
    {
        $arrPesquisa = [
            'start' => (int)$this->input->post('start', TRUE),
            'qtd_por_pg' =>  (int)$this->input->post('qtd_por_pg', TRUE),
            'pesquisa' => ($this->input->post('pesquisa', TRUE)) ? $this->input->post('pesquisa', TRUE) : null
        ];


        $sqlPesquisa = (isset($arrPesquisa['pesquisa'])) ? " tec_products.code = '{$arrPesquisa['pesquisa']}' AND " : '';

        $ENVIADAS_arrTransferenciasConfirmadasCanceladas = $this->transferenciaestoque_model->getTransferenciasDepositos(
            "transferencia_estoque.*, lo.tipo as loja_origem_tipo, ld.tipo as loja_destino_tipo, 
								CONCAT(tec_products.code, ' - ', tec_products.name, ' - ', tec_categories.name) as produto_completo",
            "$sqlPesquisa status != " . STATUS_TRANSFERENCIA_PENDENTE,
            $arrPesquisa['qtd_por_pg'],
            $arrPesquisa['start'],
            "data_solicitacao DESC",
            "origem"
        );


        echo json_encode(['dados' => $ENVIADAS_arrTransferenciasConfirmadasCanceladas]);
    }

    function salvaProdutosSessao()
    {

        $this->session->set_userdata('solicitacaoTransferencia', ['dados' =>  $this->input->post('produtos'), 'id' => (int)$this->input->post('id')]);
    }

    function getProdutosSessao()
    {

        echo json_encode($this->session->solicitacaoTransferencia);
    }

    function excluirProdutosSessao()
    {

        $this->session->unset_userdata('solicitacaoTransferencia');
    }

    function enviaRelatorioEstoque()
    {

        $arrResposta = json_decode(consumirApi('getSolicitacaoRelatorioEstoque', ['token' => TOKEN_MESTRE, 'cod_loja_origem' => CODIGO_LOJA]));

        if ($arrResposta->sucesso) {

            if ($arrResposta->dados) {

                foreach ($arrResposta->dados as $arr) {

                    $arrRVs = $this->products_model->getRelatorioVendas($arr->data_i, $arr->data_f);
                    $arrRelatorioEstoque = [];

                    foreach ($arrRVs as $arrRV) {

                        $arrRelatorioEstoque[$arrRV->name]['qtd_vendas'] = $arrRV->qtd_total;
                    }

                    $arrREs = $this->products_model->getBy('tec_products.name, tec_products.quantity', 'quantity IS NOT NULL');


                    foreach ($arrREs as $arrRE) {

                        $arrRelatorioEstoque[$arrRE->name]['qtd_estoque'] = $arrRE->quantity;
                    }

                    $post = [
                        'token' => TOKEN_MESTRE,
                        'cod_loja_origem' => CODIGO_LOJA,
                        'id_relatorio' => $arr->id,
                        'json_envio' => json_encode($arrRelatorioEstoque)
                    ];


                    consumirApi('enviaRelatorioEstoque', $post);
                }
            }
        }
    }
    
    public function ajusteestoque() {
        
        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->data['page_title'] = 'Ajuste de Estoque';

        $this->data['arrLojas'] = $this->lojas_model->getAllCod('LOJA');
        
        $this->data['date_start'] = strtotime('-7 days') * 1000;
        
        $this->data['date_end'] = time() * 1000;
        
        $this->load->model('ajusteestoque_model');
        
        $bc = array(
            array('link' => site_url('products'), 'page' => lang('products')),
            array('link' => '#', 'page' => $this->data['page_title'])
        );

        $meta = array('page_title' => $this->data['page_title'], 'bc' => $bc);

        $this->page_construct('products/ajusteestoque', $this->data, $meta);
    }
    
    public function ajusteestoque_lista($cod_loja) {
        
        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            exit;
        }
        
        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);
            
        $this->load->model('ajusteestoque_model');
         
        if ($cod_loja === "0") {
            $rows = $this->ajusteestoque_model->getAllLoja($date_start, $date_end);
        } else {
            $rows = $this->ajusteestoque_model->getByLoja($cod_loja, $date_start, $date_end);
        }
         
        header('Content-Type: application/json');
         
        echo json_encode($rows);
    }
    
    public function ajusteestoque_salvar() {
        
        if (!$this->Admin && $this->data['user_group'] !== 'adm') {
            exit;
        }
        
        header('Content-Type: application/json');
        
        $post = $this->input->post();
        
        $response  = [
            'error' => true,
            'message' => ''
        ];
        
        $cod_loja = $post['cod_loja'];
        
        if (empty($cod_loja)) {
            $response['message'] = "Selecione a loja.";
            echo json_encode($response);
            exit;
        }
        
        $this->load->model('ajusteestoque_model');
        
        $rows = [];
        
        foreach ($post['ajuste_code'] as $i => $code) {
            
            $code = trim($code);
            
            $prod = $this->products_model->getEstoqueLoja($code, $cod_loja);
            
            if (!$prod) {
                $response['message'] = "Código $code não encontrado.";
                echo json_encode($response);
                exit;
            }
                        
            if ($post['ajuste_qty'][$i] === "") {
                $response['message'] = "Código $code:  Informe a quantidade.";
                echo json_encode($response);
                exit;
            }
            
            $quantity = intval($post['ajuste_qty'][$i]);
            
            $rows[$prod->code] = [
                'cod_produto' => $prod->code,
                'quantity' => $quantity,
                'cod_loja' => $cod_loja,
                'createdBy' => $this->session->userdata('user_id'),
                'loja_quantity' => $prod->quantity,
                'loja_ajuste' => $quantity - $prod->quantity
            ];
        }
        
        foreach ($rows as $save) {
            $this->ajusteestoque_model->insert($save);
        }
        
        $response['error'] = false;
        
        echo json_encode($response);
    }
    
    public function alteracao_preco() {

        $bc = array(array('link' => '#', 'page' => lang('products')), array('link' => '#', 'page' => lang('Alteração de preço'))); //rota na página
        
        $meta = array('page_title' => lang('Alteração de preço'), 'bc' => $bc); //título na página
        
        $lojas = $this->lojas_model->getAllCod('LOJA');
        
        $lista_lojas = [];
        
        foreach ($lojas as $cod) {
            
            if ($cod === 'ONLINE') {
                continue;
            }
            
            $lista_lojas[$cod] = [];
        }
                
        $this->load->model('alteracaopreco_model');
        
        $lista = $this->alteracaopreco_model->lista();
                        
        foreach ($lista as &$row) {
            
            $row->lojas = $lista_lojas;
                    
            $dados_lojas = $this->alteracaopreco_model->lista_lojas($row->id);
            
            foreach ($dados_lojas as $loja) {
                $row->lojas[$loja->cod_loja] = $loja;
            }
        }
            
        $this->data['lojas'] = $lista_lojas;
        
        $this->data['alteracoes'] = $lista;
                
        $this->page_construct('products/alteracao_preco', $this->data, $meta); //rota
    }
    
    public function alteracao_preco_salvar() {
        
        $post = $this->input->post();

        if (empty($post['cod_produto'])) {
            exit;
        }
        
        if (empty($post['preco_novo'])) {
            exit;
        }
        
        $post['preco_novo'] = str_replace(",", ".", $post['preco_novo']);
        
        $produto = $this->products_model->getByCode($post['cod_produto']);
        
        if (!$produto) {
            exit("Erro: código {$post['cod_produto']} não encontrado");
        }
        
        $this->load->model('alteracaopreco_model');
        
        $dados = [
            'cod_produto' => $produto->code,
            'preco_anterior' => $produto->price,
            'preco_novo' => $post['preco_novo']
        ];
        
        if (!empty($post['id'])) {
            $this->alteracaopreco_model->update($post['id'], $dados);
            exit('Ok');
        }
        
        $dados['data_criada'] = date('Y-m-d H:i:s');
        
        $cod_alteracao = $this->alteracaopreco_model->insert($dados);
        
        $lojas = $this->lojas_model->getAllCod('LOJA');        
        
        foreach ($lojas as $cod) {

            if ($cod === 'ONLINE') {
                continue;
            }

            $dados_loja = [
                'cod_alteracao' => $cod_alteracao,
                'cod_loja' => $cod
            ];

            $this->alteracaopreco_model->insert_lojas($dados_loja);
        }

        exit('Ok');
    }
    
    public function alteracao_preco_confirma($id) {

        $this->load->model('alteracaopreco_model');

        $alteracao = $this->alteracaopreco_model->select($id);

        if (!$alteracao) {
            exit;
        }

        $produto = $this->products_model->getByCode($alteracao->cod_produto);

        if (!$produto) {
            exit;
        }

        $this->alteracaopreco_model->update($id, ['data_aprovacao' => date('Y-m-d H:i:s')]);

        $update = ['price' => $alteracao->preco_novo];

        $this->products_model->updateProduct($produto->id, $update);

        editaProdutos($produto->id, $update);
    }
    
    public function alteracao_preco_delete() {

        $post = $this->input->post();
        
        if (empty($post['id'])) {
            exit;
        }
        
        $this->load->model('alteracaopreco_model');
        
        $this->alteracaopreco_model->delete($post['id']);
    }

}
