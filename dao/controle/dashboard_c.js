//--------------------------------------------------------------------------------------------------------------
//-> CARREGAMENTO DOS CARDS E GRÁFICO...
//--------------------------------------------------------------------------------------------------------------
$(document).ready(function () {   
   posicionarNoTopo();

   //-> CARDS DO DASHBOARD...
   fetch('dao/modelo/dashboard_m.php?acao=cards', {
   })
      .then(dashboardCards => dashboardCards.json())
      .then(dashboardCards => {
         $('#div_metames').html(dashboardCards.met_valor);
         $('#div_vendmes').html(dashboardCards.ven_valor);
         $('#div_valorat').html(dashboardCards.val_falta);
         $('#div_contvenda').html(dashboardCards.conta_venda);
         $('#div_titmetames').html('META TOTAL DO MÊS DE '+dashboardCards.mesReferencia);
         $('#div_titvendmes').html('TOTAL DE VENDAS DO MÊS DE '+dashboardCards.mesReferencia);
         $('#div_titvalorat').html('VALOR A SER ATINGIDO EM '+dashboardCards.mesReferencia);
         $('#div_titcontmes').html('VENDAS CONCRETIZADAS EM '+dashboardCards.mesReferencia);
      })

   fetch('dao/modelo/dashboard_m.php?acao=grafico', {
   })
      .then(dashboardGrafico => dashboardGrafico.json())
      .then(dashboardGrafico => {
         var grafDash = document.getElementById('grafVendas').getContext('2d');
         let meses = [];
         let totalvendas = [];

         for (i = 0; i < dashboardGrafico.length; i++) {
            meses.push(dashboardGrafico[i].MES);
            totalvendas.push(dashboardGrafico[i].TOTAL_VENDAS);
         }

         var chartjs = new Chart(grafDash, {
            type: 'line',
            data: {
               labels: meses,
               datasets: [{
                  data: totalvendas,
                  label: "TOTAL VENDAS",
                  borderColor: "rgb(77,118,241)",
                  backgroundColor: "rgb(77,118,241, 0.9)",
               }]
            },
         });
      })
});