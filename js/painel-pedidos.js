$(document).ready(function () {
 
    $.ajax({
        type : 'POST',
        url  : './conexao/add_cardapio',
        data : { new_session: true },
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



});



$(document).on('click', '.faixa-pedido', function(){
    faixaPedidosClick($(this));
});

function faixaPedidosClick(clicker) {
    ativa = $('#row-list-orders .active');

    if(ativa.hasClass('alerta') == true){
        ativa.addClass('animate__animated');
        console.log('Tava em alerta');
    }

    if(clicker.hasClass('alerta') == true){
        clicker.removeClass('animate__animated');
    }

    ativa.removeClass('active');
    clicker.addClass('active');
};

$(document).on('click', '.btn-status', function(){
    var platform = $(this).attr('data-platform');
    var origin   = $(this);
    var status   = $(this).attr('data-status');

    if(status == 'close'){
        origin.find('.platform').addClass('d-none');
        origin.find('.loading').removeClass('d-none');





        setTimeout(function(){
            origin.find('.platform').removeClass('d-none');
            origin.find('.loading').addClass('d-none');

            origin.removeClass('btn-dark');
            origin.addClass('btn-success');
            origin.attr('data-status', 'open');
        }, 2000);
    }else if(status == 'open'){
        origin.find('.platform').addClass('d-none');
        origin.find('.loading').removeClass('d-none');





        setTimeout(function(){
            origin.find('.platform').removeClass('d-none');
            origin.find('.loading').addClass('d-none');

            origin.addClass('btn-dark');
            origin.removeClass('btn-success');
            origin.attr('data-status', 'close');
        }, 2000);
    }
});

