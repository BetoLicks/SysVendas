<?php
include "funcoes.php";

//-------------------------------------------------------------------------------------------------------------------------------
//-> REGRAS 
//-------------------------------------------------------------------------------------------------------------------------------
if (isset($_GET['ven_relmesref'])) {$ven_relmesref = $_REQUEST['ven_relmesref'];}
$sequen  = 1;     
$wlimite = 49;
$linha   = 1;
$html = '';
$html .= '<h2><center>RELAT&Oacute;RIO GERAL DE VENDAS</center></h2>
          <h2><center>'.$ven_relmesref.'</center></h2>
          <hr class="linha"></hr>
      
          <table>
            <tbody>
            <tr style="line-height: 21px;" bgcolor="#dcdcdc" class="tablin">
               <th width="600px" style="text-align: left;"><strong>CODIGO DO CONTRATO</strong></th>
               <th width="100px" style="text-align: left;"><strong>VALOR</strong></th>
               <th width="150px" style="text-align: left;"><strong>DATA DA ENTRADA</strong></th>
            </tr>
            </tbody>
          </table>

          <table>
            <tbody>
        ';

$wsql = "SELECT ven_codigo, ven_mesref, ven_contrato, 
                CONCAT('R$ ',format(ven_valor,2,'de_DE')) as ven_valor,
                ven_valor as VALOR_SOMA,
                DATE_FORMAT(ven_dtentrada,'%d/%m/%Y às %Hh%i') AS ven_dtentrada
         FROM tab_vendas       
         WHERE ven_mesref = '$ven_relmesref'
         ORDER BY ven_codigo
         ";

$SQL = ConectaBanco()->prepare($wsql);   
$SQL->execute();
$VALOR_SOMA = 0;

while ($linha = $SQL->fetch(PDO::FETCH_ASSOC)){
   $html .= '<tr>';
      $html .= '   <td width="600px" style="text-align: left;">'.$linha['ven_contrato'].'</td>';
      $html .= '   <td width="100px" style="text-align: left;">'.$linha['ven_valor'].'</td>';
      $html .= '   <td width="150px" style="text-align: left;">'.$linha['ven_dtentrada'].'</td>';
   $html .= '</tr>';      
   $sequen++;
   $VALOR_SOMA = $VALOR_SOMA + $linha['VALOR_SOMA'];
}     

$wsql = "SELECT met_valor
         FROM tab_metas       
         WHERE met_mesref = '$ven_relmesref'
         ";

$SQL = ConectaBanco()->prepare($wsql);   
$SQL->execute();
$dados = $SQL->fetch(PDO::FETCH_ASSOC);

$html .= ' </table></tbody> ';
$html .= '<hr class="linha"></hr>';
$html .= '&nbsp;<strong>META DO MÊS: R$ '.number_format($dados['met_valor'],2,",",".").'</strong><br>';
$html .= '&nbsp;<strong>TOTAL DE VENDAS: R$ '.number_format($VALOR_SOMA,2,",",".").'</strong><br>';
$html .= '&nbsp;<strong>VALOR A SER ATINGIDO: R$ '.number_format(($dados['met_valor']-$VALOR_SOMA),2,",",".").'</strong><br>';

for ($i = 0; $i <= ($wlimite - $sequen); $i++) {
   $html .= '<br>';
}

//-> MINI TEMPLATE HTML
$htmltemplate = '
   <!doctype html>
   <html>
      <head>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8">         
         '.cssRelatorio('P').'
         <style>         
            tr:nth-child(even) {background-color: #f2f2f2;}
         </style>         
      </head>
      <body>
         '.trim($html).'
      </body>
   </html>';

$arqrel = "../../dao/modelo/relatorios/relatorio_de_vendas.html";

if (file_exists($arqrel)){
   unlink($arqrel);
}

file_put_contents($arqrel, $htmltemplate);

$arqrel = "dao/modelo/relatorios/relatorio_de_vendas.html";

echo '{"sucesso":true,"arquivo":"'.$arqrel.'"}';

?>