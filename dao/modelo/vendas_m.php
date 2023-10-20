<?php
include "funcoes.php";

//-> AÇÃO REALIZADA...
if (isset($_GET['acao'])) {   
   $wacao = addslashes(strip_tags($_GET['acao']));

   if (($wacao == 'incluir') || ($wacao == 'alterar')){
      $ven_contrato = $_REQUEST['ven_contrato'];
      $ven_mesref   = $_REQUEST['ven_mesref'];
      $ven_valor    = moeda($_REQUEST['ven_valor']); 
   }
}

//------------------------------------------------------------------------------------------------
// INCLUIR
//------------------------------------------------------------------------------------------------
if ($wacao == 'incluir') {    
   $pdo = ConectaBanco();
   try {
      $pdo->beginTransaction();
      $sql_con = " INSERT INTO tab_vendas (
                       ven_mesref, ven_valor, ven_contrato
                  )".
                  " VALUES (".
                     "'$ven_mesref','$ven_valor', '$ven_contrato'
                  )";        
            
      $sql_res = $pdo->prepare($sql_con);

      if($sql_res->execute() === FALSE) {
         $pdo->rollback();
         echo json_encode(array('sucesso' => false));      
      } else {
         $pdo->commit();
         echo json_encode(array('sucesso'=>true));
      }
   } catch (Exception $e) {
      $pdo->rollback();
      echo json_encode(array('sucesso'=>false));
   }   

//------------------------------------------------------------------------------------------------
// ALTERA DADOS...
//------------------------------------------------------------------------------------------------
} elseif ($wacao == 'alterar') {   
   $ven_codigo = $_REQUEST['ven_codigo'];  
   $pdo = ConectaBanco();
   try {
      $pdo->beginTransaction();
      $sql_con = "UPDATE tab_vendas SET
                     ven_valor='$ven_valor', 
                     ven_contrato='$ven_contrato', 
                     ven_mesref='$ven_mesref'
                  WHERE ven_codigo='$ven_codigo'
               ";
      $sql_res = $pdo->prepare($sql_con);

      if($sql_res->execute() === FALSE) {
         $pdo->rollback();
         echo json_encode(array('sucesso' => false));      
      } else {
         $pdo->commit();
         echo json_encode(array('sucesso'=>true));
      }
   } catch (Exception $e) {
      $pdo->rollback();
      echo json_encode(array('sucesso'=>false));
   }   

//------------------------------------------------------------------------------------------------
// FILTRO
//------------------------------------------------------------------------------------------------
} elseif ($wacao == 'filtrar') {  
   $wsql_con = " SELECT ven_codigo, ven_mesref, ven_contrato,
                 CONCAT('R$ ',format(ven_valor,2,'de_DE')) as ven_valor,
                 DATE_FORMAT(ven_dtentrada,'%d/%m/%Y às %Hh%i') AS ven_dtentrada
                 FROM tab_vendas       
                 ORDER BY ven_codigo DESC
               ";                  

   $SQL = ConectaBanco()->prepare($wsql_con);   
   $SQL->execute(); 
   $dados = array();

   while ($linha = $SQL->fetch(PDO::FETCH_ASSOC)){
      $dados[] = $linha;
   }

   $retorno = array(
     "iTotalRecords" => count($dados),
     "iTotalDisplayRecords" => count($dados),
     "aaData"=>$dados
   );

   echo json_encode($retorno);

//------------------------------------------------------------------------------------------------
// DADOS DO usuário
//------------------------------------------------------------------------------------------------
} elseif ($wacao == 'dadosvenda') {   
   $ven_codigo = $_REQUEST['ven_codigo'];       

   $wsql_con = " SELECT ven_codigo, ven_mesref, ven_contrato,
                        format(ven_valor,2,'de_DE') as ven_valor 
                 FROM tab_vendas       
                 WHERE ven_codigo='$ven_codigo'
               ";          

   $SQL = ConectaBanco()->prepare($wsql_con);   
   $SQL->execute(); 
   $dadosven = $SQL->fetch(PDO::FETCH_ASSOC);

   echo json_encode(array(
      'ven_codigo'=>$dadosven['ven_codigo'],
      'ven_valor'=>$dadosven['ven_valor'],
      'ven_contrato'=>$dadosven['ven_contrato'],
      'ven_mesref'=>$dadosven['ven_mesref']
   ));   

//------------------------------------------------------------------------------------------------
// NADA MESMO
//------------------------------------------------------------------------------------------------
} else {
   echo 'NADA';
}