<?php
ob_start();
session_start();

if(isset($_POST['criaCod']) && $_POST['criaCod'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $pararLoop = 8999;

    for($numero = 0; ; $numero++){
        $cod = mt_rand(1000, 9999);

        $sql = "SELECT * FROM receitas WHERE cod='$cod'";
        $resultado = $conexaoAdmin->prepare($sql);	
        $resultado->execute();
        $contar = $resultado->rowCount();

        if($contar == 0):
            $retorno['erro']     = 0;
            $retorno['cod'] = $cod;
            echo json_encode($retorno);
            break;
        endif;
        
        if($numero == $pararLoop):
            $retorno['erro']     = 1;
            $retorno['mensagem'] = 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!';
            echo json_encode($retorno);
            break;
        endif;
    }
    exit();
endif;

if(isset($_POST['upload-img']) && $_POST['upload-img'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    // VERIFICAÇÃO 2 - SE TEM IMAGEM //
    if(isset($_FILES['img-receita']) && $_FILES['img-receita']['size'] > 0):  
        $extensoes_aceitas1 = array('png', 'jpg', 'jpeg'); // Extensões aceitas ('pdf', 'png', 'jpg'...)
        $array_extensoes1   = explode('.', $_FILES['img-receita']['name']);
        $extensao1 = strtolower(end($array_extensoes1));

        // Validamos se a extensão do arquivo é aceita
        if (array_search($extensao1, $extensoes_aceitas1) === false):
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Formato do arquivo não aceito';
            echo json_encode($retorno);
            exit(); 
        endif;


        function aleatorio(){
        $novo_valor= "";
        $valor = "abcdefghijklmnopqrstuvwxyz0123456789";
        srand((double)microtime()*1000000);
        for ($i=0; $i<10; $i++){
        $novo_valor.= $valor[rand()%strlen($valor)];
        }
        return strtoupper ($novo_valor);
        }

        // --------------------------------------------- //
        // ESTANDO TUDO OK, ENVIA O ARQUIVO PARA A PASTA //
        // --------------------------------------------- //

        // Verifica se o upload foi enviado via POST   
        if(is_uploaded_file($_FILES['img-receita']['tmp_name'])):  

            // Verifica se o diretório de destino existe, senão existir cria o diretório  
            if(!file_exists("../upload/receitas")):  
                mkdir("../upload/receitas");  
            endif;  

            // Monta o caminho de destino com o nome do arquivo
            $ext = strtolower(substr($_FILES['img-receita']['name'],-4)); //Pegando extensão do arquivo
            $novo_nome_img = aleatorio().$ext; //Definindo um novo nome para o arquivo

            // Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
            if (!move_uploaded_file($_FILES['img-receita']['tmp_name'], '../upload/receitas/'. $novo_nome_img)):  
                $retorno['erro']     = '1';
                $retorno['mensagem'] = 'O servidor encontrou um erro interno e não pode completar o cadastro. Se o erro persistir, por favor entre em contato com o WebMaster';
                echo json_encode($retorno);
                exit();
            else:
                $retorno['erro']     = '0';
                $retorno['img'] = $novo_nome_img;
                echo json_encode($retorno);
                exit();
            endif;
        endif;
    endif;
endif;

if(isset($_POST['cadastraReceita']) && $_POST['cadastraReceita'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $receita   = (isset($_POST['receita'])) ? $_POST['receita'] : '' ;
    $produto   = (isset($_POST['produto'])) ? $_POST['produto'] : '' ;
    $qtd    = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    $img    = (isset($_POST['img'])) ? $_POST['img'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($produto) || empty($receita) || empty($qtd) || empty($img)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Preencha todos os campos corretamente!';
        echo json_encode($retorno);
        exit();
    endif;

    $visible = 1;
    
    $sql = 'INSERT INTO receitas (cod, receita, produto, qtd, img, visible) VALUES (:cod, :receita, :produto, :qtd, :img, :visible)';
    $stmt = $conexaoAdmin->prepare($sql);
    $stmt->bindParam(':cod', $cod);
    $stmt->bindParam(':receita', $receita);
    $stmt->bindParam(':produto', $produto);
    $stmt->bindParam(':qtd', $qtd);
    $stmt->bindParam(':img', $img);
    $stmt->bindParam(':visible', $visible);
    $resposta = $stmt->execute();

    if( !$resposta ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '0';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['consultasReceita']) && $_POST['consultasReceita'] == '1'):
    require_once("conexao.php");
    $response = array();

    $col = (isset($_POST['col'])) ? $_POST['col'] : '' ;

    $sql = 'SELECT *, count(id) total FROM receitas GROUP BY cod';
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0):
        while($exibeO = $resultado->fetch(PDO::FETCH_OBJ)){
            $cod = $exibeO->cod;
            $receita = $exibeO->receita;
            $img = $exibeO->img;

            $total = $exibeO->total;

            $response[] = '<div class="'.$col.' receita-item px-4 animate__fadeIn animate__animated " data-cod="'.$cod.'"><div class="card cPointer shadow-sm receita-card"><div class="card-body d-flex p-0"><div class="dg-01 div-img-custon-01 rounded-top"><div class="div-img-custon-01-int rounded-top" style="opacity: 55%; background-image: url(\'upload/receitas/'.$img.'\');"></div></div></div><div class="card-footer p-0"><div class=""><div class="new-arrival-content text-start px-4 py-3"><span class="span-filter"><h4 class="mb-0"><span class="fs-18">'.$receita.'</span></h4><p class="fs-16 mb-0">#'.$cod.'</p></span><p class="fs-16">Ingredientes: '.$total.'</p></div></div></div></div></div>';
        }//While
    else:
        $response[] = '<div class="p-2 text-center"><p>Não encontramos compras cadastrados</p></div>';
    endif;

    echo json_encode($response);
    exit();
endif;

if(isset($_POST['infoReceita']) && $_POST['infoReceita'] == '1'):
    require_once("conexao.php");
    $response = array();
    $retorno = array();

    $cod = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    $sql = "SELECT * FROM receitas WHERE cod='$cod'";
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0):
        while($exibeO = $resultado->fetch(PDO::FETCH_OBJ)){
            $id = $exibeO->id;
            $codProduto = $exibeO->produto;
            $qtd = $exibeO->qtd;
            
            $sql2 = "SELECT * FROM estoque WHERE cod='$codProduto'";
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            if($contar2 > 0):
                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                $produto = $exibe2->produto;
                $unidade = $exibe2->unidade;
                if($unidade == 'un'):
                    $unidade = ' '.$unidade;
                endif;
            else:
                $produto = 'Produto não encontrado.';
                $unidade = '?';
            endif;

            $img = $exibeO->img;
            $receita = $exibeO->receita;

            $response[] =   '<tr class="ingrediente-receita animate__animated" data-id="'.$id.'" data-ingrediente="'.$produto.'" data-qtd="'.$qtd.$unidade.'" data-un="'.$exibe2->unidade.'">
                                <td style="width: 10%;">'.$id.'</td>
                                <td class="produto" style="width: 50%;">'.$produto.'</td>

                                
                                <td class="qtdItem" data-un="'.$unidade.'" data-info="'.$qtd.'" style="width: 30%;">'.$qtd.$unidade.'</td>

                                <td class="w-10">
                                    <div class="d-flex">
                                        <a class="btn btn-primary light shadow sharp mr-3 editItem"><i class="fa fa-pencil"></i></a>
                                        <a class="btn btn-danger light shadow sharp apagaItem"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>';
        }//While
        
        $retorno['erro'] = '0';
        $retorno['img']  = $img;
        $retorno['receita']  = $receita;
        $retorno['cod']  = $cod;
        $retorno['response']  = $response;
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro'] = '1';
        $retorno['mensagem']  = 'O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o WebMaster';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['addIngrediente']) && $_POST['addIngrediente'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $ingrediente   = (isset($_POST['ingrediente'])) ? $_POST['ingrediente'] : '' ;
    $qtd   = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($ingrediente) || empty($qtd)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Preencha todos os campos corretamente!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql = "SELECT * FROM receitas WHERE cod='$cod' LIMIT 1";
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0):
        $exibe = $resultado->fetch(PDO::FETCH_OBJ);
        
        $receita = $exibe->receita;
        $img = $exibe->img;
        $visible = $exibe->visible;

        $sql = 'INSERT INTO receitas (cod, receita, produto, qtd, img, visible) VALUES (:cod, :receita, :produto, :qtd, :img, :visible)';
        $stmt = $conexaoAdmin->prepare($sql);
        $stmt->bindParam(':cod', $cod);
        $stmt->bindParam(':receita', $receita);
        $stmt->bindParam(':produto', $ingrediente);
        $stmt->bindParam(':qtd', $qtd);
        $stmt->bindParam(':img', $img);
        $stmt->bindParam(':visible', $visible);
        $resposta = $stmt->execute();

        if( !$resposta ):
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!';
            echo json_encode($retorno);
            exit();
        else:
            $retorno['erro']     = '0';
            echo json_encode($retorno);
            exit();
        endif;
    else:
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['editIngrediente']) && $_POST['editIngrediente'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $id       = (isset($_POST['id'])) ? $_POST['id'] : '' ;
    $qtd   = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($id) || empty($qtd)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Preencha todos os campos corretamente!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql1 = 'UPDATE receitas SET qtd=:qtd WHERE id=:id';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':id', $id);
    $stmt1->bindParam(':qtd', $qtd);
    $resposta1 = $stmt1->execute();

    if(!$resposta1):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel atualizar as informaçõesw!';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '0';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['deletaIngrediente']) && $_POST['deletaIngrediente'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $id       = (isset($_POST['id'])) ? $_POST['id'] : '' ;
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    // VERIFICACAO 1 - SE PASSOU TUDO //
    if(empty($id) || empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel remover o ingrediente!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql = "SELECT * FROM receitas WHERE cod='$cod'";
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 1):
        $sql1 = 'DELETE FROM receitas WHERE id = :id';
        $stmt1 = $conexaoAdmin->prepare($sql1);
        $stmt1->bindParam(':id', $id);
        $resposta1 = $stmt1->execute();

        if( !$resposta1 ):
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel remover o ingrediente!';
            echo json_encode($retorno);
            exit();
        else:
            $retorno['erro']     = '0';
            echo json_encode($retorno);
            exit();
        endif;
    elseif($contar == 1):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Não foi possivel excluir o ingrediente, entre em contato com o WebMaster.';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel remover o ingrediente!';
        echo json_encode($retorno);
        exit();
    endif;
endif;
