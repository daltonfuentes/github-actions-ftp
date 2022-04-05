<div class="modal fade modal-fullscreen fullscreen-lg" id="addReceita">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar receita</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form">
                    <form id="form-receita-1" class="">
                        <div class="row justify-content-md-center">
                            <div class="form-group col-md-12">
                                <label>Receita</label>
                                <input name="nome-receita" type="text" class="form-control input-border">
                            </div>
                            <div class="col-md-12">
                                <label>Imagem</label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="btn btn-primary" for="input-upload-img">UPLOAD</label>
                                        
                                    </div>
                                    <input type="text" class="form-control input-border" placeholder="Adicione uma imagem do produto" id="fake-input-upload" disabled>
                                </div>
                            </div> 
                            <div class="table-responsive pt-2">
                                <table class="table header-border verticle-middle">
                                    <thead>
                                        <tr>
                                            <th class="pt-0" scope="col">Ingrediente</th>
                                            <th class="pt-0" scope="col">Quantidade</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-receitas">
                                        <tr class="item">
                                            <td style="width: 70%;" class="pr-2">
                                                <div class="form-group">
                                                    <select class="select-produto select-valida" name="select-produto">
                                                        <option class="py-3 px-2 border border-light" value="">Escolha o
                                                            ingrediente</option>
                                                        <?php            
                                                        $sql = 'SELECT * FROM estoque';
                                                        try{
                                                            $resultado = $conexaoAdmin->prepare($sql);
                                                            $resultado->execute();
                                                            $contar = $resultado->rowCount();

                                                            if($contar > 0){

                                                                while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                        ?>
                                                        <option class="py-3 px-2 border border-light" data-un="<?php echo $exibe->unidade; ?>" value="<?php echo $exibe->cod; ?>"> <?php echo $exibe->produto; ?></option>
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
                                            <td style="width: 30%;"  class="pl-2">
                                                <div class="form-group">
                                                    <input name="qtd" class="qtd form-control input-border" type="text" disabled>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-outline-light btn-block btn-sm btn-square addLinha" type="button"
                                    style="margin-top: -25px;">+Adicionar</button>
                            </div>

                        </div>
                        <button type="submit" id="submit-real" class="d-none"></button>
                    </form>
                    <form id="add-nova-receita" method="post">
                        <input type="file" class="d-none" id="input-upload-img" name="img-receita">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary finalizar">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-fullscreen fullscreen-md" id="addIngrediente">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar ingrediente</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form">
                    <form id="" class="">
                        <div class="row justify-content-md-center">
                            <div class="form-group col-md-8 pr-2 mb-0">
                                <label>Ingrediente</label>
                                <select class="select-produto select-valida" name="select-produto">
                                    <option class="py-3 px-2 border border-light" value="">Escolha o
                                        ingrediente</option>
                                    <?php            
                                    $sql = 'SELECT * FROM estoque';
                                    try{
                                        $resultado = $conexaoAdmin->prepare($sql);
                                        $resultado->execute();
                                        $contar = $resultado->rowCount();

                                        if($contar > 0){

                                            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                    ?>
                                    <option class="py-3 px-2 border border-light" data-un="<?php echo $exibe->unidade; ?>" value="<?php echo $exibe->cod; ?>"> <?php echo $exibe->produto; ?></option>
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
                            <div class="form-group col-md-4 pl-2 mb-0">
                                <label>Quantidade</label>
                                <input name="qtd" class="qtd form-control input-border" type="text" disabled>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary finalizar">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-fullscreen fullscreen-md" id="editIngrediente" data-id="" data-un="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar ingrediente - #<span class="id-item"></span></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form">
                    <form id="" class="">
                        <div class="row justify-content-md-center">
                            <div class="form-group col-md-8 pr-2 mb-0">
                                <label>Ingrediente</label>
                                <input name="ingrediente" type="text" class="form-control input-border bg-light cNoDrop" disabled>
                            </div>
                            <div class="form-group col-md-4 pl-2 mb-0">
                                <label>Quantidade</label>
                                <input name="qtd" class="form-control input-border" type="text" disabled>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary finalizar">Salvar</button>
            </div>
        </div>
    </div>
</div>