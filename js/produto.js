$(document).ready(function () {
    //var id = $('#info-produto').attr('data-cod');
    //var produto = $('#info-produto').attr('data-produto');
    //var min = $('#info-produto').attr('data-estoque-min');
    //var ideal = $('#info-produto').attr('data-estoque-ideal');
    var unidade = $('#info-produto').attr('data-unidade'); 

    if (unidade == 'un') {
        $('#editProduto').find('input[name="estoque_minimo"]').mask('#0.0 un', {reverse: true});
        $('#editProduto').find('input[name="estoque_ideal"]').mask('#0.0 un', {reverse: true});
    } else if (unidade == 'g') {
        $('#editProduto').find('input[name="estoque_minimo"]').mask('#0g', {reverse: true});
        $('#editProduto').find('input[name="estoque_ideal"]').mask('#0g', {reverse: true});
    } else if (unidade == 'ml') {
        $('#editProduto').find('input[name="estoque_minimo"]').mask('#0ml', {reverse: true});
        $('#editProduto').find('input[name="estoque_ideal"]').mask('#0ml', {reverse: true});
    }else{
        $('#editProduto').find('input[name="estoque_minimo"]').val('');
        $('#editProduto').find('input[name="estoque_minimo"]').prop("disabled",true);
        $('#editProduto').find('input[name="estoque_ideal"]').val('');
        $('#editProduto').find('input[name="estoque_ideal"]').prop("disabled",true);
    }
});

$(document).on('click', '#editProduto .salvar', function(){
    var unidade = $('#info-produto').attr('data-unidade');
    var cod = $('#info-produto').attr('data-cod');
    var atual = $('#info-produto').attr('data-estoque-atual');
    
    var produto = $(this).closest('#editProduto').find('input[name="produto"]').val();
    var min     = $(this).closest('#editProduto').find('input[name="estoque_minimo"]').val();
    var ideal   = $(this).closest('#editProduto').find('input[name="estoque_ideal"]').val();

    if(!unidade || !cod){
        Swal.fire("Oops...", 'Tivemos um erro interneo e não foi possivel alterar os as informações!', "warning");
        return;
    }

    min = min.replace(unidade, "");
    min = min.replace(" ", "");
    min = parseFloat(min);

    ideal = ideal.replace(unidade, "");
    ideal = ideal.replace(" ", "");
    ideal = parseFloat(ideal);


    if(!produto || !min || !ideal){
        Swal.fire("Oops...", "Preencha todos os campos para finalizar!", "warning");
        return;
    }

    if(min >= ideal){
        Swal.fire("Oops...", "O estoque minimo não pode ser igual ou maior ao estoque ideal", "warning");
        return;
    }

    $.ajax({
        type : 'POST',
        url  : './conexao/estoque',
        data : { editaProduto:'1', cod:cod, produto:produto, min:min, ideal:ideal },
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

                var status = Math.round(100*(atual/ideal));
                var bgl;
                var bg;
                var alerta;
                if(atual>= ideal){
                    bgl="bgl-primary";
                    bg="bg-primary";
                    alerta = "";
                }else if(atual < ideal && atual > min){
                    bgl="bgl-warning";
                    bg="bg-warning";
                    alerta = '';
                }else{
                    bgl="bgl-warning";
                    bg="bg-warning";
                    alerta = '<i class="fas fa-exclamation-triangle text-warning animate__animated animate__flash animate__infinite animate__slower animate__delay-2s mt-4" style="font-size: 300%;"></i>';
                }

                $('.estoque-produto').text(produto);
                $('.estoque-min').text(min+unidade);
                $('.estoque-ideal').text(ideal+unidade);
                $('.estoque-status').html('<div class="progress '+bgl+'" style="height: 10px;"><div class="progress-bar progress-animated '+bg+'" style="width: '+status+'%;" role="progressbar"></div></div>')
                $('.estoque-alerta').html(alerta);
                $('#editProduto').modal('toggle');
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

$(document).on("click", ".deleteProduto", function () {
    var cod = $('#info-produto').attr('data-cod');

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
                url  : './conexao/estoque',
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
                        setTimeout(function(){ window.location.href = "./produtos"; }, 2000);
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