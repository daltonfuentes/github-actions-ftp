$(document).ready(function () {
    $(document).on("click", "#editVariacao .item .delete", function (event) {
        var linha = $(this).closest(".item");
        linha.removeClass("animate__fadeInDown");
        linha.addClass("animate__fadeOutUp");
        setTimeout(function () {
            linha.remove();
        }, 500);
    });

    $(document).on("click", "#addCategoria .item .delete", function (event) {
        var linha = $(this).closest(".item");
        linha.removeClass("animate__fadeInDown");
        linha.addClass("animate__fadeOutUp");
        setTimeout(function () {
            linha.remove();
        }, 500);
    });

    $(document).on("click", "#addCardapioCozinha .salvar", function (event) {
        var nome = $("#addCardapioCozinha").find('input[name="nome"]').val();
        var categoria = $("#addCardapioCozinha").find('select[name="categoria"]').val();
        var nVariacoes = $("#addCardapioCozinha").find('select[name="variacoes"]').val();
        var erro = 0;

        if (!nome || !categoria || !nVariacoes) {
            Swal.fire("Oops...", "Insira todas as informações para finalizar!", "warning");
            return;
        }

        var nBoxVariacoes = $("#addCardapioCozinha .box-variacoes .variacao").length;
        if(nVariacoes != nBoxVariacoes && nVariacoes > 1){
            Swal.fire("Oops...", "Erro interno, não foi possivel salvar!", "warning");
            return;
        }

        $("#addCardapioCozinha .box-variacoes .variacao").each(function () {
            var itens = $(this).find('li.item').length;

            if(itens < 1){
                ++erro;
            }
        });

        if (erro > 0) {
            Swal.fire("Oops...", "Finalize todas as variações para finalizar!", "warning");
            return;
        }

        var fakeImg = $("#fake-input-upload").val();

        if (fakeImg == '') {
            Swal.fire("Oops...", "Faça o upload da imagem do produto para finalizar!", "warning");
            return;
        }

        var $input, file;
        $input = document.getElementById('input-upload-img');
        if (!window.FileReader) {
            Swal.fire("Oops...", "A File API não é suportada pelo seu navegador.", "warning");
            return;
        }
        if (!$input) {
            Swal.fire("Oops...", "Elemento input file não localizado!", "warning");
            return;
        } else if (!$input.files) {
            Swal.fire("Oops...", "Este navegador não tem suporte a propriedade 'files' para inputs do tipo file.", "warning");
            return;
        } else if (!$input.files[0]) {
            Swal.fire("Oops...", "Nenhum arquivo selecionado!", "warning");
            return;
        } else {
            file = $input.files[0];
            $tamanho = file.size;

            if ($tamanho >= 29999999) {
                Swal.fire("Oops...", "Arquivo excede o limite de tamanho. *30MB", "warning");
                return;
            }
        }

        var form_data = new FormData();
        form_data.append('file', $('#input-upload-img').prop('files')[0]);
        form_data.append('add_img', '1');

        $.ajax({
            url: "./conexao/cozinha",
            type: "POST",
            data: form_data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (retorno) {
                if (retorno.erro == '0') {
                    var new_img = retorno.img;
                    $.ajax({
                        type: 'POST',
                        url: './conexao/cozinha',
                        data: {
                            cria_cod_cardapio: '1'
                        },
                        dataType: 'json',
                        success: function (retorno) {
                            if (retorno.erro == 0) {
                                var cod = retorno.cod;

                                var total = $("#addCardapioCozinha .box-variacoes .variacao li.item").length;
                                var c = 1;
                                var parar;

                                $("#addCardapioCozinha .box-variacoes .variacao").each(function () {
                                    var variacao = $(this).attr('data-variacao');
                                    var nome_variacao = $(this).find('span.nome-variacao').text();

                                    $(this).find("li.item").each(function () {
                                        var cod_ingrediente = $(this).attr('data-cod');
                                        var tipo_ingrediente = $(this).attr('data-tipo');
                                        var qtd = $(this).find('span.quantidade').attr('data-qtd');
                                        var unidade = $(this).find('span.quantidade').attr('data-un');

                                        if(c == total){
                                            parar = 1;
                                        }else{
                                            parar = 0
                                            ++c;
                                        }
                                        
                                        $.ajax({
                                            type: 'POST',
                                            url: './conexao/cozinha',
                                            data: {
                                                cadastra_cardapio: '1',
                                                cod: cod,
                                                nome: nome,
                                                categoria: categoria,
                                                cod_ingrediente: cod_ingrediente,
                                                tipo_ingrediente: tipo_ingrediente,
                                                qtd: qtd,
                                                variacao: variacao,
                                                unidade: unidade,
                                                img: new_img,
                                                nome_variacao:nome_variacao,
                                                parar:parar
                                            },
                                            dataType: 'json',
                                            success: function (retorno) {
                                                if (retorno.erro == 0) {
                                                    Swal.fire("Ótimo", "Item cadastrado corretamente ao cardapio", "success");
                                                    $('#addCardapioCozinha').modal('toggle');
                                                    atualizaItens();
                                                    return;
                                                } else if(retorno.erro == 1) {
                                                    Swal.fire("Oops...", retorno.mensagem, "warning");
                                                    return;
                                                }
                                            }
                                        });
                                    });
                                });

                                return;
                            } else {
                                Swal.fire("Oops...", retorno.mensagem, "warning");
                                return;
                            }
                        }
                    });
                } else {
                    Swal.fire("Oops...", retorno.mensagem, "warning");
                    return;
                }
            }
        });
    });

    $(function () {
        $("#input-upload-img").change(function () {
            var path = $(this).val();
            var fileName = path.replace(/^.*\\/, "");
            $("#fake-input-upload").val(fileName);
        });
    });


    // external js: isotope.pkgd.js

    // init Isotope
    var $grid = $('.grid').isotope({
        itemSelector: '.cardapio-item',
        layoutMode: 'fitRows'
    });
    // filter functions
    var filterFns = {
        // show if number is greater than 50
        numberGreaterThan50: function () {
            var number = $(this).find('.number').text();
            return parseInt(number, 10) > 50;
        },
        // show if name ends with -ium
        ium: function () {
            var name = $(this).find('.name').text();
            return name.match(/ium$/);
        }
    };
    // bind filter button click
    $('.filters-button-group').on('click', 'button', function () {
        var filterValue = $(this).attr('data-filter');
        // use filterFn if matches value
        filterValue = filterFns[filterValue] || filterValue;
        $grid.isotope({
            filter: filterValue
        });
    });
    // change is-checked class on buttons
    $('.filters-button-group').each(function (i, buttonGroup) {
        var $buttonGroup = $(buttonGroup);
        $buttonGroup.on('click', 'button', function () {
            $buttonGroup.find('.is-checked').removeClass('is-checked');
            $(this).addClass('is-checked');
        });
    });

    $(document).on("click", "#addCategoria .salvar", function (event) {
        var categoria = $(this).closest('#addCategoria').find('input[name="categoria"]');
        var visible   = '1';

        // VALIDA SE ESTA VAZIO - ERRO 1
        if (!categoria.val()) {
            inputErro(categoria);
            Swal.fire("Oops...", "Informe um nome em categoria para finalizar!", "warning");
            return;
        } else {
            inputCorrige(categoria);
        }

        var erro = 0;
        var typeErro1 = 0;
        var typeErro2 = 0;

        $("#addCategoria .tbody-cozinha-cardapio .item").each(function () {
            var ingrediente = $(this).find('select[name="select-produto"]');
            var qtd = $(this).find('input[name="qtd"]');

            var qtdver = $(this).find('input[name="qtd"]').val();
            var unidade = $(this).find('option:selected').attr("data-un");

            qtdVer = qtdver.replace(unidade, "");
            qtdVer = parseFloat(qtdVer);

            if ( (ingrediente.val() && !qtdVer) || (!ingrediente.val() && qtdVer) ) {
                typeErro1 = 1;
                
                erro++;
                if (!ingrediente.val()) {
                    inputErro(ingrediente);
                } else {
                    inputCorrige(ingrediente)
                }
                if (!qtdVer) {
                    inputErro(qtd);
                } else {
                    inputCorrige(qtd)
                }
            }else if (!ingrediente.val() && !qtdVer) {
                typeErro2 = 1;

                erro++;
                if (!ingrediente.val()) {
                    inputErro(ingrediente);
                } else {
                    inputCorrige(ingrediente)
                }
                if (!qtdVer) {
                    inputErro(qtd);
                } else {
                    inputCorrige(qtd)
                }
            }else{
                inputCorrige(ingrediente);
                inputCorrige(qtd);
            }
        });

        var total = $('#addCategoria .tbody-cozinha-cardapio .item').length;

        if (erro > 0) {
            if(typeErro2 == 1 && total > 1) {
                Swal.fire("Oops...", "Retire as linhas vazias para prosseguir!", "warning");
                return;
            }else if(typeErro1 == 1) {
                Swal.fire("Oops...", "Complete as linhas incompleta para prosseguir!", "warning");
                return;
            }
        }

        categoria = categoria.val();

        var c = 1;
        var parar;

        $.ajax({
            type : 'POST',
            url  : './conexao/cozinha',
            data : { cria_cod_categoria:'1' },
            dataType: 'json',
            success :  function(retorno){
                if (retorno.erro == 0) {
                    var cod = retorno.cod;

                    
                    $("#addCategoria .tbody-cozinha-cardapio .item").each(function() {                   
                        var tipo_ingrediente = $(this).find(".select-valida").find("option:selected").attr("data-tipo");
                        var cod_ingrediente = $(this).find('select[name="select-produto"]').val();
                        var qtd_ingrediente = $(this).find('input[name="qtd"]').val();
                        var unidade = $(this).find('option:selected').attr("data-un");

                        qtd = qtd_ingrediente.replace(unidade, "");
                        qtd = parseFloat(qtd);

                        //console.log(cod+' - '+categoria+' - '+tipo_ingrediente+' - '+cod_ingrediente+' - '+qtd+' - '+visible); return;
                        
                        if(c == total){
                            parar = 1;
                        }else{
                            parar = 0
                            ++c;
                        }
                
                        $.ajax({
                            type : 'POST',
                            url  : './conexao/cozinha',
                            data : { cadastra_categoria:'1', cod:cod, categoria:categoria, tipo_ingrediente:tipo_ingrediente, cod_ingrediente:cod_ingrediente, qtd:qtd, visible:visible, parar:parar },
                            dataType: 'json',
                            success :  function(retorno){
                                if (retorno.erro == 0) {
                                    Swal.fire("Ótimo", "Categoria cadastrada corretamente.", "success");
                                    $('#addCategoria').modal('toggle');
                                    
                                    setTimeout(function(){ location.reload(); }, 2000);

                                    return;
                                } else if(retorno.erro == 1) {
                                    Swal.fire("Oops...", retorno.mensagem, "warning");
                                    return;
                                }
                            }
                        });
                    });
                } else {
                    Swal.fire("Oops...", retorno.mensagem, "warning");
                    return;
                }
            }
        });
    });
});

$(document).on('change', 'div.modal select[name="categoria"]', function (event) {
    var categoria = $(this).val();

    var variacaoN = $(this).closest('div.modal').find('select[name="variacoes"]').val();
    if(categoria == '3' && variacaoN > 1){
        Swal.fire("Oops...", "Adicionais podem ter apenas 1 variação!", "warning");
        $(this).closest('div.modal').find('select[name="categoria"]').find('option[value=""]').prop("selected", true);
        $(this).closest('div.modal').find(".div-categoria .filter-option-inner-inner").text('Selecione a categoria');
    }
});

$(document).on('change', 'div.modal select[name="variacoes"]', function (event) {
    var variacaoN = $(this).val();
    var origin = $(this).closest('div.modal');
    var variacaoA = $(origin).find(".box-variacoes").find("div.variacao").length;

    var categoria = $(this).closest('div.modal').find('select[name="categoria"]').val();
    if(categoria == '3' && variacaoN > 1){
        Swal.fire("Oops...", "Adicionais podem ter apenas 1 variação!", "warning");
        $(this).closest('div.modal').find('select[name="categoria"]').find('option[value=""]').prop("selected", true);
        $(this).closest('div.modal').find(".div-categoria .filter-option-inner-inner").text('Selecione a categoria');
    }

    for (i = variacaoA; i < variacaoN; ++i) {
        var variacoes = $(origin).find(".box-variacoes");
        variacoes.append('<div class="card d-inline text-white bg-muted text-dark variacao variacao-'+(i+1)+'" data-variacao="'+(i+1)+'"><ul class="list-group list-group-flush border mb-3"><li class="list-group-item d-flex justify-content-between rounded-bottom bg-light text-secondary rounded-top"><span class="mb-0 nome-variacao">Variação '+(i+1)+'</span><strong class="fs-18 cPointer editar"><i class="fa-solid fa-pen-to-square"></i></strong></li></ul></div>');
    }

    for (i = variacaoA; i > variacaoN; --i) {
        $(origin).find(".box-variacoes div.variacao:last-child").remove();
    }
});

$(document).on("click", "#editVariacao .salvar", function (event) {
    var origin = $('.modal-primary.show');

    var nome = $("#editVariacao").find('input[name="nome"]').val();
    var variacao = $("#editVariacao").attr('data-variacao');
    var erro = 0;

    if(!nome) {
        Swal.fire("Oops...", "Insira o nome da variação para prosseguir!", "warning");
        return;
    }

    $("#editVariacao .tbody-cozinha-cardapio .item").each(function () {
        var ingrediente = $(this).find('select[name="select-produto"]');
        var qtd = $(this).find('input[name="qtd"]');

        // VALIDA SE ESTA VAZIO - ERRO 1
        if (!ingrediente.val() || !qtd.val()) {
            erro++;
            if (!ingrediente.val()) {
                inputErro(ingrediente);
            } else {
                inputCorrige(ingrediente)
            }
            if (!qtd.val()) {
                inputErro(qtd);
            } else {
                inputCorrige(qtd)
            }
        } else {
            inputCorrige(ingrediente);
            inputCorrige(qtd);
        }
    });

    if (erro > 0) {
        Swal.fire("Oops...", "Preencha todos os campos para prosseguir!", "warning");
        return;
    }
    
    var box_variacao = $(origin).find(".variacao-"+variacao);
    box_variacao.find('.item').remove();
    box_variacao.find('.nome-variacao').text(nome);
    
    $("#editVariacao .tbody-cozinha-cardapio .item").each(function () {
        var cod_ingrediente = $(this).find('select[name="select-produto"]').val();
        var tipo_ingrediente = $(this).find(".select-valida").find("option:selected").attr("data-tipo");
        var unidade_medida = $(this).find(".select-valida").find("option:selected").attr("data-unidade-medida");
        var qtd = $(this).find('input[name="qtd"]').val();
        var nome_ingrediente = $(this).find(".select-valida").find("option:selected").text();

        qtdN = qtd.replace(unidade_medida, "");
        qtdN = parseFloat(qtdN);

        var qtdF;
        if(unidade_medida == 'un'){
            qtdF = qtdN+' '+unidade_medida;
        }else{
            qtdF = qtdN+unidade_medida;
        }

        $(origin).find(".variacao-"+variacao+" ul.list-group").append('<li class="list-group-item item d-flex justify-content-between" data-cod="'+cod_ingrediente+'" data-tipo="'+tipo_ingrediente+'"><span class="mb-0"><span class="mr-2"><i class="fa fa-chevron-right"></i></span> <span class="ingrediente">'+nome_ingrediente+'</span></span><span class="quantidade" data-qtd="'+qtdN+'" data-un="'+unidade_medida+'">'+qtdF+'</span></li>')
    });

    $('#editVariacao').modal('toggle');
});

$(document).on("change", "#editVariacao .select-valida", function (event) {
    var unidade = $(this).find("option:selected").attr("data-unidade-medida");

    if (unidade == 'un') {
        $(this).closest("tr.item").find('input[name="qtd"]').val('');
        $(this).closest("tr.item").find('input[name="qtd"]').prop('disabled', false);
        $(this).closest("tr.item").find('input[name="qtd"]').mask("#0.000 un", {
            reverse: true
        });
    } else if (unidade == 'g') {
        $(this).closest("tr.item").find('input[name="qtd"]').val('');
        $(this).closest("tr.item").find('input[name="qtd"]').prop('disabled', false);
        $(this).closest("tr.item").find('input[name="qtd"]').mask("#0.0g", {
            reverse: true
        });
    } else if (unidade == 'ml') {
        $(this).closest("tr.item").find('input[name="qtd"]').val('');
        $(this).closest("tr.item").find('input[name="qtd"]').prop('disabled', false);
        $(this).closest("tr.item").find('input[name="qtd"]').mask("#00ml", {
            reverse: true
        });
    } else {
        $(this).closest("tr.item").find('input[name="qtd"]').val('');
        $(this).closest("tr.item").find('input[name="qtd"]').prop('disabled', true);
    }
});

$(document).on("change", "#addCategoria .select-valida", function (event) {
    var unidade = $(this).find("option:selected").attr("data-unidade-medida");

    if (unidade == 'un') {
        $(this).closest("tr.item").find('input[name="qtd"]').val('');
        $(this).closest("tr.item").find('input[name="qtd"]').prop('disabled', false);
        $(this).closest("tr.item").find('input[name="qtd"]').mask("#0.000 un", {
            reverse: true
        });
    } else if (unidade == 'g') {
        $(this).closest("tr.item").find('input[name="qtd"]').val('');
        $(this).closest("tr.item").find('input[name="qtd"]').prop('disabled', false);
        $(this).closest("tr.item").find('input[name="qtd"]').mask("#0.0g", {
            reverse: true
        });
    } else if (unidade == 'ml') {
        $(this).closest("tr.item").find('input[name="qtd"]').val('');
        $(this).closest("tr.item").find('input[name="qtd"]').prop('disabled', false);
        $(this).closest("tr.item").find('input[name="qtd"]').mask("#00ml", {
            reverse: true
        });
    } else {
        $(this).closest("tr.item").find('input[name="qtd"]').val('');
        $(this).closest("tr.item").find('input[name="qtd"]').prop('disabled', true);
    }
});

$(document).ready(function(){
    $("#inputBusca").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".box-cardapio div.cardapio-item").filter(function() {
        $(this).toggle($(this).find('.palavras-chaves').text().toLowerCase().indexOf(value) > -1);
      });
    });
});

$(document).on("click", ".cardapio-item-body", function (event) {
    var cod = $(this).attr('data-cod');

    $.ajax({
        type : 'POST',
        url  : './conexao/cozinha',
        data : { montaModal:'1', cod:cod },
        dataType: 'json',
        success :  function(retorno){
            if(retorno.erro == 0){
                $('#editCardapioCozinha .box-variacoes').html(retorno.listVariacoes);
                $("#editCardapioCozinha").find('input[name="nome"]').val(retorno.nome);
                $("#editCardapioCozinha").find('select[name="variacoes"]').find('option[value="'+retorno.variacoes+'"]').prop("selected", true);
                $("#editCardapioCozinha .div-variacoes .filter-option-inner-inner").text(retorno.variacoes);
                $("#editCardapioCozinha").find('select[name="categoria"]').find('option[value="'+retorno.idCategoria+'"]').prop("selected", true);
                $("#editCardapioCozinha .div-categoria .filter-option-inner-inner").text(retorno.categoria);
                $('#editCardapioCozinha').attr('data-cod', cod)
                $('#editCardapioCozinha').modal('show');
                return;
            }else{
                Swal.fire("Oops...", retorno.mensagem, "warning");
                return;
            }
        }
    });
});

$(document).on("click", "#editCardapioCozinha .salvar", function (event) {
    var nome = $("#editCardapioCozinha").find('input[name="nome"]').val();
    var categoria = $("#editCardapioCozinha").find('select[name="categoria"]').val();
    var nVariacoes = $("#editCardapioCozinha").find('select[name="variacoes"]').val();
    var cod = $('#editCardapioCozinha').attr('data-cod');
    var erro = 0;

    if (!nome || !categoria || !nVariacoes) {
        Swal.fire("Oops...", "Insira todas as informações para finalizar!", "warning");
        return;
    }

    var nBoxVariacoes = $("#editCardapioCozinha .box-variacoes .variacao").length;
    if(nVariacoes != nBoxVariacoes && nVariacoes > 1){
        Swal.fire("Oops...", "Erro interno, não foi possivel salvar!", "warning");
        return;
    }

    $("#editCardapioCozinha .box-variacoes .variacao").each(function () {
        var itens = $(this).find('li.item').length;

        if(itens < 1){
            ++erro;
        }
    });

    if (erro > 0) {
        Swal.fire("Oops...", "Finalize todas as variações para finalizar!", "warning");
        return;
    }

    $.ajax({
        type : 'POST',
        url  : './conexao/cozinha',
        data : { preEdit:'1', cod:cod },
        dataType: 'json',
        success :  function(retorno){
            if (retorno.erro == '0') {

                var img = retorno.img;

                var total = $("#editCardapioCozinha .box-variacoes .variacao li.item").length;
                var c = 1;
                var parar;

                $("#editCardapioCozinha .box-variacoes .variacao").each(function () {
                    var variacao = $(this).attr('data-variacao');
                    var nome_variacao = $(this).find('span.nome-variacao').text();

                    $(this).find("li.item").each(function () {
                        var cod_ingrediente = $(this).attr('data-cod');
                        var tipo_ingrediente = $(this).attr('data-tipo');
                        var qtd = $(this).find('span.quantidade').attr('data-qtd');
                        var unidade = $(this).find('span.quantidade').attr('data-un');

                        if(c == total){
                            parar = 1;
                        }else{
                            parar = 0
                            ++c;
                        }
                        
                        $.ajax({
                            type: 'POST',
                            url: './conexao/cozinha',
                            data: {
                                atualiza_cardapio: '1',
                                cod: cod,
                                nome: nome,
                                categoria: categoria,
                                cod_ingrediente: cod_ingrediente,
                                tipo_ingrediente: tipo_ingrediente,
                                qtd: qtd,
                                variacao: variacao,
                                unidade: unidade,
                                img: img,
                                nome_variacao:nome_variacao,
                                parar:parar
                            },
                            dataType: 'json',
                            success: function (retorno) {
                                if (retorno.erro == 0) {
                                    Swal.fire("Ótimo", "Item atualizado!", "success");
                                    $('#editCardapioCozinha').modal('toggle');
                                    atualizaItens();
                                    return;
                                } else if(retorno.erro == 1) {
                                    Swal.fire("Oops...", retorno.mensagem, "warning");
                                    return;
                                }
                            }
                        });
                    });
                });
                return;

            } else {
                Swal.fire("Oops...", retorno.mensagem, "warning");
                return;
            }
        }
    });
});

function atualizaItens() {
    $.ajax({
        type : 'POST',
        url  : './conexao/cozinha',
        data : { atualizaItens:'1' },
        dataType: 'json',
        success :  function(retorno){
            $('.box-cardapio').html(retorno.itens);
        }
    }); 
};

$('.modal form').submit(function (event) {
    event.preventDefault();

    $(this).closest('.modal').find('.salvar').click();
});

$(".modal form").keypress(function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if(code==13){
        $(this).closest('.modal').find('.salvar').click();
    }
});