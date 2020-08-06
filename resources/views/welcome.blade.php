<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Booking Scheduler</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Booking Scheduler API
                </div>

                <div class="links">
                    <a href="https://github.com/Oubayda-Khayata/booking-scheduler">GitHub Backend</a>
                    <a href="https://github.com/Oubayda-Khayata/booking-scheduler-frontend">GitHub Frontend</a>
                    <a href="https://oubayda.npplusplus.com">Demo</a>
                    <a href="https://api.oubayda.npplusplus.com/api">API Base URL</a>
                    <a href="https://app.getpostman.com/join-team?invite_code=9eccc2c87eb431e66f87f678968ca093&ws=a0999ac4-55c2-4163-b5b2-f2cceddfab23">Postman API Workspace</a>
                </div>
            </div>
        </div>
    </body>
</html>
