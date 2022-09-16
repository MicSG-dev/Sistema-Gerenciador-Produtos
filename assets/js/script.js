var erro_presente = false;
function main(){
	$("#form_edit_produto").on("submit", function(event) {
	  event.preventDefault();
	  sendDataFormToServer("form_edit_produto","edita-produto.php");
	});
	
	$("#form_cadastrar_produto").on("submit", function(event) {
	  event.preventDefault();
	  sendDataFormToServer("form_cadastrar_produto","cadastra-produto.php");
	  document.getElementById("input_cadastrar_descricao").value = "";
	  document.getElementById("input_cadastrar_marca").value = "";
	  document.getElementById("input_cadastrar_estoque").value = "";  
	  document.getElementById("input_cadastrar_preco").value = "";
	});
	
	$("#form_excluir_produto").on("submit", function(event) {
	  event.preventDefault();
	  sendDataFormToServer("form_excluir_produto","exclui-produto.php");
	  document.getElementById("check_confirmacao_delete").checked = false;
	});
	
	var elementModalCadastrar = document.getElementById('modal_novo_produto');	
	elementModalCadastrar.addEventListener('hidden.bs.modal', function (event) {
	    if(erro_presente === false){
	        recarregarPagina();
	    }
	    erro_presente = false;
	});
	elementModalCadastrar.addEventListener('hidden.bs.modal', function (event) {
	   document.getElementById("input_cadastrar_descricao").value = "";
	   document.getElementById("input_cadastrar_marca").value = "";
	   document.getElementById("input_cadastrar_estoque").value = "";  
	   document.getElementById("input_cadastrar_preco").value = "";
	});
		
    var elementModalEdit = document.getElementById('modal_editar_produto');	
	elementModalEdit.addEventListener('show.bs.modal', function (event) {
	  
	  var button = event.relatedTarget;
	  
	  elementModalEdit.querySelector('.modal-body #edit_input_id').value = button.getAttribute('data-bs-id');
	  elementModalEdit.querySelector('.modal-body #edit_input_descricao').value = button.getAttribute('data-bs-descricao');
	  elementModalEdit.querySelector('.modal-body #edit_input_marca').value = button.getAttribute('data-bs-marca');
	  elementModalEdit.querySelector('.modal-body #edit_input_estoque').value = button.getAttribute('data-bs-estoque');
	  elementModalEdit.querySelector('.modal-body #edit_input_preco').value = button.getAttribute('data-bs-preco');
	});
		
	var elementModalExclui = document.getElementById('modal_deletar_produto');	
	elementModalExclui.addEventListener('show.bs.modal', function (event) {
	  
	  var button = event.relatedTarget;
	  
	  elementModalExclui.querySelector('.modal-body #delete_input_id').value = button.getAttribute('data-bs-id');
	  elementModalExclui.querySelector('.modal-body #delete_descricao_produto').innerText = button.getAttribute('data-bs-descricao');
	});	
	elementModalExclui.addEventListener('hidden.bs.modal', function (event) {
	   document.getElementById("check_confirmacao_delete").checked = false;
	});
	
	var elementModalError = document.getElementById('modal_error');	
	elementModalError.addEventListener('show.bs.modal', function (event) {
	  
	  var button = event.relatedTarget;
	  elementModalError.querySelector('.modal-body #error_message_erro').innerText = button.getAttribute('data-bs-error');
	});	
}

function recarregarPagina(){
	window.location.reload();
}


function sendDataFormToServer(idForm, urlServer){
	$.ajax({
		   type: "POST",
		   url: urlServer,
		   data: $("#"+idForm).serialize(),
		 })
		 .done(function(data){
			if(urlServer.indexOf("cadastra-produto.php")== -1){
				recarregarPagina();
			}			
			new bootstrap.Modal(document.getElementById('modal_success')).show();			
		 })
		 .fail(function(jqXHR, textStatus){
		     erro_presente = true;
			let btn = document.getElementById("acionaModalError");
			btn.setAttribute("data-bs-error",jqXHR.responseText);
			btn.click();
		 });
}

$(window).ready(main);
