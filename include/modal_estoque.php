<div class="modal fade modal-fullscreen fullscreen-md" id="addProduto">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar produto</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form">
                    <form class="form-new-color"> 
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Produto</label>
                                <input name="produto" type="text" class="form-control input-border">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Estoque minimo</label>
                                <input name="estoque_minimo" type="text" class="qtdun form-control input-border">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Estoque ideal</label>
                                <input name="estoque_ideal" type="text" class="qtdun form-control input-border">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Unidade M.</label>
                                <select name="unidade" class="form-control default-select">
                                    <option class="py-4" value="un">un</option>
                                    <option class="py-4" value="g">g</option>
                                    <option class="py-4" value="ml">ml</option>
                                </select>
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