<?php
ob_start();
session_start();

if(isset($_POST['cadastraProduto']) && $_POST['cadastraProduto'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $produto       = (isset($_POST['produto'])) ? $_POST['produto'] : '' ;
    $estoqueMinimo   = (isset($_POST['estoqueMinimo'])) ? $_POST['estoqueMinimo'] : '' ;
    $estoqueIdeal   = (isset($_POST['estoqueIdeal'])) ? $_POST['estoqueIdeal'] : '' ;
    $unidade   = (isset($_POST['unidade'])) ? $_POST['unidade'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($produto) || empty($estoqueMinimo) || empty($estoqueIdeal) || empty($unidade)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Preencha todos os campos corretamente!';
        echo json_encode($retorno);
        exit();
    endif;

    if($estoqueIdeal <= $estoqueMinimo):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'O Estoque Ideal precisa ser maior que Estoque minimo!';
        echo json_encode($retorno);
        exit();
    endif;

    $estoque_atual = '0';
    $visible = '1';
    $alert = '2';

    $sql = 'INSERT INTO estoque (produto, estoque_min, estoque_ideal, estoque_atual, unidade, visible, alert) VALUES (:produto, :estoqueMinimo, :estoqueIdeal, :estoque_atual, :unidade, :visible, :alert)';
    $stmt = $conexaoAdmin->prepare($sql);
    $stmt->bindParam(':produto', $produto);
    $stmt->bindParam(':estoqueMinimo', $estoqueMinimo);
    $stmt->bindParam(':estoqueIdeal', $estoqueIdeal);
    $stmt->bindParam(':unidade', $unidade);
    $stmt->bindParam(':estoque_atual', $estoque_atual);
    $stmt->bindParam(':visible', $visible);
    $stmt->bindParam(':alert', $alert);
    $resposta = $stmt->execute();

    if( !$resposta ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!';
        echo json_encode($retorno);
        exit();
    else:
        $sql1 = "SELECT * FROM estoque WHERE visible='1' ORDER BY cod DESC LIMIT 1";
        $resultado1 = $conexaoAdmin->prepare($sql1);	
        $resultado1->execute();
        $contar = $resultado1->rowCount();
        

        if($contar > 0):
            $exibe = $resultado1->fetch(PDO::FETCH_OBJ);

            $ideal = floatval($exibe->estoque_ideal);
            $atual = floatval($exibe->estoque_atual);
            $status = round(100*($atual/$ideal));    
            $min = floatval($exibe->estoque_min);
            if($atual>= $ideal):
                $bgl="bgl-primary";
                $bg="bg-primary";
                $badge="badge-primary";
                $alerta = "";
            elseif($atual < $ideal && $atual > $min):
                $bgl="bgl-warning";
                $bg="bg-warning";
                $badge="badge-warning";
                $alerta = '';
            else:
                $bgl="bgl-warning";
                $bg="bg-warning";
                $badge="badge-warning";
                $alerta = '<i class="fas fa-exclamation-triangle text-warning animate__animated animate__flash animate__infinite animate__slow"></i>';
            endif;

            $unidade = $exibe->unidade;
            if($unidade == 'un'):
                $space = ' ';
            else:
                $space = '';
            endif;

            $popover = 'data-toggle="popover" data-placement="top" data-html="true" data-content="Estoque mínimo: '.$min.$space.$unidade.'<br /> Estoque ideal: '.$ideal.$space.$unidade.'<br /> Estoque atual: '.$atual.$space.$unidade.'"';

            $linha =    '<tr class="estoque-item" data-cod="'.$exibe->cod.'">
                            <td scope="row"><strong>'.$exibe->cod.'</strong></td>
                            <td style="width: 40%;">'.$exibe->produto.' '.$alerta.'</td>
                            <td style="width: 35%;" '.$popover.'>
                                <div class="progress '.$bgl.'" style="height: 10px;">
                                    <div class="progress-bar progress-animated '.$bg.'" style="width: '.$status.'%;"
                                        role="progressbar">
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge '.$badge.' light">'.$status.'%</span>
                            </td>
                            <td style="width: 5%;">
                                <a class="btn btn-outline-secondary light sharp delete"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>';
            $retorno['erro']     = '0';
            $retorno['info']     = '1';
            $retorno['linha']     = $linha;
            echo json_encode($retorno);
            exit();
        else:
            $retorno['erro']     = '0';
            $retorno['info']     = '0';
            echo json_encode($retorno);
            exit();
        endif;
    endif;
endif;

if(isset($_POST['excluiProduto']) && $_POST['excluiProduto'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o desenvolvedor.';
        echo json_encode($retorno);
        exit();
    endif;

    $newVisible = '0';

    $sql1 = 'UPDATE estoque SET visible=:newVisible WHERE cod=:cod';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':cod', $cod);
    $stmt1->bindParam(':newVisible', $newVisible);
    $resposta1 = $stmt1->execute();

    if(!$resposta1):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel excluir o produto!';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '0';
        $retorno['mensagem'] = 'Produto excluido corretamente';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['atualizaTbodyProdutos']) && $_POST['atualizaTbodyProdutos'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    $retorno['linhas'] = '';

    $sql = 'SELECT * FROM estoque WHERE visible = "1" ORDER BY alert DESC';
    try{
        $resultado = $conexaoAdmin->prepare($sql);
        $resultado->execute();
        $contar = $resultado->rowCount();

        if($contar > 0):
            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                $ideal = floatval($exibe->estoque_ideal);
                $atual = floatval($exibe->estoque_atual);
                $status = round(100*($atual/$ideal));    
                $min = floatval($exibe->estoque_min);
                if($atual>= $ideal):
                    $bgl="bgl-primary";
                    $bg="bg-primary";
                    $badge="badge-primary";
                    $alerta = "";
                elseif($atual < $ideal && $atual > $min):
                    $bgl="bgl-warning";
                    $bg="bg-warning";
                    $badge="badge-warning";
                    $alerta = '';
                else:
                    $bgl="bgl-warning";
                    $bg="bg-warning";
                    $badge="badge-warning";
                    $alerta = '<i class="fas fa-exclamation-triangle text-warning animate__animated animate__flash animate__infinite animate__slow"></i>';
                endif;

                $unidade = $exibe->unidade;
                if($unidade == 'un'):
                    $space = ' ';
                else:
                    $space = '';
                endif;

                $popover = 'data-toggle="popover" data-placement="top" data-html="true" data-content="Estoque mínimo: '.$min.$space.$unidade.'<br /> Estoque ideal: '.$ideal.$space.$unidade.'<br /> Estoque atual: '.$atual.$space.$unidade.'"';

                $linha = '<tr class="estoque-item" data-cod="'.$exibe->cod.'">
                <td class="clickavel cPointer" scope="row"><strong>'.$exibe->cod.'</strong></td>
                <td class="clickavel cPointer" style="width: 40%;">'.$exibe->produto.' '.$alerta.'</td>
                <td class="clickavel cPointer" style="width: 35%;" '.$popover.'>
                    <div class="progress '.$bgl.'" style="height: 10px;">
                        <div class="progress-bar progress-animated '.$bg.'" style="width: '.$status.'%;"
                            role="progressbar">
                        </div>
                    </div>
                </td>
                <td class="clickavel cPointer">
                    <span class="badge '.$badge.' light">'.$status.'%</span>
                </td>
                <td style="width: 5%;">
                    <a class="btn btn-outline-secondary light sharp delete"><i class="fa fa-trash"></i></a>
                </td>
            </tr>';

                $retorno['linhas'] = $retorno['linhas'].$linha;
            }//While
        else:
            $retorno['linhas'] = $retorno['linhas'].'<tr>
                                                <td class="text-center pt-5" colspan="3">
                                                    Não existem produtos cadastrados.
                                                </td>
                                            </tr>';
        endif;
    }catch(PDOException $erro){
        
    }
    echo json_encode($retorno);
    exit();
endif;

if(isset($_POST['editaProduto']) && $_POST['editaProduto'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $produto       = (isset($_POST['produto'])) ? $_POST['produto'] : '' ;
    $min       = (isset($_POST['min'])) ? $_POST['min'] : '' ;
    $ideal       = (isset($_POST['ideal'])) ? $_POST['ideal'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($produto) ||empty($min) ||empty($ideal)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o desenvolvedor.';
        echo json_encode($retorno);
        exit();
    endif;

    // ATUALIZA DADOS //
    $sql1 = 'UPDATE estoque SET produto=:produto, estoque_min=:estoque_min, estoque_ideal=:estoque_ideal WHERE cod=:cod';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':cod', $cod);
    $stmt1->bindParam(':produto', $produto);
    $stmt1->bindParam(':estoque_min', $min);
    $stmt1->bindParam(':estoque_ideal', $ideal);
    $resposta1 = $stmt1->execute();

    if( !$resposta1 ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel salvar as alterações!';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '0';
        $retorno['mensagem'] = 'As informações foram alteradas corretamente!';
        echo json_encode($retorno);
        exit();
    endif;






endif;