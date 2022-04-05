<div class="modal fade modal-fullscreen fullscreen-md" id="addCardapio">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-modal-cardapio">
            <div class="modal-header">
                <h5 class="modal-title text-terceiro">Adicionar produto</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0">
                <form class=""> 
                    <div class="row justify-content-md-center custom-tab-1">

                        <div class="form-group col-12 div-produto mb-4">
                            <label>Produto</label>
                            <select name="produto" class="form-control default-select bg-modal-cardapio">
                                <option class="py-4" value="">Selecione o produto</option>
                                <?php            
                                $sql = 'SELECT * FROM cozinha_cardapio WHERE ativo="true" AND valor_ifood IS NULL AND valor_whats IS NULL GROUP BY cod';
                                $resultado = $conexaoAdmin->prepare($sql);
                                $resultado->execute();
                                $contar = $resultado->rowCount();

                                if($contar > 0):

                                    while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                ?>
                                <option class="py-4" value="<?php echo $exibe->cod; ?>"><?php echo '#'.$exibe->cod.' - '.$exibe->nome; ?></option>
                                <?php
                                    }//While
                                else:
                                //Informar que não existem parceiros cadastrados - ERRO-M
                                endif;
                                ?>
                            </select>
                        </div>

                        <div class="row box-variacoes col-12 px-0">
                            
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary salvar">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-fullscreen fullscreen-md" id="editCardapio">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-modal-cardapio">

        </div>
    </div>
</div>