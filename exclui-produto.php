<?php
  define("the_cred-banco",true);
  require("./acesso-controlado/cred-banco.php");
  
  $id_produto = $_POST['id_produto'];
  $confirmacao_exclusao = $_POST['confirmacao_exclusao'];
  
  if(verificaSeParamOk($id_produto) &&
    verificaSeParamOk($confirmacao_exclusao) && $confirmacao_exclusao == "on"
  ){
      $con = new mysqli($host, $user_banco, $senha_banco, $nome_banco);
      $stmt = $con->prepare("DELETE FROM produtos WHERE id=(?)");
      $stmt->bind_param("i",$id_produto);
      $stmt->execute();
      $deuCerto = $stmt->affected_rows == 1 ? true : false;
      $stmt->close();
      
      if($deuCerto){
          http_response_code (200);
          echo "OK";
      }else{
          http_response_code (400);
          echo "Falha na conexão com o servidor";
      }
      
      http_response_code (200);
      echo "OK";
  }else{
      http_response_code (400);
      echo "Erro: Falha na conexão com o servidor";
  }


  function verificaSeParamOk($param){
      if($param != "" && !is_null($param)){
          return true;
      }
      return false;
  }
  $con->close();
?>