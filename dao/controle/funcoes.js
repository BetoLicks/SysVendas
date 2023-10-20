//-----------------------------------------------------------------------------------------------
// CARREGAMENTO DE PÁGINA
//-----------------------------------------------------------------------------------------------
function carregaPagina(element, wpagina) {
   document.getElementById('content-wrapper').innerHTML = '';
   $("#content-wrapper").load('dao/visao/' + wpagina + '.html', function () {
      $('#content-wrapper').scrollTop(0);
   }).fadeIn('slow');       
   $('#content-wrapper').fadeIn('fast');

   return false;
}

//-----------------------------------------------------------------------------------------------
// VALIDAÇÃO DE CAMPO 
//-----------------------------------------------------------------------------------------------
function validaCampo(campo, label, tipo) {
   let wcampojs = document.getElementById(campo);
   let regexEmailValido = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
   if ((wcampojs.value == '') || (wcampojs.value == '0') || (wcampojs.value == 'TODOS') || (wcampojs.value == 'TODAS')) {
      wcampojs.focus();
      mensagem('Atenção', 'É necessário preencher o campo: ' + label.toUpperCase() + '.', 'atencao');
      return false;
   } else {
      if (tipo != undefined) {
         if (tipo == 'email') {
            if (!wcampojs.value.match(regexEmailValido)) {
               mensagem('Atenção', 'Campo ' + label.toUpperCase() + ' inválido.', 'atencao');
               return false;
            }
         }

         if (tipo == 'data') {
            let dia = wcampojs.value.split("/")[0];
            let mes = wcampojs.value.split("/")[1];
            let ano = wcampojs.value.split("/")[2];
            let dataCorreta = new Date(ano, mes - 1, dia);
            if ((dataCorreta.getMonth() + 1 != mes) ||
               (dataCorreta.getDate() != dia) ||
               (dataCorreta.getFullYear() != ano)) {
               mensagem('Atenção', 'Data de ' + label.toUpperCase() + ' inválida.', 'atencao');
               return false;
            }
         }

         if (tipo == 'cpfcnpj') {
            if (!valida_cpf_cnpj(wcampojs.value)) {
               mensagem('Atenção', 'Campo ' + label.toUpperCase() + ' inválido.', 'atencao');
               return false;
            }
         }
      }

      return true;
   }
}

//-----------------------------------------------------------------------------------------------
// MOVE PRO TOPO TODOS OS ELEMENTOS
//-----------------------------------------------------------------------------------------------
function posicionarNoTopo() {
   $("html, body").animate({ scrollTop: 0 }, "slow");
   $("html").attr("style", "overflow:visible");
}

//-----------------------------------------------------------------------------------------------
// MENSAGEM
//-----------------------------------------------------------------------------------------------
function mensagem(wtitulo, wmensagem, wtipo) {
   if (wtipo == 'avisorapido') {
      swal.fire({
         icon: "success",
         closeOnEsc: false,
         type: 'success',
         title: wtitulo,
         text: wmensagem,
         showConfirmButton: false,
         timer: 4000
      });
      return false;
   }

   if (wtipo == 'erro') {
      swal.fire({
         icon: "error",
         closeOnEsc: false,
         type: 'error',
         title: wtitulo,
         text: wmensagem,
      });
      return false;
   }

   if (wtipo == 'atencao') {
      swal.fire({
         icon: "warning",
         closeOnEsc: false,
         type: 'warning',
         title: wtitulo,
         text: wmensagem,
      });
      return false;
   }
   if (wtipo == 'rapida') {
      swal.fire({
         position: 'top-end',
         type: 'info',
         closeOnEsc: false,
         title: wmensagem,
         showConfirmButton: false,
         position: 'top-end',
         timer: 1000
      });
      return false;
   }

   if (wtipo == 'info') {
      swal.fire({
         icon: "info",
         position: 'top-end',
         type: 'success',
         closeOnEsc: false,
         title: wtitulo,
         text: wmensagem,
         showConfirmButton: false,
         timer: 4000
      });
      return false;
   }
}

//-----------------------------------------------------------------------------------------------
// MENSAGEM TOAST PARA INFORMAÇÕES RÁPIDAS
//-----------------------------------------------------------------------------------------------
function mensagemTempo(wmensagem) {
   let tempo = Math.floor(Math.random() * 4000);
   if (tempo < 2000) { tempo = 2000 };
   Swal.fire({
      title: 'Aguarde,',
      html: wmensagem,
      timer: tempo,
      timerProgressBar: true,
      didOpen: () => {
         Swal.showLoading();
      }
   })

   return false;
}

//-----------------------------------------------------------------------------------------------
// MENSAGEM TOAST PARA INFORMAÇÕES RÁPIDAS
//-----------------------------------------------------------------------------------------------
function mensagemToast(wmensagem, wtipo) {
   const Toast = swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      background: '#e6f2ff',
      timer: 6000,
      timerProgressBar: true,
      didOpen: (toast) => {
         toast.addEventListener('mouseenter', Swal.stopTimer)
         toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
   })

   let wicon = '';
   switch (wtipo) {
      case 'S':
         wicon = 'success';
         break;
      case 'E':
         wicon = 'error';
         break;
      case 'W':
         wicon = 'warning';
         break;
      case 'I':
         wicon = 'info';
         break;
      case 'T':
         wicon = 'info';
         break;
   }

   if (wtipo == 'T') {
      let timerInterval;
      let tempo = Math.floor(Math.random() * 2000);
      Swal.fire({
         title: 'Aguarde...',
         html: wmensagem,
         timer: tempo,
         timerProgressBar: true,
         didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
               b.textContent = Swal.getTimerLeft()
            }, 100)
         },
         willClose: () => {
            clearInterval(timerInterval)
         }
      })
   } else {
      Toast.fire({
         icon: wicon,
         text: wmensagem
      })
   }

   return false;
}

