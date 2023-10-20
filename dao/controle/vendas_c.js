$('#ven_valor').mask("#.##0,00", {reverse: true});
$("select[name=ven_mesref]").load('dao/modelo/carrega_mesano.php?tipo=vendas');
$("select[name=ven_relmesref]").load('dao/modelo/carrega_mesano.php?tipo=vendas');
var wgrd_vendas;
var wacao = 'I';
var ven_codigo = 0;
var wregistros = 0;

function releListagem(){
	wgrd_vendas.ajax.reload();
}

function limpaCampos(){
   document.getElementById('ven_contrato').value = '';
   document.getElementById('ven_valor').value = '';
	ven_codigo = 0;
}

function cadastroVenda(){	
	wacao = 'incluir';	
	document.getElementById('ven_contrato').focus();
	$("#mod_cadvendas").modal();	   
}

function relatorioVendas(){		
	$("#mod_relvendas").modal();	
}

posicionarNoTopo();

listaVendas();

function gravaVenda(){
	if (!validaCampo('ven_contrato','Número do contrato')){return false;}
	if (!validaCampo('ven_mesref','Mês de referência')){return false;}
	if (!validaCampo('ven_valor','Valor')){return false;}
  
   fetch("dao/modelo/vendas_m.php?acao="+wacao+
      "&ven_codigo=" + ven_codigo +
      "&ven_contrato=" + document.getElementById('ven_contrato').value +
      "&ven_mesref=" + document.getElementById('ven_mesref').value +
      "&ven_valor=" + document.getElementById('ven_valor').value ,
   {method: "POST",}
   )
   .then((gravavendas) => {            
      listaVendas();
		limpaCampos();
		document.getElementById('ven_contrato').focus();
      mensagemToast('Dados da venda gravados com sucesso.','S');		
   })
   .catch(function (erro) {
      mensagem("Erro", "Erro na alteração dos dados da venda." + erro, "erro");
   });
}

function listaVendas(){	
	var wpath = 'dao/modelo/vendas_m.php?acao=filtrar';
	wgrd_vendas = $('#grd_vendas').DataTable({      
		"pageLength": 10,
		"destroy": true,      
		"bLengthChange": false, 
		"bPaginate": true,
		"sPaginationType":"full_numbers",
		"type": "post",
		"sAjaxSource": wpath,
		"lengthChange": false,
		"responsive": true,
		"ordering": false,
		"bProcessing": true,
		"bFilter": true,
		"dom": "lrtip",
		"aoColumns": [     
			{ mData: 'ven_contrato', "sTitle": "Código do contrato", "bSortable": false, "sWidth": "20%"},
			{ mData: 'ven_mesref', "sTitle": "Mês referência", "bSortable": false, "sWidth": "10%"},
			{ mData: 'ven_valor', "sTitle": "Valor", "bSortable": false, "sWidth": "10%"},			
			{ mData: 'ven_dtentrada', "sTitle": "Data entrada", "bSortable": false, "sWidth": "10%"},
			{ mData: 'ven_codigo', "sTitle": "Ações", "sClass": "center", "bSortable": false, "sWidth": "3%"}
		],
		"columnDefs": [
			{
				"targets": 4,
				"data": null,
				orderable: false,
				"render": function(data, type, row, meta){
					return '<div class="btn-group col-md-12" role="group">'+
 							 '  <button type="button" class="btn btn-secondary" title="Alterar dados da venda" onclick="alteraVenda('+row['ven_codigo']+')" id='+row['ven_codigo']+'><span class="bi person-fill-slash"></span>Alterar</button> '+
							 '</div> ';
					}
				},
			],        
		"oLanguage": {
			"sProcessing":   "Selecionando registros...",
			"sLengthMenu":   "Mostrar _MENU_ registros",
			"sZeroRecords":  "Não existem dados para apresentar",
			"sInfo":         "Mostrando de _START_ até _END_ de _TOTAL_ registros",
			"sInfoEmpty":    "Mostrando de 0 até 0 de 0 registros",
			"sInfoFiltered": "",
			"sInfoPostFix":  "",
			"sSearch":       "Pesquisar",
			"sUrl":          "",
			"oPaginate": {
			"sFirst":    "Primeiro",
			"sPrevious": "Anterior",
			"sNext":     "Seguinte",
			"sLast":     "Último"
			}
		},
		"initComplete": function (settings, json) {             
			var info = this.api().page.info();            
			wregistros = info.recordsTotal;			
      },  		
	});

   $('#edt_pesquisa').keyup(function () {
      wgrd_vendas.search($(this).val()).draw();
   })	
}

function alteraVenda(wid){
	posicionarNoTopo();
   listaVendas();
	cadastroVenda()
   wacao = 'alterar';
	ven_codigo = wid;
   
   fetch('dao/modelo/vendas_m.php?acao=dadosvenda&ven_codigo='+wid, {
      method: 'POST',               
      headers: {'Content-type': 'application/json; charset="utf-8'}
   })
   .then(resposta => resposta.json()) 
   .then(dadosdavenda => {      		      
      document.getElementById('ven_contrato').value = dadosdavenda.ven_contrato;
      document.getElementById('ven_mesref').value   = dadosdavenda.ven_mesref;
      document.getElementById('ven_valor').value    = dadosdavenda.ven_valor;
   })
   .catch(function(erro) {
      mensagem('Erro', 'Erro na recuperação dos dados: .'+erro, 'erro');
   });   	
}

function imprimeRelatorioVendas(){
	fetch('dao/modelo/relvendas_m.php?ven_relmesref='+document.getElementById('ven_relmesref').value, {
		method: 'POST',               
		headers: {'Content-type': 'application/json; charset="utf-8'}
	})
	.then(resposta => resposta.json()) 
	.then(resultado => {      		      
   	window.open(resultado.arquivo, "_blank");
		return false;	
	})
	.catch(function(erro) {
		mensagem('Erro', 'Erro na recuperação dos dados: .'+erro, 'erro');
	});   	
					
   return false;
};
