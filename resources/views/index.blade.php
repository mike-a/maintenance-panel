<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Engineers console</title>

    <style>
        .wrapper {
            padding: 30px 0;
        }

        .container {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .group_item {
            padding: 10px;
        }

        .packages-setup {
            padding: 30px 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .package-item {
            padding: 5px 10px;
            margin: 10px 0;
            border: 1px #d4d4d4 solid;
            border-radius: 3%;
            text-align: center;
            width: 40%;
        }

        .package-name {
            width: 15%;
            text-align: center;
        }

        .package-action {
            padding: 0 10px;
        }

        input#check {
            position: absolute;
            opacity: 0;
        }
        input#check:checked + label svg path {
            stroke-dashoffset: 0;
            color: green
        }
        input#check:focus + label {
            transform: scale(1.03);
        }

        #check + label {
            display: block;
            border: 2px solid #333;
            width: var(--d);
            height: var(--d);
            border-radius: 1px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        #check + label:active {
            transform: scale(1.05);
            border-radius: 30px;
        }
        #check + label svg {
            pointer-events: none;
        }
        #check + label svg path {
            fill: none;
            stroke: #333;
            stroke-width: 4px;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 100;
            stroke-dashoffset: 101;
            transition: all 350ms cubic-bezier(1, 0, 0.37, 0.91);
        }
    </style>
</head>
<body class="wrapper">

@guest()
    @includeIf('user-management::include')

@else
    @if($errors->any())
        {!! implode('', $errors->all('<div style="color: red">:message</div> <br/>')) !!}
    @endif
    <!-- Here Make sure to include the nagivation menu of the usermanagement -->
    @includeIf('user-management::components.user-header')
    <div class="container">
        <div class="group_item" style="margin-right: 10%">
            <form action="{{ route('maintenance-panel.setup') }}" method="post">
                <div style="display: flex; flex-direction: column">
                    <div style="padding: 5px 0">
                        <label>Package name as is on composer.json second e.g basetheme</label>
                        <input type="text" name="package_name"/>
                    </div>
                    <div style="padding: 5px 0">
                        <label>Url</label>
                        <input type="text" name="url"/>
                    </div>
                    <div style="padding: 5px 0">
                        <label>Source type</label>
                        <input type="text" name="source_type"/>
                    </div>
                    <div style="padding: 5px 0">
                        <label>Install command</label>
                        <input type="text" name="install_command"/>
                    </div>

                    @csrf
                    <button name="action" value="install_package" type="submit">Install package</button>
                </div>
            </form>
        </div>

        <div class="group_item">
            <form action="{{ route('maintenance-panel.setup') }}" method="post">
                @csrf
                <button name="action" value="compile" type="submit">Compile project</button>
            </form>
        </div>

        <div class="group_item">
            <form action="{{ route('maintenance-panel.setup') }}" method="post">
                @csrf
                <button name="action" value="dump" type="submit">Dump</button>
            </form>
        </div>

        <div class="group_item">
            <form action="{{ route('maintenance-panel.setup') }}" method="post">
                @csrf
                <button name="action" value="update_project" type="submit">Project update</button>
            </form>
        </div>

    </div>


    <div class="packages-setup">
        @foreach(config('maintenance-panel.packages') as $name => $package)

            <?php $package_installed = command_exists($package['install_command']) ?>

            <div class="package-item">
                <div class="container">
                    <div class="package-name">
                        {{ ucfirst($name) }}
                    </div>
                    <div class="package-action">
                        <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                            @csrf
                            <input hidden name="package" value="{{ $name }}"/>
                            <button  {{ $package_installed == false ? 'disabled' : '' }} name="action" value="compile" type="submit">Compile package</button>
                        </form>
                    </div>

                    <div class="package-action" style="display: flex">
                        @if($package_installed)
                            <div class="form" style="margin: 0 5px; background: lime">
                                <input id="check" type="checkbox" checked disabled/>
                                <label for="check" style="--d: 15px; ">
                                    <svg viewBox="0,0,50,50">
                                        <path d="M5 30 L 20 45 L 45 5"></path>
                                    </svg>
                                </label>
                            </div>
                        @else
                            <div class="form" style="margin: 0 5px; background: red">
                                <input id="check" type="checkbox" disabled/>
                                <label for="check" style="--d: 15px">
                                    <svg viewBox="0,0,50,50">
                                        <path d="M5 30 L 20 45 L 45 5"></path>
                                    </svg>
                                </label>
                            </div>
                        @endif
                    </div>

                    <div class="package-action">
                        <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                            @csrf
                            <input hidden name="package" value="{{ $name }}"/>
                            <button {{ $package_installed == false ? '' : 'disabled' }} name="action" value="park" type="submit">Park</button>
                        </form>
                    </div>
                    <div class="package-action">
                        <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                            @csrf
                            <input hidden name="package" value="{{ $name }}"/>
                            <button  {{ $package_installed == false ? 'disabled' : '' }} name="action" value="unplug" type="submit">Unplug</button>
                        </form>
                    </div>
                    <div class="package-action">
                        <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                            @csrf
                            <input hidden name="package" value="{{ $name }}"/>
                            <button  {{ $package_installed == false ? 'disabled' : '' }} name="action" value="info" type="submit">Info</button>
                        </form>
                    </div>
                    <div class="package-action">
                        <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                            @csrf
                            <input hidden name="package" value="{{ $name }}"/>
                            <button  {{ $package_installed == false ? 'disabled' : '' }} name="action" value="test" type="submit">Test</button>
                        </form>
                    </div>
                </div>

                @if(Session::has('package_info'))
                    <?php $session_data = json_decode(Session::get('package_info'), true);  ?>

                    @if($session_data['package_name'] === $name)
                        <span>{{ $session_data['info'] }}</span>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
@endguest
</body>
</html>
