$(document).on("click", "#addReceita .item .delete", function (event) {
    var linha = $(this).closest(".item");
    linha.removeClass("animate__fadeInDown");
    linha.addClass("animate__fadeOutUp");
    setTimeout(function() { linha.remove(); }, 500);
});

$(document).on("change", "#addReceita .select-valida", function (event) {
    var unidade = $(this).find('option:selected').attr("data-un");
    $(this).closest("tr.item").find('input[name="un"]').val(unidade);

    var inputQtd = $(this).closest("tr.item").find('input[name="qtd"]');
    inputQtd.val('');
    
    if (unidade == 'un') {
        inputQtd.mask('#0.0 un', {reverse: true});
        inputQtd.prop('disabled', false);
    } else if (unidade == 'g') {       
        inputQtd.mask('#0g', {reverse: true});
        inputQtd.prop('disabled', false);
    } else if (unidade == 'ml') {      
        inputQtd.mask('#0ml', {reverse: true});
        inputQtd.prop('disabled', false);
    }else{
        inputQtd.prop('disabled', true);
    }
});

$(document).on("click", "#addReceita .modal-footer .finalizar", function(){
    $('#add-nova-receita').submit();
});

$('#add-nova-receita').submit(function (event) {
    event.preventDefault();

    var arrayProduto = [];
    var erro;

    var receita = $("#addReceita").find('input[name="nome-receita"]');

    if(!receita.val()){
        erro = 1;
        inputErro(receita);
    }else{
        inputCorrige(receita);
    }

    $(".tbody-receitas").find(".item").each(function() {
        var produto = $(this).find('select[name="select-produto"]');
        var qtd = $(this).find('input[name="qtd"]');

        // VALIDA SE ESTA VAZIO - ERRO 1
        if(!produto.val() || !qtd.val()){
            erro = 1;
            if(!produto.val()){ inputErro(produto); }else{ inputCorrige(produto) }
            if(!qtd.val()){ inputErro(qtd); }else{ inputCorrige(qtd) }
        }else{
            inputCorrige(produto);
            inputCorrige(qtd);
        }
    });

    if(erro == 1){
        Swal.fire("Oops...", "Preencha todos os campos para finalizar!", "warning");
        return;
    }

    $(".tbody-receitas").find(".item").each(function() {
        var produto = $(this).find('select[name="select-produto"]');

        // NOTIFICA QUE TEM PRODUTO REPETIDO - NOTIFICAÇÃO 1
        var cod = produto.val();

        if(arrayProduto.indexOf(cod) > -1){
            erro = 2;
        }else{
            arrayProduto.push(produto.val());
        }
    });

    if(erro == 2){
        Swal.fire("Oops...", "Ingredientes repetidos, unifique-os em apenas uma linha para prosseguir.", "warning");
        return;
    }

    receita = receita.val();

    // -------------------------- //
    // INICIO PROCEDIMENTO DE IMG //
    // -------------------------- //

    var fakeImg = $("#fake-input-upload").val();

    if(fakeImg == ''){
        Swal.fire("Oops...", "Faça o upload da imagem do produto para prosseguir.", "warning");
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
    }
    else if (!$input.files) {
        Swal.fire("Oops...", "Este navegador não tem suporte a propriedade 'files' para inputs do tipo file.", "warning");
        return;
    }
    else if (!$input.files[0]) {
        Swal.fire("Oops...", "Nenhum arquivo selecionado!", "warning");
        return;
    }
    else {
        file = $input.files[0];
        $tamanho = file.size;

        if($tamanho >= 29999999){
            Swal.fire("Oops...", "Arquivo excede o limite de tamanho. *30MB", "warning");
            return;
        }
    }
    // Captura os dados do formulário *Esta pegando apenas a img e instância o FormData passando como parâmetro o formulário
    var formulario = document.getElementById('add-nova-receita');
    var formData = new FormData(formulario);

    $.ajax({
        type : 'POST',
        url  : './conexao/add_receita',
        data : { criaCod:'1' },
        dataType: 'json',
        success :  function(retorno){
            if(retorno.erro == 0){
                var cod = retorno.cod;

                formData.append('upload-img', '1');

                $.ajax({
                    url: "./conexao/add_receita.php",
                    type: "POST",
                    data: formData,
                    dataType: 'json',
                    processData: false,  
                    contentType: false,
                    success: function(retorno){
                        if (retorno.erro == '0'){
                            var nImg = retorno.img;

                            $(".tbody-receitas").find(".item").each(function() {
                                var Vproduto = $(this).find('select[name="select-produto"]').val();
                                var Vqtd = $(this).find('input[name="qtd"]').val();

                                var unidade = $(this).find('option:selected').attr("data-un");

                                Vqtd = Vqtd.replace(unidade, "");
                                Vqtd = parseFloat(Vqtd);
                        
                                $.ajax({
                                    type : 'POST',
                                    url  : './conexao/add_receita',
                                    data : { cadastraReceita:'1', cod:cod, produto:Vproduto, receita:receita, qtd:Vqtd, img:nImg },
                                    dataType: 'json',
                                    success :  function(retorno){
                                        if(retorno.erro == 0){
                                            loadingContainer();
                                            recolheCard();
                                            setTimeout(function(){ atualizaReceitas('col-lg-3');; }, 400);
                                            $('#addReceita').modal('toggle');
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Ótimo',
                                                text: 'Receita cadastrada com sucesso!',
                                                showConfirmButton: false,
                                                timer: 1500
                                            });
                                            
                                            $('#form-receita-1').trigger("reset");
                                            $('#input-upload-img').val(); 

                                            $('#form-receita-1 .qtd').prop('disabled', true);

                                            return;
                                        }else{
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: retorno.mensagem,
                                                showConfirmButton: false,
                                                timer: 4000
                                            });
                                            return;
                                        }
                                    }
                                });
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: retorno.mensagem,
                                showConfirmButton: false,
                                timer: 4000
                            });
                            return;
                        }
                    }
                }); 
                return;
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: retorno.mensagem,
                    showConfirmButton: false,
                    timer: 4000
                });
                return;
            }
        }
    });
});



$(document).on("click", ".receita-card", function (event) {
    if($('.box-card-receita').hasClass('d-none') == true){ // CONSULTA SE A BOX DOS DOIS CARD'S ESTA ABERTA
        // Atualiza card-1
        var card = $('.card-edit-receita-1');
        var receita = $(this);

        atualizaCard(card, receita).then(response => {
            // ESTANDO ABERTA MOSTRA PRIMEIRO O CARD-1
            if($('.nav-header .hamburger').hasClass('is-active') == false){
                // RECOLHE O NAV LATERAL CASO ESTEJA ABERTO
                $('.nav-header .hamburger').click();
            }

            // Modifica o col das receitas
            $('.receita-item').removeClass('col-lg-3');
            $('.receita-item').addClass('col-lg-6');

            // Modifica a div onde estao as receitas. col-12 ==> col-6
            $(this).closest('div.config-col').removeClass('col-12');
            $(this).closest('div.config-col').addClass('col-6');
            
            $('.card-edit-receita-2').addClass('d-none')
            $('.box-card-receita').removeClass('d-none');
            $('.card-edit-receita-1').removeClass('d-none');

            $('.receita-card').removeClass('shadow');
            $(this).addClass('shadow'); 
        }).catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o WebMaster.',
                showConfirmButton: false,
                timer: 4000
            });
            return;
        });
    }else{
        var ativo =  $('.card-edit:not(.d-none)'); // procura qual card esta ativo (vai ser oculto)
        var oculto = $('.card-edit.d-none'); // procuta qual card esta oculto (vai ser atualizado e exibido)

        // atualiza card
        var receita = $(this);
        atualizaCard(oculto, receita).then(response => {
            // oculta card que estava ativo
            ativo.addClass('animate__bounceOutRight');
            setTimeout(function(){ ativo.addClass('d-none'); ativo.removeClass('animate__bounceOutRight'); }, 400);

            // exibe card que estava oculto
            setTimeout(function(){ oculto.removeClass('d-none'); }, 400);

            $('.receita-card').removeClass('shadow');
            $(this).addClass('shadow'); 
        }).catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o WebMaster.',
                showConfirmButton: false,
                timer: 4000
            });
            return;
        }); 
    }  
});

$(document).on("click", ".close-card", function (event) {
    recolheCard();
});

$(document).ready(function () {
    atualizaReceitas('col-lg-3');
});

function atualizaReceitas (col){
    $.ajax({
        type : 'POST',
        url  : './conexao/add_receita',
        data : { consultasReceita:'1', col:col },
        dataType: 'json',
        success: function(response) {
            $('div.box-receitas').html(response);
            return;
         }
    });
};

function recolheCard() {
    if($('.nav-header .hamburger').hasClass('is-active') == true){

        $('.card-edit:not(.d-none)').addClass('animate__fadeOutRight');

        setTimeout(function(){ 
            $('.nav-header .hamburger').click();
            $('.receita-item').addClass('col-lg-3');
            $('.receita-item').removeClass('col-lg-6');
            $('div.config-col').addClass('col-12');
            $('div.config-col').removeClass('col-6');
            $('.box-card-receita').addClass('d-none');

            $('.card-edit:not(.d-none)').removeClass('animate__fadeOutRight');
        }, 300);
        
    }else{
        $('.card-edit:not(.d-none)').addClass('animate__fadeOutRight');

        setTimeout(function(){ 
            $('.receita-item').addClass('col-lg-3');
            $('.receita-item').removeClass('col-lg-6');
            $('div.config-col').addClass('col-12');
            $('div.config-col').removeClass('col-6');
            $('.box-card-receita').addClass('d-none');

            $('.card-edit:not(.d-none)').removeClass('animate__fadeOutRight');
        }, 300);
    }
    $('.receita-card').removeClass('shadow');    
};

function loadingContainer() {
    $('.loarder-new').removeClass('d-none');

    setTimeout(function(){
        $('.loarder-new').removeClass('animate__fadeInNew');
        $('.loarder-new').addClass('animate__fadeOutNew');
    }, 1500);
    
    setTimeout(function(){
        $('.loarder-new').removeClass('animate__fadeOutNew');
        $('.loarder-new').addClass('animate__fadeInNew d-none');
    }, 2500);
};

function atualizaCard(card, receita) {
    return new Promise((resolve, reject) => {
        var cod = receita.closest('.receita-item').attr('data-cod');
    
        $.ajax({
            type : 'POST',
            url  : './conexao/add_receita',
            data : { infoReceita:'1', cod:cod },
            dataType: 'json',
        }).done(function(retorno) {
            if(retorno.erro == "0"){
                card.find('tbody').html(retorno.response);
                card.find('.product-detail-content h2').text('#'+retorno.cod+' | '+retorno.receita);
                card.find('.div-img-custon-02-int').css("background-image", "url('upload/receitas/"+retorno.img+"')");
                card.attr('data-cod', retorno.cod);
                resolve();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: retorno.mensagem,
                    showConfirmButton: false,
                    timer: 4000
                });
                reject();
            }
        });
    });
};

function atualizaCardCod(card, cod) {
    return new Promise((resolve, reject) => {    
        $.ajax({
            type : 'POST',
            url  : './conexao/add_receita',
            data : { infoReceita:'1', cod:cod },
            dataType: 'json',
        }).done(function(retorno) {
            if(retorno.erro == "0"){
                card.find('tbody').html(retorno.response);
                card.find('.product-detail-content h2').text('#'+retorno.cod+' | '+retorno.receita);
                card.find('.div-img-custon-02-int').css("background-image", "url('upload/receitas/"+retorno.img+"')");
                card.attr('data-cod', retorno.cod);
                resolve();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: retorno.mensagem,
                    showConfirmButton: false,
                    timer: 4000
                });
                reject();
            }
        });
    });
};

$(document).on("change", "#addIngrediente .select-valida", function (event) {
    var unidade = $(this).find('option:selected').attr("data-un");

    var inputQtd = $(this).closest("div.row").find('input[name="qtd"]');
    inputQtd.val('');
    
    if (unidade == 'un') {
        inputQtd.mask('#0.0 un', {reverse: true});
        inputQtd.prop('disabled', false);
    } else if (unidade == 'g') {       
        inputQtd.mask('#0g', {reverse: true});
        inputQtd.prop('disabled', false);
    } else if (unidade == 'ml') {      
        inputQtd.mask('#0ml', {reverse: true});
        inputQtd.prop('disabled', false);
    }else{
        inputQtd.prop('disabled', true);
    }
});

$(document).on("click", "#addIngrediente .modal-footer .finalizar", function(){
    var ativo =  $('.card-edit:not(.d-none)'); // procura qual card esta ativo (vai ser atualizado)

    var cod =  $('.card-edit:not(.d-none)').attr('data-cod');
    var ingrediente = $('#addIngrediente').find('select[name="select-produto"]').val();
    var qtd = $('#addIngrediente').find('input[name="qtd"]').val();
    var unidade = $('#addIngrediente').find('option:selected').attr("data-un");

    qtd = qtd.replace(unidade, "");
    qtd = parseFloat(qtd);
    
    // VALIDA SE ESTA VAZIO - ERRO 1
    if(!ingrediente || !qtd){
        Swal.fire("Oops...", "Preencha todos os campos para finalizar!", "warning");
        return;
    }

    $.ajax({
        type : 'POST',
        url  : './conexao/add_receita',
        data : { addIngrediente:'1', cod:cod, ingrediente:ingrediente, qtd:qtd },
        dataType: 'json',
    }).done(function(retorno) {
        if(retorno.erro == "0"){
            Swal.fire({
                icon: 'success',
                title: 'Ótimo',
                text: 'Ingrediente cadastrado com sucesso!',
                showConfirmButton: false,
                timer: 1500
            });
            $('#addIngrediente').modal('toggle');
            $('#addIngrediente').find('select[name="select-produto"]').val('');
            $('#addIngrediente').find('input[name="qtd"]').val('');
            loadingContainer();
            atualizaCardCod(ativo, cod);
            atualizaReceitas('col-lg-6');
            resolve();
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: retorno.mensagem,
                showConfirmButton: false,
                timer: 4000
            });
            reject();
        }
    });
});

$(document).on("click", "tr.ingrediente-receita .editItem", function(){
    var id          = $(this).closest('tr.ingrediente-receita').attr('data-id');
    var ingrediente = $(this).closest('tr.ingrediente-receita').attr('data-ingrediente');
    var qtd         = $(this).closest('tr.ingrediente-receita').attr('data-qtd');
    var unidade      = $(this).closest('tr.ingrediente-receita').attr('data-un');

    $('#editIngrediente').find('span.id-item').text(id);
    $('#editIngrediente').attr('data-id', id);
    
    $('#editIngrediente').find('input[name="ingrediente"]').val(ingrediente);

    $('#editIngrediente').find('input[name="qtd"]').val(qtd);
    var inputQtd = $('#editIngrediente').find('input[name="qtd"]');
    if (unidade == 'un') {
        inputQtd.mask('#0.0 un', {reverse: true});
        inputQtd.prop('disabled', false);
    } else if (unidade == 'g') {       
        inputQtd.mask('#0g', {reverse: true});
        inputQtd.prop('disabled', false);
    } else if (unidade == 'ml') {      
        inputQtd.mask('#0ml', {reverse: true});
        inputQtd.prop('disabled', false);
    }else{
        inputQtd.prop('disabled', true);
    }
    $('#editIngrediente').attr('data-un', unidade);

    $('#editIngrediente').modal('show');
});

$('#editIngrediente').on('hidden.bs.modal', function (e) {
    $('#editIngrediente').find('input[name="ingrediente"]').val('');
    $('#editIngrediente').find('input[name="qtd"]').val('');
});

$(document).on("click", "#editIngrediente .modal-footer .finalizar", function(){
    var ativo =  $('.card-edit:not(.d-none)'); // procura qual card esta ativo (vai ser atualizado)
    var cod =  $('.card-edit:not(.d-none)').attr('data-cod');

    var id      = $('#editIngrediente').attr('data-id');
    var qtd     = $('#editIngrediente').find('input[name="qtd"]').val();
    var unidade = $('#editIngrediente').attr('data-un');

    qtd = qtd.replace(unidade, "");
    qtd = parseFloat(qtd);
    
    // VALIDA SE ESTA VAZIO - ERRO 1
    if(!qtd){
        Swal.fire("Oops...", "Preencha o campo quantidade finalizar!", "warning");
        return;
    }

    $.ajax({
        type : 'POST',
        url  : './conexao/add_receita',
        data : { editIngrediente:'1', id:id, qtd:qtd },
        dataType: 'json',
    }).done(function(retorno) {
        if(retorno.erro == "0"){
            Swal.fire({
                icon: 'success',
                title: 'Ótimo',
                text: 'Ingrediente atualizado com sucesso!',
                showConfirmButton: false,
                timer: 1500
            });
            $('#editIngrediente').modal('toggle');
            loadingContainer();
            atualizaCardCod(ativo, cod);
            resolve();
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: retorno.mensagem,
                showConfirmButton: false,
                timer: 4000
            });
            reject();
        }
    });
});

$(document).on("click", "tr.ingrediente-receita .apagaItem", function(){
    var id    = $(this).closest('tr.ingrediente-receita').attr('data-id');
    var cod   = $('.card-edit:not(.d-none)').attr('data-cod');
    var ativo = $('.card-edit:not(.d-none)'); // procura qual card esta ativo (vai ser atualizado)

    Swal.fire({
        title: 'Deseja mesmo excluir?',
        text: "Esta ação não terá mais volta",
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
                url  : url+'/conexao/add_receita',
                data : { deletaIngrediente:'1', id:id, cod:cod },
                dataType: 'json',
                success :  function(retorno){
                    if(retorno.erro == 0){
                        Swal.fire({
                            icon: 'success',
                            title: 'Ótimo',
                            text: 'Ingrediente excluido corretamente.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        loadingContainer();
                        atualizaCardCod(ativo, cod);
                        atualizaReceitas('col-lg-6');
                        return;
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: retorno.mensagem,
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
});

$(document).ready(function(){
    $("#inputBusca").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".box-receitas div.receita-item").filter(function() {
        $(this).toggle($(this).find('.span-filter').text().toLowerCase().indexOf(value) > -1);
      });
    });
});