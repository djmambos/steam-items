<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Steam Items Wiki</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->

        <style>
            body {
                font-family: 'Nunito', sans-serif;
                background-color: #1a202c;
                color: #e2e8f0;
            }

            .row {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .item__card {
                max-width: 200px;
                min-width: 200px;
                flex-grow: 4;
                border: 1px solid #cbd5e0;
                margin: 10px;
                text-align: center;
            }

            .item__img {
                width: 80%;
            }
        </style>
    </head>
    <body>
    <div class="container__main">
        <div class="row">
            @foreach ($items as $item)
                <div class="item__card">
                    <span>{{ $item->name }}</span>
                    <img class="item__img" src="https://community.cloudflare.steamstatic.com/economy/image/{{ $item->icon_url }}" alt="">
                    <div>Price: {{ $item->price }}</div>
                    <div>Quantity: {{ $item->quantity }}</div>
                </div>
            @endforeach
        </div>
    </div>
    </body>
</html>
