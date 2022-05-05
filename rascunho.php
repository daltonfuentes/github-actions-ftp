[
    {
        "operation": "delivery",
        "salesChannel": "ifood-app",
        "available": false,
        "state": "CLOSED",
        "reopenable": {
            "identifier": null,
            "type": null,
            "reopenable": false
        },
        "validations": [
            {
                "id": "is-connected",
                "code": "is.not.connected.config",
                "state": "ERROR",
                "message": {
                    "title": "Fale com a sua integradora",
                    "subtitle": "Entre em contato com a empresa responsável pelo seu sistema de PDV ou com o nosso atendimento.",
                    "description": "",
                    "priority": 9
                }
            },
            {
                "id": "opening-hours",
                "code": "outside.opening-hours.config",
                "state": "CLOSED",
                "message": {
                    "title": "Fora do horário de funcionamento",
                    "subtitle": "Seu próximo turno começa terça-feira às 00:00",
                    "description": "",
                    "priority": 26
                }
            }
        ],
        "message": {
            "title": "Loja fechada",
            "subtitle": "Fora do horário programado",
            "description": null,
            "priority": null
        }
    }
]


[
    {
        "operation": "delivery",
        "salesChannel": "ifood-app",
        "available": false,
        "state": "CLOSED",
        "reopenable": {
            "identifier": null,
            "type": null,
            "reopenable": false
        },
        "validations": [
            {
                "id": "is-connected",
                "code": "is.connected.config",
                "state": "OK",
                "message": {
                    "title": "Loja conectada à rede do iFood",
                    "subtitle": "",
                    "description": "",
                    "priority": 26
                }
            },
            {
                "id": "opening-hours",
                "code": "outside.opening-hours.config",
                "state": "CLOSED",
                "message": {
                    "title": "Fora do horário de funcionamento",
                    "subtitle": "Seu próximo turno começa terça-feira às 00:00",
                    "description": "",
                    "priority": 26
                }
            }
        ],
        "message": {
            "title": "Loja fechada",
            "subtitle": "Fora do horário programado",
            "description": null,
            "priority": null
        }
    }
]




{
    "id": "30357a33-385d-4a2f-87ba-bc4e99da1115",
    "delivery": {
        "mode": "DEFAULT",
        "deliveredBy": "MERCHANT",
        "deliveryDateTime": "2022-05-05T17:09:28.715Z",
        "deliveryAddress": {
            "streetName": "PEDIDO DE TESTE - NÃO ENTREGAR - Ramal Bujari",
            "streetNumber": "122",
            "formattedAddress": "PEDIDO DE TESTE - NÃO ENTREGAR - Ramal Bujari, 122",
            "neighborhood": "Bujari",
            "postalCode": "00000000",
            "city": "Bujari",
            "state": "AC",
            "country": "BR",
            "coordinates": {
                "latitude": -9.822384,
                "longitude": -67.948589
            }
        }
    },
    "orderType": "DELIVERY",
    "orderTiming": "IMMEDIATE",
    "displayId": "3813",
    "createdAt": "2022-05-05T16:19:28.715Z",
    "preparationStartDateTime": "2022-05-05T16:19:28.715Z",
    "isTest": true,
    "merchant": {
        "id": "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e",
        "name": "Teste - Admin Sweet Confetty"
    },
    "customer": {
        "id": "f0b78ff5-d0f8-4726-99a3-b7b6a4287ee4",
        "name": "PEDIDO DE TESTE - Dalton Gonzalo Cornelio Fuentes",
        "documentNumber": "10134957997",
        "phone": {
            "number": "0800 200 5011",
            "localizer": "59128561",
            "localizerExpiration": "2022-05-05T19:19:28.715Z"
        },
        "ordersCountOnMerchant": 0
    },
    "items": [
        {
            "index": 1,
            "id": "1e71b649-8b72-43a4-89d7-3fd8b3e8c6f4",
            "name": "PEDIDO DE TESTE - Nome do Refrigerante 2 L",
            "unit": "GRAMS",
            "quantity": 1,
            "unitPrice": 10.00,
            "optionsPrice": 0,
            "totalPrice": 10.00,
            "price": 10.00
        },
        {
            "index": 2,
            "id": "c9c55bb4-494e-443c-bbe8-de58f08d92b8",
            "name": "PEDIDO DE TESTE - Duplo Brigadeiro",
            "externalCode": "5619",
            "unit": "GRAMS",
            "quantity": 1,
            "unitPrice": 0.00,
            "optionsPrice": 15.00,
            "totalPrice": 15.00,
            "observations": "teste de comentario",
            "options": [
                {
                    "index": 3,
                    "id": "6be65d3e-67ac-43d9-bf6b-517c29ca1304",
                    "name": "Copo G",
                    "externalCode": "2589",
                    "unit": "UN",
                    "quantity": 1,
                    "unitPrice": 15.00,
                    "addition": 0.00,
                    "price": 15.00
                }
            ],
            "imageUrl": "https://static-images.ifood.com.br/image/upload/t_high/pratos/86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e/202109231002_CNYU_.jpeg",
            "price": 0.00
        },
        {
            "index": 4,
            "id": "c9c55bb4-494e-443c-bbe8-de58f08d92b8",
            "name": "PEDIDO DE TESTE - Duplo Brigadeiro",
            "externalCode": "5619",
            "unit": "GRAMS",
            "quantity": 1,
            "unitPrice": 0.00,
            "optionsPrice": 10.00,
            "totalPrice": 10.00,
            "options": [
                {
                    "index": 5,
                    "id": "7978ce07-e944-4103-b2eb-73db23e817ab",
                    "name": "Copo M",
                    "externalCode": "2467",
                    "unit": "UN",
                    "quantity": 1,
                    "unitPrice": 10.00,
                    "addition": 0.00,
                    "price": 10.00
                }
            ],
            "imageUrl": "https://static-images.ifood.com.br/image/upload/t_high/pratos/86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e/202109231002_CNYU_.jpeg",
            "price": 0.00
        },
        {
            "index": 6,
            "id": "c9c55bb4-494e-443c-bbe8-de58f08d92b8",
            "name": "PEDIDO DE TESTE - Duplo Brigadeiro",
            "externalCode": "5619",
            "unit": "GRAMS",
            "quantity": 1,
            "unitPrice": 0.00,
            "optionsPrice": 20.25,
            "totalPrice": 20.25,
            "observations": "Tirar creme x",
            "options": [
                {
                    "index": 7,
                    "id": "7978ce07-e944-4103-b2eb-73db23e817ab",
                    "name": "Copo M",
                    "externalCode": "2467",
                    "unit": "UN",
                    "quantity": 1,
                    "unitPrice": 10.00,
                    "addition": 0.00,
                    "price": 10.00
                },
                {
                    "index": 8,
                    "id": "4145b406-21ef-44f9-942f-044a8b5a4b23",
                    "name": "Brownie",
                    "unit": "UN",
                    "quantity": 2,
                    "unitPrice": 2.50,
                    "addition": 0.00,
                    "price": 5.00
                },
                {
                    "index": 9,
                    "id": "20d54896-25ae-4e3b-a1e0-6b1a69eb47e1",
                    "name": "Morango",
                    "unit": "UN",
                    "quantity": 1,
                    "unitPrice": 1.45,
                    "addition": 0.00,
                    "price": 1.45
                },
                {
                    "index": 10,
                    "id": "cf5fb901-a6ff-4eed-9404-0d6ab559432a",
                    "name": "Suspiro",
                    "unit": "UN",
                    "quantity": 1,
                    "unitPrice": 3.80,
                    "addition": 0.00,
                    "price": 3.80
                }
            ],
            "imageUrl": "https://static-images.ifood.com.br/image/upload/t_high/pratos/86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e/202109231002_CNYU_.jpeg",
            "price": 0.00
        },
        {
            "index": 11,
            "id": "1e71b649-8b72-43a4-89d7-3fd8b3e8c6f4",
            "name": "PEDIDO DE TESTE - Nome do Refrigerante 2 L",
            "unit": "GRAMS",
            "quantity": 1,
            "unitPrice": 10.00,
            "optionsPrice": 0,
            "totalPrice": 10.00,
            "observations": "Bem gelado",
            "price": 10.00
        }
    ],
    "salesChannel": "IFOOD",
    "total": {
        "subTotal": 65.25,
        "deliveryFee": 8.90,
        "benefits": 0,
        "orderAmount": 74.15,
        "additionalFees": 0.00
    },
    "payments": {
        "prepaid": 0,
        "pending": 74.15,
        "methods": [
            {
                "value": 74.15,
                "currency": "BRL",
                "method": "CASH",
                "type": "OFFLINE",
                "cash": {
                    "changeFor": 120.00
                },
                "prepaid": false
            }
        ]
    }
}