<div class="modal fade modal-fullscreen fullscreen-lg modal-primary" id="addCardapioCozinha">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Item</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>  
            </div>
            <div class="modal-body">
                <form class="form-new-color"> 
                    <div class="row justify-content-md-center custom-tab-1">
                        <div class="form-group col-md-12">
                            <label>Nome do produto</label>
                            <input name="nome" type="text" class="form-control input-border">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Imagem</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="btn btn-primary" for="input-upload-img">UPLOAD</label>
                                    <input type="file" class="d-none" id="input-upload-img" name="img-cardapio">
                                </div>
                                <input type="text" class="form-control input-border" placeholder="Imagem do produto" id="fake-input-upload" disabled>
                            </div>
                        </div>
                        <div class="form-group col-md-6 div-categoria">
                            <label>Categoria</label>
                            <select name="categoria" class="form-control default-select">
                                <option class="py-4" value="">Selecione a categoria</option>
                                <?php            
                                $sql = 'SELECT * FROM categoria_cardapio GROUP BY cod ORDER BY categoria ASC';
                                try{
                                    $resultado = $conexaoAdmin->prepare($sql);
                                    $resultado->execute();
                                    $contar = $resultado->rowCount();

                                    if($contar > 0){

                                        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                ?>
                                <option class="py-4" value="<?php echo $exibe->id; ?>"><?php echo $exibe->categoria; ?></option>
                                <?php
                                        }//While
                                    }else{
                                    //Informar que não existem parceiros cadastrados - ERRO-M
                                    }
                                }catch(PDOException $erro){
                                echo $erro;
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6 div-variacoes">
                            <label>Variações</label>
                            <select name="variacoes" class="form-control default-select">
                                <option class="py-4" value="">Quantidade de variações</option>
                                <option class="py-4" value="1">1</option>
                                <option class="py-4" value="2">2</option>
                                <option class="py-4" value="3">3</option>
                                <option class="py-4" value="4">4</option>
                            </select>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="row">
                                <div class="col-12 box-variacoes">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary salvar">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-fullscreen fullscreen-lg modal-primary" id="editCardapioCozinha" data-cod="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Item</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-new-color"> 
                    <div class="row justify-content-md-center custom-tab-1">
                        <div class="form-group col-md-12">
                            <label>Nome do produto</label>
                            <input name="nome" type="text" class="form-control input-border">
                        </div>
                        <div class="form-group col-md-6 div-categoria">
                            <label>Categoria</label>
                            <select name="categoria" class="form-control default-select">
                                <option class="py-4" value="">Selecione a categoria</option>
                                <?php            
                                $sql = 'SELECT * FROM categoria_cardapio GROUP BY cod ORDER BY categoria ASC';
                                try{
                                    $resultado = $conexaoAdmin->prepare($sql);
                                    $resultado->execute();
                                    $contar = $resultado->rowCount();

                                    if($contar > 0){

                                        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                ?>
                                <option class="py-4" value="<?php echo $exibe->id; ?>"><?php echo $exibe->categoria; ?></option>
                                <?php
                                        }//While
                                    }else{
                                    //Informar que não existem parceiros cadastrados - ERRO-M
                                    }
                                }catch(PDOException $erro){
                                echo $erro;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 div-variacoes">
                            <label>Variações</label>
                            <select name="variacoes" class="form-control default-select">
                                <option class="py-4" value="">Quantidade de variações</option>
                                <option class="py-4" value="1">1</option>
                                <option class="py-4" value="2">2</option>
                                <option class="py-4" value="3">3</option>
                                <option class="py-4" value="4">4</option>
                            </select>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="row">
                                <div class="col-12 box-variacoes">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary salvar">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-fullscreen fullscreen-lg" id="editVariacao" data-variacao="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar variação</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-new-color"> 
                    <div class="row justify-content-md-center custom-tab-1">            
                        <div class="form-group col-md-12">
                            <label>Nome da variação</label>
                            <input name="nome" type="text" class="form-control input-border">
                        </div>
                        <div class="table-responsive mb-0 item box-tamanhos visible" data-variacao="1">
                            <table class="table header-border verticle-middle">
                                <thead>
                                    <tr>
                                        <th class="" scope="col">Receita/Produto</th>
                                        <th class="" scope="col">Peso (g) / Qtd (un.)</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-cozinha-cardapio">
                                    <tr class="item">
                                        <td style="width: 60%;">
                                            <div class="form-group mb-0">
                                                <select class="select-produto select-valida" name="select-produto">
                                                    <option class="py-3 px-2 border border-light" value="">Escolha o
                                                        ingrediente</option>
                                                    <?php            
                                                    $sql = 'SELECT * FROM producao GROUP BY cod_receita';
                                                    $resultado = $conexaoAdmin->prepare($sql);
                                                    $resultado->execute();
                                                    $contar = $resultado->rowCount();

                                                    if($contar > 0):

                                                        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                            $cod = $exibe->cod_receita;

                                                            $sql2 = "SELECT * FROM receitas WHERE cod='$cod'";
                                                            $resultado2 = $conexaoAdmin->prepare($sql2);
                                                            $resultado2->execute();
                                                            $contar2 = $resultado2->rowCount();
                                                            
                                                            if($contar2 > 0):
                                                                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                                                            
                                                    ?>
                                                                <option class="py-3 px-2 border border-light" data-unidade-medida="g" data-tipo="1" value="<?php echo $cod; ?>"><?php echo $exibe2->receita; ?></option>
                                                    <?php
                                                            endif;
                                                        }//While
                                                    else:
                                                        //Informar que não existem parceiros cadastrados - ERRO-M
                                                    endif;
                                                            
                                                    $sql = 'SELECT * FROM estoque';
                                                    try{
                                                        $resultado = $conexaoAdmin->prepare($sql);
                                                        $resultado->execute();
                                                        $contar = $resultado->rowCount();

                                                        if($contar > 0){

                                                            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                    ?>
                                                    <option class="py-3 px-2 border border-light" data-unidade-medida="value="<?php echo $exibe->unidade; ?>" data-tipo="2" value="<?php echo $exibe->cod; ?>"><?php echo $exibe->produto; ?></option>
                                                    <?php
                                                            }//While
                                                        }else{
                                                        //Informar que não existem parceiros cadastrados - ERRO-M
                                                        }
                                                    }catch(PDOException $erro){
                                                    echo $erro;
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width: 25%;">
                                            <div class="form-group mb-0">
                                                <input name="qtd" class="form-control input-border" disabled type="text">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-md-12 pt-4">
                                <button class="btn btn-outline-light btn-block btn-sm btn-square addLinha mb-0" type="button" style="margin-top: -25px;">+Adicionar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary salvar">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-fullscreen fullscreen-lg" id="addCategoria">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar categoria</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form">
                    <form class="form-new-color"> 
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Categoria</label>
                                <input name="categoria" type="text" class="form-control input-border">
                            </div>
                            <div class="form-group col-md-4 d-none">
                                <label>Visivel</label>
                                <select name="visible" class="form-control default-select">
                                    <option class="py-4" value="1">Visivel</option>
                                    <option class="py-4" value="0">Oculto</option>
                                </select>
                            </div>
                            <div class="table-responsive mb-0 item box-tamanhos visible">
                                <table class="table header-border verticle-middle">
                                    <thead>
                                        <tr>
                                            <th class="" scope="col">Receita/Produto</th>
                                            <th class="" scope="col">Peso (g) / Qtd (un.)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-cozinha-cardapio">
                                        <tr class="item">
                                            <td style="width: 60%;">
                                                <div class="form-group mb-0">
                                                    <select class="select-produto select-valida" name="select-produto">
                                                        <option class="py-3 px-2 border border-light" value="">Escolha o
                                                            ingrediente</option>
                                                        <?php            
                                                        $sql = 'SELECT * FROM producao GROUP BY cod_receita';
                                                        $resultado = $conexaoAdmin->prepare($sql);
                                                        $resultado->execute();
                                                        $contar = $resultado->rowCount();

                                                        if($contar > 0):

                                                            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                                $cod = $exibe->cod_receita;

                                                                $sql2 = "SELECT * FROM receitas WHERE cod='$cod'";
                                                                $resultado2 = $conexaoAdmin->prepare($sql2);
                                                                $resultado2->execute();
                                                                $contar2 = $resultado2->rowCount();
                                                                
                                                                if($contar2 > 0):
                                                                    $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                                                                
                                                        ?>
                                                                    <option class="py-3 px-2 border border-light" data-unidade-medida="g" data-tipo="1" value="<?php echo $cod; ?>"><?php echo $exibe2->receita; ?></option>
                                                        <?php
                                                                endif;
                                                            }//While
                                                        else:
                                                            //Informar que não existem parceiros cadastrados - ERRO-M
                                                        endif;
                                                                
                                                        $sql = 'SELECT * FROM estoque';
                                                        try{
                                                            $resultado = $conexaoAdmin->prepare($sql);
                                                            $resultado->execute();
                                                            $contar = $resultado->rowCount();

                                                            if($contar > 0){

                                                                while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                        ?>
                                                        <option class="py-3 px-2 border border-light" data-unidade-medida="<?php echo $exibe->unidade; ?>" data-tipo="2" value="<?php echo $exibe->cod; ?>"><?php echo $exibe->produto; ?></option>
                                                        <?php
                                                                }//While
                                                            }else{
                                                            //Informar que não existem parceiros cadastrados - ERRO-M
                                                            }
                                                        }catch(PDOException $erro){
                                                        echo $erro;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td style="width: 25%;">
                                                <div class="form-group mb-0">
                                                    <input name="qtd" class="form-control input-border" disabled type="text">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12 pt-4">
                                    <button class="btn btn-outline-light btn-block btn-sm btn-square addLinha mb-0" type="button" style="margin-top: -25px;">+Adicionar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary salvar">Salvar</button>
            </div>
        </div>
    </div>
</div>