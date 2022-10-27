<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: black;
        }
        main {
            padding-top: 25px;
            background-color: black;
            width: 100%;
            height: 100%;
        }
        main .head-name{
            margin-left: 35%;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 35px;
            color: white;
        }

        main .parent {
            padding: 50px;
        }

        main .parent .content{
            color: #989494;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
        }

        main .parent .content a{
            text-decoration: none;
            border: 1px solid #ffffff00;
            padding: 15px;
            border-radius: 5px;
            background-color: black;
            color: white;
            margin-left: 40%;
            margin-bottom: 100px;
        }

        main .parent .content a:hover{
            background-color: #434343;
        }
    </style>
</head>
<body>
    <main>
        <h3 class="head-name">Customer Boxbyld</h3>
        <div class="parent">
            <div class="content">
                <h1 style="color: black">Hello</h1>
                <h3>Forgot Password</h3>
                <br>
                <a href="{{$url}}" target="_blank">Click To Reset</a>
                <h4>Thank you for using Customer BoxByld</h4>
            </div>
        </div>
    </main>
</body>
</html>
