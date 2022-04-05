var hoje = new Date();

$('.tipo-compras').click(function (event) {
    var tipo = $(this).attr('data-tipo');

    if (tipo == "lista") {

    }
    if (tipo == "fornecedor") {
        $("#aEtapa2").click();

        $("#addCompra .modal-footer .voltar").removeClass("d-none");
        $("#addCompra .modal-footer .proximo").removeClass("d-none");
    }
});

$("#addCompra .modal-footer .proximo").click(function (event) {
    if ($('#etapa2').hasClass('active') == true) {
        
        var fornecedor = $('select[name="uf-fornecedor"]').val();
        var data_compra = $('input[name="uf-data-compra"]').val();

        if (!fornecedor || !data_compra) {
            Swal.fire("Oops...", "Preencha todos os campos para prosseguir !!", "warning");
            return;
        }

        var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;

        if (dateRegex.test(data_compra) == false) {
            Swal.fire("Oops...", "Insisa uma data valida !!", "warning");
            return;
        }
        

        $("#addCompra .modal-footer .proximo").addClass("d-none");
        $("#addCompra .modal-footer .finalizar").removeClass("d-none");
        $("#addCompra .modal-footer .voltar").removeClass("d-none");
        $("#aEtapa3").click();
    }
});

$("#addCompra .modal-footer .voltar").click(function (event) {
    if ($('#etapa2').hasClass('active') == true) {
        $("#addCompra .modal-footer .proximo").addClass("d-none");
        $("#addCompra .modal-footer .voltar").addClass("d-none");
        $("#aEtapa1").click();
    }
    if ($('#etapa3').hasClass('active') == true) {
        $("#addCompra .modal-footer .proximo").removeClass("d-none");
        $("#addCompra .modal-footer .voltar").addClass("d-none");
        $("#addCompra .modal-footer .finalizar").addClass("d-none");
        $("#aEtapa2").click();
    }
});

$(document).on("click", "#addCompra .item .delete", function (event) {
    var linha = $(this).closest(".item");
    linha.removeClass("animate__fadeInDown");
    linha.addClass("animate__fadeOutUp");
    setTimeout(function() { linha.remove(); }, 500);
});

$(document).on("click", "#addCompra .modal-footer .finalizar", function(){
    var arrayProduto = [];
    var erro;
    var notificacao1 = false;
    var notificacao2 = false;
    var notificacao3 = false;

    var fornecedor = $('select[name="uf-fornecedor"]').val();
    var data_compra = $('input[name="uf-data-compra"]').val();

    $(".tbody-fornecedor").find(".item").each(function() {
        var produto = $(this).find('select[name="select-produto"]');
        var marca = $(this).find('input[name="marca"]');
        var qtd = $(this).find('input[name="qtd"]');
        var validade = $(this).find('input[name="validade"]');
        var vTotal = $(this).find('input[name="preco"]');

        var unidade = $(this).find('select[name="select-produto"] option:selected').attr("data-un");               

        var vqtd = qtd.val().replace(unidade, "");
        vqtd = vqtd.replace(" ", "");

        // VALIDA SE ESTA VAZIO - ERRO 1
        if(!produto.val() || !marca.val() || !vqtd || !validade.val() || !vTotal.val()){
            erro = 1;
            if(!produto.val()){ inputErro(produto); }else{ inputCorrige(produto) }
            if(!marca.val()){ inputErro(marca); }else{ inputCorrige(marca) }
            if(!vqtd){ inputErro(qtd); }else{ inputCorrige(qtd) }
            if(!validade.val()){ inputErro(validade); }else{ inputCorrige(validade) }
            if(!vTotal.val()){ inputErro(vTotal); }else{ inputCorrige(vTotal) }
        }else{
            inputCorrige(produto);
            inputCorrige(marca);
            inputCorrige(qtd);
            inputCorrige(validade) ;
            inputCorrige(vTotal);
        }
    });

    if(erro == 1){
        Swal.fire("Oops...", "Preencha todos os campos para finalizar !!", "warning");
        return;
    }

    $(".tbody-fornecedor").find(".item").each(function() {
        var validade = $(this).find('input[name="validade"]');

        // VALIDA FORMATO DE VALIDADE - ERRO 2
        var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;

        if (dateRegex.test(validade.val()) == false) {
            erro = 2;
            inputErro(validade);
        }else{
            inputCorrige(validade);
        }
    });

    if(erro == 2){
        Swal.fire("Oops...", "Um ou mais campos (validade) estão prenchidos incorretamente !!", "warning");
        return;
    }

    $(".tbody-fornecedor").find(".item").each(function() {
        var produto = $(this).find('select[name="select-produto"]');

        // NOTIFICA QUE TEM PRODUTO REPETIDO - NOTIFICAÇÃO 1
        var cod = produto.val();

        if(arrayProduto.indexOf(cod) > -1){
            notificacao1 = 'Produtos repetidos';
        }else{
            arrayProduto.push(produto.val());
        }
    });

    $(".tbody-fornecedor").find(".item").each(function() {
        // NOTIFICA QUE PODE HAVER ALGO ERRADO COM O VALOR - ABAIXO DE R$ 1,00
        var vTotal = $(this).find('input[name="preco"]');

        var valor = parseFloat(vTotal.val().split('.').join('').split(',').join('.').split('R$').join(''));

        if(valor < 1){
            notificacao2 = 'Preços incomuns';
        }
    });

    $(".tbody-fornecedor").find(".item").each(function() {
        // NOTIFICA QUE ESTA CADASTRANDO PRODUTO VENCIDO
        var validade = $(this).find('input[name="validade"]');

        var dtBegin = validade.val().split("/");
            var bdia = dtBegin[0];
            var bmes = dtBegin[1];
            var bano = dtBegin[2];
        var dataInformada = new Date(bmes +"/"+bdia+"/"+bano)  

        if ( new Date(dataInformada.getFullYear(), dataInformada.getMonth(), dataInformada.getDate()) < new Date(hoje.getFullYear(), hoje.getMonth(), hoje.getDate()) ){
            notificacao3 = 'Produtos vencidos';
        }
    });

    if(notificacao1 || notificacao2 || notificacao3 ){
        if(notificacao1 && notificacao2 && notificacao3){ var not = "("+notificacao1+"), ("+notificacao2+"), ("+notificacao3+")." }
        if(!notificacao1 && notificacao2 && notificacao3){ var not = "("+notificacao2+"), ("+notificacao3+")." }
        if(!notificacao1 && !notificacao2 && notificacao3){ var not = "("+notificacao3+")." }
        if(!notificacao1 && notificacao2 && !notificacao3){ var not = "("+notificacao2+")." }
        if(notificacao1 && !notificacao2 && !notificacao3){ var not = "("+notificacao1+")." }
        if(notificacao1 && notificacao2 && !notificacao3){ var not = "("+notificacao1+"), ("+notificacao2+")." }
        if(notificacao1 && !notificacao2 && notificacao3){ var not = "("+notificacao1+"), ("+notificacao3+")." }

        Swal.fire({
            title: 'Deseja mesmo continuar?',
            text: "Foram detectados: "+not+"!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Sim, prosseguir!',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {   
                $.ajax({
                    type : 'POST',
                    url  : './conexao/add_compra',
                    data : { criaCod:'1' },
                    dataType: 'json',
                    success :  function(retorno){
                        if(retorno.erro == 0){
                            var cod = retorno.cod;

                            var qtdItens =  $(".tbody-fornecedor").find(".item").length;
                            var itemAtual = 0;

                            $(".tbody-fornecedor").find(".item").each(function() {
                                var Vproduto = $(this).find('select[name="select-produto"]').val();
                                var Vmarca = $(this).find('input[name="marca"]').val();
                                var Vqtd = $(this).find('input[name="qtd"]').val();
                                var Vvalidade = $(this).find('input[name="validade"]').val();
                                var VvTotal = $(this).find('input[name="preco"]').val();
                                var unidade = $(this).find('select[name="select-produto"] option:selected').attr("data-un");
                            
                                itemAtual++;

                                Vqtd = Vqtd.replace(unidade, "");
                                Vqtd = parseFloat(Vqtd);
                        
                                $.ajax({
                                    type : 'POST',
                                    url  : './conexao/add_compra',
                                    data : { cadastraCompra:'1', cod:cod, produto:Vproduto, marca:Vmarca, qtd:Vqtd, validade:Vvalidade, total:VvTotal, fornecedor:fornecedor, data_compra:data_compra, unidade:unidade, qtdItens:qtdItens, itemAtual:itemAtual },
                                    dataType: 'json',
                                    success :  function(retorno){
                                        if(retorno.erro == 0){
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Sucesso',
                                                text: retorno.mensagem,
                                                showConfirmButton: false,
                                                timer: 2000
                                            });

                                            $("#addCompra .modal-footer .voltar").click();
                                            $('#addCompra input').val('');
                                            $('#addCompra').modal('toggle');

                                            if(retorno.refresh == 'false'){
                                                atualizaPagination();
                                                atualizaChart();
                                            }else {
                                                setTimeout(function(){ location.reload(); }, 2000);
                                                return;  
                                            } 
                                        }else if(retorno.erro == 1){
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: retorno.mensagem,
                                                showConfirmButton: false,
                                                timer: 2000
                                            });
                                            return;
                                        }
                                    }
                                });
                            });

                            return;
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!',
                                showConfirmButton: false,
                                timer: 4000
                            });
                            return;
                        }
                    }
                });
            }else{
                return;
            }
          });
    }else{
        $.ajax({
            type : 'POST',
            url  : './conexao/add_compra',
            data : { criaCod:'1' },
            dataType: 'json',
            success :  function(retorno){
                if(retorno.erro == 0){
                    var cod = retorno.cod;

                    var qtdItens =  $(".tbody-fornecedor").find(".item").length;
                    var itemAtual = 0;

                    $(".tbody-fornecedor").find(".item").each(function() {
                        var Vproduto = $(this).find('select[name="select-produto"]').val();
                        var Vmarca = $(this).find('input[name="marca"]').val();
                        var Vqtd = $(this).find('input[name="qtd"]').val();
                        var Vvalidade = $(this).find('input[name="validade"]').val();
                        var VvTotal = $(this).find('input[name="preco"]').val();
                        var unidade = $(this).find('select[name="select-produto"] option:selected').attr("data-un");

                        itemAtual++;
                    
                        Vqtd = Vqtd.replace(unidade, "");
                        Vqtd = parseFloat(Vqtd);
                
                        $.ajax({
                            type : 'POST',
                            url  : './conexao/add_compra',
                            data : { cadastraCompra:'1', cod:cod, produto:Vproduto, marca:Vmarca, qtd:Vqtd, validade:Vvalidade, total:VvTotal, fornecedor:fornecedor, data_compra:data_compra, unidade:unidade, qtdItens:qtdItens, itemAtual:itemAtual },
                            dataType: 'json',
                            success :  function(retorno){
                                if(retorno.erro == 0){
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sucesso',
                                        text: retorno.mensagem,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });

                                    $("#addCompra .modal-footer .voltar").click();
                                    $('#addCompra input').val('');
                                    $('#addCompra').modal('toggle');

                                    if(retorno.refresh == 'false'){
                                        atualizaPagination();
                                        atualizaChart();
                                    }else {
                                        setTimeout(function(){ location.reload(); }, 2000);
                                        return;  
                                    }       
                                }else if(retorno.erro == 1){
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: retorno.mensagem,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    return;
                                }
                            }
                        });
                    });

                    return;
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!',
                        showConfirmButton: false,
                        timer: 4000
                    });
                    return;
                }
            }
        });
    }
});

$(document).on('change', '#addProduto select[name="unidade"]', function (event) {
    var unidade = $(this).val();
    var estoqueMinimo = $(this).closest("#addProduto").find('input[name="estoque_minimo"]');
    var estoqueIdeal = $(this).closest("#addProduto").find('input[name="estoque_ideal"]');
    
    if (unidade == 'un') {
        estoqueMinimo.val(''); estoqueMinimo.removeClass('qtdun'); estoqueMinimo.mask('#0.0 un', {reverse: true});
        estoqueIdeal.val('');  estoqueIdeal.removeClass('qtdun'); estoqueIdeal.mask('#0.0 un', {reverse: true});
    } else if (unidade == 'g') {
        estoqueMinimo.val(''); estoqueMinimo.removeClass('qtdun'); estoqueMinimo.mask('#0g', {reverse: true});
        estoqueIdeal.val('');  estoqueIdeal.removeClass('qtdun'); estoqueIdeal.mask('#0g', {reverse: true});
    } else if (unidade == 'ml') {
        estoqueMinimo.val(''); estoqueMinimo.removeClass('qtdun'); estoqueMinimo.mask('#0ml', {reverse: true});
        estoqueIdeal.val('');  estoqueIdeal.removeClass('qtdun'); estoqueIdeal.mask('#0ml', {reverse: true});
    }
});

$(document).on('click', '#addProduto button.salvar', function (event) {
    var produto = $(this).closest("#addProduto").find('input[name="produto"]').val();
    var estoqueMinimo = $(this).closest("#addProduto").find('input[name="estoque_minimo"]').val();
    var estoqueIdeal = $(this).closest("#addProduto").find('input[name="estoque_ideal"]').val();
    var unidade = $(this).closest("#addProduto").find('select[name="unidade"]').val();

    if(!produto || !estoqueMinimo || !estoqueIdeal || !unidade){
        Swal.fire("Oops...", "Preencha todos os campos para finalizar!", "warning");
        return;
    }

    estoqueMinimo = estoqueMinimo.replace(unidade, "");
    estoqueIdeal = estoqueIdeal.replace(unidade, "");

    estoqueMinimo = parseFloat(estoqueMinimo);
    estoqueIdeal = parseFloat(estoqueIdeal);

    if(estoqueIdeal <= estoqueMinimo){
        Swal.fire("Oops...", "O Estoque ideal precisa ser maior que Estoque minimo!", "warning");
        console.log(estoqueMinimo+' - '+estoqueIdeal);
        return;
    }

    $.ajax({
        type : 'POST',
        url  : './conexao/estoque',
        data : { cadastraProduto:'1', produto:produto, estoqueMinimo:estoqueMinimo, estoqueIdeal:estoqueIdeal, unidade:unidade },
        dataType: 'json',
        success :  function(retorno){
            if(retorno.erro == 0){
                if(retorno.info == 1){
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        text: retorno.mensagem,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('#addProduto input').val('');
                    $('#addProduto').modal('toggle');
                    atualizaTbodyProdutos();
                    return;
                }else{
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        text: retorno.mensagem,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('#addProduto input').val('');
                    $('#addProduto').modal('toggle');
                    setTimeout(function(){ location.reload(); }, 2000);
                    return;
                }
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: retorno.mensagem,
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
        }
    });
});

$(document).ready(function() {
    $('#addCompra form').bind("keypress", function(e) {
        if ((e.keyCode == 10)||(e.keyCode == 13)) {
            e.preventDefault();
        }
    });
});

$(document).on('change', '#addCompra select[name="select-produto"]', function (event) {
    
    var unidade = $(this).closest("tr.item").find('option:selected').attr("data-un");
    var input   = $(this).closest("tr.item").find('input[name="qtd"]');
    
    if (unidade == 'un') {
        input.val(''); input.prop("disabled",false); input.mask('#0.0 un', {reverse: true});
    } else if (unidade == 'g') {
        input.val(''); input.prop("disabled",false); input.mask('#0g', {reverse: true});
    } else if (unidade == 'ml') {
        input.val(''); input.prop("disabled",false); input.mask('#0ml', {reverse: true});
    }else{
        input.val(''); input.prop("disabled",true);
    }
});

$(document).ready(function () {
    if(jQuery('#pagination-container').length > 0 ){
        var condicao = $('.where-data').attr('data-where');
        $('#pagination-container').pagination({
            dataSource: function(done) {
                $.ajax({
                    type : 'POST',
                    url  : './conexao/add_compra',
                    data : { consultaComrpras:'1', condicao:condicao },
                    dataType: 'json',
                    success: function(response) {
                        done(response);
                    }
                });
            },
            totalNumberLocator: function(response) {
                // you can return totalNumber by analyzing response content
                return Math.floor(Math.random() * (1000 - 100)) + 100;
            },
            pageSize: 10,
            callback: function(data, pagination) {
                // template method of yourself
                var html = data;
                $('#sellingItemsContent').html(html);
            }
        });

        $.ajax({
            type : 'POST',
            url  : './conexao/add_compra',
            data : { consultaQtdComrpras:'1', condicao:condicao },
            dataType: 'json',
            success :  function(retorno){
                x = retorno.pages;
                
                if(x == 0){
                    $('ul.ul-pagination').html('<li class="page-item active"><a class="page-link" data-page="1" href="javascript:void(0)">1</a></li>');
                }else{
                    var i = 1;
                    for (; i <= x; i++) {
                        var temporario = $('ul.ul-pagination').html();
                        if(i == 1){
                            $('ul.ul-pagination').html('<li class="page-item active"><a class="page-link" data-page="1" href="javascript:void(0)">1</a></li>');
                        }else{
                            $('ul.ul-pagination').html(temporario+'<li class="page-item"><a class="page-link" data-page="'+i+'" href="javascript:void(0)">'+i+'</a></li>');
                        }
                        
                    }
                }
                $('.valor_total_compras').text(retorno.soma);
            }
        });
    };
});

$(document).on('click', 'ul.pagination .page-link', function (event) {
    var n = $(this).attr('data-page');
    var i = $('#pagination-container').pagination('getSelectedPageNum');
    if(n != i){
        $('#pagination-container').pagination('go', n);
        $(this).closest('.ul-pagination').find('.active').removeClass('active');
        $(this).closest('.page-item').addClass('active');
    }
});

$(document).on('click', 'a.page-link-next', function (event) {
    $('#pagination-container').pagination('next');
    var n = $('#pagination-container').pagination('getSelectedPageNum');
    $('ul.pagination').find('.page-item').each(function() {
        var i = $(this).find('.page-link').attr('data-page');
        if(i == n){
            $(this).addClass('active');
        }else{
            $(this).removeClass('active');
        }
    });    
});

$(document).on('click', 'a.page-link-previous', function (event) {
    $('#pagination-container').pagination('previous');
    var n = $('#pagination-container').pagination('getSelectedPageNum');
    $('ul.pagination').find('.page-item').each(function() {
        var i = $(this).find('.page-link').attr('data-page');
        if(i == n){
            $(this).addClass('active');
        }else{
            $(this).removeClass('active');
        }
    }); 
});

function atualizaPagination() {
    $('#pagination-container').pagination('destroy');

    $('#sellingItemsContent').html('');
    $('ul.ul-pagination').html('');

    var condicao = $('.where-data').attr('data-where');

    $('#pagination-container').pagination({
        dataSource: function(done) {
            $.ajax({
                type : 'POST',
                url  : './conexao/add_compra',
                data : { consultaComrpras:'1', condicao:condicao },
                dataType: 'json',
                success: function(response) {
                    done(response);
                }
            });
        },
        totalNumberLocator: function(response) {
            // you can return totalNumber by analyzing response content
            return Math.floor(Math.random() * (1000 - 100)) + 100;
        },
        pageSize: 10,
        callback: function(data, pagination) {
            // template method of yourself
            var html = data;
            $('#sellingItemsContent').html(html);
        }
    });

    $.ajax({
        type : 'POST',
        url  : './conexao/add_compra',
        data : { consultaQtdComrpras:'1', condicao:condicao },
        dataType: 'json',
        success :  function(retorno){
            x = retorno.pages;
            
            if(x == 0){
                $('ul.ul-pagination').html('<li class="page-item active"><a class="page-link" data-page="1" href="javascript:void(0)">1</a></li>');
            }else{
                var i = 1;
                for (; i <= x; i++) {
                    var temporario = $('ul.ul-pagination').html();
                    if(i == 1){
                        $('ul.ul-pagination').html('<li class="page-item active"><a class="page-link" data-page="1" href="javascript:void(0)">1</a></li>');
                    }else{
                        $('ul.ul-pagination').html(temporario+'<li class="page-item"><a class="page-link" data-page="'+i+'" href="javascript:void(0)">'+i+'</a></li>');
                    }
                    
                }
            }
            $('.valor_total_compras').text(retorno.soma);
        }
    });
};

$(document).on("click", ".compra a.delete", function () {
    var cod = $(this).closest("div.compra").attr("data-cod");

    Swal.fire({
        title: 'Deseja mesmo continuar?',
        text: "Esta ação nao tera mais volta",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {   
            $.ajax({
                type : 'POST',
                url  : url+'/conexao/add_compra',
                data : { excluiCompras:'1', cod:cod },
                dataType: 'json',
                success :  function(retorno){
                    if(retorno.erro == 0){
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: retorno.mensagem,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        atualizaPagination();
                        atualizaChart();
                        return;
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: retorno.mensagem,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        return;
                    }
                }
            });   
        }else{
            return;
        }
    });
});

if(jQuery('#chart_compras_1').length > 0 ){

    const ctx = document.getElementById("chart_compras_1").getContext('2d');
    //generate gradient
    // const gradientStroke = chart_widget_1.createLinearGradient(0, 0, 0, 250);
    // gradientStroke.addColorStop(0, "#00abc5");
    // gradientStroke.addColorStop(1, "#000080");

    // chart_widget_1.attr('height', '100');

    const barChart_2gradientStroke = ctx.createLinearGradient(0, 0, 0, 250);
    barChart_2gradientStroke.addColorStop(0, "rgba(234, 122, 154, 1)");
    barChart_2gradientStroke.addColorStop(1, "rgba(234, 122, 154, 0.5)");


    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            defaultFontFamily: 'Poppins',
            labels: [0,0,0,0,0,0],
            datasets: [
                {
                    data: [0,0,0,0,0,0],
                    borderWidth: "0",
                    backgroundColor: barChart_2gradientStroke, 
                    hoverBackgroundColor: barChart_2gradientStroke,
                }
            ]
        },
        plugins: [ChartDataLabels],
        options: {
            plugins: {
                legend: false,
                tooltip: {
                    enabled: false,
                    callbacks: {
                        label: function(context) {
                            var label = context.dataset.label || '';

                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                },
                datalabels: {
                    formatter: function(value, context) {
                        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
                    },
                    color: '#EA7A9A',
                    anchor: 'end',
                    align: 'end',
                    offset: 10
                },
            },
            layout: {
                padding: {
                    top: 25,
                    bottom: 10,
                }
            },
            scales: {
                y: {
                    display: false,
                    drawBorder: false               
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#EA7A9A',
                    }
                },
            }
        },
    });
};


jQuery(window).on('load',function(){
    if(jQuery('#chart_compras_1').length > 0 ){
        $.ajax({
            type : 'POST',
            url  : url+'/conexao/add_compra',
            data : { consultaMontaChart:'1' },
            dataType: 'json',
            success :  function(meses){
                var valores = meses.map(function(e) {
                    return e.valor;
                });
                var labels = meses.map(function(e) {
                    return e.mesnome;
                });
                myChart.data.labels = labels;
                myChart.data.datasets[0].data = valores;
                myChart.update(); 

                var valorMesAtual = valores[5];
                var valorMesAnterior = valores[4];
                
                if(valorMesAtual > valorMesAnterior){
                    var dif = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valorMesAtual-valorMesAnterior);
                    var per = ((valorMesAtual*100)/valorMesAnterior).toFixed(1);
                    var mA  = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valorMesAtual);
                    $('.valor_mes_atual_chart').text(mA);
                    $('.dif_chart').text('+'+dif);
                    $('.dif_chart_i').html('<i class="fas fa-caret-up text-warning"></i>');
                    $('.per_chart').text(per+'%');
                }else if(valorMesAtual < valorMesAnterior){
                    var dif = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valorMesAnterior-valorMesAtual);
                    var per = ((valorMesAtual*100)/valorMesAnterior).toFixed(1);
                    var mA  = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valorMesAtual);
                    $('.valor_mes_atual_chart').text(mA);
                    $('.dif_chart').text('-'+dif);
                    $('.dif_chart_i').html('<i class="fas fa-caret-down text-success"></i>');
                    $('.per_chart').text(per+'%');
                }else{
                    $('.valor_mes_atual_chart').text(valorMesAtual);
                    $('.dif_chart').text('R$ 0,00');
                    $('.dif_chart_i').html('');
                    $('.per_chart').text('100%');
                }
            }
        });
    };
});

// 

$(document).on("click", ".condicao-data", function () {
    var concicao = $(this).attr("data-where");
    var text = $(this).text();

    $('.where-data').text(text);
    $('.where-data').attr("data-where", concicao);

    $('.desc_valor_total_compras').text(text);

    atualizaPagination();
});

function atualizaChart() {
    $.ajax({
        type : 'POST',
        url  : url+'/conexao/add_compra',
        data : { consultaMontaChart:'1' },
        dataType: 'json',
        success :  function(meses){
            var valores = meses.map(function(e) {
                return e.valor;
            });
            var labels = meses.map(function(e) {
                return e.mesnome;
            });
            myChart.data.labels = labels;
            myChart.data.datasets[0].data = valores;
            myChart.update();

            var valorMesAtual = valores[5];
            var valorMesAnterior = valores[4];
            
            if(valorMesAtual > valorMesAnterior){
                var dif = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valorMesAtual-valorMesAnterior);
                var per = ((valorMesAtual*100)/valorMesAnterior).toFixed(1);
                var mA  = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valorMesAtual);
                $('.valor_mes_atual_chart').text(mA);
                $('.dif_chart').text('+'+dif);
                $('.dif_chart_i').html('<i class="fas fa-caret-up text-warning"></i>');
                $('.per_chart').text(per+'%');
            }else if(valorMesAtual < valorMesAnterior){
                var dif = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valorMesAnterior-valorMesAtual);
                var per = ((valorMesAtual*100)/valorMesAnterior).toFixed(1);
                var mA  = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valorMesAtual);
                $('.valor_mes_atual_chart').text(mA);
                $('.dif_chart').text('-'+dif);
                $('.dif_chart_i').html('<i class="fas fa-caret-down text-success"></i>');
                $('.per_chart').text(per+'%');
            }else{
                $('.valor_mes_atual_chart').text(valorMesAtual);
                $('.dif_chart').text('R$ 0,00');
                $('.dif_chart_i').html('');
                $('.per_chart').text('100%');
            }
        }
    }); 
};

$(document).on("click", ".estoque-item .clickavel", function () {
    var cod = $(this).closest('.estoque-item').attr('data-cod');
    window.location.href = url+"/produto/"+cod; 
});

$(document).on("click", ".estoque-item .delete", function () {
    var cod = $(this).closest('.estoque-item').attr('data-cod');

    Swal.fire({
        title: 'Deseja mesmo continuar?',
        text: "Esta ação nao tera mais volta",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {   
            $.ajax({
                type : 'POST',
                url  : url+'/conexao/estoque',
                data : { excluiProduto:'1', cod:cod },
                dataType: 'json',
                success :  function(retorno){
                    if(retorno.erro == 0){
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: retorno.mensagem,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        atualizaTbodyProdutos();
                        return;
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: retorno.mensagem,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        return;
                    }
                }
            });   
        }else{
            return;
        }
    });
});

function atualizaTbodyProdutos() {
    $.ajax({
        type : 'POST',
        url  : url+'/conexao/estoque',
        data : { atualizaTbodyProdutos:'1' },
        dataType: 'json',
        success :  function(retorno){
            $('.tbody-estoque-lista').html(retorno.linhas);
            $('[data-toggle="popover"]').popover({ trigger: "hover" });
        }
    }); 
};
