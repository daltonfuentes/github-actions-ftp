$(".revelador").click(function (){
    var classe = $(this).attr("data-class");

    var n = $(this).closest(".revealing").find("tbody ."+classe).length;

    var h1 = $(".revealing").find("tbody tr").height();
    var h2 = $(".revealing").find(".table-responsive").height();

    h = (h1*5)+h2;

    $(".revealing").find(".table-responsive").css("max-height" , h+"px");

    var div = $('.table-animate');

    if(n >= 5){
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);

        if(n == 5){
            $(this).closest("div.col-12").addClass("animate__backOutDown animate__animated ");
        }
    
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    }else if(n == 4){
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);

        if(n == 4){
            $(this).closest("div.col-12").addClass("animate__backOutDown animate__animated ");
        }
      
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    }else if(n == 3){
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);

        if(n == 3){
            $(this).closest("div.col-12").addClass("animate__backOutDown animate__animated ");
        }
       
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    }else if(n == 2){
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);

        if(n == 2){
            $(this).closest("div.col-12").addClass("animate__backOutDown animate__animated ");
        }
     
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    }else if(n == 1){
        $(this).closest(".revealing").find("tbody").children("."+classe).eq(0).removeClass(classe);
        
        $(this).closest("div.col-12").addClass("animate__backOutDown animate__animated ");

        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        // ALTERA PARA RECOLHER

    }
});

$(document).ready(function () {
    var h1 = $(".revealing").find("tbody tr").height();
    var h2 = $(".revealing").find("thead tr").height();
    h = (h1*10)+h2;
    $(".revealing").find(".table-responsive").css({"max-height" : h+"px", "overflow" : "hidden"});

    var n = $(".revealing").find("tbody .td-oculto").length;

    if(n == 0){
        $(".final-rolagem").addClass("d-none");
    };
});

$(document).on('click', '.editItem',function () {
    var id = $(this).closest("tr.item-compra").attr("data-id");
    var produto = $(this).closest("tr.item-compra").find("td.produto").text();
    var marca = $(this).closest("tr.item-compra").find("td.marca").text();
    var validade = $(this).closest("tr.item-compra").find("td.validade").text();
    var qtd = $(this).closest("tr.item-compra").find("td.qtdItem").attr("data-info");
    var unidade = $(this).closest("tr.item-compra").find("td.qtdItem").attr("data-un");
    var valorTotal = $(this).closest("tr.item-compra").find("td.valor-total").text();

    $('#editItem').modal('show');

    $('#editItem').find('span.id-item').text(id);
    $('#editItem').find('span.produto-item').text(produto);

    $('#editItem').find('input[name="marca"]').val(marca);
    $('#editItem').find('input[name="validade"]').val(validade);

    if(unidade == 'un' && qtd.indexOf('.') == -1){
        qtd = qtd+".0";
    }

    $('#editItem').find('input[name="qtd"]').val(qtd);

    $('#editItem').find('input[name="valorTotal"]').val(valorTotal);

    $('#editItem').find('input[name="qtd"]').attr('data-un',unidade);

    if (unidade == 'un') {
        $('#editItem').find('input[name="qtd"]').mask('#0.0 un', {reverse: true});
    } else if (unidade == 'g') {
        $('#editItem').find('input[name="qtd"]').mask('#0g', {reverse: true});
    } else if (unidade == 'ml') {
        $('#editItem').find('input[name="qtd"]').mask('#0ml', {reverse: true});
    }
});

$(document).on('click', '#editItem button.salvar',function () {
    var id = $('#editItem').find('span.id-item').text();
    var marca = $('#editItem').find('input[name="marca"]').val();
    var validade = $('#editItem').find('input[name="validade"]').val();
    var qtd = $('#editItem').find('input[name="qtd"]').val();
    var valorTotal = $('#editItem').find('input[name="valorTotal"]').val();
    var codCompra = $('#cod-compra').text();

    var unidade  = $('#editItem').find('input[name="qtd"]').attr('data-un');

    // VALIDA SE PEGOU COD COMPRA
    if (!codCompra || !unidade) {
        Swal.fire("Oops...", "Erro interno, não foi possivel alterar as informações!", "warning");
        return;
    }

    // VALIDA CAMPOS VAZIOS
    if (!marca || !validade || !qtd || !valorTotal) {
        Swal.fire("Oops...", "Preencha todos os campos para prosseguir!", "warning");
        return;
    }

    var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;

    // VALIDA DATA INCORRETA
    if (dateRegex.test(validade) == false) {
        Swal.fire("Oops...", "Insisa uma data valida!", "warning");
        return;
    }

    var valor = parseFloat(valorTotal.split('.').join('').split(',').join('.').split('R$').join(''));

    qtd = qtd.replace(unidade, "");
    qtd = parseFloat(qtd);

    // VALIDA VALOR INCOMUN
    if(valor < 1){
        Swal.fire({
            title: 'Deseja mesmo continuar?',
            text: "Preço Total fora do comum",
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
                    url  : url+'/conexao/add_compra',
                    data : { editaItem:'1', id:id, marca:marca, validade:validade, qtd:qtd, valorTotal:valorTotal, cod:codCompra },
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
                            
                            $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.marca").text(marca);
                            $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.validade").text(validade);
                            $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.qtdItem").text(qtd+' '+unidade);
                            $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.qtdItem").attr("data-info",qtd+' '+unidade);
                            $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.valor-total").text(valorTotal);
                            
                            $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.valor-un").text(retorno.valor_un);
                            $("p.soma-compra").text(retorno.soma);

                            $('#editItem input').val("");

                            $('#editItem').modal('hide');
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
    }else{
        $.ajax({
            type : 'POST',
            url  : url+'/conexao/add_compra',
            data : { editaItem:'1', id:id, marca:marca, validade:validade, qtd:qtd, valorTotal:valorTotal, cod:codCompra },
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
                    
                    $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.marca").text(marca);
                    $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.validade").text(validade);
                    $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.qtdItem").text(qtd+' '+unidade);
                    $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.qtdItem").attr("data-info",qtd+' '+unidade);
                    $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.valor-total").text(valorTotal);
                    
                    $(".table-produtos").find('tr[data-id="'+id+'"]').find("td.valor-un").text(retorno.valor_un);
                    $("p.soma-compra").text(retorno.soma);
                    
                    $('#editItem input').val("");

                    $('#editItem').modal('hide');
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
    }
});

$(document).on('click', '.apagaItem',function () {
    var id = $(this).closest("tr.item-compra").attr("data-id");
    var codCompra = $('#cod-compra').text();
    var origin = $(this).closest("tr.item-compra");

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
                data : { deletaItem:'1', id:id, cod:codCompra },
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
                        $("p.soma-compra").text(retorno.soma);
                        $("span.qtd-itens").text(retorno.itens);
                        origin.addClass("animate__fadeOutUp");
                        setTimeout(function() { origin.remove(); }, 500);
                        if(retorno.refresh == 1){
                            setTimeout(function() { 
                                window.location.href = url+"/compras"; 
                            }, 2000);
                        }
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

$(document).on('click', '#addItem button.salvar',function () {''
    var codCompra = $('#info-compra').attr('data-cod');
    var fornecedor = $('#info-compra').attr('data-fornecedor');
    var data_compra = $('#info-compra').attr('data-data-compra');
    var data_add = $('#info-compra').attr('data-data-add');

    var produto = $('#addItem').find('select[name="produto"]').val();
    var marca = $('#addItem').find('input[name="marca"]').val();
    var validade = $('#addItem').find('input[name="validade"]').val();
    var qtd = $('#addItem').find('input[name="qtd"]').val();
    var valorTotal = $('#addItem').find('input[name="valorTotal"]').val();
    var unidade = $('#addItem').find('option:selected').attr("data-un");

    // VALIDA CAMPOS VAZIOS
    if (!produto || !marca || !validade || !qtd || !valorTotal) {
        Swal.fire("Oops...", "Preencha todos os campos para prosseguir!", "warning");
        return;
    }

    // VALIDA SE PEGOU COD COMPRA
    if (!codCompra || !unidade) {
        Swal.fire("Oops...", "Erro interno, não foi possivel alterar as informações!", "warning");
        return;
    }

    qtd = qtd.replace(unidade, "");
    qtd = parseFloat(qtd);

    var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;

    // VALIDA DATA INCORRETA
    if (dateRegex.test(validade) == false) {
        Swal.fire("Oops...", "Insisa uma data valida!", "warning");
        return;
    }

    var valor = parseFloat(valorTotal.split('.').join('').split(',').join('.').split('R$').join(''));

    var padrao = '1';

    // VALIDA VALOR INCOMUN
    if(valor < 1){
        Swal.fire({
            title: 'Deseja mesmo continuar?',
            text: "Preço Total fora do comum",
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
                    url  : url+'/conexao/add_compra',
                    data : { cadastraCompra:'1' ,addItem:'1', cod:codCompra, produto:produto, marca:marca, validade:validade, qtd:qtd, total:valorTotal, fornecedor:fornecedor, data_compra:data_compra, data_add:data_add, unidade:unidade, qtdItens:padrao, itemAtual:padrao, pagina:padrao },
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
                            
                            $('#addItem').find('input[name="qtd"]').unmask();
                            $('#addItem input').val('');
                            $('#addItem').modal('toggle');

                            if(retorno.refresh == 'false'){
                                $(".table-produtos tbody").children("tr.item-compra").first().before(retorno.linha);
                            }else{
                                setTimeout(function(){ location.reload(); }, 2000);
                            }
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
    }else{
        $.ajax({
            type : 'POST',
            url  : url+'/conexao/add_compra',
            data : { cadastraCompra:'1' ,addItem:'1', cod:codCompra, produto:produto, marca:marca, validade:validade, qtd:qtd, total:valorTotal, fornecedor:fornecedor, data_compra:data_compra, data_add:data_add, unidade:unidade, qtdItens:padrao, itemAtual:padrao, pagina:padrao },
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
                            
                    $('#addItem').find('input[name="qtd"]').unmask();
                    $('#addItem input').val('');
                    $('#addItem').modal('toggle');

                    if(retorno.soma && retorno.itens){
                        $("span.qtd-itens").text(retorno.itens);
                        $("p.soma-compra").text(retorno.soma);
                    }else{
                        setTimeout(function(){ location.reload(); }, 2000);
                        return;
                    }

                    if(retorno.refresh == 'false'){
                        $(".table-produtos tbody").children("tr.item-compra").first().before(retorno.linha);
                    }else{
                        setTimeout(function(){ location.reload(); }, 2000);
                    }
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
    }
});

$('#editItem').on('hidden.bs.modal', function () {
    $('#editItem').find('input[name="qtd"]').unmask();
});

$(document).on('change', '#addItem select[name="produto"]', function (event) {
    var produto = $(this).val();
    var unidade = $(this).find('option:selected').attr("data-un");
    var input   = $('#addItem').find('input[name="qtd"]');

    if(produto){
        if (unidade == 'un') {
            input.prop("disabled",false); 
            input.mask('#0.0 un', {reverse: true});
        } else if (unidade == 'g' || unidade == 'ml') {
            input.prop("disabled",false); 
            input.mask('#0'+unidade, {reverse: true});
        }else{
            input.prop("disabled",true);
            input.val('');
            input.unmask();
        }
    }else{
        input.prop("disabled",true);
        input.val('');
        input.unmask();
    }
});

$('#addItem').on('hidden.bs.modal', function () {
    $('#addItem').find('input[name="qtd"]').unmask();
    $('#addItem input').val('');
});

$(document).on("click", ".deleteCompra", function () {
    var cod = $('#info-compra').attr('data-cod');

    Swal.fire({
        title: 'Deseja mesmo excluir toda essa compra?',
        text: "Esta ação não tera mais volta",
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
                        setTimeout(function() { 
                            window.location.href = url+"/compras"; 
                        }, 2000);
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

$(document).on('click', '#editCompra button.salvar',function () {
    var fornecedor  = $('#editCompra').find('select[name="fornecedor"]').val();
    var data_compra = $('#editCompra').find('input[name="data"]').val();

    var cod = $('#info-compra').attr('data-cod');

    // VALIDA CAMPOS VAZIOS
    if (!fornecedor || !data_compra) {
        Swal.fire("Oops...", "Preencha todos os campos para prosseguir!", "warning");
        return;
    }

    // VALIDA SE PEGOU COD COMPRA
    if (!cod) {
        Swal.fire("Oops...", "Erro interno, não foi possivel alterar as informações!", "warning");
        return;
    }

    var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;

    // VALIDA DATA INCORRETA
    if (dateRegex.test(data_compra) == false) {
        Swal.fire("Oops...", "Insisa uma data valida!", "warning");
        return;
    }

    $.ajax({
        type : 'POST',
        url  : url+'/conexao/add_compra',
        data : { atualizaInfoCompra:'1', cod:cod, fornecedor:fornecedor, data_compra:data_compra },
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
                $('#editCompra').modal('toggle');

                if(retorno.fornecedor && retorno.img && retorno.data){
                    $(".nome-fornecedor").text(retorno.fornecedor);
                    $(".img-fornecedor").attr('src', url+'/upload/fornecedor/'+retorno.img);
                    $(".data-compra").text(retorno.data);
                }else{
                    setTimeout(function(){ location.reload(); }, 2000);
                }
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



});