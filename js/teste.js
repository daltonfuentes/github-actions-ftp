$(document).ready(function () {

    return;

    
    var token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJzdWIiOiIyYzk5ZWQ0OS00NzhiLTQ5NTktYjM5Mi00ODgyOGVkYTk5NTQiLCJhdWQiOlsiY2F0YWxvZyIsImZpbmFuY2lhbCIsInJldmlldyIsIm1lcmNoYW50Iiwib3JkZXIiLCJvYXV0aC1zZXJ2ZXIiXSwiYXBwX25hbWUiOiJhZG1pbnN3ZWV0Y29uZmV0dHl0ZXN0ZWMiLCJvd25lcl9uYW1lIjoiYWRtaW5zd2VldGNvbmZldHR5Iiwic2NvcGUiOlsiY2F0YWxvZyIsInJldmlldyIsIm1lcmNoYW50Iiwib3JkZXIiLCJjb25jaWxpYXRvciJdLCJpc3MiOiJpRm9vZCIsIm1lcmNoYW50X3Njb3BlIjpbIjg2YzM2NGU1LWFhMzAtNDk5ZS1hZWIxLWEyZDNkZGZjMmIzZTptZXJjaGFudCIsIjg2YzM2NGU1LWFhMzAtNDk5ZS1hZWIxLWEyZDNkZGZjMmIzZTpvcmRlciIsIjg2YzM2NGU1LWFhMzAtNDk5ZS1hZWIxLWEyZDNkZGZjMmIzZTpjYXRhbG9nIiwiODZjMzY0ZTUtYWEzMC00OTllLWFlYjEtYTJkM2RkZmMyYjNlOmNvbmNpbGlhdG9yIiwiODZjMzY0ZTUtYWEzMC00OTllLWFlYjEtYTJkM2RkZmMyYjNlOnJldmlldyJdLCJleHAiOjE2MzI0MzcyNzEsImlhdCI6MTYzMjQxNTY3MSwianRpIjoiODc2N2MyOTYtZDFiOS00MjY5LTk3NDMtZjViOWYyYjRkNGM0IiwibWVyY2hhbnRfc2NvcGVkIjp0cnVlLCJjbGllbnRfaWQiOiIyYzk5ZWQ0OS00NzhiLTQ5NTktYjM5Mi00ODgyOGVkYTk5NTQifQ.FwBKajQEF_h3ScvlwkhGG2jVOBsKEJlygcaGzQbLFJXdRA4nl28FYK0tQnxTrymbcCCYTsVAD6zfDkG-OLaYO2C_nLCxd1eoF7p1Hbq7Cfx7omD1hmuSiJ0dt6KABlRS1H3m51jgfgUeq3dMgKUmWdFbQ5loQMh7zNAnUoqNoZU';

    token = "Bearer "+token;




    function confirmaOuCancelaPedido(orderId, token){
        Swal.fire({
            title: 'Deseja aceitar o pedido?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2BC155',
            cancelButtonColor: '#F94687',
            confirmButtonText: 'ACEITAR',
            cancelButtonText: 'RECUSAR'
            }).then((result) => {
            if (result.isConfirmed) {   
                var settings = {
                    "url": "https://merchant-api.ifood.com.br/order/v1.0/orders/"+orderId+"/confirm",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Authorization": token
                    },
                };
                
                $.ajax(settings).done(function (response, jqXHR, textStatus, errorThrown) {
                    var status = textStatus.status;
                    if(status == 202){
                        Swal.fire("Confirmado", "Pedido aceito!", "success");
                    }else if(status == 404){
                        Swal.fire("Oops", status, "warning");
                        return;
                    }else if(status == 500){
                        Swal.fire("Oops", status, "warning");
                        return;
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.status);
                });
                return;
            }else{
                var settings = {
                    "url": "https://merchant-api.ifood.com.br/order/v1.0/orders/"+orderId+"/requestCancellation",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                    "x-client-id": "75e08a83-c4b8-4c88-834b-a927ca5cbc0f",
                    "Authorization": token
                    },
                    "data": JSON.stringify({
                    "cancellationCode": "503",
                    "reason": ""
                    }),
                };
                
                $.ajax(settings).done(function (response, jqXHR, textStatus, errorThrown) {
                    var status = textStatus.status;
                    if(status == 202){
                        Swal.fire("Cancelado", "Pedido recusado!", "warning");
                        return;
                    }else if(status == 404){
                        Swal.fire("Oops", status, "warning");
                        return;
                    }else if(status == 500){
                        Swal.fire("Oops", status, "warning");
                        return;
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.status);
                });
            return;
            }
            });
    };

    $.ajax({
        type : "GET",
        url  : "https://merchant-api.ifood.com.br/order/v1.0/events:polling",
        timeout: 0,
        headers: {
          "Authorization": token
        },
        success :  function(response, jqXHR, textStatus, errorThrown){
            var status = textStatus.status;
            if(status == 200){
                $.each(response, function(i, item) {
                    var orderId = response[i].orderId;
                    var fullCode = response[i].fullCode;
                    Swal.fire("Ótimo", orderId+' - '+fullCode, "success");
                });
            }else{
                Swal.fire("Oops...", status, "warning");
                return;
            }
        }
    });





    

    var clientId = "2c99ed49-478b-4959-b392-48828eda9954";
    var clientSecret = "oqy93k6snxjmgruuabkt82hz7fw5djuorlcxcaaapdtzo0zsa0neyz3ztqv452ein8d3j0jibusrejcox7mzkzoakzmokg93rc7";
    var grantType = "client_credentials";
    var merchantId = "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e";

    var url_base = "https://merchant-api.ifood.com.br/";
    var session  = "authentication/v1.0/";
    var final    = "oauth/token";

    

    var settings = {
        "url": "https://merchant-api.ifood.com.br/order/v1.0/events:polling",
        "method": "GET",
        "timeout": 0,
        "headers": {
          "Authorization": token
        },
    };
    
    
    $.ajax(settings).done(function (response, jqXHR, textStatus, errorThrown) {
        var status = textStatus.status;
        if(status == 200){
            $.each(response, function(i, item) {
                var orderId = response[i].orderId;
                var fullCode = response[i].fullCode;
                console.log(orderId);
                console.log(fullCode);

                var settings = {
                    "url": "https://merchant-api.ifood.com.br/order/v1.0/orders/"+orderId+"/confirm",
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Authorization": token
                    },
                };
                
                $.ajax(settings).done(function (response, jqXHR, textStatus, errorThrown) {
                    var status = textStatus.status;
                    if(status == 202){
                        Swal.fire("Confirmado", "Pedido aceito!", "success");
                    }else if(status == 404){
                        Swal.fire("Oops", status, "warning");
                        return;
                    }else if(status == 500){
                        Swal.fire("Oops", status, "warning");
                        return;
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.status);
                });
            });
        }else if(status == 204){
            console.log('Não tem pedidos.');
        }else if(status == 400){
            console.log('Erro: '+status);
        }else if(status == 403){
            console.log('Erro: '+status);
        }else if(status == 404){
            console.log('Erro: '+status);
        }else if(status == 413){
            console.log('Erro: '+status);
        }else if(status == 415){
            console.log('Erro: '+status);
        }else if(status == 500){
            console.log('Erro: '+status);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.status);
    });




    return;

    consulta.done((e, status, code) => {
        console.log(code.status)
    });

    
    
    var settings = {
        "url": url_base+session+final,
        "method": "POST",
        "timeout": 0,
        "headers": {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        "data": {
          "grantType": grantType,
          "clientId": clientId,
          "clientSecret": clientSecret,
        }
    };
      
    $.ajax(settings).done(function (response) {
        var retorno = response;
        console.log(retorno.accessToken);
    });




    return;
    
    
    



});