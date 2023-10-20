<?php

require 'dotenv/vendor/autoload.php';

# ===============================================================================================================================
function ConectaBanco(){
   date_default_timezone_set('America/Sao_Paulo');
   $dotenv = Dotenv\Dotenv::createUnsafeImmutable('../../');
   $dotenv->load();

   $banco    = getenv('DB_BANCO');
   $usuario  = getenv('DB_USUARIO');
   $senha    = getenv('DB_SENHA');
   $servidor = getenv('DB_SERVER');

   try {      
      $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha,
             array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));    
      $pdo->exec("SET lc_time_names = 'pt_BR'");            
   } catch(PDOException $erro) {
      echo 'ERRO: ' . $erro->getMessage();
   }

   return $pdo;
}

# =================================================================================================================================
function moeda($get_valor) {
	$source = array('.', ',');
	$replace = array('', '.');
	$valor = str_replace($source, $replace, $get_valor); 
	return $valor; 
}

# =================================================================================================================================
function converterDataINGLES($strData) {
   if ($strData == ''){
      return null;
   } else {
      $strDataFinal = '';
      if ( preg_match("#/#",$strData) == 1 ) {
         $strDataFinal .= implode('-', array_reverse(explode('/',$strData)));
         return $strDataFinal;
      }
   }
}

# ===============================================================================================================================
function cssRelatorio($orientacao){
   $str = '<style>';

   if ($orientacao == 'P'){
       $pagina = '@page{orientation:portrait; size: A4;size:21cm 29.7cm;
                          margin: 1cm 1cm 1cm 1cm;mso-title-page:yes;
                                 mso-page-orientation: portrait;mso-header: cabecalho; mso-footer: rodape;}
                                 ';
   }

   if ($orientacao == 'L'){
       $pagina = '@page{orientation:portrait; size: A4; size:29.7cm 26cm;
                          margin: 0.5cm 0.5cm 0.5cm 0.5cm;mso-title-page:yes;
                                 mso-page-orientation: portrait;mso-header: cabecalhorel; mso-footer: rodape;}
                                 @media print{@page {size: portrait}}
                                 ';
   }

  $str .= '
      '.$pagina.'
      html {font: normal 11px Arial, Helvetica, Sans-serif; background: white;}
        div.header {display: block; text-align: center; position: running(header); width: 100%;}
         p { margin: 0; font-family: Arial, Helvetica, sans-serif;font-size: 12px;}
         hr.linha {border: 0; border-top: 1px dotted #8c8c8c; width:110%;}
         #marcacdagua {
           content: "";
            display: block;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0px;
            left: 0px;
            background-image: url(../imagens/marca_dagua.png");
            background-size: 100px 100px;
            background-position: 30px 30px;
            background-repeat: no-repeat;
            opacity: 0.5;
         }
        .marcadagua {
         position: fixed;
         width: 500px;
         height: 500px;
         padding-top: 100px;
         top: 50%;
         left: 50%;
         opacity: 0.5;
         background-image: url(../imagens/marca_dagua.png");
         margin-right: -50%;
         transform: translate(-50%, -50%);
         z-index:-1;
        }

        .tabrelvac {
          border-collapse: collapse;
        }

        table.tabrel {
          border-collapse: collapse;
                    width: 100%;
                }

        th, td {
            text-align: left;
            padding: 2px;
        }

        table.tabanexo {border-collapse: collapse;width: 100%; font-size: 8px;}

        .tablin {
          border: 1px solid #999;
          padding: 0.5rem;
          text-align: left;
          white-space: pre;           
        }

        .tabsem {border-left: 0px solid !important;padding: 0.5rem;}

  </style>
   ';

  return $str;
}

# =================================================================================================================================
function mesReferencia() {
   $data = date('D');
   $mes = date('M');
   $dia = date('d');
   $ano = date('Y');
   
   $mes_extenso = array(
       'Jan' => 'Janeiro',
       'Feb' => 'Fevereiro',
       'Mar' => 'Marco',
       'Apr' => 'Abril',
       'May' => 'Maio',
       'Jun' => 'Junho',
       'Jul' => 'Julho',
       'Aug' => 'Agosto',
       'Nov' => 'Novembro',
       'Sep' => 'Setembro',
       'Oct' => 'Outubro',
       'Dec' => 'Dezembro'
   );
   
   return $mes_extenso["$mes"]."/{$ano}";   
}
?>