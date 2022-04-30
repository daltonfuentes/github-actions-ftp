$(document).ready(function () {
 
    $.ajax({
        type : 'POST',
        url  : './conexao/ifood_api.php',
        data : { new_session: true },
        dataType: 'json',
        success :  function(retorno){
            console.log(retorno.accessToken);
        }
    });

});

$(document).on('click', '.btn-group-type button', function(){
    if($(this).hasClass('.ativo') == false){
        var type = $(this).attr('data-type');
        $('.btn-group-type').find('.ativo').addClass('btn-outline-custom-2');
        $('.btn-group-type').find('.ativo').removeClass('btn-custom-2 ativo');
        $(this).removeClass('btn-outline-custom-2');
        $(this).addClass('ativo btn-custom-2');

        if(type == 'scheduled'){
            $('#row-list-orders-immediate').addClass('d-none');
            $('#row-list-orders-scheduled').removeClass('d-none');
        }else if(type == 'immediate'){
            $('#row-list-orders-scheduled').addClass('d-none');
            $('#row-list-orders-immediate').removeClass('d-none');
        }
    }
});

$(document).on('click', '.faixa-pedido', function(){
    faixaPedidosClick($(this));
});

function faixaPedidosClick(clicker) {
    ativa = $('#row-list-orders-immediate .active');

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

$(document).on('click', '.dropdown-menu-status', function(){
    e.stopPropagation();
});

$(document).ready(function() {
    var nStatusIfood = 0;
    function refreshStatusIfood() {
        $.ajax({
            type : 'POST',
            url  : './conexao/ifood_api.php',
            data : { status_ifood: true },
            dataType: 'json',
            beforeSend: function() {
                
            },
            success :  function(retorno){
                if(retorno.code == 200){
                    nStatusIfood = 0;
                    $("#box_satus_ifood").html(retorno.html);
                    
                }else{ nStatusIfood++; console.log('Success com code diferente.'); }
            },
            error: function() {
                nStatusIfood++;
            },
            complete: function() {
                setTimeout(refreshStatusIfood, 30000);
                if(nStatusIfood >= 4){
                    
                }
                console.log(nStatusIfood);
            }
        });
    };
    refreshStatusIfood();

    const checkOnlineStatus = async () => {
        try {
            const online = await fetch("http://www.google.com/");
            const result = online.status >= 200 && online.status < 300; // either true or false
            console.log(result);
        } catch (err) {
            const result = false; // definitely offline
            console.log(result);
        }
    };


    function testaConexao() {
        checkOnlineStatus();

        setTimeout(testaConexao, 2000);
    };
    testaConexao();
});