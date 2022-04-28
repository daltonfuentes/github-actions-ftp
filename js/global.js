$('.maskMoney').maskMoney({
    thousands: '.',
    decimal: ',',
    prefix: 'R$ '
});

// COLOCA ADICIONAL
$(document).on('click', '.addAdicional', function () {
    if ($(this).hasClass("btn-disable")) {
        return;
    }
    var qtd = $(this).closest('div.adicionalCalculate').find('strong.qtd').text();
    qtd = parseInt(qtd);

    if (qtd == 0) {
        $(this).closest('div.adicionalCalculate').find('.divQtd').removeClass('d-none');
        $(this).closest('div.adicionalCalculate').find('.divQtd').addClass('d-inline-block');

        $(this).closest('div.adicionalCalculate').find('.diminuiAdicional').removeClass('d-none');
        $(this).closest('div.adicionalCalculate').find('.diminuiAdicional').addClass('d-inline-block');

        $(this).closest('div.adicionalCalculate').find('strong.qtd').text(qtd+1);
    } else {
        $(this).closest('div.adicionalCalculate').find('strong.qtd').text(qtd+1);
    }

    var valorAdicional = $(this).closest('.adicionalItem').find('.valorAdicional').text();
    var final = $(this).closest(".itemDetail").find('strong.valor-total-item').text(); // VALOR QUE ESTA NO TOTAL
    var un = $(this).closest('div.itemDetail').find('strong.qtdItem').text(); // MULTIPLICADOR DE ITENS

    if(un == 1){
        var resultado = somaValoresReal(valorAdicional, final);
        $(this).closest(".itemDetail").find('strong.valor-total-item').text(resultado);
    }else{
        final = parseFloat(final.split('.').join('').split(',').join('.').split('R$').join(''));
        somar = parseFloat(valorAdicional.split('.').join('').split(',').join('.').split('R$').join(''));
        total = final + (somar*un);
        total = total.toLocaleString("pt-BR", {
            style: "currency",
            currency: "BRL"
        });
        $(this).closest(".itemDetail").find('strong.valor-total-item').text(total);
    }

    // VERIFICA SE FOI ATINGIDO LIMITE DE ADICIONAIS
    var q = 0;
    $(this).closest('div.adicionais').find('strong.qtd').each(function () {
        q += parseInt($(this).text());
    });

    if (q == 3) {
        $(this).closest('div.adicionais').find('.addAdicional').addClass('btn-disable');
    }
});

// TIRA ADICIONAL
$(document).on('click', '.diminuiAdicional', function () {
    var qtd = $(this).closest('div.adicionalCalculate').find('strong.qtd').text();
    qtd = parseInt(qtd);

    if (qtd == 1) {
        $(this).closest('div.adicionalCalculate').find('.divQtd').addClass('d-none');
        $(this).closest('div.adicionalCalculate').find('.divQtd').removeClass('d-inline-block');

        $(this).addClass('d-none');
        $(this).removeClass('d-inline-block');

        $(this).closest('div.adicionalCalculate').find('strong.qtd').text(0);
    } else {
        $(this).closest('div.adicionalCalculate').find('strong.qtd').text(qtd-1);
    }

    var valorAdicional = $(this).closest('.adicionalItem').find('.valorAdicional').text();
    var final = $(this).closest(".itemDetail").find('strong.valor-total-item').text(); // VALOR QUE ESTA NO TOTAL
    var un = $(this).closest('div.itemDetail').find('strong.qtdItem').text(); // MULTIPLICADOR DE ITENS

    if(un == 1){
        var resultado = subtraiValoresReal(final, valorAdicional);
        $(this).closest(".itemDetail").find('strong.valor-total-item').text(resultado);
    }else{
        final = parseFloat(final.split('.').join('').split(',').join('.').split('R$').join(''));
        diminuir = parseFloat(valorAdicional.split('.').join('').split(',').join('.').split('R$').join(''));
        total = final-(diminuir*un);
        total = total.toLocaleString("pt-BR", {
            style: "currency",
            currency: "BRL"
        });
        $(this).closest(".itemDetail").find('strong.valor-total-item').text(total);
    }

    // LIBERA BOTAO DE ADICIONAR CASO ESTIVESSE BLOQUEADO
    var q = 0;
    $(this).closest('div.adicionais').find('strong.qtd').each(function () {
        q += parseInt($(this).text());
    });

    if (q < 3) {
        $(this).closest('div.adicionais').find('.addAdicional').removeClass('btn-disable');
    }
});

// ALTERAR TAMANHO DO COPO
$('.itemDetail .labelTamanho').on('click', function () { 
    if(!$(".itemDetail input[name='tamanho']:checked").length){
        var valor = $(this).closest("label").find('.valor').text(); // VALOR DO COPO
        var final = $(this).closest(".itemDetail").find('strong.valor-total-item').text(); // VALOR QUE ESTA NO TOTAL
        var un = $(this).closest('div.itemDetail').find('strong.qtdItem').text(); // MULTIPLICADOR DE ITENS

        if(un == 1){
            var resultado = somaValoresReal(final, valor);
            $(this).closest(".itemDetail").find('strong.valor-total-item').text(resultado);
        }else{
            atual = parseFloat(final.split('.').join('').split(',').join('.').split('R$').join(''));
            somar = parseFloat(valor.split('.').join('').split(',').join('.').split('R$').join(''));
            total = atual + (somar*un);
            total = total.toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL"
            });
            $(this).closest(".itemDetail").find('strong.valor-total-item').text(total);
        }
    }else{
        var subtrair = $(".itemDetail input[name='tamanho']:checked").closest("label").find('.valor').text();
        var final = $(this).closest(".itemDetail").find('strong.valor-total-item').text();
        var un = $(this).closest('div.itemDetail').find('strong.qtdItem').text(); // MULTIPLICADOR DE ITENS
        if(un == 1){
            var resultado = subtraiValoresReal(final, subtrair);
            var add = $(this).closest("label").find('.valor').text();
            resultado = somaValoresReal(resultado, add);
            $(this).closest(".itemDetail").find('strong.valor-total-item').text(resultado);
        }else{
            total = parseFloat(final.split('.').join('').split(',').join('.').split('R$').join(''));
            copoAtual = parseFloat(subtrair.split('.').join('').split(',').join('.').split('R$').join(''));
            sobra = total-(copoAtual*un);
            var add = $(this).closest("label").find('.valor').text();
            copoNovo = parseFloat(add.split('.').join('').split(',').join('.').split('R$').join(''));
            total = sobra+(copoNovo*un);
            total = total.toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL"
            });
            $(this).closest(".itemDetail").find('strong.valor-total-item').text(total);
        }  
    }
    // $('.valor-tamanho').addClass("d-none");
    // $(this).closest("label").find('.valor-tamanho').removeClass("d-none");
});

function somaValoresReal(atual, somar) {
    atual = parseFloat(atual.split('.').join('').split(',').join('.').split('R$').join(''));
    somar = parseFloat(somar.split('.').join('').split(',').join('.').split('R$').join(''));
    total = atual + somar;
    total = total.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
    });
    return total;
};
function subtraiValoresReal(atual, diminuir) {
    atual = parseFloat(atual.split('.').join('').split(',').join('.').split('R$').join(''));
    diminuir = parseFloat(diminuir.split('.').join('').split(',').join('.').split('R$').join(''));
    total = atual - diminuir;
    total = total.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
    });
    return total;
};
function multiplicaValorReal(valor, multi) {
    atual = parseFloat(valor.split('.').join('').split(',').join('.').split('R$').join(''));
    total = atual * multi;
    total = total.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
    });
    return total;
};
function diminuiQtdItem(valor, multiAtual) {
    atual = parseFloat(valor.split('.').join('').split(',').join('.').split('R$').join(''));
    un = atual/multiAtual;
    total = un*(multiAtual-1);
    total = total.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
    });
    return total;
};
function addQtdItem(valor, multiAtual) {
    atual = parseFloat(valor.split('.').join('').split(',').join('.').split('R$').join(''));
    un = atual/multiAtual;
    total = un*(multiAtual+1);
    total = total.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
    });
    return total;
};

function realParaNumero(valor) {
    valor = parseFloat(valor.split('.').join('').split(',').join('.').split('R$').join(''));
    return valor;
};

$(document).on('click', '.addQtdItem', function () {
    var qtd = $(this).closest('div.qtdCalculate').find('strong.qtdItem').text();
    qtd = parseInt(qtd);
    var valor = $(this).closest(".itemDetail").find('strong.valor-total-item').text();
    var resultado = addQtdItem(valor, qtd);
    qtd = qtd+1;
    $(this).closest(".itemDetail").find('strong.valor-total-item').text(resultado);
    $(this).closest('div.qtdCalculate').find('strong.qtdItem').text(qtd);
});

$(document).on('click', '.diminuiQtdItem', function () {
    var qtd = $(this).closest('div.qtdCalculate').find('strong.qtdItem').text();
    qtd = parseInt(qtd);
    
    if(qtd == 1){
        toastr.warning('Numero minimo para este item é "1"', 'Atenção', {
            positionClass: "toast-bottom-center",
            timeOut: 10e3,
            closeButton: !0,
            debug: !1,
            newestOnTop: !0,
            progressBar: !0,
            preventDuplicates: !0,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            tapToDismiss: !1
        });
        return;
    }else{
        var valor = $(this).closest(".itemDetail").find('strong.valor-total-item').text();
        var resultado = diminuiQtdItem(valor, qtd);
        qtd = qtd-1;
        $(this).closest(".itemDetail").find('strong.valor-total-item').text(resultado);
        $(this).closest('div.qtdCalculate').find('strong.qtdItem').text(qtd);
    } 
});

$(document).ready(function(){
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
    $('.money2').mask("#.##0,00", {reverse: true});
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
      translation: {
        'Z': {
          pattern: /[0-9]/, optional: true
        }
      }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {reverse: true});
    $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
    $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
    $('.fallback').mask("00r00r0000", {
        translation: {
          'r': {
            pattern: /[\/]/,
            fallback: '/'
          },
          placeholder: "__/__/____"
        }
      });
    $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
 
    $('.qtd').mask('#0.0', {reverse: true});
    $('.qtdun').mask('#0.0 un', {reverse: true});
    $('.peso').mask("#0g", {reverse: true});
});

var url = "https://painel.sweetconfetty.com.br"; 

function geraSerial6D() {
    var text = "";
    var possible = "0123456789";
  
    for (var i = 0; i < 4; i++)
      text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
  }

function inputErro(input){
    if(input.hasClass("select-valida") == true ){
        $(input).closest("div.form-group").find(".select2-selection").addClass("border-danger");
    }else{
        $(input).removeClass("input-border");
        $(input).closest("div.form-group").addClass("input-danger");
    }
};

function inputCorrige(input){
    if(input.hasClass("select-valida") == true ){
        $(input).closest("div.form-group").find(".border-danger").removeClass("border-danger");
    }else{
        $(input).addClass("input-border");
        $(input).closest("div.form-group").removeClass("input-danger");
    }
};

function stringToMoney(string) {
    var formatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        // These options are needed to round to whole numbers if that's what you want.
        //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    });
    return formatter.format(string);
};

//window.onload = refresh();
function refresh(){
    var segundos = 5; //5 segundos de espera
    var oReq = new XMLHttpRequest();

    oReq.responseType = 'json';

    //Defina como true
    oReq.open("GET", "./conexao/refresh.php?refresh_estoque=1", true);

    //Função assíncrona que aguarda a resposta
    oReq.onreadystatechange = function(){
        var jsonResponse = oReq.response;

        if (oReq.readyState == 4) {
            if (oReq.status == 200) {
                if(jsonResponse.erro != 0){
                    console.log(jsonResponse.erro); // PARA TUDO E MUDA DE PAGINA CASO TENHA ERRO
                    return;
                }else{
                    setTimeout(refresh, segundos * 1000);
                }
            }
        }
    };
    //Envia a requisição, mas a resposta fica sendo aguardada em Background
    oReq.send(null);
};
