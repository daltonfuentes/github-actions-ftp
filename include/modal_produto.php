<div class="modal fade modal-fullscreen fullscreen-md" id="editProduto">
    <div class="modal-dialog modal modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Produto - #<span class="id-produto"><?php echo $codProduto; ?></span><span
                        class="produto-item"></span></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Produto</label>
                                <input name="produto" type="text" class="form-control new-color-input input-border" value="<?php echo $nomeProduto; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Estoque minimo</label>
                                <input name="estoque_minimo" type="text" class="form-control new-color-input input-border" value="<?php if($unidade == ' un'): 
                                                                                                                                            if(strstr($min, '.')): 
                                                                                                                                                echo $min.$unidade;
                                                                                                                                            else:
                                                                                                                                                echo $min.'.0'.$unidade;
                                                                                                                                            endif; 
                                                                                                                                        else: 
                                                                                                                                            echo $min.$unidade; 
                                                                                                                                        endif; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Estoque ideal</label>
                                <input name="estoque_ideal" type="text" class="form-control new-color-input input-border" value="<?php if($unidade == ' un'): 
                                                                                                                                            if(strstr($ideal, '.')): 
                                                                                                                                                echo $ideal.$unidade;
                                                                                                                                            else:
                                                                                                                                                echo $ideal.'.0'.$unidade;
                                                                                                                                            endif; 
                                                                                                                                        else: 
                                                                                                                                            echo $ideal.$unidade; 
                                                                                                                                        endif; ?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary salvar">Salvar Mudanças</button>
            </div>
        </div>
    </div>
</div>