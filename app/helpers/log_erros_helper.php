<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function _logErro($arquivo = null, $funcao = null, $linha = null, $erro = null) {

    if (is_array($erro)) {

        if ($erro['code']) {

            $msgErro = $erro['message'];
        } else {

            return false;
        }
    } else {

        $msgErro = $erro;
    }

    $CI = get_instance();

    $CI->load->model('logerros_model');
   

    $dados = [
       
        'arquivo' => $arquivo,
        'funcao' => $funcao,
        'linha' => $linha,
        'data' => date('Ymdhis'),
        'erro' => $msgErro,
    ];

    
    $CI->logerros_model->add($dados);
}
