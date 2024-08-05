Aby uruchomić projekt należy użyć docker compose:
```docker compose build```

Przy pierwszym uruchomieniu kontenerów należy uruchomić composer:

- ```docker compose run --rm php composer install```

I migracje:

- ```docker compose run --rm php php bin/console doctrine:migrations:migrate --no-interaction --env=dev```

Aby postawić kontenery:

- ```docker compose up -d```

Aby zatrzymać kontenery:

- ```docker compose down```

Aby uruchomić testy:

- ```docker compose run --rm php vendor/bin/phpunit tests```

Aby pobrać dane i zapisać je do bazy można użyć komendy:

- ```docker compose run --rm php bin/console app:fetch-product {gtin} ```

Przed uruchomieniem komendy należy dodać użytkownika i hasło do env

Do komunikacji z serwisem można użyć dwóch poniższych endpointów:

Do wyciągania informacji o produkcie:
```GET /api/product/{gtin}```

Response:
```
{
    "id": 1,
    "gtin": "123839042137",
    "name": "example",
    "description": null,
    "brand": "Venbo",
    "status": "ACT",
    "markets": [
        {
            "code": "BE"
        },
        {
            "code": "BG"
        },
        {
            "code": "CZ"
        },
    ]
}
```

Do wyciągania wszystkich produktów z danego rynku:
```GET /api/products/market/{marketCode}```

Response:
```
[
    {
        "id": 1,
        "gtin": "1509810958",
        "name": "example",
        "description": null,
        "brand": "example",
        "status": "ACT",
        "markets": [
            {
                "code": "BE"
            },
        ]
    },
    {
        "id": 3,
        "gtin": "1235256126",
        "name": "example2",
        "description": null,
        "brand": "example2",
        "status": "ACT",
        "markets": [
            {
                "code": "BE"
            }
        ]
    }
]
```