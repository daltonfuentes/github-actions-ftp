<div class="modal fade" id="modalOrderCancel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancelar pedido</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-4">Cancelar muitos pedidos pode afetar o desempenho da sua loja no iFood. Assim que possivel, ajuste sua operação para não cancelar novos pedidos pelo mesmo motivo.</p>
                <p class="text-danger mb-4">ATENÇÃO: Muitos cancelamentos pela falta de confirmação podem fechar o seu restaurante na plataforma.</p>
                <p class="mb-4 question_cancel"></p>
                <div class="row">
                    <div class="col-4 pr-0">
                        <div class="form-group mb-0">
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="501">
                                    <span class="checkmark ml-2">Outro</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="504">
                                    <span class="checkmark ml-2">Restaurante sem entregador</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="507">
                                    <span class="checkmark ml-2">Cliente golpista / Trote</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="511">
                                    <span class="checkmark ml-2">Área de risco</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 pr-0">
                        <div class="form-group mb-0">
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="502">
                                    <span class="checkmark ml-2">Pedido em duplicidade</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="505">
                                    <span class="checkmark ml-2">Cardápio desatualizado</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="508">
                                    <span class="checkmark ml-2">Fora do horário de entrega</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="512">
                                    <span class="checkmark ml-2">Restaurante irá abrir mais tarde</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 pr-0">
                        <div class="form-group mb-0">
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="503">
                                    <span class="checkmark ml-2">Item indisponível</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="506">
                                    <span class="checkmark ml-2">Pedido fora da área de entrega</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="509">
                                    <span class="checkmark ml-2">Dificuldades internas do restaurante</span>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="cPointer">
                                    <input class="radio1_5x cPointer" type="radio" name="cancel_code" value="513">
                                    <span class="checkmark ml-2">Restaurante fechou mais cedo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="cancel_code_other" class="col-12 mt-3 animated fadeInUp d-none">
                        <div class="form-group mb-0">
                            <label>Qual o motivo?</label>
                            <textarea class="form-control p-3 input-border cancel_reason" rows="3" placeholder="Escreva aqui ouro motivo que não esteja listado acima"></textarea>
                            <div class="invalid-feedback animated fadeInUp mt-2 fs-14 d-block">O motivo não pode estar vazio</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger light" data-dismiss="modal">Voltar</button>
                <button type="button" class="btn btn-outline-dark btnOrderCanFinish disabled" data-orderId="">Cancelar pedido</button>
            </div>
        </div>
    </div>
</div>