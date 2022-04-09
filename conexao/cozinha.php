<?php
ob_start();
session_start();

if(isset($_POST['add_img']) && $_POST['add_img'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    if ( isset($_FILES['file']) && !empty($_FILES['file']['name']) ):
        $nome = $_FILES['file']['name'];
        $partes = explode(".", $nome);
        $ext = array_pop($partes);

        $ext_aceitas =  array('jpeg','png' ,'jpg');
        if(!in_array($ext,$ext_aceitas) ):
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Formato do arquivo não aceito.';
            echo json_encode($retorno);
            exit(); 
        endif;

        // Verifica se o upload foi enviado via POST   
        if(is_uploaded_file($_FILES['file']['tmp_name'])):

            // Verifica se o diretório de destino existe, senão existir cria o diretório  
            if(!file_exists("../upload/cardapio")):  
                mkdir("../upload/cardapio");  
            endif;  

            // Monta o caminho de destino com o nome do arquivo
            $novo_nome = md5($nome . microtime());
            $destino = "../upload/cardapio/{$novo_nome}.{$ext}";

            $novo_nome_ext = $novo_nome.".".$ext;

            // Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $destino)):  
                $retorno['erro']     = '1';
                $retorno['mensagem'] = 'O servidor encontrou um erro interno e não pode completar o cadastro. Se o erro persistir, por favor entre em contato com o desenvolvedor.';
                echo json_encode($retorno);
                exit();
            else:
                $retorno['erro']     = '0';
                $retorno['img'] = $novo_nome_ext;
                echo json_encode($retorno);
                exit();
            endif;
        endif;
        // $file_name = $_FILES['file']['name'];
        // $file_type = $_FILES['file']['type'];
        // $file_size = $_FILES['file']['size'];
        // $file_tmp_name = $_FILES['file']['tmp_name'];
    endif;
endif;

if(isset($_POST['cria_cod_cardapio']) && $_POST['cria_cod_cardapio'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $pararLoop = 8999;

    for($numero = 0; ; $numero++){
        $cod = mt_rand(1000, 9999);

        $sql = "SELECT * FROM cozinha_cardapio WHERE cod='$cod'";
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
            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel finalizar o cadastro!';
            echo json_encode($retorno);
            break;
        endif;
    }
    exit();
endif;

if(isset($_POST['cadastra_cardapio']) && $_POST['cadastra_cardapio'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $parar       = (isset($_POST['parar'])) ? $_POST['parar'] : '' ;

    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $nome   = (isset($_POST['nome'])) ? $_POST['nome'] : '' ;
    $categoria   = (isset($_POST['categoria'])) ? $_POST['categoria'] : '' ;
    $cod_ingrediente   = (isset($_POST['cod_ingrediente'])) ? $_POST['cod_ingrediente'] : '' ;
    $tipo_ingrediente    = (isset($_POST['tipo_ingrediente'])) ? $_POST['tipo_ingrediente'] : '' ;
    $qtd    = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    $variacao   = (isset($_POST['variacao'])) ? $_POST['variacao'] : '' ;
    $unidade   = (isset($_POST['unidade'])) ? $_POST['unidade'] : '' ;
    $img   = (isset($_POST['img'])) ? $_POST['img'] : '' ;
    $nome_variacao   = (isset($_POST['nome_variacao'])) ? $_POST['nome_variacao'] : '' ;

    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($nome) || empty($categoria) || empty($cod_ingrediente) || empty($tipo_ingrediente) || empty($qtd) || empty($variacao) || empty($unidade) || empty($img) || empty($nome_variacao)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel finalizar o cadastro!';
        echo json_encode($retorno);
        exit();
    endif;

    $editable = 'true';
    $ativo = 'true';

    $sql = 'INSERT INTO cozinha_cardapio (cod, nome, categoria, cod_ingrediente, tipo_ingrediente, qtd_ingrediente, unidade, variacao, nome_variacao, img, editable, ativo) VALUES (:cod, :nome, :categoria, :cod_ingrediente, :tipo_ingrediente, :qtd_ingrediente, :unidade, :variacao, :nome_variacao, :img, :editable, :ativo)';
    $stmt = $conexaoAdmin->prepare($sql);
    $stmt->bindParam(':cod', $cod);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':cod_ingrediente', $cod_ingrediente);
    $stmt->bindParam(':tipo_ingrediente', $tipo_ingrediente);
    $stmt->bindParam(':qtd_ingrediente', $qtd);
    $stmt->bindParam(':unidade', $unidade);
    $stmt->bindParam(':variacao', $variacao);
    $stmt->bindParam(':nome_variacao', $nome_variacao);
    $stmt->bindParam(':img', $img);
    $stmt->bindParam(':editable', $editable);
    $stmt->bindParam(':ativo', $ativo);
    $resposta = $stmt->execute();

    if( !$resposta ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel finalizar o cadastro!';
        echo json_encode($retorno);
        exit();
    elseif($resposta && $parar == 1):
        $retorno['erro']     = '0';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['montaModal']) && $_POST['montaModal'] == '1'):
    require_once("conexao.php");
    $retorno = array();
    $html = array();
    
    $cod  = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    if(empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel preosseguir!';
        echo json_encode($retorno);
        exit();
    endif;
   
    $sql = "SELECT categoria FROM cozinha_cardapio WHERE cod='$cod' && ativo='true' GROUP BY variacao";
    $resultado = $conexaoAdmin->prepare($sql);	
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar == 0):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno!';
        echo json_encode($retorno);
        exit();
    endif;

    $exibe = $resultado->fetch(PDO::FETCH_OBJ);
    $retorno['variacoes']    = $contar;

    $idCategoria = $exibe->categoria;
    $retorno['idCategoria'] = $idCategoria;

    $sql4 = "SELECT categoria FROM categoria_cardapio WHERE cod='$idCategoria' GROUP BY cod";
    $resultado4 = $conexaoAdmin->prepare($sql4);	
    $resultado4->execute();
    $contar4 = $resultado4->rowCount();

    if($contar4 == 0):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno!';
        echo json_encode($retorno);
        exit();
    endif;

    $exibe4 = $resultado4->fetch(PDO::FETCH_OBJ);
    $categoria = $exibe4->categoria;

    $retorno['categoria']   = $categoria;

    $sql2 = "SELECT * FROM cozinha_cardapio WHERE cod='$cod' && ativo='true'";
    $resultado2 = $conexaoAdmin->prepare($sql2);	
    $resultado2->execute();
    $contar2 = $resultado2->rowCount();

    if($contar2 == 0):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno!';
        echo json_encode($retorno);
        exit();
    endif;

    $html['list1'] = '';
    $html['list2'] = '';
    $html['list3'] = '';
    $html['list4'] = '';
    $html['list5'] = '';
    $html['base1'] = '';
    $html['base2'] = '';
    $html['base3'] = '';
    $html['base4'] = '';
    $html['base5'] = '';
    $html['fecha1'] = '';
    $html['fecha2'] = '';
    $html['fecha3'] = '';
    $html['fecha4'] = '';
    $html['fecha5'] = '';

    while($exibe2 = $resultado2->fetch(PDO::FETCH_OBJ)){
        $variacao = $exibe2->variacao;
        $retorno['nome'] = $exibe2->nome;

        $html['base'.$variacao] = '<div class="card d-inline text-white bg-muted text-dark variacao variacao-'.$variacao.'" data-variacao="'.$variacao.'"><ul class="list-group list-group-flush border mb-3"><li class="list-group-item d-flex justify-content-between bg-light text-secondary rounded-top"><span class="mb-0 nome-variacao">'.$exibe2->nome_variacao.'</span><strong class="fs-18 cPointer editar"><i class="fa-solid fa-pen-to-square"></i></strong></li>';

        $html['fecha'.$variacao] = '</ul></div>';

        if($exibe2->unidade == 'un'):
            $qtdF = $exibe2->qtd_ingrediente.$exibe2->unidade;
        else:
            $qtdF = $exibe2->qtd_ingrediente.' '.$exibe2->unidade;
        endif;

        if($exibe2->tipo_ingrediente == '1'):
            $baseBusca = 'receitas';
            $selecaoBusca = 'receita';
        elseif($exibe2->tipo_ingrediente == '2'):
            $baseBusca = 'estoque';
            $selecaoBusca = 'produto';
        endif;

        $sql3 = "SELECT ".$selecaoBusca." FROM ".$baseBusca." WHERE cod='$exibe2->cod_ingrediente'";
        $resultado3 = $conexaoAdmin->prepare($sql3);	
        $resultado3->execute();
        $contar3 = $resultado3->rowCount();

        if($contar3 == 0):
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Tivemos um erro interno!';
            echo json_encode($retorno);
            exit();
        else:
            $exibe3 = $resultado3->fetch(PDO::FETCH_OBJ);
        endif;

        $html['list'.$variacao] = $html['list'.$variacao].'<li class="list-group-item item d-flex justify-content-between" data-cod="'.$exibe2->cod_ingrediente.'" data-tipo="'.$exibe2->tipo_ingrediente.'"><span class="mb-0"><span class="mr-2"><i class="fa fa-chevron-right"></i></span> <span class="ingrediente">'.$exibe3->$selecaoBusca.'</span></span><span class="quantidade" data-qtd="'.$exibe2->qtd_ingrediente.'" data-un="'.$exibe2->unidade.'">'.$qtdF.'</span></li>';
    }

    $retorno['listVariacoes'] = $html['base1'].$html['list1'].$html['fecha1'].$html['base2'].$html['list2'].$html['fecha2'].$html['base3'].$html['list3'].$html['fecha3'].$html['base4'].$html['list4'].$html['fecha4'].$html['base5'].$html['list5'].$html['fecha5'];

    $retorno['erro'] = '0';
    echo json_encode($retorno);
    exit();
endif;

if(isset($_POST['preEdit']) && $_POST['preEdit'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $cod  = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    if(empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel preosseguir!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql1 = "SELECT * FROM cozinha_cardapio WHERE cod='$cod' && ativo='true'";
    $resultado1 = $conexaoAdmin->prepare($sql1);	
    $resultado1->execute();
    $contar1 = $resultado1->rowCount();

    if($contar1 == 0):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel preosseguir!';
        echo json_encode($retorno);
        exit();
    else:
        $exibe1 = $resultado1->fetch(PDO::FETCH_OBJ);

        $ativo = 'false';

        $sql2 = 'UPDATE cozinha_cardapio SET ativo=:ativo WHERE cod=:cod';
        $stmt2 = $conexaoAdmin->prepare($sql2);
        $stmt2->bindParam(':cod', $cod);
        $stmt2->bindParam(':ativo', $ativo);
        $resposta2 = $stmt2->execute();
    
        if( !$resposta2 ):
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel preosseguir!';
            echo json_encode($retorno);
        endif;

        $retorno['img']     = $exibe1->img;
        $retorno['erro']     = '0';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['atualiza_cardapio']) && $_POST['atualiza_cardapio'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $parar       = (isset($_POST['parar'])) ? $_POST['parar'] : '' ;

    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $nome   = (isset($_POST['nome'])) ? $_POST['nome'] : '' ;
    $categoria   = (isset($_POST['categoria'])) ? $_POST['categoria'] : '' ;
    $cod_ingrediente   = (isset($_POST['cod_ingrediente'])) ? $_POST['cod_ingrediente'] : '' ;
    $tipo_ingrediente    = (isset($_POST['tipo_ingrediente'])) ? $_POST['tipo_ingrediente'] : '' ;
    $qtd    = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    $variacao   = (isset($_POST['variacao'])) ? $_POST['variacao'] : '' ;
    $unidade   = (isset($_POST['unidade'])) ? $_POST['unidade'] : '' ;
    $img   = (isset($_POST['img'])) ? $_POST['img'] : '' ;
    $nome_variacao   = (isset($_POST['nome_variacao'])) ? $_POST['nome_variacao'] : '' ;

    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($nome) || empty($categoria) || empty($cod_ingrediente) || empty($tipo_ingrediente) || empty($qtd) || empty($variacao) || empty($unidade) || empty($img) || empty($nome_variacao)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel preosseguir!';
        echo json_encode($retorno);
        exit();
    endif;

    $editable = 'true';
    $ativo = 'true';

    $sql = 'INSERT INTO cozinha_cardapio (cod, nome, categoria, cod_ingrediente, tipo_ingrediente, qtd_ingrediente, unidade, variacao, nome_variacao, img, editable, ativo) VALUES (:cod, :nome, :categoria, :cod_ingrediente, :tipo_ingrediente, :qtd_ingrediente, :unidade, :variacao, :nome_variacao, :img, :editable, :ativo)';
    $stmt = $conexaoAdmin->prepare($sql);
    $stmt->bindParam(':cod', $cod);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':cod_ingrediente', $cod_ingrediente);
    $stmt->bindParam(':tipo_ingrediente', $tipo_ingrediente);
    $stmt->bindParam(':qtd_ingrediente', $qtd);
    $stmt->bindParam(':unidade', $unidade);
    $stmt->bindParam(':variacao', $variacao);
    $stmt->bindParam(':nome_variacao', $nome_variacao);
    $stmt->bindParam(':img', $img);
    $stmt->bindParam(':editable', $editable);
    $stmt->bindParam(':ativo', $ativo);
    $resposta = $stmt->execute();

    if( !$resposta ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel preosseguir!';
        echo json_encode($retorno);
        exit();
    elseif($resposta && $parar == 1):
        $retorno['erro']     = '0';
        echo json_encode($retorno);
        exit();
    endif;
endif;


if(isset($_POST['atualizaItens']) && $_POST['atualizaItens'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $sql = 'SELECT * FROM cozinha_cardapio WHERE ativo="true" GROUP BY cod ORDER BY nome ASC';
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0):
        $retorno['itens'] = '';
        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
            $cod_categoria = $exibe->categoria;
                        
            $sql2 = "SELECT categoria FROM categoria_cardapio WHERE cod='$cod_categoria' GROUP BY cod";
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            if($contar2 > 0):
                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
            endif;
    
            $retorno['itens'] = $retorno['itens'].'<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 cardapio-item animate__fadeIn animate__animated">
                <span class="d-none palavras-chaves">'.$exibe2->categoria.' '.$exibe->nome.'</span>
                <div class="card cPointer cardapio-item-body shadow-sm" data-cod="'.$exibe->cod.'">
                    <div class="card-body d-flex p-0">
                        <div class="div-img-quadrado rounded-top" style="background-image: url(\'upload/cardapio/'.$exibe->img.'\');"></div>
                    </div>
                    <div class="card-footer p-0">
                        <div class="">
                            <div class="new-arrival-content text-center mt-2 px-3 py-3">
                                <h4 class="fs-18"><a>'.$exibe->nome.'</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }//While
    else:
        $retorno['itens'] = '<div class="col-12">
            <span class="">Sem itens cadastrados.</span>
        </div>';
    endif;

    $retorno['erro'] = '0';
    echo json_encode($retorno);
    exit();
endif;




































if(isset($_POST['cria_cod_categoria']) && $_POST['cria_cod_categoria'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $pararLoop = 8999;

    for($numero = 0; ; $numero++){
        $cod = mt_rand(1000, 9999);

        $sql = "SELECT * FROM categoria_cardapio WHERE cod='$cod'";
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
            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel finalizar o cadastro!';
            echo json_encode($retorno);
            break;
        endif;
    }
    exit();
endif;

if(isset($_POST['cadastra_categoria']) && $_POST['cadastra_categoria'] == '1'):
    require_once("conexao.php");
    $retorno = array();
    
    $cod                = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $categoria          = (isset($_POST['categoria'])) ? $_POST['categoria'] : '' ;
    $tipo_ingrediente   = (isset($_POST['tipo_ingrediente'])) ? $_POST['tipo_ingrediente'] : '' ;
    $cod_ingrediente    = (isset($_POST['cod_ingrediente'])) ? $_POST['cod_ingrediente'] : '' ;
    $qtd                = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    $visible            = (isset($_POST['visible'])) ? $_POST['visible'] : '' ;
    $parar              = (isset($_POST['parar'])) ? $_POST['parar'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($categoria) || empty($tipo_ingrediente) || empty($cod_ingrediente) || empty($qtd) || empty($visible)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql = 'INSERT INTO categoria_cardapio (cod, categoria, tipo_ingrediente, cod_ingrediente, qtd_ingrediente, visible) VALUES (:cod, :categoria, :tipo_ingrediente, :cod_ingrediente, :qtd_ingrediente, :visible)';
    $stmt = $conexaoAdmin->prepare($sql);
    $stmt->bindParam(':cod', $cod);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':tipo_ingrediente', $tipo_ingrediente);
    $stmt->bindParam(':cod_ingrediente', $cod_ingrediente);
    $stmt->bindParam(':qtd_ingrediente', $qtd);
    $stmt->bindParam(':visible', $visible);
    $resposta = $stmt->execute();

    if( !$resposta ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel finalizar o cadastro!';
        echo json_encode($retorno);
        exit();
    elseif($resposta && $parar == 1):
        $retorno['erro']     = '0';
        echo json_encode($retorno);
        exit();
    endif;
endif;