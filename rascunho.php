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














[
    {
        "id": "96f2c7c1-5004-43cf-9061-0c53c47b1e17",
        "code": "PLC",
        "fullCode": "PLACED",
        "orderId": "18dc0f48-3729-4e35-8388-406bd7633abe",
        "merchantId": "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e",
        "createdAt": "2022-06-07T14:20:27.57Z"
    },
    {
        "id": "0d48c4c6-62a1-4e1a-b4e2-5461ba528d31",
        "code": "CFM",
        "fullCode": "CONFIRMED",
        "orderId": "18dc0f48-3729-4e35-8388-406bd7633abe",
        "merchantId": "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e",
        "createdAt": "2022-06-07T14:20:41.511Z",
        "metadata": {
            "ORIGIN": "ORDER_API",
            "ownerName": "ifood",
            "CLIENT_ID": "ifood:iconnect_v3_homologation",
            "appName": "iconnect_v3_homologation"
        }
    },
    {
        "id": "119eaab5-0aa0-42ee-9379-29260a3498d5",
        "code": "ADR",
        "fullCode": "ASSIGN_DRIVER",
        "orderId": "7fd336a6-03ff-4eb3-a17a-527d010366de",
        "merchantId": "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e",
        "createdAt": "2022-06-07T14:22:07.58Z",
        "metadata": {
            "workerPhone": "+3099980100154",
            "workerVehicleType": "MOTORCYCLE",
            "workerName": "Testworkerbujari",
            "workerExternalUuid": "119cddac-1b51-465f-b569-7f2268893742",
            "workerPhotoUrl": "https://s3.amazonaws.com/nv-logistics-data.ifood.com.br/attributes/IMAGE/WORKER/entity-uuid-119cddac-1b51-465f-b569-7f2268893742/attribute-key-WORKER_PHOTO/sampledriverslicense.jpg?X-Amz-Security-Token=IQoJb3JpZ2luX2VjEN7%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEaCXVzLWVhc3QtMSJHMEUCIA%2BDXbKehGkw1QQTjAxZHg7vRhiU9DXCVCDwDCWnaoFfAiEAwLzeG7Lg%2BB2v2dPFO58hxfRS2Ozd7SEeJtDzDwEFjO0q2wQI1v%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FARAAGgwzNzg0OTcwMjk3MDkiDDERUZ0EKaQXQUZD%2FSqvBN%2BcaYiS3rF4vSq%2Bnw2gwST17h8CZzrC3DcGa9Lh%2BIwMn9Kv8BPxFlCDIp1C05Q9p2vdhiSnhokmw92mDUJEymnLHxNwt%2BM1F0pn6iQk7TH3RaFjV%2BE6XXE4svL8Aj2iBbOfTC5pUBjtdO2sE5QVeiGrZqHu0IEe4msTGPaxd5wPshmMI%2BvbiyUT52KOEQ%2FYhf5TsQi0yZCEsFmim1tSz1c6%2FYTrkfVLBdKHyw2tsvgYal3A9My6YXMlVNB4qFPXYVpQC7azyVVUlk4ii5%2BH33GqE8UK3bFG7F598qpRASPxyeZoYgzU%2FZIEWIQ0dhmBDSTWBHEyq9M0qzE3pmQgg2CKr%2F%2BX11yZDwGfm4QKFXv53XbrLxY89kTfDQMJ%2B2BHbNGZYeHrbtim0A7JX%2B8qq16nA1NKEX5GfQJYYU33uoh6WaHt5TwkngH4b9lzRS7g5UrZfdzvpbRldWhy07vtD2tT2kmsxMQfr3WubogJMROTvu5lsoxTlG3p8A35CqnyNJqOUe4j08VWk7bHQ2dovwYSpSh%2BFuXna537TPHy%2FbaLliEgXac5%2F3uMByN5%2Fk%2BQxCCackduEJ5AKjECexpbWz7GJsk70eBSbjiiaFZOpWpBuCPWaX8z1Oi2d%2Bu7Da0EvX7j9CVOUPYk64asWoFeGkmCsjHi7MX3ymbQF3DGGjCp3MAZJU2YFZjESKHMcdHWB9eR039s89pUl%2Bw0U8%2B3mo2O8MpCICKTmrcBZvxRme8wkqH9lAY6qQHLM5gyScZ9V9THptu5VzOTDQmuE86FW3VF6WhmGAJX9zKsMHVs7mvD%2Bt2rvFGzDGfH9WgSWK%2FapltQP4ioYZDRUOnETsMTFNbrYztmcQ6k9kUcoq8do1aj%2BPPj1ppKL77FucDFftiaMLeinVUBeZZBfiJ%2FIWHn3%2FzQZ9LTY6zDmIg398fx82aD29fRj%2BZIRfXUZzmjeB7H5BKgKWwCJvCyX5uPH3H0co6%2F&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20220607T142207Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Credential=ASIAVQIBOKZGZDQQZAXS%2F20220607%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Signature=ac21b5e92d7ed4be5b6958c9caa865a71b48865a64ab7e11447a39805117c0d7"
        }
    },
    {
        "id": "80d1315d-836a-4bb7-b446-b9b9fe71e212",
        "code": "GTO",
        "fullCode": "GOING_TO_ORIGIN",
        "orderId": "7fd336a6-03ff-4eb3-a17a-527d010366de",
        "merchantId": "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e",
        "createdAt": "2022-06-07T14:22:07.589Z"
    },
    {
        "id": "f8f3cc7b-a4e2-474a-96f9-d7192a287205",
        "code": "ADR",
        "fullCode": "ASSIGN_DRIVER",
        "orderId": "18dc0f48-3729-4e35-8388-406bd7633abe",
        "merchantId": "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e",
        "createdAt": "2022-06-07T14:22:07.618Z",
        "metadata": {
            "workerPhone": "+3099980100154",
            "workerVehicleType": "MOTORCYCLE",
            "workerName": "Testworkerbujari",
            "workerExternalUuid": "119cddac-1b51-465f-b569-7f2268893742",
            "workerPhotoUrl": "https://s3.amazonaws.com/nv-logistics-data.ifood.com.br/attributes/IMAGE/WORKER/entity-uuid-119cddac-1b51-465f-b569-7f2268893742/attribute-key-WORKER_PHOTO/sampledriverslicense.jpg?X-Amz-Security-Token=IQoJb3JpZ2luX2VjEN7%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEaCXVzLWVhc3QtMSJHMEUCIA%2BDXbKehGkw1QQTjAxZHg7vRhiU9DXCVCDwDCWnaoFfAiEAwLzeG7Lg%2BB2v2dPFO58hxfRS2Ozd7SEeJtDzDwEFjO0q2wQI1v%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FARAAGgwzNzg0OTcwMjk3MDkiDDERUZ0EKaQXQUZD%2FSqvBN%2BcaYiS3rF4vSq%2Bnw2gwST17h8CZzrC3DcGa9Lh%2BIwMn9Kv8BPxFlCDIp1C05Q9p2vdhiSnhokmw92mDUJEymnLHxNwt%2BM1F0pn6iQk7TH3RaFjV%2BE6XXE4svL8Aj2iBbOfTC5pUBjtdO2sE5QVeiGrZqHu0IEe4msTGPaxd5wPshmMI%2BvbiyUT52KOEQ%2FYhf5TsQi0yZCEsFmim1tSz1c6%2FYTrkfVLBdKHyw2tsvgYal3A9My6YXMlVNB4qFPXYVpQC7azyVVUlk4ii5%2BH33GqE8UK3bFG7F598qpRASPxyeZoYgzU%2FZIEWIQ0dhmBDSTWBHEyq9M0qzE3pmQgg2CKr%2F%2BX11yZDwGfm4QKFXv53XbrLxY89kTfDQMJ%2B2BHbNGZYeHrbtim0A7JX%2B8qq16nA1NKEX5GfQJYYU33uoh6WaHt5TwkngH4b9lzRS7g5UrZfdzvpbRldWhy07vtD2tT2kmsxMQfr3WubogJMROTvu5lsoxTlG3p8A35CqnyNJqOUe4j08VWk7bHQ2dovwYSpSh%2BFuXna537TPHy%2FbaLliEgXac5%2F3uMByN5%2Fk%2BQxCCackduEJ5AKjECexpbWz7GJsk70eBSbjiiaFZOpWpBuCPWaX8z1Oi2d%2Bu7Da0EvX7j9CVOUPYk64asWoFeGkmCsjHi7MX3ymbQF3DGGjCp3MAZJU2YFZjESKHMcdHWB9eR039s89pUl%2Bw0U8%2B3mo2O8MpCICKTmrcBZvxRme8wkqH9lAY6qQHLM5gyScZ9V9THptu5VzOTDQmuE86FW3VF6WhmGAJX9zKsMHVs7mvD%2Bt2rvFGzDGfH9WgSWK%2FapltQP4ioYZDRUOnETsMTFNbrYztmcQ6k9kUcoq8do1aj%2BPPj1ppKL77FucDFftiaMLeinVUBeZZBfiJ%2FIWHn3%2FzQZ9LTY6zDmIg398fx82aD29fRj%2BZIRfXUZzmjeB7H5BKgKWwCJvCyX5uPH3H0co6%2F&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20220607T142207Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Credential=ASIAVQIBOKZGZDQQZAXS%2F20220607%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Signature=ac21b5e92d7ed4be5b6958c9caa865a71b48865a64ab7e11447a39805117c0d7"
        }
    },
    {
        "id": "c5fbf7d7-0abc-4c80-97e5-f36f4dfb2044",
        "code": "GTO",
        "fullCode": "GOING_TO_ORIGIN",
        "orderId": "18dc0f48-3729-4e35-8388-406bd7633abe",
        "merchantId": "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e",
        "createdAt": "2022-06-07T14:22:07.637Z"
    }
]