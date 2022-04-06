$(document).ready(function(){
    $(document).on("change", "#addProducao .select-produto", function (event) {
        var cod = $(this).val();
        var qtd = $("#addProducao").find('select[name="qtd"]').val();
        
        if(cod){
            $.ajax({
                type : 'POST',
                url  : './conexao/add_producao',
                data : { consultaValorTotal:'1', cod:cod, qtd:qtd },
                dataType: 'json',
                success :  function(retorno){
                    if(retorno.erro == 0){
                        $("#addProducao").find('input[name="valorTotal"]').val(retorno.valor);
                        return;
                    }else{
                        Swal.fire("Oops...", retorno.mensagem, "warning");
                        return;
                    }
                }
            });
        }else{
            $("#addProducao").find('input[name="valorTotal"]').val("R$ 0,00");
        }
    });

    $(document).on('change', '#addProducao select[name="qtd"]', function (event) {
        var cod = $("#addProducao").find('.select-produto').val();
        var qtd = $(this).val();
        
        if(cod){
            $.ajax({
                type : 'POST',
                url  : './conexao/add_producao',
                data : { consultaValorTotal:'1', cod:cod, qtd:qtd },
                dataType: 'json',
                success :  function(retorno){
                    if(retorno.erro == 0){
                        $("#addProducao").find('input[name="valorTotal"]').val(retorno.valor);
                        return;
                    }else{
                        Swal.fire("Oops...", retorno.mensagem, "warning");
                        return;
                    }
                }
            });
        }else{
            $("#addProducao").find('input[name="valorTotal"]').val("R$ 0,00");
        }
    });

    $("#addProducao .salvar").click(function (){
        var cod = $('#addProducao').find('select[name="receita"]').val();
        var data = $('#addProducao').find('input[name="data"]').val();
        var qtd = $('#addProducao').find('select[name="qtd"]').val();
        var rendimento = $('#addProducao').find('input[name="rendimento"]').val();
        rendimento = rendimento.replace("g", "");

        // VALIDA CAMPOS VAZIOS
        if (!cod || !data || !qtd || !rendimento) {
            Swal.fire("Oops...", "Preencha todos os campos para prosseguir!", "warning");
            return;
        }

        var dateRegex = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{4})$/;

        // VALIDA DATA INCORRETA
        if (dateRegex.test(data) == false) {
            Swal.fire("Oops...", "Insisa uma data valida!", "warning");
            return;
        }

        $.ajax({
            type : 'POST',
            url  : './conexao/add_producao',
            data : { cadastraProducao:'1', cod:cod, data:data, qtd:qtd, rendimento:rendimento },
            dataType: 'json',
            success :  function(retorno){
                if(retorno.erro == 0){
                    Swal.fire("Ótimo", retorno.mensagem, "success");
                    $('#addProducao').modal('toggle');
                    $('#form_add_producao').trigger("reset");
                    $('#sellingItemsContent .timeline .producao_item').removeClass('animate__fadeInUp');
                    $('#sellingItemsContent .timeline .producao_item').addClass('animate__fadeOutUp');
                    setTimeout(function(){ 
                        atualizaPagination();
                        atualizaRanking();
                    }, 500);
                    return;
                }else{
                    Swal.fire("Oops...", retorno.mensagem, "warning");
                    return;
                }
            }
        });
    });

    $(document).on('click', 'li.producao_item .edit', function (event) {
        var origin = $(this).closest('li.producao_item');
    
        var id          = origin.attr('data-id');
        var receita     = origin.attr('data-receita');
        var data        = origin.attr('data-date');
        var qtd         = origin.attr('data-qtd');
        var rendimento  = origin.attr('data-rendimento');
        var valor       = origin.attr('data-valor');
    
        $('#editProducao').find('span.idReceita').text(id);
        $('#editProducao').find('input[name="receita"]').val(receita);
        $('#editProducao').find('input[name="data"]').val(data);
        $('#editProducao').find('input[name="qtd"]').val(qtd);
        $('#editProducao').find('input[name="rendimento"]').val(rendimento);
        $('#editProducao').find('input[name="valor"]').val(valor);
    
        $('#editProducao').modal('show');
    });

    $("#editProducao .salvar").click(function (){
        var cod         = $('#editProducao').find('span.idReceita').text();
        var data        = $('#editProducao').find('input[name="data"]').val();
        var rendimento  = $('#editProducao').find('input[name="rendimento"]').val();
        rendimento = rendimento.replace("g", "");

        // VALIDA CAMPOS VAZIOS
        if (!cod || !data || !rendimento) {
            Swal.fire("Oops...", "Preencha todos os campos para prosseguir!", "warning");
            return;
        }

        var dateRegex = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{4})$/;

        // VALIDA DATA INCORRETA
        if (dateRegex.test(data) == false) {
            Swal.fire("Oops...", "Insisa uma data valida!", "warning");
            return;
        }

        $.ajax({
            type : 'POST',
            url  : './conexao/add_producao',
            data : { editaProducao:'1', cod:cod, data:data, rendimento:rendimento },
            dataType: 'json',
            success :  function(retorno){
                if(retorno.erro == 0){
                    Swal.fire("Ótimo", retorno.mensagem, "success");
                    $('#editProducao').modal('toggle');
                    $('#form_edit_producao').trigger("reset");
                    $('#sellingItemsContent .timeline .producao_item').removeClass('animate__fadeInUp');
                    $('#sellingItemsContent .timeline .producao_item').addClass('animate__fadeOutUp');
                    setTimeout(function(){ 
                        atualizaPagination();
                        atualizaRanking();
                    }, 500);
                    return;
                }else{
                    Swal.fire("Oops...", retorno.mensagem, "warning");
                    return;
                }
            }
        });
    });

    $(document).on("click", "li.producao_item .delete", function () {    
        var id = $(this).closest('li.producao_item').attr('data-id');
    
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
                    url  : './conexao/add_producao',
                    data : { deletaProducao:'1', id:id },
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
                            setTimeout(function(){ 
                                atualizaPagination();
                                atualizaRanking();
                            }, 500);
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




});

atualizaRanking();

$(document).ready(function () {
    if(jQuery('#pagination-container').length > 0 ){
        $('#pagination-container').pagination({
            dataSource: function(done) {
                $.ajax({
                    type : 'POST',
                    url  : './conexao/add_producao',
                    data : { atualizaProducao:'1' },
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
                $('#sellingItemsContent .timeline').html(html);
            }
        });

        $.ajax({
            type : 'POST',
            url  : './conexao/add_producao',
            data : { consultaQtdProducao:'1' },
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
            }
        });
    };
});

function atualizaPagination() {
    $('#pagination-container').pagination('destroy');

    $('#sellingItemsContent .timeline').html('');
    $('ul.ul-pagination').html('');

    if(jQuery('#pagination-container').length > 0 ){
        $('#pagination-container').pagination({
            dataSource: function(done) {
                $.ajax({
                    type : 'POST',
                    url  : './conexao/add_producao',
                    data : { atualizaProducao:'1' },
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
                $('#sellingItemsContent .timeline').html(html);
            }
        });

        $.ajax({
            type : 'POST',
            url  : './conexao/add_producao',
            data : { consultaQtdProducao:'1' },
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
            }
        });
    };
};

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

function delay(fn, ms) {
    let timer = 0
    return function(...args) {
      clearTimeout(timer)
      timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
};

$(document).ready(function(){
    var anterior = 0
    var vAnterior;

    $('#inputBusca').keyup(delay(function (e) {
        var value = $(this).val().toLowerCase();
        var letra = " ";
        var newQtd = 0;

        for (var i = 0; i < value.length; i++) {
            if (value[i] == letra) {
                newQtd++
            }
        }

        var tamanho = (value.length)-newQtd;

        if(vAnterior == value){
            vAnterior = value;
            return;
        }else{
            vAnterior = value;
        }

        if(tamanho >= 3){
            $('#sellingItemsContent .timeline .producao_item').removeClass('animate__fadeInUp');
            $('#sellingItemsContent .timeline .producao_item').addClass('animate__fadeOutUp');
            setTimeout(function(){ 
                atualizaPaginationContition(value);
            }, 500);
            
        }else if(tamanho < 3 && anterior >= 3){
            $('#sellingItemsContent .timeline .producao_item').removeClass('animate__fadeInUp');
            $('#sellingItemsContent .timeline .producao_item').addClass('animate__fadeOutUp');
            setTimeout(function(){ 
                atualizaPagination(value);
            }, 500);
        }

        console.log(anterior+' - '+tamanho);

        anterior = tamanho;
    }, 500));
});

function atualizaPaginationContition(condicao) {
    $('#pagination-container').pagination('destroy');

    $('#sellingItemsContent .timeline').html('');
    $('ul.ul-pagination').html('');

    if(jQuery('#pagination-container').length > 0 ){
        $('#pagination-container').pagination({
            dataSource: function(done) {
                $.ajax({
                    type : 'POST',
                    url  : './conexao/add_producao',
                    data : { atualizaProducaoCondicao:'1', condicao:condicao },
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
                $('#sellingItemsContent .timeline').html(html);
            }
        });

        $.ajax({
            type : 'POST',
            url  : './conexao/add_producao',
            data : { consultaQtdProducaoCondicao:'1', condicao:condicao },
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
            }
        });
    };
};

function atualizaRanking() {
    $.ajax({
        type : 'POST',
        url  : './conexao/add_producao',
        data : { refreshRanking:'1' },
        dataType: 'json',
        success :  function(response){
            $('#dailyMenus').html(response);
        }
    }); 
};

