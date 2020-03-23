<?php
  /*
   * Filtro de seguridad para clientes
   */
  function loginFilter($params, $headers){
    global $core;
    $ret = ['status'=>'error', 'data'=>null];

    $tk = new OToken($core->config->getExtra('secret'));
    if ($tk->checkToken($headers['Authorization'])){
      $ret['status'] = 'ok';
      $ret['id'] = intval($tk->getParam('id'));
    }

    return $ret;
  }
