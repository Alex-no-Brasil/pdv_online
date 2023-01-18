<?php

defined('BASEPATH') or exit('No direct script access allowed');

function getStatusRelatorios($idRelatorio) {
    $CI = $CI = get_instance();
    $CI->load->model('lojas_model');
    $arrLojas = $CI->lojas_model->getLojaBy("tipo = 'LOJA'");
    $qtdLojas = count($arrLojas);

    $CI->load->model('relatorioestoquelojas_model');
    $arrRelatoriosEnviados = $CI->relatorioestoquelojas_model->getRelatorioBy("id_relatorio_estoque = $idRelatorio");
    $qtdEnviados = count($arrRelatoriosEnviados);

    $progress = '<div class="progress" style="background-color:red">
            <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="' . $qtdEnviados . '" aria-valuemin="0" aria-valuemax="' . $qtdLojas . '" style="width: ' . ($qtdEnviados / $qtdLojas) * 100 . '%">
            ' . $qtdEnviados . '
            </div>
            <center>' . ($qtdLojas - $qtdEnviados) . '</center>
          </div>';

    $arrLojasFaltantes = "";
    $arrLojasEnviadas = "";

    if ($arrLojas && $arrRelatoriosEnviados) {
        foreach ($arrLojas as $arr) {

            $lojaEnviou = false;
            if ($arrRelatoriosEnviados) {
                foreach ($arrRelatoriosEnviados as $arr2) {

                    if ($arr->cod == $arr2->cod_loja) {
                        $lojaEnviou = true;
                    }
                }
            }


            if ($lojaEnviou) {
                $arrLojasEnviadas .= "Loja {$arr->cod} - {$arr->nome}<br>";
            } else {
                $arrLojasFaltantes .= "Loja {$arr->cod} - {$arr->nome}<br>";
            }
        }

        return $progress . "<br><br>Enviadas:<br>$arrLojasEnviadas<br>Faltantes:<br>$arrLojasFaltantes";
    } else {

        return "Nenhum relatório enviado";
    }
}

function dataDMYToYMD2($dataO) {

    $data = explode(" ", $dataO)[0];

    // a data deve estar no formato ("d/m/Y")
    $dataE = explode("/", $data);
    $dataT['dia'] = $dataE[0];
    $dataT['mes'] = $dataE[1];
    $dataT['ano'] = $dataE[2];

    if (strlen($dataT['dia']) < 2) {

        $dataT['dia'] = "0" . $dataT['dia'];
    }

    if (strlen($dataT['mes']) < 2) {

        $dataT['mes'] = "0" . $dataT['mes'];
    }

    $data = $dataT['ano'] . "-" . $dataT['mes'] . "-" . $dataT['dia'];

    return $data;
}

function getEstoquesDepositos($cod) {
    static $CI;

    if (!$CI) {
        $CI = get_instance();
        $CI->load->model('depositoproduto_model');
    }

    $arrEstoques = $CI->depositoproduto_model->getEstoqueByCod($cod);
    $str = "";

    foreach ($arrEstoques as $arrE) {
        $str .= $arrE->cod . " : " . $arrE->qtd . "<br>";
    }


    return ($str) ? $str : 'Nada nos depósitos';
}

function consumirApi($endpoint, $post) {

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

function getTipoLD($cod) {

    $CI = get_instance();
    return (isset($CI->session->lojasSessao[$cod]->tipo)) ? $CI->session->lojasSessao[$cod]->tipo : '';
}

function editaProdutos($id_produto, $dados) {

    $CI = get_instance();
    $CI->load->model('produtosedicoes_model');

    unset($dados['quantity']);

    $dadosAdd = [
        'id_produto' => $id_produto,
        'operation' => 'update',
        'dados' => json_encode($dados)
    ];

    $CI->produtosedicoes_model->addProdutoEdicao($dadosAdd);
}

function adicionaProdutos($id_produto, $dados) {

    $CI = get_instance();
    $CI->load->model('produtosedicoes_model');

    unset($dados['quantity']);

    $dadosAdd = [
        'id_produto' => $id_produto,
        'operation' => 'insert',
        'dados' => json_encode($dados)
    ];

    $CI->produtosedicoes_model->addProdutoEdicao($dadosAdd);
}

function nivel_oficina($dias) {

    if ($dias <= 5) {
        return "A";
    }

    if ($dias <= 10) {
        return "B";
    }

    if ($dias <= 15) {
        return "C";
    }

    if ($dias <= 20) {
        return "D";
    }

    if ($dias <= 25) {
        return "E";
    }

    return "F";
}

function nivel_acabamento($dias) {

    if ($dias <= 2) {
        return "A";
    }

    if ($dias <= 4) {
        return "B";
    }

    if ($dias <= 6) {
        return "C";
    }

    return "D";
}
