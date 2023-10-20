<?php
include "funcoes.php";

//-> AÇÃO REALIZADA...
if (isset($_GET['acao'])) {
   $wacao = addslashes(strip_tags($_GET['acao']));
}
 
//------------------------------------------------------------------------------------------------
// CARDS
//------------------------------------------------------------------------------------------------
if ($wacao == 'cards') {   
   //-> MÊS DE REFERÊNCIA...
   $wsqlmr = "SELECT ven_mesref 
              FROM tab_vendas
              WHERE ven_codigo = (SELECT MAX(ven_codigo) FROM tab_vendas)   
             ";
   
   $SQL = ConectaBanco()->prepare($wsqlmr);   
   $SQL->execute();
   $mesref = $SQL->fetch(PDO::FETCH_ASSOC);
   $mesreferencia = $mesref['ven_mesref'];

   //-> META DO MÊS...
   $wsql = "SELECT met_valor
            FROM tab_metas       
            WHERE met_mesref = '$mesreferencia'
           ";
   
   $SQL = ConectaBanco()->prepare($wsql);   
   $SQL->execute();
   $meta = $SQL->fetch(PDO::FETCH_ASSOC);
   $met_valor = $meta['met_valor'];

   //-> VENDAS DO MÊS...
   $wsql = "SELECT SUM(ven_valor) AS ven_valor, COUNT(ven_codigo) AS CONTAGEM_VENDAS
            FROM tab_vendas       
            WHERE ven_mesref = '$mesreferencia'
           ";
   
   $SQL = ConectaBanco()->prepare($wsql);   
   $SQL->execute();
   $vendas = $SQL->fetch(PDO::FETCH_ASSOC);
   $ven_valor = $vendas['ven_valor'];
   
   echo '{"met_valor":"'.'R$ '.number_format(round($met_valor, 2, PHP_ROUND_HALF_DOWN),2,",",".").'",
          "ven_valor":"'.'R$ '.number_format(round($ven_valor, 2, PHP_ROUND_HALF_DOWN),2,",",".").'",
          "val_falta":"'.'R$ '.number_format(round(($met_valor-$ven_valor), 2, PHP_ROUND_HALF_DOWN),2,",",".").'",
          "conta_venda":"'.$vendas['CONTAGEM_VENDAS'].'",
          "mesReferencia":"'.mesReferencia().'"
         }';
    
//------------------------------------------------------------------------------------------------
// GRÁFICO...
//------------------------------------------------------------------------------------------------
} elseif ($wacao == 'grafico') {   
   $array_grafico = array();
   $wsql_sol = " SELECT SUM(ven_valor) AS TOTAL_VENDAS, 
                        MONTH(ven_dtentrada) AS MES_VENDAS, UPPER(MONTHNAME(ven_dtentrada)) AS NOME_MES
                 FROM tab_vendas
                 WHERE YEAR(ven_dtentrada) = YEAR(CURRENT_DATE)
                   AND ven_mesref = 'Setembro/2023'    
                 GROUP BY MONTH(ven_dtentrada) 
                 ORDER BY MES_VENDAS
               ";    
            
   $SQL = ConectaBanco()->prepare($wsql_sol);   
   $SQL->execute(); 
   
   while ($grafico = $SQL->fetch(PDO::FETCH_ASSOC)){
      $array_grafico[] = array(
         "MES" => $grafico['NOME_MES'],
         "TOTAL_VENDAS" => $grafico['TOTAL_VENDAS']
      );
   }

   echo json_encode($array_grafico);

//------------------------------------------------------------------------------------------------
// NADA MESMO
//------------------------------------------------------------------------------------------------
} else {
   echo 'NADA';
}