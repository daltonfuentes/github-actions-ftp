<div class="modal fade modal-fullscreen fullscreen-xl" id="addCompra">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto;"> <!-- max-height: 700px; --> 
                <div class="default-tab">
                    <ul id="tabCompra" class="nav nav-tabs d-none" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link " id="aEtapa1" data-toggle="tab" href="#etapa1"><i
                                    class="la la-home mr-2"></i>
                                01</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="aEtapa2" data-toggle="tab" href="#etapa2"><i
                                    class="la la-user mr-2"></i>
                                02</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="aEtapa3" data-toggle="tab" href="#etapa3"><i
                                    class="la la-phone mr-2"></i>
                                03</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane d-none" id="etapa1" role="tabpanel">
                            <div class="pt-4">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-12 col-xxl-4 col-sm-12">
                                        <div class="card border input-border pb-1">
                                            <div class="card-body text-center ai-icon text-primary row">
                                                <div class="col-12">
                                                    <i class="far fa-store-alt mb-2" style="font-size: 75px;"></i>
                                                </div>
                                                <div class="col-12">
                                                    <div class="btn btn-primary light my-2 btn-lg px-4 mt-4 tipo-compras"
                                                        data-tipo="lista">Lista de compras</div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-12 col-xxl-4 col-sm-12">
                                        <div class="card border input-border pb-1">
                                            <div class="card-body text-center ai-icon text-primary">
                                                <div class="col-12">
                                                    <i class="far fa-shopping-basket mb-2" style="font-size: 75px;"></i>
                                                </div>
                                                <div class="col-12">
                                                    <div class="btn my-2 btn-primary light btn-lg px-4 mt-4 tipo-compras"
                                                        data-tipo="fornecedor">Único fornecedor</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade  show active" id="etapa2">
                            <div class="">
                                <form id="form-add-compra">
                                    <div class="form-group">
                                        <label class="text-black font-w500">Fornecedor</label>
                                        <select name="uf-fornecedor" class="select-fornecedor">
                                            <option class="py-3 px-2 border border-light" value="">Escolha o fornecedor</option>
                                            <?php            
                                            $sql = 'SELECT * FROM fornecedor';
                                            try{
                                                $resultado = $conexaoAdmin->prepare($sql);
                                                $resultado->execute();
                                                $contar = $resultado->rowCount();

                                                if($contar > 0){

                                                    while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                                    <option class="py-3 px-2 border border-light" value="<?php echo $exibe->id; ?>"><?php echo $exibe->fornecedor; ?></option>
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
                                    <div class="form-group">
                                        <label class="text-black font-w500">Data</label>
                                        <input name="uf-data-compra" class="date form-control input-border" type="text">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="etapa3">
                            <div class="">
                                <form id="form-compras-produtos">
                                    <div class="row justify-content-md-center">
                                        <div class="table-responsive">
                                            <table class="table header-border verticle-middle">
                                                <thead>
                                                    <tr>
                                                        <th class="pt-0" scope="col">Produto</th>
                                                        <th class="pt-0" scope="col">Marca</th>
                                                        <th class="pt-0" scope="col">Qtd.</th>
                                                        <th class="pt-0" scope="col">Validade</th>
                                                        <th class="pt-0" scope="col">Valor total</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-fornecedor">
                                                    <tr class="item">
                                                        <td style="width: 30%;">
                                                            <div class="form-group">
                                                                <select class="select-produto select-valida" name="select-produto">
                                                                    <option class="py-3 px-2 border border-light" value="">Escolha o produto</option>
                                                                    <?php            
                                                                    $sql = 'SELECT * FROM estoque';
                                                                    try{
                                                                        $resultado = $conexaoAdmin->prepare($sql);
                                                                        $resultado->execute();
                                                                        $contar = $resultado->rowCount();

                                                                        if($contar > 0){

                                                                            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                                    ?>
                                                                            <option class="py-3 px-2 border border-light" data-un="<?php echo $exibe->unidade; ?>" value="<?php echo $exibe->cod; ?>"><?php echo $exibe->produto; ?></option>
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
                                                            <div class="form-group">
                                                                <input name="marca" class="form-control input-border" type="text">
                                                            </div>
                                                        </td>
                                                        <td style="width: 15%;">
                                                            <div class="form-group">
                                                                <input name="qtd" class="form-control input-border" disabled type="text">
                                                            </div>
                                                        </td>
                                                        <td style="width: 15%;">
                                                            <div class="form-group">
                                                                <input name="validade" class="date form-control input-border">
                                                            </div>
                                                        </td>
                                                        <td style="width: 15%;">
                                                            <div class="form-group">
                                                                <input name="preco" class="form-control input-border maskMoney">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-outline-light btn-block btn-sm btn-square addLinha"
                                                type="button" style="margin-top: -25px;">+Adicionar</button>
                                        </div>

                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="form-group">
                    <button type="button" class="btn btn-primary voltar d-none" style="float: left;">Voltar</button>
                    <button type="button" class="btn btn-primary proximo" style="float: right;">Proximo</button>
                    <button type="button" class="btn btn-primary finalizar d-none" style="float: right;">Finalizar</button>
                </div>
            </div>
        </div>
    </div>
</div>