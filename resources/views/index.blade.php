<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Engineers console</title>

    <style>
        .display_flex {
            display: flex;
        }

        .group_item {
            padding: 10px;
        }
    </style>
</head>
<body>
<div>

    @if($errors->any())
        {!! implode('', $errors->all('<div style="color: red">:message</div> <br/>')) !!}
    @endif

    <div class="display_flex">
        <div class="group_item">
            <form action="{{ route('engineers-console.setup') }}" method="post">
                @csrf
                <button name="action" value="install_package" type="submit">Install package</button>
            </form>
        </div>

        <div class="group_item">
            <form action="{{ route('engineers-console.setup') }}" method="post">
                @csrf
                <button name="action" value="compile_project" type="submit">Compile project</button>
            </form>
        </div>

        <div class="group_item">
            <form action="{{ route('engineers-console.setup') }}" method="post">
                @csrf
                <button name="action" value="dump" type="submit">Dump</button>
            </form>
        </div>

        <div class="group_item">
            <form action="{{ route('engineers-console.setup') }}" method="post">
                @csrf
                <button name="action" value="project_update" type="submit">Project update</button>
            </form>
        </div>

    </div>
</div>
</body>
</html>
