<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>I-We - Engineers console</title>
    <link rel="stylesheet" href="/css/maintenance-panel.css">
    <style>

        .holy-grail{
            min-height: 100vh;
        }

        .holy-grail, .holy-grail-body{
            display: flex;
            flex: 1 1 auto;
            flex-direction: column;
            text-align: center;
            margin: 0;
        }

        .holy-grail-body{
            flex-direction: row;
        }

        .holy-grail-content{
            flex: 70;
            background-color: #00222B;
            padding: 20px;
            color: white;
        }

        .hg-sidebar{
            flex: 30;
            background-color: #00222B;
            color: white;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .holy-grail-body{
                flex-direction: column;
            }

            .hg-sidebar{
                flex: 0 0 260px;
            }
        }
    </style>
</head>

<body class="holy-grail">
<!-- Here we the base Project Header composer should be loading right here -->
<header>
    <x-basetheme-header />
</header>

<div class="holy-grail-body">
    <section class="holy-grail-content">
        Main Content
        <!-- The Required System content should be loading right in the hooks or override way -->
    </section>
    <div class="holy-grail-sidebar-2 hg-sidebar">
        <!-- Some side bar content would be displaying right here by the way -->
        <x-basetheme-right-side />
    </div>
</div>
<!-- Here is the system footer information -->
<footer>
    Base theme Footer
</footer>

</body>
