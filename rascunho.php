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