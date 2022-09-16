<?php
  define("the_cred-banco",true);
  require("./acesso-controlado/cred-banco.php");
  
  $id_produto = $_POST['id_produto'];
  $descricao = $_POST['nome_produto'];
  $marca = $_POST['marca_produto'];
  $estoque = $_POST['estoque_produto'];
  $preco = $_POST['preco_produto'];

  if(verificaSeParamOk($id_produto) &&
    verificaSeParamOk($descricao) &&
    verificaSeParamOk($marca) &&
    verificaSeParamOk($estoque) &&
    verificaSeParamOk($preco)
  ){
      $preco = doubleval(str_replace(",",".",$preco));
      
      $con = new mysqli($host, $user_banco, $senha_banco, $nome_banco);
      $stmt = $con->prepare("UPDATE produtos SET descricao = (?), marca = (?), estoque = (?), preco = (?) WHERE id = (?)");
      $stmt->bind_param("ssidi",$descricao,$marca,$estoque,$preco,$id_produto);
      $stmt->execute();
      $deuCerto = $stmt->affected_rows == 1 ? true : false;
      $stmt->close();
      
      if($deuCerto){
          http_response_code (200);
          echo "OK";
      }else{
          http_response_code (500);
          echo "Falha na conex«ªo com o servidor";
      }
      
      http_response_code (200);
      echo "OK";
  }else{
      http_response_code (400);
      echo "Houve alguma falha na conex«ªo com o servidor";
  }


  function verificaSeParamOk($param){
      if($param != "" && !is_null($param)){
          return true;
      }
      return false;
  }
  $con->close();
?>
