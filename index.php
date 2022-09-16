<?php
  define("the_cred-banco",true);
  require("./acesso-controlado/cred-banco.php");
  
  $con = new mysqli($host, $user_banco, $senha_banco, $nome_banco);
  $stmt = $con->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$table_schema' AND TABLE_NAME = 'produtos'");
  $stmt->execute();
  $res = $stmt->get_result();
  $stmt->close();
  $row = $res->fetch_assoc();
  
  $qtdTable = $row['COUNT(*)'];
  
  $tem_a_tabela = false;
  
  if($qtdTable == 0){
      echo 'Deve-se criar uma nova tabela'."<br>";
      $stmt = $con->prepare("CREATE TABLE produtos (
                            	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                descricao VARCHAR(120) NOT NULL,
                                marca VARCHAR(80) NOT NULL,
                                estoque MEDIUMINT NOT NULL,
                                preco DOUBLE(10,2) NOT NULL,
                                PRIMARY KEY(id)
                            );");
       $stmt->execute();
       $stmt->close();
       $stmt = $con->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$table_schema' AND  TABLE_NAME = 'produtos'");
       $stmt->execute();
       $res = $stmt->get_result();
       $stmt->close();
       $row = $res->fetch_assoc();
      
       $qtdTable = $row['COUNT(*)'];
       if($qtdTable == 1){
           $tem_a_tabela = true;
       }
  }else{
     $tem_a_tabela = true;
  }
  
echo "
<!DOCTYPE html>
<html lang=\"pt-br\">

<head>
    <meta charset=\"utf-8\">
<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js\"></script>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, shrink-to-fit=no\">
    <title>Sistema Gerenciador de Produtos</title>
    <meta name=\"twitter:description\" content=\"Demonstração do Sistema Gerenciador de Produtos desenvolvido por MicSG.\">
    <meta name=\"twitter:card\" content=\"summary_large_image\">
    <meta property=\"og:image\" content=\"assets/img/sistema_print.png\">
    <meta property=\"og:type\" content=\"website\">
    <meta name=\"description\" content=\"Demonstração do Sistema Gerenciador de Produtos desenvolvido por MicSG.\">
    <meta name=\"twitter:image\" content=\"assets/img/sistema_print.png\">
    <meta name=\"twitter:title\" content=\"Sistema Gerenciador de Produtos\">
    <link rel=\"apple-touch-icon\" type=\"image/png\" sizes=\"180x180\" href=\"assets/img/180x180.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"18x18\" href=\"assets/img/16x16.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"34x34\" href=\"assets/img/32x32.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"180x180\" href=\"assets/img/180x180.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"192x192\" href=\"assets/img/192x192.png\">
    <link rel=\"icon\" type=\"image/png\" sizes=\"512x512\" href=\"assets/img/512x512.png\">
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootswatch@5.1.3/dist/darkly/bootstrap.min.css\">
    <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&amp;display=swap\">
    <link rel=\"stylesheet\" href=\"https://use.fontawesome.com/releases/v5.12.0/css/all.css\">
    <link rel=\"stylesheet\" href=\"assets/css/Navbar-Centered-Links.css\">
    <link rel=\"stylesheet\" href=\"assets/css/styles.css\">
</head>

<body>
    <nav class=\"navbar navbar-light navbar-expand-md py-3\">
        <div class=\"container\"><a class=\"navbar-brand d-flex align-items-center\" href=\"#\"><span class=\"bs-icon-sm bs-icon-rounded bs-icon-primary d-flex justify-content-center align-items-center me-2 bs-icon\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" fill=\"currentColor\" viewBox=\"0 0 16 16\" class=\"bi bi-boxes\">
                        <path fill-rule=\"evenodd\" d=\"M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434L7.752.066ZM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567L4.25 7.504ZM7.5 9.933l-2.75 1.571v3.134l2.75-1.571V9.933Zm1 3.134 2.75 1.571v-3.134L8.5 9.933v3.134Zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567-2.742 1.567Zm2.242-2.433V3.504L8.5 5.076V8.21l2.75-1.572ZM7.5 8.21V5.076L4.75 3.504v3.134L7.5 8.21ZM5.258 2.643 8 4.21l2.742-1.567L8 1.076 5.258 2.643ZM15 9.933l-2.75 1.571v3.134L15 13.067V9.933ZM3.75 14.638v-3.134L1 9.933v3.134l2.75 1.571Z\"></path>
                    </svg></span><span class=\"text-white\">Gerenciador de Produtos</span></a></div>
    </nav>
    <div style=\"margin: 5px;margin-top: 10px;\">
        <p>Veja os Produtos, edite-os ou exclua-os. Sistema desenvolvido por MicSG. Acesse mais sistemas em <a href=\"https://portfolio.micsg.com.br/\" target=\"_blank\">portfolio.micsg.com.br</a></p><button class=\"btn btn-primary\" id=\"atualizar_lista\" type=\"button\" title=\"Atualize a lista de Produtos\" style=\"margin: 5px;\" onclick=\"recarregarPagina();\"><i class=\"fas fa-redo\" style=\"margin-right: 5px;\"></i>Atualizar lista</button><button class=\"btn btn-secondary\" id=\"cadastrar_produto\" type=\"button\" title=\"Cadastre um novo Produto\" style=\"margin: 5px;\" data-bs-target=\"#modal_novo_produto\" data-bs-toggle=\"modal\"><i class=\"fas fa-plus\" style=\"margin-right: 5px;\"></i>Cadastrar novo Produto</button>
    </div>
    <div class=\"table-responsive\">
        <table class=\"table table-striped table-hover table-dark table-sm\">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Marca</th>
                    <th>Estoque</th>
                    <th>Preço</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id=\"tableBody\">
";

  
if($tem_a_tabela == true){
   $stmt = $con->prepare("SELECT * FROM produtos");
   $stmt->execute();
   $res = $stmt->get_result();
   $stmt->close();
   $qtdRow = 0;
   while($row = $res->fetch_assoc()){
       $qtdRow++;
        $id_produto = $row['id'];
        $descricao_produto = $row['descricao'];
        $marca_produto = $row['marca'];
        $estoque_produto = $row['estoque'];
        $preco_produto = str_replace(".",",",$row['preco']);
        echo"            
                <tr>
                    <td>$id_produto</td>
                    <td>$descricao_produto</td>
                    <td>$marca_produto</td>
                    <td>$estoque_produto</td>
                    <td>$preco_produto</td>
                    <td><button class=\"btn btn-info btn btn-primary\" type=\"button\" style=\"margin: 5px;margin-left: 5px;width: 44px;height: 38px;\" data-bs-toggle=\"modal\" data-bs-id=\"$id_produto\" data-bs-descricao=\"$descricao_produto\" data-bs-marca=\"$marca_produto\" data-bs-estoque=\"$estoque_produto\" data-bs-preco=\"$preco_produto\" data-bs-target=\"#modal_editar_produto\" title=\"Edite o Produto\"><i class=\"far fa-edit\"></i></button><button class=\"btn btn-danger\" type=\"button\" style=\"margin: 5px;margin-left: 5px;width: 44px;height: 38px;\" title=\"Exclua o produto\" data-bs-target=\"#modal_deletar_produto\" data-bs-toggle=\"modal\" data-bs-id=\"$id_produto\" data-bs-descricao=\"$descricao_produto\"><i class=\"far fa-trash-alt\"></i></button></td>
                </tr>
        "; 
   }
   if($qtdRow == 0){
       echo "<td style='text-align:center'colspan=\"6\">Sem produtos cadastrados</td>";
   }
  
}else{
    echo "<div class=\"alert alert-danger\" role=\"alert\">
        <h4 class=\"alert-heading\">Erro no banco de dados</h4><span>Não foi possível criar uma tabela na base de dados. Consulte o desenvolvedor e informe este erro.</span>
        </div>";
}


echo" 
            </tbody>
        </table>
    </div>
    <div class=\"modal fade\" role=\"dialog\" tabindex=\"-1\" id=\"modal_editar_produto\" data-bs-backdrop=\"static\" data-bs-keyboard=\"false\">
        <div class=\"modal-dialog modal-dialog-centered modal-dialog-scrollable\" role=\"document\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h4 class=\"modal-title\">Editar Produto</h4><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                </div>
                <div class=\"modal-body\">
                    <form id=\"form_edit_produto\" method=\"post\">
                        <div><span>ID do Produto</span><input class=\"form-control\" type=\"text\" id=\"edit_input_id\" style=\"max-width: 150px;\" name=\"id_produto\" readonly=\"\" autofocus=\"\" autocomplete=\"off\"></div>
                        <div><span>Descrição do Produto</span><input class=\"form-control\" type=\"text\" id=\"edit_input_descricao\" required=\"\" style=\"max-width: 450px;\" name=\"nome_produto\" maxlength=\"120\" autocomplete=\"off\"></div>
                        <div><span>Marca</span><input class=\"form-control\" type=\"text\" id=\"edit_input_marca\" style=\"max-width: 450px;\" required=\"\" name=\"marca_produto\" maxlength=\"80\" autocomplete=\"off\">
                            <div style=\"padding: 5px;padding-right: 0;padding-left: 0;margin-top: -11px;\"></div>
                        </div>
                        <div><span>Estoque</span><input class=\"form-control\" type=\"text\" id=\"edit_input_estoque\" style=\"max-width: 140px;\" inputmode=\"numeric\" required=\"\" pattern=\"^([0-9]*)?$\" name=\"estoque_produto\" autocomplete=\"off\">
                            <div style=\"padding: 5px;padding-right: 0;padding-left: 0;margin-top: -11px;\"><span style=\"font-size: 12px;\">Formato aceito: somente números inteiros.&nbsp;Ex: 12</span></div>
                        </div>
                        <div><span>Preço</span><input class=\"form-control\" type=\"text\" id=\"edit_input_preco\" style=\"max-width: 200px;\" required=\"\" pattern=\"^[0-9]\d{0,9}(,[0-9]{1,2})?$\" name=\"preco_produto\" autocomplete=\"off\">
                            <div style=\"padding: 5px;padding-right: 0;padding-left: 0;margin-top: -11px;\"><span style=\"font-size: 12px;\">Formato aceito: números e vírgula. Ex: 10,25</span></div>
                        </div>
                    </form>
                </div>
                <div class=\"modal-footer\"><button class=\"btn btn-light\" type=\"button\" data-bs-dismiss=\"modal\">Cancelar</button><button class=\"btn btn-primary\" type=\"submit\" form=\"form_edit_produto\">Salvar</button></div>
            </div>
        </div>
    </div>
    <div class=\"modal fade\" role=\"dialog\" tabindex=\"-1\" id=\"modal_novo_produto\">
        <div class=\"modal-dialog modal-dialog-centered modal-dialog-scrollable\" role=\"document\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h4 class=\"modal-title\">Cadastrar novo Produto</h4><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                </div>
                <div class=\"modal-body\">
                    <form id=\"form_cadastrar_produto\" method=\"post\">
                        <div><span>Descrição do Produto</span><input class=\"form-control\" type=\"text\" id=\"input_cadastrar_descricao\" required=\"\" style=\"max-width: 450px;\" name=\"nome_produto\" autocomplete=\"off\" maxlength=\"120\"></div>
                        <div><span>Marca</span><input class=\"form-control\" type=\"text\" id=\"input_cadastrar_marca\" style=\"max-width: 450px;\" required=\"\" name=\"marca_produto\" autocomplete=\"off\" maxlength=\"80\">
                            <div style=\"padding: 5px;padding-right: 0;padding-left: 0;margin-top: -11px;\"></div>
                        </div>
                        <div><span>Estoque</span><input class=\"form-control\" type=\"text\" id=\"input_cadastrar_estoque\" style=\"max-width: 140px;\" inputmode=\"numeric\" required=\"\" pattern=\"^([0-9]*)?$\" name=\"estoque_produto\" autocomplete=\"off\">
                            <div style=\"padding: 5px;padding-right: 0;padding-left: 0;margin-top: -11px;\"><span style=\"font-size: 12px;\">Formato aceito: somente números inteiros.&nbsp;Ex: 12</span></div>
                        </div>
                        <div><span>Preço</span><input class=\"form-control\" type=\"text\" id=\"input_cadastrar_preco\" style=\"max-width: 200px;\" required=\"\" pattern=\"^[0-9]\d{0,9}(,[0-9]{1,2})?$\" name=\"preco_produto\" autocomplete=\"off\">
                            <div style=\"padding: 5px;padding-right: 0;padding-left: 0;margin-top: -11px;\"><span style=\"font-size: 12px;\">Formato aceito: números e vírgula. Ex: 10,25</span></div>
                        </div>
                    </form>
                </div>
                <div class=\"modal-footer\"><button class=\"btn btn-light\" type=\"button\" data-bs-dismiss=\"modal\">Cancelar</button><button class=\"btn btn-primary\" type=\"submit\" form=\"form_cadastrar_produto\">Cadastrar</button></div>
            </div>
        </div>
    </div>
    <div class=\"modal fade\" role=\"dialog\" tabindex=\"-1\" id=\"modal_deletar_produto\">
        <div class=\"modal-dialog modal-dialog-centered modal-dialog-scrollable\" role=\"document\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h4 class=\"modal-title\">Excluir Produto</h4><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                </div>
                <div class=\"modal-body\">
                    <p>Esta ação é irreversível. Tem certeza que deseja excluir o Produto<strong> </strong><span><strong id=\"delete_descricao_produto\">--</strong></span>? Se sim, marque a caixa de confirmação abaixo:</p>
                    <form id=\"form_excluir_produto\" method=\"post\">
                        <div class=\"form-check\"><input class=\"form-check-input\" type=\"checkbox\" id=\"check_confirmacao_delete\" required=\"\" name=\"confirmacao_exclusao\"><label class=\"form-check-label\" for=\"check_confirmacao_delete\">Confirmo que desejo excluir o produto e que estou ciente que esta é uma ação irreversível.</label></div><input class=\"form-control\" type=\"hidden\" id=\"delete_input_id\" name=\"id_produto\">
                    </form>
                </div>
                <div class=\"modal-footer\"><button class=\"btn btn-light\" type=\"button\" data-bs-dismiss=\"modal\">Cancelar</button><button class=\"btn btn-danger\" type=\"submit\" form=\"form_excluir_produto\">Excluir</button></div>
            </div>
        </div>
    </div>
    <div class=\"modal fade\" role=\"dialog\" tabindex=\"-1\" id=\"modal_success\">
        <div class=\"modal-dialog modal-dialog-centered modal-dialog-scrollable\" role=\"document\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h4 class=\"modal-title\">Ação executada com sucesso!</h4><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                </div>
                <div class=\"modal-body\">
                    <p>A ação requerida no servidor foi executada com sucesso.</p>
                </div>
                <div class=\"modal-footer\"><button class=\"btn btn-primary\" type=\"button\" data-bs-dismiss=\"modal\">Ok</button></div>
            </div>
        </div>
    </div>
    <div class=\"modal fade\" role=\"dialog\" tabindex=\"-1\" id=\"modal_error\">
        <div class=\"modal-dialog modal-dialog-centered modal-dialog-scrollable\" role=\"document\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <h4 class=\"modal-title\">Houve algum erro na execução da ação</h4><button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>
                </div>
                <div class=\"modal-body\">
                    <p>Durante a execução da ação requerida no servidor houve algum erro.&nbsp;<br>Mensagem do erro:&nbsp;<span id=\"error_message_erro\">-</span></p>
                </div>
                <div class=\"modal-footer\"><button class=\"btn btn-primary\" type=\"button\" data-bs-dismiss=\"modal\">Ok</button></div>
            </div>
        </div>
    </div><button class=\"btn btn-primary d-none invisible\" id=\"acionaModalError\" type=\"button\" tabindex=\"-1\" style=\"height: 0px;width: 0px;padding: 0px;\" data-bs-error=\"-\" data-bs-target=\"#modal_error\" data-bs-toggle=\"modal\"></button>
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js\"></script>
    <script src=\"assets/js/script.js\"></script>
</body>

</html>
";
  $con->close();
?>