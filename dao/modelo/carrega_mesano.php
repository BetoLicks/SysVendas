<?php
   include "../modelo/funcoes.php";
   setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
   date_default_timezone_set('America/Sao_Paulo');

   if (isset($_GET['tipo'])) {
      $tipo = $_GET['tipo'];
   }

   if ($tipo == 'metas'){
      $arr_meses = array(
         'Janeiro' => 'Janeiro',
         'Fevereiro' => 'Fevereiro',
         'Março' => 'Março',
         'Abril' => 'Abril',
         'Maio' => 'Maio',
         'Junho' => 'Junho',
         'Julho' => 'Julho',
         'Agosto' => 'Agosto',
         'Setembro' => 'Setembro',
         'Outubro' => 'Outubro',
         'Novembro' => 'Novembro',
         'Dezembro' => 'Dezembro'
      );
   
      foreach($arr_meses as $mes => $meses) {      
         $mesano = $mes.'/'.date("Y");
         echo "<option value='".$mesano."'>".$mesano."</option>";
      }   
   }

   if ($tipo == 'vendas'){
      $wsql_con = " SELECT met_mesref
                    FROM tab_metas       
                    ORDER BY met_codigo DESC
                  ";                  

      $SQL = ConectaBanco()->prepare($wsql_con);   
      $SQL->execute(); 

      while ($linha = $SQL->fetch(PDO::FETCH_ASSOC)){
         echo "<option value='".$linha['met_mesref']."'>".$linha['met_mesref']."</option>";
      }
   }
?>
