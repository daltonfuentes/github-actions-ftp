$(document).on('change', '#addCardapio select[name="produto"]', function(){
    var cod = $(this).val();

    if(!cod){
        $('#addCardapio .box-variacoes').empty();
        return;
    }else{
        monta_modal_cardapio(cod);
    }
});

function monta_modal_cardapio(cod) {
    $.ajax({
        type : 'POST',
        url  : url+'/conexao/add_cardapio',
        data : { monta_modal_cardapio:'1', cod:cod },
        dataType: 'json',
        success :  function(retorno){
            if (retorno.erro == 0) {
                $('#addCardapio .box-variacoes').html(retorno.itens);

                $('.maskMoney').maskMoney({
                    thousands: '.',
                    decimal: ',',
                    prefix: 'R$ '
                });

                return;
            } else if(retorno.erro == 1) {
                Swal.fire("Oops...", retorno.mensagem, "warning");
                return;
            }
        }
    }); 
};

$(document).on('keyup', '.input_valor_modal_cardapio', function () {
    var val = $(this).val();
    var widthInput = $(this).width();

    $('.fake-h5').text(val);
    var widthH5 = $('.fake-h5').width();

    $(this).width(widthH5);
});

$(document).on('blur', '.input_valor_modal_cardapio', function(){
    var val = $(this).val();

    if(val == 'R$ 0,00'){
        $(this).val('R$ 0,00');
    }
});

$(document).on('keyup', '.input_valor_modal_cardapio', function () {
    var venda = $(this).val();
    var custo = $(this).attr('data-custo-total');

    venda = realParaNumero(venda);
    
    if($(this).hasClass('ifood') == true ){
        if(venda == 0){
            $(this).closest('div.variacao').find('.lucros-ifood').find('.recebivel-ifood, .recebivel-pix, .recebivel-dinheiro').text('R$ 0,00');
            $(this).closest('div.variacao').find('.lucros-ifood span.valor-porcentagem').text('0%');
            $(this).closest('div.variacao').find('.lucros-ifood span.valor-lucro').text('R$ 0,00');
            return;
        }else{
            var taxa_online = $(this).attr('data-taxa-online');
            var taxa_dinheiro = $(this).attr('data-taxa-dinheiro');

            $(this).closest('div.variacao').find('.lucros-ifood .recebivel-ifood').text(stringToMoney(calculaRecebivel(venda, taxa_online)));
            $(this).closest('div.variacao').find('.lucros-ifood .recebivel-pix').text(stringToMoney(calculaRecebivel(venda, taxa_online)));
            $(this).closest('div.variacao').find('.lucros-ifood .recebivel-dinheiro').text(stringToMoney(calculaRecebivel(venda, taxa_dinheiro)));

            if(venda <= custo){
                $(this).closest('div.variacao').find('.lucros-ifood span.valor-porcentagem').text('0%');
                $(this).closest('div.variacao').find('.lucros-ifood span.valor-lucro').text('R$ 0,00');
            }else{
                var venda2 = calculaRecebivel(venda, taxa_dinheiro);
                var lucro = venda2-custo;
                var porcentagem = (lucro*100)/custo;
                porcentagem = parseFloat(porcentagem.toFixed(1));
                
                $(this).closest('div.variacao').find('.lucros-ifood span.valor-porcentagem').text(porcentagem+'%');
                $(this).closest('div.variacao').find('.lucros-ifood span.valor-lucro').text(stringToMoney(lucro));
            }
        }
    }else if($(this).hasClass('loja') == true){
        if(venda == 0){
            $(this).closest('div.variacao').find('.lucros-loja').find('.recebivel-credito, .recebivel-debito, .recebivel-dinheiro').text('R$ 0,00');
            $(this).closest('div.variacao').find('.lucros-loja span.valor-porcentagem').text('0%');
            $(this).closest('div.variacao').find('.lucros-loja span.valor-lucro').text('R$ 0,00');
            return;
        }else{
            var taxa_credito = $(this).attr('data-taxa-credito');
            var taxa_debito = $(this).attr('data-taxa-debito');

            $(this).closest('div.variacao').find('.lucros-loja .recebivel-credito').text(stringToMoney(calculaRecebivel(venda, taxa_credito)));
            $(this).closest('div.variacao').find('.lucros-loja .recebivel-debito').text(stringToMoney(calculaRecebivel(venda, taxa_debito)));
            $(this).closest('div.variacao').find('.lucros-loja .recebivel-dinheiro').text(stringToMoney(venda));

            if(venda <= custo){
                $(this).closest('div.variacao').find('.lucros-loja span.valor-porcentagem').text('0%');
                $(this).closest('div.variacao').find('.lucros-loja span.valor-lucro').text('R$ 0,00');
            }else{
                var lucro = venda-custo;
                var porcentagem = (lucro*100)/custo;
                porcentagem = parseFloat(porcentagem.toFixed(1));
                
                $(this).closest('div.variacao').find('.lucros-loja span.valor-porcentagem').text(porcentagem+'%');
                $(this).closest('div.variacao').find('.lucros-loja span.valor-lucro').text(stringToMoney(lucro));
            }
        }
    }
});

function calculaRecebivel(venda, taxa) {
    var recebivel = venda-(venda*(taxa/100));
    return recebivel;
};

$(document).on('mouseenter', '.cardapio-loja', function () {
    $(this).find('.editar, .visualizar').removeClass('d-none');
});

$(document).on('mouseleave', '.cardapio-loja', function () {
    $(this).find('.editar, .visualizar').addClass('d-none');
});

function atualizaBoxCardapio() {
    $.ajax({
        type : 'POST',
        url  : url+'/conexao/add_cardapio',
        data : { atualiza_cardapio_itens:'1' },
        dataType: 'json',
        success :  function(retorno){
            if (retorno.erro == 0) {
                $('.box-itens-carapio-loja').html(retorno.html);
                return;
            } else if(retorno.erro == 1) {
                Swal.fire("Oops...", retorno.mensagem, "warning");
                return;
            }
        }
    });
};

$(document).ready(function () {
    atualizaBoxCardapio();
});

$(document).on('click', '#addCardapio .salvar', function(){
    var cod = $('#addCardapio select[name="produto"]').val();

    if(!cod){
        Swal.fire("Oops...", 'Selecione um produto para adicionar os valores.', "warning");
        return;
    }

    var erro = 0;

    $('#addCardapio .input_valor_modal_cardapio').each(function() {
        var venda = $(this).val();
        venda =realParaNumero(venda);

        if(!venda){
            erro++;
        }
    });

    if(erro != 0){
        Swal.fire("Oops...", 'Adicione o valor de "VENDA" em todos os campos.', "warning");
        return;
    }
    
    var max = $('#addCardapio .box-variacoes .variacao').length;
    var contar = 1;

    $('#addCardapio .box-variacoes .variacao').each(function() {
        var ifood = $(this).find('input.ifood').val();
        var loja  = $(this).find('input.loja').val();
        var variacao = $(this).attr('data-variacao');

        if(contar == max){
            parar = 'true';
        }else{
            parar = 'false';
            ++contar;
        }

        $.ajax({
            type : 'POST',
            url  : url+'/conexao/add_cardapio',
            data : { edit_valores_cardapio:'1', cod:cod, variacao:variacao, ifood:ifood, loja:loja, parar:parar },
            dataType: 'json',
            success :  function(retorno){
                if (retorno.erro == 0) {
                    atualizaBoxCardapio();
                    $('#addCardapio').modal('toggle');
                    var exib =  $("#addCardapio .div-produto .filter-option-inner-inner").text();
                    $("#addCardapio").find('select[name="produto"]').find('option[value=""]').prop("selected", true);
                    $("#addCardapio").find('select[name="produto"]').find('option[value="'+cod+'"]').remove();
                    $("#addCardapio .div-produto .dropdown-menu").find("span.text:contains('"+exib+"')").closest('li').remove();
                    $("#addCardapio .div-produto .filter-option-inner-inner").text('Selecione o produto');
                    $('#addCardapio .box-variacoes .variacao').remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Valores atualizados!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                } else if(retorno.erro == 1) {
                    Swal.fire("Oops...", retorno.mensagem, "warning");
                    return;
                }
            }
        });
    });
});

$(document).on('click', '.cardapio-loja .cPointer', function(){
    var cod = $(this).closest('.cardapio-item').attr('data-cod');

    if(!cod){
        Swal.fire("Oops...", 'Tivemos um erro interno. Tente novamente mais tarde!', "warning");
        return;
    }

    $.ajax({
        type : 'POST',
        url  : url+'/conexao/add_cardapio',
        data : { monta_modal_editar:'1', cod:cod },
        dataType: 'json',
        success :  function(retorno){
            if (retorno.erro == 0) {
                $('#editCardapio .modal-content').html(retorno.html);
                $('#editCardapio').modal('show');
                ajustaWidthIput();
                $('.maskMoney').maskMoney({
                    thousands: '.',
                    decimal: ',',
                    prefix: 'R$ '
                });
                return;
            } else if(retorno.erro == 1) {
                Swal.fire("Oops...", retorno.mensagem, "warning");
                return;
            }
        }
    });
});

$(document).on('change', '#editCardapio .input_valor_modal_cardapio', function () {
    $('#editCardapio .modal-footer').removeClass('d-none');
});

function ajustaWidthIput() {
    $('#editCardapio .input_valor_modal_cardapio').each(function() {
        var val = $(this).val();
        var widthInput = $(this).width();
        $('.fake-h5').text(val);
        var widthH5 = $('.fake-h5').width();
        $(this).width(widthH5);
    });
};

$(document).on('click', '#editCardapio .salvar', function(){
    var cod = $(this).attr('data-cod');

    if(!cod){
        Swal.fire("Oops...", 'Tivemos um erro interno e nao foi possivel prosseguir!', "warning");
        return;
    }

    var erro = 0;

    $('#editCardapio .input_valor_modal_cardapio').each(function() {
        var venda = $(this).val();
        venda =realParaNumero(venda);

        if(!venda){
            erro++;
        }
    });

    if(erro != 0){
        Swal.fire("Oops...", 'Informe o valor de "VENDA" em todos os campos.', "warning");
        return;
    }
    
    var max = $('#editCardapio .box-variacoes .variacao').length;
    var contar = 1;

    $('#editCardapio .box-variacoes .variacao').each(function() {
        var ifood = $(this).find('input.ifood').val();
        var loja  = $(this).find('input.loja').val();
        var variacao = $(this).attr('data-variacao');

        if(contar == max){
            parar = 'true';
        }else{
            parar = 'false';
            ++contar;
        }

        $.ajax({
            type : 'POST',
            url  : url+'/conexao/add_cardapio',
            data : { edit_valores_cardapio:'1', cod:cod, variacao:variacao, ifood:ifood, loja:loja, parar:parar },
            dataType: 'json',
            success :  function(retorno){
                if (retorno.erro == 0) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Valores atualizados!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                } else if(retorno.erro == 1) {
                    Swal.fire("Oops...", retorno.mensagem, "warning");
                    return;
                }
            }
        });
    });
});

$(document).ready(function(){
    $("#inputBusca").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".box-itens-carapio-loja div.cardapio-item").filter(function() {
        $(this).toggle($(this).find('.palavras-chaves').text().toLowerCase().indexOf(value) > -1);
      });
    });
});