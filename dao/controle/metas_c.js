$('#met_valor').mask("#.##0,00", {reverse: true});
$("select[name=met_mesref]").load('dao/modelo/carrega_mesano.php?tipo=metas');
var wgrd_metas;
var wacao = 'I';
var met_codigo = 0;
var wregistros = 0;

function releListagem(){
	wgrd_metas.ajax.reload();
}

function limpaCampos(){
	document.getElementById('met_valor').value = '';
	met_codigo = 0;
}

function cadastroMeta(){	
	wacao = 'incluir';	
	$("#mod_cadmetas").modal();	
	met_mesref.focus();	
}

posicionarNoTopo();

listaMetas();

function gravaMeta(){
	if (!validaCampo('met_mesref','Mês de referência')){return false;}
	if (!validaCampo('met_valor','Valor')){return false;}

   fetch("dao/modelo/metas_m.php?acao="+wacao+
      "&met_codigo=" + met_codigo +
      "&met_mesref=" + document.getElementById('met_mesref').value +
      "&met_valor=" + document.getElementById('met_valor').value ,
   {method: "POST",}
   )
   .then((gravametas) => {            
      listaMetas();
		limpaCampos();
      mensagemToast('Dados da meta gravados com sucesso.','S');
		$('#mod_cadmetas').modal('hide'); 
   })
   .catch(function (erro) {
      mensagem("Erro", "Erro na alteração dos dados da meta." + erro, "erro");
   });
}

function listaMetas(){	
	var wpath = 'dao/modelo/metas_m.php?acao=filtrar';
	wgrd_metas = $('#grd_metas').DataTable({      
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
			{ mData: 'met_mesref', "sTitle": "Mês referência", "bSortable": false, "sWidth": "10%"},
			{ mData: 'met_valor', "sTitle": "Valor", "bSortable": false, "sWidth": "10%"},			
			{ mData: 'met_dtentrada', "sTitle": "Data entrada", "bSortable": false, "sWidth": "10%"},
			{ mData: 'met_codigo', "sTitle": "Ações", "sClass": "center", "bSortable": false, "sWidth": "3%"}
		],
		"columnDefs": [
			{
				"targets": 3,
				"data": null,
				orderable: false,
				"render": function(data, type, row, meta){
					return '<div class="btn-group col-md-12" role="group">'+
 							 '  <button type="button" class="btn btn-secondary" title="Alterar dados da meta" onclick="alteraMeta('+row['met_codigo']+')" id='+row['met_codigo']+'><span class="bi person-fill-slash"></span>Alterar</button> '+
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
      wgrd_metas.search($(this).val()).draw();
   })	
}

function alteraMeta(wid){
	posicionarNoTopo();
   listaMetas();
	cadastroMeta()
   wacao = 'alterar';
	met_codigo = wid;
   
   fetch('dao/modelo/metas_m.php?acao=dadosmeta&met_codigo='+wid, {
      method: 'POST',               
      headers: {'Content-type': 'application/json; charset="utf-8'}
   })
   .then(resposta => resposta.json()) 
   .then(dadosmeta => {      		      
      document.getElementById('met_mesref').value = dadosmeta.met_mesref;
      document.getElementById('met_valor').value  = dadosmeta.met_valor;
   })
   .catch(function(erro) {
      mensagem('Erro', 'Erro na recuperação dos dados: .'+erro, 'erro');
   });   	
}