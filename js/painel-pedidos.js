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
    const errorConnectionStatus = '<div class="media align-items-center"><i class="fa-solid fa-circle-exclamation text-red fs-24 mr-3"></i><h4 class="fs-16 font-w600 text-red mb-0">Erro ao carregar status<br><span class="fs-14 font-w400">Verifique sua conexão</span></h4></div><hr><h4 class="fs-14 font-w500 text-black">Atualize a pagina e verifique novamente. Caso o problema persista, fale com o suporte.</h4>';
    const errorInternStatus = '<div class="media align-items-center"><i class="fa-solid fa-circle-exclamation text-warning fs-24 mr-3"></i><h4 class="fs-16 font-w600 text-warning mb-0">Erro ao carregar status<br><span class="fs-14 font-w400">Passando por problemas internos</span></h4></div><hr><h4 class="fs-14 font-w500 text-black">Atualize a pagina e verifique novamente. Caso o problema persista, fale com o suporte.</h4>';
    const errorStatus = '<div class="media align-items-center"><i class="fa-solid fa-circle-exclamation text-warning fs-24 mr-3"></i><h4 class="fs-16 font-w600 text-warning mb-0">Erro ao carregar status<br><span class="fs-14 font-w400">Tentando reconectar</span></h4></div>';

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
                    changeStyleButtonStatus(retorno.state, 'ifood');
                }else{ 
                    nStatusIfood++; 
                    $("#box_satus_ifood").html(errorStatus);
                    changeStyleButtonStatus('FAIL', 'ifood');
                }
            },
            error: function() {
                nStatusIfood++;
                $("#box_satus_ifood").html(errorInternStatus);
                changeStyleButtonStatus('FAIL', 'ifood');
            },
            complete: function() {
                setTimeout(refreshStatusIfood, 30000);
                if(nStatusIfood >= 4){
                    $("#box_satus_ifood").html(errorConnectionStatus);
                    changeStyleButtonStatus('FAIL', 'ifood');
                }
            }
        });
    };
    refreshStatusIfood();

    function changeStyleButtonStatus(status, plataform) {
        if(status == 'OK' || status == 'WARNING'){
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').removeClass('merchant-close');
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').addClass('merchant-open');
        }else if(status == 'CLOSED' || status == 'ERROR'){
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').removeClass('merchant-open');
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').addClass('merchant-close');
        }else if(status == 'FAIL'){
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').removeClass('merchant-open merchant-close');
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').addClass('merchant-fail');
        }
    }
});