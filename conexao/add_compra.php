<?php
ob_start();
session_start();

if(isset($_POST['criaCod']) && $_POST['criaCod'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $pararLoop = 8999;

    for($numero = 0; ; $numero++){
        $cod = mt_rand(1000, 9999);

        $sql = "SELECT * FROM compras WHERE cod='$cod'";
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

if(isset($_POST['cadastraCompra']) && $_POST['cadastraCompra'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $produto   = (isset($_POST['produto'])) ? $_POST['produto'] : '' ;
    $marca    = (isset($_POST['marca'])) ? $_POST['marca'] : '' ;
    $qtd    = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    $validade    = (isset($_POST['validade'])) ? $_POST['validade'] : '' ;
    $valorTotal   = (isset($_POST['total'])) ? $_POST['total'] : '' ;
    $fornecedor   = (isset($_POST['fornecedor'])) ? $_POST['fornecedor'] : '' ;
    $data_compra   = (isset($_POST['data_compra'])) ? $_POST['data_compra'] : '' ;
    $unidade   = (isset($_POST['unidade'])) ? $_POST['unidade'] : '' ;
    $qtdItens = (isset($_POST['qtdItens'])) ? $_POST['qtdItens'] : '' ; 
    $itemAtual = (isset($_POST['itemAtual'])) ? $_POST['itemAtual'] : '' ;
    $pagina = (isset($_POST['pagina'])) ? $_POST['pagina'] : '' ;

    if(isset($_POST['addItem']) && $_POST['addItem'] == '1'):
        $dataAdd   = (isset($_POST['data_add'])) ? $_POST['data_add'] : '' ;
    else:
        $dataAdd = date("Y-m-d H:i:s");
    endif;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($produto) || empty($marca) || empty($qtd) || empty($validade) || empty($valorTotal) || empty($fornecedor) || empty($data_compra) || empty($dataAdd) || empty($unidade)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Preencha todos os campos corretamente!';
        echo json_encode($retorno);
        exit();
    endif;

    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($qtdItens) || empty($itemAtual)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o desenvolvedor.';
        echo json_encode($retorno);
        exit();
    endif;

    $valorTotal = reaisParaSql($valorTotal);

    $valorUnidade = $valorTotal/$qtd;

    $dataId = dataParaSql($data_compra);
    
    $sql = 'INSERT INTO compras (cod, produto, fornecedor, data_compra, marca, validade, qtd, valor_total, valor_un, dataId, dataAdd, unidade) VALUES (:cod, :produto, :fornecedor, :data_compra, :marca, :validade, :qtd, :valor_total, :valor_un, :dataId, :dataAdd, :unidade)';
    $stmt = $conexaoAdmin->prepare($sql);
    $stmt->bindParam(':cod', $cod);
    $stmt->bindParam(':produto', $produto);
    $stmt->bindParam(':fornecedor', $fornecedor);
    $stmt->bindParam(':data_compra', $data_compra);
    $stmt->bindParam(':marca', $marca);
    $stmt->bindParam(':validade', $validade);
    $stmt->bindParam(':qtd', $qtd);
    $stmt->bindParam(':valor_total', $valorTotal);
    $stmt->bindParam(':valor_un', $valorUnidade);
    $stmt->bindParam(':dataId', $dataId);
    $stmt->bindParam(':dataAdd', $dataAdd);
    $stmt->bindParam(':unidade', $unidade);
    $resposta = $stmt->execute();

    if( !$resposta ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o desenvolvedor.';
        echo json_encode($retorno);
        exit();
    else:
        if($itemAtual == $qtdItens):
            if($pagina == '1'):
                $sql = "SELECT count(id) total, sum(valor_total) soma FROM compras WHERE cod='$cod'"; 
                $resultado = $conexaoAdmin->prepare($sql);
                $resultado->execute();
                $contar = $resultado->rowCount();

                if($contar > 0):
                    $exibe = $resultado->fetch(PDO::FETCH_OBJ);

                    $retorno['soma'] = numeroParaReal($exibe->soma);
                    $retorno['itens'] = $exibe->total;
                else:
                    $retorno['refresh'] = 'true';
                endif;

                $sql1 = "SELECT * FROM compras WHERE cod='$cod' ORDER BY id DESC LIMIT 1";
                $resultado1 = $conexaoAdmin->prepare($sql1);	
                $resultado1->execute();
                $contar1 = $resultado1->rowCount();
                
                if($contar1 > 0):
                    $exibe1 = $resultado1->fetch(PDO::FETCH_OBJ);

                    $sqlF = "SELECT * FROM estoque WHERE cod='$produto'";
                    $resultadoF = $conexaoAdmin->prepare($sqlF);	
                    $resultadoF->execute();
                    $resultadoF->rowCount();
                    $exibeF = $resultadoF->fetch(PDO::FETCH_OBJ);

                    if($unidade == 'un'):
                        $qtdF = $qtd.' un';
                    else:
                        $qtdF = $qtd.$unidade;
                    endif;

                    $retorno['linha'] = '<tr class="item-compra animate__animated" data-id="'.$exibe1->id.'">
                                            <td>'.$exibe1->id.'</td>
                                            <td class="produto">'.$exibeF->produto.'</td>
                                            <td class="marca">'.$marca.'</td>
                                            <td class="validade">'.$validade.'</td>
                                            <td class="qtdItem" data-un="'.$unidade.'" data-info="'.$qtd.'">'.$qtdF.'</td>
                                            <td class="valor-un">'.numeroParaReal($valorUnidade).'</td>
                                            <td class="valor-total">'.numeroParaReal($valorTotal).'</td>
                                            <td class="w-10">
                                                <div class="d-flex">
                                                    <a class="btn btn-primary light shadow sharp mr-3 editItem"><i class="fa fa-pencil"></i></a>
                                                    <a class="btn btn-danger light shadow sharp apagaItem"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>';
                    
                    $retorno['refresh'] = 'false';
                else:
                    $retorno['refresh'] = 'true';
                endif;

                $retorno['erro']     = '0';
                $retorno['mensagem'] = 'Produto adicionado corretamente!';
                echo json_encode($retorno);
                exit();
            else:
                $sql = "SELECT cod, fornecedor, data_compra, dataAdd, dataId, count(id) total, sum(valor_total) soma FROM compras WHERE cod='$cod'"; 
                $resultado = $conexaoAdmin->prepare($sql);
                $resultado->execute();
                $contar = $resultado->rowCount();

                if($contar > 0):
                    $exibe = $resultado->fetch(PDO::FETCH_OBJ);
                    $fornecedor = $exibe->fornecedor;
                    $sqlF = "SELECT * FROM fornecedor WHERE id='$fornecedor'";
                    $resultadoF = $conexaoAdmin->prepare($sqlF);	
                    $resultadoF->execute();
                    $resultadoF->rowCount();
                    $exibeF = $resultadoF->fetch(PDO::FETCH_OBJ);

                    if($exibe->total == 1):
                        $nItens = $exibe->total.' Item';
                    else:
                        $nItens = $exibe->total.' Itens';
                    endif;

                    
                    $retorno['linha'] = '<div class="media p-0 mb-4 alert alert-dismissible items-list-2 border-0 compra animate__animated" data-cod="'.$exibe->cod.'">
                                            <a><img class="img-fluid rounded mr-3" width="85" src="./upload/fornecedor/'.$exibeF->img.'" alt="DexignZone"></a>
                                            <div class="media-body col-6 px-0 pt-2">
                                                <h5 class="mt-0 mb-1"><a class="text-black">'.$exibeF->fornecedor.'</a></h5>
                                                <small class="font-w500 mb-3"><a class="text-primary">'.$nItens.'</a></small>
                                                <ul class="fs-14 list-inline">
                                                    <li class="mr-3"><a>Data da compra: '.$exibe->data_compra.'</a></li>
                                                    <li class="mr-3"><a>Adicionado á 2 Seg</a></li>
                                                </ul>
                                            </div>
                                            <div class="media-footer align-self-center ml-auto d-block align-items-center d-sm-flex">
                                                <h3 class="mb-0 font-w600 text-secondary"><a>'.numeroParaReal($exibe->soma).'</a></h3>
                                                <div class="dropdown ml-3 ">
                                                    <button type="button" class="btn btn-secondary sharp tp-btn-light " data-toggle="dropdown">
                                                        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item py-4" href="compra/'.$fornecedor."/".$exibe->cod.'">Editar</a>
                                                        <a class="dropdown-item py-4 cPointer delete">Excluir</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';

                    $retorno['refresh'] = 'false';
                else:
                    $retorno['refresh'] = 'true';
                endif;

                $retorno['erro']     = '0';
                $retorno['mensagem'] = 'Compra adicionada corretamente!';
                echo json_encode($retorno);
                exit();
            endif;
        endif;
    endif;
endif;

if(isset($_POST['editaItem']) && $_POST['editaItem'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $id       = (isset($_POST['id'])) ? $_POST['id'] : '' ;
    $marca    = (isset($_POST['marca'])) ? $_POST['marca'] : '' ;
    $qtd    = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    $validade    = (isset($_POST['validade'])) ? $_POST['validade'] : '' ;
    $valorTotal   = (isset($_POST['valorTotal'])) ? $_POST['valorTotal'] : '' ;
    $cod   = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    // VERIFICACAO 1 - SE PEGOU COD //
    if(empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel salvar as alterações!';
        echo json_encode($retorno);
        exit();
    endif;

    // VERIFICACAO 2 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($id) || empty($marca) || empty($qtd) || empty($validade) || empty($valorTotal)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Preencha todos os campos corretamente';
        echo json_encode($retorno);
        exit();
    endif;

    // CALCULA VALOR UN. //
    $valorTotal = reaisParaSql($valorTotal);

    $valorUnidade = $valorTotal/$qtd;

    // ATUALIZA DADOS //
    $sql1 = 'UPDATE compras SET marca=:marca, validade=:validade, qtd=:qtd, valor_total=:valor_total, valor_un=:valor_un WHERE id=:id';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':id', $id);
    $stmt1->bindParam(':marca', $marca);
    $stmt1->bindParam(':validade', $validade);
    $stmt1->bindParam(':qtd', $qtd);
    $stmt1->bindParam(':valor_total', $valorTotal);
    $stmt1->bindParam(':valor_un', $valorUnidade);
    $resposta1 = $stmt1->execute();

    if( !$resposta1 ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel salvar as alterações!';
        echo json_encode($retorno);
        exit();
    else:
        $sql2 = "SELECT sum(valor_total) soma FROM compras WHERE cod='$cod'";
        $resultado2 = $conexaoAdmin->prepare($sql2);	
        $resultado2->execute();
        $resultado2->rowCount();
        $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);

        $retorno['erro']     = '0';
        $retorno['valor_un'] = numeroParaReal($valorUnidade);
        $retorno['soma'] = numeroParaReal($exibe2->soma);
        $retorno['mensagem'] = 'Alterações foram salvas corretamente!';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['deletaItem']) && $_POST['deletaItem'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $id       = (isset($_POST['id'])) ? $_POST['id'] : '' ;
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    // VERIFICACAO 1 - SE PASSOU TUDO //
    if(empty($id) || empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel remover o item!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql1 = 'DELETE FROM compras WHERE id = :id';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':id', $id);
    $resposta1 = $stmt1->execute();

    if( !$resposta1 ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel remover o item!';
        echo json_encode($retorno);
        exit();
    else:
        $sql2 = "SELECT count(id) total, sum(valor_total) soma FROM compras WHERE cod='$cod'";
        $resultado2 = $conexaoAdmin->prepare($sql2);	
        $resultado2->execute();
        $resultado2->rowCount();
        $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);

        $sqlValida = "SELECT * FROM compras WHERE cod='$cod'";
        $resultadoValida = $conexaoAdmin->prepare($sqlValida);	
        $resultadoValida->execute();
        $contarValida = $resultadoValida->rowCount();

        if($contarValida == 0):
            $refresh = 1;
        else:
            $refresh = 0;
        endif;

        $retorno['erro']     = '0';
        $retorno['refresh']  = $refresh;
        $retorno['itens']    = $exibe2->total;
        $retorno['soma']     = numeroParaReal($exibe2->soma);
        $retorno['mensagem'] = 'Item excluido de compra!';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['excluiCompras']) && $_POST['excluiCompras'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    // VERIFICACAO 1 - SE PASSOU TUDO //
    if(empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel remover essa lista de compras!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql1 = 'DELETE FROM compras WHERE cod = :cod';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':cod', $cod);
    $resposta1 = $stmt1->execute();

    if( !$resposta1 ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel remover o item!';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '0';
        $retorno['mensagem'] = 'Lista de compras excluida!';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['consultaComrpras']) && $_POST['consultaComrpras'] == '1'):
    require_once("conexao.php");
    $response = array();

    $condicao = (isset($_POST['condicao'])) ? $_POST['condicao'] : '' ;

    if($condicao == '30' || $condicao == '60' || $condicao == '90'):
        $hoje = date('Ymd');
        $data = date('Ymd', strtotime('-'.$condicao.' days'));
        $sql = 'SELECT cod, fornecedor, data_compra, dataAdd, dataId, count(id) total, sum(valor_total) soma FROM compras WHERE dataId >= "'.$data.'" AND dataId <= "'.$hoje.'"  GROUP BY cod ORDER BY dataId DESC';
    else:
        $sql = 'SELECT cod, fornecedor, data_compra, dataAdd, dataId, count(id) total, sum(valor_total) soma FROM compras GROUP BY cod ORDER BY dataId DESC';
    endif;

    try{
        $resultado = $conexaoAdmin->prepare($sql);
        $resultado->execute();
        $contar = $resultado->rowCount();

        if($contar > 0):
            
            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                $fornecedor = $exibe->fornecedor;
                $sqlF = "SELECT * FROM fornecedor WHERE id='$fornecedor'";
                $resultadoF = $conexaoAdmin->prepare($sqlF);	
                $resultadoF->execute();
                $resultadoF->rowCount();
                $exibeF = $resultadoF->fetch(PDO::FETCH_OBJ);

                $dataAdd = $exibe->dataAdd;

                if($exibe->total == 1):
                    $nItens = $exibe->total.' Item';
                else:
                    $nItens = $exibe->total.' Itens';
                endif;

                $response[] = '<div class="media p-0 mb-4 alert alert-dismissible items-list-2 border-0 compra animate__fadeInUp animate__animated" data-cod="'.$exibe->cod.'"><a href="compra/'.$exibe->cod.'"><img class="img-fluid rounded mr-3" width="110" src="./upload/fornecedor/'.$exibeF->img.'" alt="DexignZone"></a><div class="media-body col-6 px-0 pt-3"><h5 class="mt-0 mb-0">#'.$exibe->cod.'</h5><h5 class="mt-0 mb-1"><a class="text-black">'.$exibeF->fornecedor.'</a></h5><small class="font-w500 mb-3"><a class="text-primary">'.$nItens.'</a></small><ul class="fs-14 list-inline"><li class="mr-3"><a>Data da compra: '.$exibe->data_compra.'</a></li><li class="mr-3 d-none"><a>Adicionado á '.comparaDatas01($dataAdd).'</a></li></ul></div><div class="media-footer align-self-center ml-auto d-block align-items-center d-sm-flex"><h3 class="mb-0 font-w600 text-secondary"><a>'.numeroParaReal($exibe->soma).'</a></h3><div class="ml-4 d-flex">
                <a class="btn btn-outline-secondary light sharp delete"><i class="fa fa-trash"></i></a>
            </div></div></div>';

            }//While
        else:
            $response[] = '<div class="p-2 text-center"><p>Não encontramos compras cadastrados</p></div>';
        endif;
    }catch(PDOException $erro){
    echo $erro;
    }

    echo json_encode($response);
    exit();
endif;

if(isset($_POST['consultaQtdComrpras']) && $_POST['consultaQtdComrpras'] == '1'):
    require_once("conexao.php");
    $retorno = array();

    $condicao = (isset($_POST['condicao'])) ? $_POST['condicao'] : '' ;

    if($condicao == '30' || $condicao == '60' || $condicao == '90'):
        $hoje = date('Ymd');
        $data = date('Ymd', strtotime('-'.$condicao.' days'));
        $sql = 'SELECT sum(valor_total) soma FROM compras WHERE dataId >= "'.$data.'" AND dataId <= "'.$hoje.'"  GROUP BY cod ORDER BY dataId DESC';
    else:
        $sql = 'SELECT sum(valor_total) soma FROM compras GROUP BY cod ORDER BY dataId DESC';
    endif;

    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();
    $pages = ceil($contar/10);
    $retorno['pages'] = $pages;
    
    $soma = 0;
    try{
        $resultado = $conexaoAdmin->prepare($sql);
        $resultado->execute();
        $contar = $resultado->rowCount();
    
        if($contar > 0):
            
            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                $soma = $soma+$exibe->soma;
            }//While
        else:
        //Informar que não existem parceiros cadastrados - ERRO-M
        endif;
    }catch(PDOException $erro){
        
    }

    $retorno['soma'] = numeroParaReal($soma);
    echo json_encode($retorno);
    exit();
endif;

if(isset($_POST['consultaMontaChart']) && $_POST['consultaMontaChart'] == '1'):
    require_once("conexao.php");

    $atual = array();
    $atual['mes'] = date('m');
    $atual['mesnome'] = numeroParaMes($atual['mes']);
    $atual['valor'] = 0; 

    $atualm1 = array();
    $atualm1['mes'] = date('m', strtotime('-1 month'));
    $atualm1['mesnome'] = numeroParaMes($atualm1['mes']);
    $atualm1['valor'] = 0;

    $atualm2 = array();
    $atualm2['mes'] = date('m', strtotime('-2 month'));
    $atualm2['mesnome'] = numeroParaMes($atualm2['mes']);
    $atualm2['valor'] = 0;

    $atualm3 = array();
    $atualm3['mes'] = date('m', strtotime('-3 month'));
    $atualm3['mesnome'] = numeroParaMes($atualm3['mes']);
    $atualm3['valor'] = 0;

    $atualm4 = array();
    $atualm4['mes'] = date('m', strtotime('-4 month'));
    $atualm4['mesnome'] = numeroParaMes($atualm4['mes']);
    $atualm4['valor'] = 0;

    $atualm5 = array();
    $atualm5['mes'] = date('m', strtotime('-5 month'));
    $atualm5['mesnome'] = numeroParaMes($atualm5['mes']);
    $atualm5['valor'] = 0;

    $sql = 'SELECT dataId, sum(valor_total) soma FROM compras GROUP BY cod ORDER BY dataId DESC';
    try{
        $resultado = $conexaoAdmin->prepare($sql);
        $resultado->execute();
        $contar = $resultado->rowCount();

        if($contar > 0):
            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                $mes = substr($exibe->dataId, 4, -2);
                $ano = substr($exibe->dataId, 0, -4);

                $dateSql = $ano.$mes;

                if($dateSql == date('Ym')):
                    $atual['valor'] = $atual['valor']+$exibe->soma;
                elseif($dateSql == date('Ym', strtotime('-1 month'))):
                    $atualm1['valor'] = $atualm1['valor']+$exibe->soma;
                elseif($dateSql == date('Ym', strtotime('-2 month'))):
                    $atualm2['valor'] = $atualm2['valor']+$exibe->soma;
                elseif($dateSql == date('Ym', strtotime('-3 month'))):
                    $atualm3['valor'] = $atualm3['valor']+$exibe->soma;
                elseif($dateSql == date('Ym', strtotime('-4 month'))):
                    $atualm4['valor'] = $atualm4['valor']+$exibe->soma;
                elseif($dateSql == date('Ym', strtotime('-5 month'))):
                    $atualm5['valor'] = $atualm5['valor']+$exibe->soma;
                endif;
                
            }//While
        else:
        //Informar que não existem parceiros cadastrados - ERRO-M
        endif;
    }catch(PDOException $erro){
        
    }

    $meses = array_reverse(array($atual, $atualm1, $atualm2, $atualm3, $atualm4, $atualm5));

    // $valores = array_reverse(array_column($meses, 'valor'));
    // $label = array_reverse(array_column($meses, 'mesnome'));

    echo json_encode($meses);
    exit();
endif;

if(isset($_POST['atualizaInfoCompra']) && $_POST['atualizaInfoCompra'] == '1'):
    require_once("conexao.php");
    $retorno = array();

    $cod = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $fornecedor = (isset($_POST['fornecedor'])) ? $_POST['fornecedor'] : '' ;
    $data_compra = (isset($_POST['data_compra'])) ? $_POST['data_compra'] : '' ;

    if(empty($cod) || empty($fornecedor) || empty($data_compra)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o desenvolvedor.';
        echo json_encode($retorno);
        exit();
    endif;

    $dataId = dataParaSql($data_compra);

    // ATUALIZA DADOS //
    $sql1 = 'UPDATE compras SET fornecedor=:fornecedor, data_compra=:data_compra, dataId=:dataId WHERE cod=:cod';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':cod', $cod);
    $stmt1->bindParam(':fornecedor', $fornecedor);
    $stmt1->bindParam(':data_compra', $data_compra);
    $stmt1->bindParam(':dataId', $dataId);
    $resposta1 = $stmt1->execute();

    if( !$resposta1 ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel salvar as alterações!';
        echo json_encode($retorno);
        exit();
    else:
        $sql2 = "SELECT * FROM fornecedor WHERE id='$fornecedor'";
        $resultado2 = $conexaoAdmin->prepare($sql2);	
        $resultado2->execute();
        $resultado2->rowCount();
        $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);

        $retorno['erro']     = '0';
        $retorno['fornecedor'] = $exibe2->fornecedor;
        $retorno['img'] = $exibe2->img;
        $retorno['data'] = $data_compra;
        $retorno['mensagem'] = 'As informações foram alteradas corretamente!';
        echo json_encode($retorno);
        exit();
    endif;

endif;




?>