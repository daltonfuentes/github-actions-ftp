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

$(window).on("load", function(){
    function fazPolling() {
        $.ajax({
            type : 'POST',
            url  : './conexao/ifood_api.php',
            data : { polling: true },
            dataType: 'json',
            beforeSend: function() {
                
            },
            success :  function(retorno){
                
            },
            error: function() {
                
            },
            complete: function() {
                setTimeout(fazPolling, 30000);
                listOrders();
            }
        });
    };
    fazPolling();

    const errorConnectionStatus = '<div class="media align-items-center"><i class="fa-solid fa-circle-exclamation text-red fs-24 mr-3"></i><h4 class="fs-16 font-w600 text-red mb-0">Erro ao carregar status<br><span class="fs-14 font-w400">Verifique sua conex??o</span></h4></div><hr><h4 class="fs-14 font-w500 text-black">Atualize a pagina e verifique novamente. Caso o problema persista, entre em contato com o suporte.</h4>';
    const errorInternStatus = '<div class="media align-items-center"><i class="fa-solid fa-circle-exclamation text-warning fs-24 mr-3"></i><h4 class="fs-16 font-w600 text-warning mb-0">Erro ao carregar status<br><span class="fs-14 font-w400">Passando por problemas internos</span></h4></div><hr><h4 class="fs-14 font-w500 text-black">Atualize a pagina e verifique novamente. Caso o problema persista, entre em contato com o suporte.</h4>';
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
        if(status == 'OK'){
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').removeClass('merchant-close merchant-warning merchant-fail');
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').addClass('merchant-open');
        }else if(status == 'WARNING'){
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').removeClass('merchant-open merchant-close merchant-fail');
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').addClass('merchant-warning');
        }else if(status == 'CLOSED' || status == 'ERROR'){
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').removeClass('merchant-open merchant-warning merchant-fail');
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').addClass('merchant-close');
        }else if(status == 'FAIL'){
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').removeClass('merchant-open merchant-close merchant-warning');
            $('.merchant-status-'+plataform+' button.btn-merchant-circle').addClass('merchant-fail');
        }
    }

    function listOrders() {
        var active = $('#row-list-orders-immediate .faixa-pedido.active').attr('data-orderId');
        //var htmlActive = $('#row-list-orders-immediate .faixa-pedido.active').html();
        //var statusActive = $('#row-list-orders-immediate .faixa-pedido.active').attr('data-status');
        

        $.ajax({
            type : 'POST',
            url  : './conexao/ifood_api.php',
            data : { orders_list: true, orderIdAtivo: active },
            dataType: 'json',
            beforeSend: function() {
                
            },
            success :  function(retorno){
                if(retorno.list == true){
                    $("#row-list-orders-immediate").html(retorno.immediate);
                }else{ 
                    
                }
            },
            error: function() {

            },
            complete: function() {
                if (typeof active != 'undefined'){
                    refreshOrderDetails(active);
                }
            }
        });
    };

    function refreshOrderDetails(id) {
        $.ajax({
            type : 'POST',
            url  : './conexao/ifood_api.php',
            data : { orders_details_ifood: true, orderId: id, type: 'IMMEDIATE' },
            dataType: 'json',
            beforeSend: function() {
                
            },
            success :  function(retorno){
                if(retorno.error == false){
                    $("#order_details").html(retorno.details);
                }else{ 
                    
                }
            },
            error: function() {
                
            },
            complete: function() {
    
            }
        });
    };

    $(document).on('click', '#row-list-orders-immediate .faixa-pedido', function(){
        var orderId = $(this).attr('data-orderId');
        refreshOrderDetails(orderId);
    });

    $(document).on('click', '.btnOrderCfm', function(){
        var orderId = $(this).attr('data-orderId');
        var origin = $(this);

        if(origin.hasClass('disabled') == true){
            return;
        }else{
            origin.html('<i class="fa-duotone fa-spinner-third fs-18 fa-spin"></i>');
            origin.addClass('disabled');
            $('.btnOrderCan').addClass('disabled');
        }

        $.ajax({
            type : 'POST',
            url  : './conexao/ifood_api.php',
            data : { order_ifood_cfm: true, orderId: orderId },
            dataType: 'json',
            beforeSend: function() {
                
            },
            success :  function(retorno){
                if(retorno.error == false){
                    $.ajax({
                        type : 'POST',
                        url  : './conexao/ifood_api.php',
                        data : { polling: true },
                        dataType: 'json',
                        beforeSend: function() {
                            
                        },
                        success :  function(retorno){
                            
                        },
                        error: function() {
                            
                        },
                        complete: function() {
                            listOrders();
                        }
                    });
                }else{
                    origin.html('ACEITAR');
                    origin.removeClass('disabled');
                    $('.btnOrderCan').removeClass('disabled');
                }          
            },
            error: function() {
                origin.html('ACEITAR');
                origin.removeClass('disabled');
                $('.btnOrderCan').removeClass('disabled');
            },
            complete: function() {
    
            }
        });
    });

    $(document).on('click', '.btnOrderDsp', function(){
        var orderId = $(this).attr('data-orderId');
        var origin = $(this);

        if(origin.hasClass('disabled') == true){
            return;
        }else{
            origin.html('<i class="fa-duotone fa-spinner-third fs-18 fa-spin"></i>');
            origin.addClass('disabled');
            $('.btnOrderCan').addClass('disabled');
        }

        $.ajax({
            type : 'POST',
            url  : './conexao/ifood_api.php',
            data : { order_ifood_dsp: true, orderId: orderId },
            dataType: 'json',
            beforeSend: function() {
                
            },
            success :  function(retorno){
                if(retorno.error == false){
                    $.ajax({
                        type : 'POST',
                        url  : './conexao/ifood_api.php',
                        data : { polling: true },
                        dataType: 'json',
                        beforeSend: function() {
                            
                        },
                        success :  function(retorno){
                            
                        },
                        error: function() {
                            
                        },
                        complete: function() {
                            listOrders();
                        }
                    });
                }else{
                    origin.html('<i class="fa-solid fa-motorcycle fs-16"></i> <span class="ml-2 fs-16">DESPACHAR</span>');
                    origin.removeClass('disabled');
                    $('.btnOrderCan').removeClass('disabled');
                }          
            },
            error: function() {
                origin.html('<i class="fa-solid fa-motorcycle fs-16"></i> <span class="ml-2 fs-16">DESPACHAR</span>');
                origin.removeClass('disabled');
                $('.btnOrderCan').removeClass('disabled');
            },
            complete: function() {
    
            }
        });
    });

    $(document).on('click', '.btnOrderOnDemand', function(){
        var orderId = $(this).attr('data-orderId');
        var origin = $(this);

        if(origin.hasClass('disabled') == true){
            return;
        }else{
            origin.html('<i class="fa-duotone fa-spinner-third fs-18 fa-spin"></i>');
            origin.addClass('disabled');
            $('.btnOrderCan').addClass('disabled');
            $('.btnOrderCfm').addClass('disabled');
        }

        $.ajax({
            type : 'POST',
            url  : './conexao/ifood_api.php',
            data : { order_ifood_rdr: true, orderId: orderId },
            dataType: 'json',
            beforeSend: function() {
                
            },
            success :  function(retorno){
                if(retorno.error == false){
                    $.ajax({
                        type : 'POST',
                        url  : './conexao/ifood_api.php',
                        data : { polling: true },
                        dataType: 'json',
                        beforeSend: function() {
                            
                        },
                        success :  function(retorno){
                            
                        },
                        error: function() {
                            
                        },
                        complete: function() {
                            listOrders();
                        }
                    });
                }else{
                    origin.html('<i class="fa-solid fa-motorcycle fs-16"></i> <span class="ml-2 fs-16">DESPACHAR</span>');
                    origin.removeClass('disabled');
                    $('.btnOrderCan').removeClass('disabled');
                    $('.btnOrderCfm').removeClass('disabled');
                }          
            },
            error: function() {
                origin.html('<i class="fa-solid fa-motorcycle fs-16"></i> <span class="ml-2 fs-16">DESPACHAR</span>');
                origin.removeClass('disabled');
                $('.btnOrderCan').removeClass('disabled');
                $('.btnOrderCfm').removeClass('disabled');
            },
            complete: function() {
    
            }
        });
    });

    $(document).on('click', '.btnOrderRej', function(){
        var orderId = $(this).attr('data-orderId');
        $('#modalOrderCancel .btnOrderCanFinish').attr('data-orderId', orderId);

        $('#modalOrderCancel').modal('show');
        $('#modalOrderCancel .question_cancel').text('Selecione o motivo pelo qual voc?? n??o pode aceitar esse pedido:');

        var origin = $(this);

        if(origin.hasClass('disabled') == true){
            return;
        }else{
            origin.html('<i class="fa-duotone fa-spinner-third fs-18 fa-spin"></i>');
            origin.addClass('disabled');
            $('.btnOrderCfm').addClass('disabled');
        }
    });

    $(document).on('click', '.btnOrderCan', function(){
        var orderId = $(this).attr('data-orderId');
        $('#modalOrderCancel .btnOrderCanFinish').attr('data-orderId', orderId);

        $('#modalOrderCancel').modal('show');
        $('#modalOrderCancel .question_cancel').text('Selecione o motivo pelo qual voc?? quer cancelar esse pedido:');

        var origin = $(this);

        if(origin.hasClass('disabled') == true){
            return;
        }else{
            origin.html('<i class="fa-duotone fa-spinner-third fs-18 fa-spin"></i>');
            origin.addClass('disabled');
            $('.btnOrderCfm').addClass('disabled');
            $('.btnOrderDsp').addClass('disabled');
            $('.btnOrderOnDemand').addClass('disabled');
        }
    });

    $('#modalOrderCancel').on('hide.bs.modal', function (event) {
        $('.btnOrderRej').html('<i class="fa-solid fa-ban fs-16 text-white"></i><span class="ml-2 fs-16">RECUSAR</span>');
        $('.btnOrderRej').removeClass('disabled');

        $('.btnOrderCan').html('<i class="fa-solid fa-ban fs-16 text-white"></i><span class="ml-2 fs-16">CANCELAR</span>');
        $('.btnOrderCan').removeClass('disabled');

        $('.btnOrderCfm').removeClass('disabled');
        $('.btnOrderDsp').removeClass('disabled');
        $('.btnOrderOnDemand').removeClass('disabled');

        $('#modalOrderCancel input[name="cancel_code"]').prop('checked', false);
        $('#cancel_code_other .cancel_reason').val('');
        $('#cancel_code_other').addClass('d-none');
        $('#modalOrderCancel .btnOrderCanFinish').addClass('btn-outline-dark disabled');
        $('#modalOrderCancel .btnOrderCanFinish').removeClass('btn-danger');
    });

    $(document).on('change', '#modalOrderCancel input[name="cancel_code"]', function () {
        if($(this).val() == '501'){
            $('#cancel_code_other').removeClass('d-none');

            var valor = $('#cancel_code_other .cancel_reason').val();

            if(valor.length > 3){
                $('#cancel_code_other .invalid-feedback').removeClass('d-block');
                $('#modalOrderCancel .btnOrderCanFinish').removeClass('btn-outline-dark disabled');
                $('#modalOrderCancel .btnOrderCanFinish').addClass('btn-danger');
            }else{
                $('#cancel_code_other .invalid-feedback').addClass('d-block');
                $('#modalOrderCancel .btnOrderCanFinish').addClass('btn-outline-dark disabled');
                $('#modalOrderCancel .btnOrderCanFinish').removeClass('btn-danger');
            }
        }else{
            $('#cancel_code_other').addClass('d-none');
            $('#modalOrderCancel .btnOrderCanFinish').removeClass('btn-outline-dark disabled');
            $('#modalOrderCancel .btnOrderCanFinish').addClass('btn-danger');
            $('#cancel_code_other .cancel_reason').val('');
        }
    });

    $(document).on('keyup', '#cancel_code_other .cancel_reason', function () {
        var valor = $(this).val();
        
        if(valor.length > 3){
            $('#cancel_code_other .invalid-feedback').removeClass('d-block');
            $('#modalOrderCancel .btnOrderCanFinish').removeClass('btn-outline-dark disabled');
            $('#modalOrderCancel .btnOrderCanFinish').addClass('btn-danger');
        }else{
            $('#cancel_code_other .invalid-feedback').addClass('d-block');
            $('#modalOrderCancel .btnOrderCanFinish').addClass('btn-outline-dark disabled');
            $('#modalOrderCancel .btnOrderCanFinish').removeClass('btn-danger');
        }
    });

    $(document).on('click', '.btnOrderCanFinish', function(){
        var orderId = $(this).attr('data-orderId');

        var cancel_code = $('#modalOrderCancel input[name="cancel_code"]:checked').val();
        var cancel_reason = $('#cancel_code_other .cancel_reason').val();

        if(cancel_code != '501'){
            cancel_reason = $('#modalOrderCancel input[name="cancel_code"]:checked').closest('label').find('.checkmark').text();
        }

        if(!cancel_code || !cancel_reason){
            return;
        }

        var origin = $(this);

        if(origin.hasClass('disabled') == true){
            return;
        }else{
            origin.html('<i class="fa-duotone fa-spinner-third fs-18 fa-spin"></i>');
            origin.addClass('disabled');
        }

        $.ajax({
            type : 'POST',
            url  : './conexao/ifood_api.php',
            data : { order_ifood_can: true, orderId: orderId, cancellationCode: cancel_code, reason: cancel_reason },
            dataType: 'json',
            beforeSend: function() {
                
            },
            success :  function(retorno){
                if(retorno.error == false){
                    $.ajax({
                        type : 'POST',
                        url  : './conexao/ifood_api.php',
                        data : { polling: true },
                        dataType: 'json',
                        beforeSend: function() {
                            
                        },
                        success :  function(retorno){
                        },
                        error: function() {
                            
                        },
                        complete: function(){
                            listOrders();
                        }
                    });
                    $('#modalOrderCancel').modal('toggle');
                    
                    $('.btnOrderCfm').addClass('disabled');
                    $('.btnOrderDsp').addClass('disabled');
                    $('.btnOrderOnDemand').addClass('disabled');

                    origin.html('Cancelar pedido');
                    origin.removeClass('disabled');
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Tivemos um problema!',
                        text: 'Atualize a pagina e tente novamente. Caso o problema persista, entre em contato com o suporte.',
                        showConfirmButton: false,
                        timer: 15000
                    });
                    origin.html('Cancelar pedido');
                    origin.removeClass('disabled');
                }          
            },
            error: function() {
                origin.html('Cancelar pedido');
                origin.removeClass('disabled');
            },
            complete: function() {
    
            }
        });
    });
});

