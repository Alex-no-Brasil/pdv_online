<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function relatorio_estoque_thead($foto = false)
{
    $CI = get_instance();

    if (!isset($CI->lojas_model)) {
        $CI->load->model('lojas_model');
    }

    $arrLojas = $CI->lojas_model->getAllCod('LOJA');

    sort($arrLojas);

    $arrDepositos = $CI->lojas_model->getAllCod('DEPOSITO');

    $thead = [];
    
    if ($foto) {
        $thead[] = 'FOTO';
    }
    
    $thead[] = 'CÓDIGO';
    $thead[] = 'DESCRIÇÃO';
    $thead[] = 'CATEGORIA';
    $thead[] = 'VALOR';

    foreach ($arrLojas as $cod) {
        $thead[] = $cod;
    }

    $thead[] = 'TOTAL';

    foreach ($arrDepositos as $cod) {
        $thead[] = str_replace("ORIENTE", "ORI", $cod);
    }

    $thead[] = 'TOTAL';

    foreach ($arrLojas as $cod) {
        $thead[] = $cod;
    }

    $thead[] = 'TOTAL';
    $thead[] = 'COHORT';

    return $thead;
}

function relatorio_estoque_data($date_start, $date_end, $alert = 20, $level = [], $foto = false)
{
    $CI = get_instance();

    if (!isset($CI->lojas_model)) {
        $CI->load->model('lojas_model');
    }

    if (!isset($CI->products_model)) {
       $CI->load->model('products_model');
    }

    if (!isset($CI->depositoproduto_model)) {
       $CI->load->model('depositoproduto_model');
    }

    $arrLojas = $CI->lojas_model->getAllCod('LOJA');

    sort($arrLojas);

    $arrDepositos = $CI->lojas_model->getAllCod('DEPOSITO');

    $vendas = $CI->products_model->getRelatorioVendas($date_start, $date_end);

    $vendasLojas = [];

    foreach ($vendas as $venda) {
        $vendasLojas[$venda->cod_loja][$venda->code] = $venda;
    }

    $arrDepositosEstoque = [];

    $depositos_rows = $CI->depositoproduto_model->getAllRelatorios();

    foreach ($depositos_rows as $dep) {
        $arrDepositosEstoque[$dep->cod_loja][$dep->cod_produto] = $dep->qtd;
    }

    $arrProdutos = $CI->products_model->getAllProductsNames();

    $rows = [];

    foreach ($arrProdutos as $prod) {
        
        $codigo = $prod['codigo'];

        $row = [];
        
        if ($foto) {
            $row[] = $prod['image'];
        }
        
        $row[] = $prod['codigo'];
        $row[] = $prod['name'];
        $row[] = $prod['category'];
        $row[] = $prod['valor'];

        //estoque lojas
        $arrEstoqueLojas = [];

        $total_estoque_lojas = 0;

        $estoqueLojas = $CI->products_model->getEstoqueLojas($codigo);

        foreach ($estoqueLojas as $e) {
            $arrEstoqueLojas[$e->cod_loja] = $e;
            $total_estoque_lojas           += $e->quantity;
        }

        //estoque depositos
        $total_estoque_depo = 0;
        
        foreach ($arrDepositos as $cod) {
            if (isset($arrDepositosEstoque[$cod][$codigo])) {
                $total_estoque_depo += $arrDepositosEstoque[$cod][$codigo];
            }
        }
        
        $bg_list = [];
        
        foreach ($arrLojas as $cod) {
            
            if (isset($arrEstoqueLojas[$cod])) {
                
                $bg = relatorio_estoque_bg_estoque($total_estoque_depo, $arrEstoqueLojas[$cod]->quantity, $alert);
                
                $row[] = [
                    $arrEstoqueLojas[$cod]->quantity,
                    $bg
                ];
                
                $k = trim($bg);
                
                $bg_list[$k] = 1;
                
            } else {
                $row[] = 0;
            }
        }

        $hide = relatorio_estoque_bg_filter($bg_list, $level);
        
        if ($hide) {
            continue;
        }
        
        $row[] = [$total_estoque_lojas, 'td-bold'];

        foreach ($arrDepositos as $cod) {
            if (isset($arrDepositosEstoque[$cod][$codigo])) {
                $row[]              = [
                    $arrDepositosEstoque[$cod][$codigo],
                    $arrDepositosEstoque[$cod][$codigo] > 0 ? 'td-bg-est' : ''
                ];
            } else {
                $row[] = 0;
            }
        }

        $total_estoque = $total_estoque_lojas + $total_estoque_depo;

        $row[] = [$total_estoque, 'td-bold'];

        //vendas
        $total_vendas = 0;
        
        foreach ($arrLojas as $cod) {
            if (isset($vendasLojas[$cod][$codigo])) {
                $qt = intval($vendasLojas[$cod][$codigo]->qtd_total);
                
                $row[] = [
                    $qt,
                    $qt > 0 ? 'td-bg-sale' : ''
                ];
                
                $total_vendas += $qt;
            } else {
                $row[] = 0;
            }
        }

        if ($total_estoque === 0 && $total_vendas === 0) {
            continue;
        }

        $row[] = [$total_vendas, 'td-bold'];

        //COHORT
        $tx = 0;

        if ($total_vendas > 0) {
            $tx = number_format($total_estoque / $total_vendas, 2);
        }

        $row[] = $tx;

        $rows[] = relatorio_estoque_format_row($row);
    }

    return $rows;
}

function relatorio_estoque_format_row($row)
{
    $data = [];

    foreach ($row as $val) {

        if (!is_array($val)) {
            $val = [$val];
        }

        $data[] = $val;
    }

    return $data;
}


function relatorio_estoque_bg_estoque($estoque, $loja, $alert) {
    if ($estoque > 0 && $loja < 1) {
        return ' td-bg-low';
    }
    
    if ($estoque > 0 && $loja < $alert) {
        return ' td-bg-med';
    }
    
    if ($loja > 60) {
        return ' td-bg-high';
    }
    
    return '';
}

function relatorio_estoque_bg_filter($bg_list, $level) {
    if (empty($level)) {
        return false;
    }
    
    foreach ($level as $n) {
        if (isset($bg_list["td-bg-$n"])) {
            return false;
        }
    }
    
    return true;
}
