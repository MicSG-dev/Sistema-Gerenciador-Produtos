<?php
  define("the_cred-banco",true);
  require("./acesso-controlado/cred-banco.php");
  
  $descricao = $_POST['nome_produto'];
  $marca = $_POST['marca_produto'];
  $estoque = $_POST['estoque_produto'];
  $preco = $_POST['preco_produto'];
  
  if(verificaSeParamOk($descricao) &&
    verificaSeParamOk($marca) &&
    verificaSeParamOk($estoque) &&
    verificaSeParamOk($preco)){
        
        
        $preco = doubleval(str_replace(",",".",$preco));
        
        $con = new mysqli($host, $user_banco, $senha_banco, $nome_banco);
        $stmt = $con->prepare("INSERT INTO produtos (descricao, marca, estoque, preco) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssid",$descricao,$marca,$estoque,$preco);
        $stmt->execute();
        $deuCerto = $stmt->affected_rows == 1 ? true : false;
        $stmt->close();
        
        if($deuCerto){
            http_response_code (200);
            echo "OK";
        }else{
            http_response_code (500);
            echo "Falha na conexão com o servidor";
        }
        
    }else{
        http_response_code (400);
        echo "Erro: Falha na estrutura enviada ao servidor";
    }

  function verificaSeParamOk($param){
      if($param != "" && !is_null($param)){
          return true;
      }
      return false;
  }
  $con->close();
?>