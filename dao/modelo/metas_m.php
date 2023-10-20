<?php
include "funcoes.php";

//-> AÇÃO REALIZADA...
if (isset($_GET['acao'])) {   
   $wacao = addslashes(strip_tags($_GET['acao']));

   if (($wacao == 'incluir') || ($wacao == 'alterar')){
      $met_mesref = $_REQUEST['met_mesref'];
      $met_valor  = moeda($_REQUEST['met_valor']); 
   }
}

//------------------------------------------------------------------------------------------------
// INCLUIR
//------------------------------------------------------------------------------------------------
if ($wacao == 'incluir') {    
   $pdo = ConectaBanco();
   try {
      $pdo->beginTransaction();
      $sql_con = " INSERT INTO tab_metas (
                       met_mesref, met_valor
                  )".
                  " VALUES (".
                     "'$met_mesref','$met_valor'
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
   $met_codigo = $_REQUEST['met_codigo'];  
   $pdo = ConectaBanco();
   try {
      $pdo->beginTransaction();
      $sql_con = "UPDATE tab_metas SET
                     met_valor='$met_valor', 
                     met_mesref='$met_mesref'
                  WHERE met_codigo='$met_codigo'
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
   $wsql_con = " SELECT met_codigo, met_mesref, 
                 CONCAT('R$ ',format(met_valor,2,'de_DE')) as met_valor,
                 DATE_FORMAT(met_dtentrada,'%d/%m/%Y às %Hh%i') AS met_dtentrada
                 FROM tab_metas       
                 ORDER BY met_codigo DESC
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
} elseif ($wacao == 'dadosmeta') {   
   $met_codigo = $_REQUEST['met_codigo'];       

   $wsql_con = " SELECT met_codigo, met_mesref,
                        format(met_valor,2,'de_DE') as met_valor 
                 FROM tab_metas       
                 WHERE met_codigo='$met_codigo'
               ";          

   $SQL = ConectaBanco()->prepare($wsql_con);   
   $SQL->execute(); 
   $dadosmet = $SQL->fetch(PDO::FETCH_ASSOC);

   echo json_encode(array(
      'met_codigo'=>$dadosmet['met_codigo'],
      'met_valor'=>$dadosmet['met_valor'],
      'met_mesref'=>$dadosmet['met_mesref']
   ));   


//------------------------------------------------------------------------------------------------
// NADA MESMO
//------------------------------------------------------------------------------------------------
} else {
   echo 'NADA';
}