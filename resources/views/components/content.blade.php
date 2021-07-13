<div class="group_item">
    <form action="{{ route('maintenance-panel.setup') }}" method="post">
        <div style="">
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
            <button class="btn-block" name="action" value="install_package" type="submit">Install package</button>
        </div>
    </form>
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
                        <button class="btn-block btn-sm" {{ $package_installed == false ? 'disabled' : '' }} name="action" value="compile" type="submit">Compile package</button>
                    </form>
                </div>

                <div class="package-action">
                    @if($package_installed)
                        <div class="form" style="margin: 0 5px;">
                            <input id="check" type="checkbox" checked disabled/>
                            <!--<label for="check" style="--d: 15px; ">
                                <svg viewBox="0,0,50,50">
                                    <path d="M5 30 L 20 45 L 45 5"></path>
                                </svg>
                            </label> -->
                        </div>
                    @else
                        <div class="form">
                            <input id="check" type="checkbox" disabled/>
                            <!--<label for="check" style="--d: 15px">
                                <svg viewBox="0,0,50,50">
                                    <path d="M5 30 L 20 45 L 45 5"></path>
                                </svg>
                            </label>-->
                        </div>
                    @endif
                </div>

                <div class="package-action">
                    <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                        @csrf
                        <input hidden name="package" value="{{ $name }}"/>
                        <button class="btn-block btn-sm" {{ $package_installed == false ? '' : 'disabled' }} name="action" value="park" type="submit">Park</button>
                    </form>
                </div>
                <div class="package-action">
                    <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                        @csrf
                        <input hidden name="package" value="{{ $name }}"/>
                        <button  class="btn-block btn-sm" {{ $package_installed == false ? 'disabled' : '' }} name="action" value="unplug" type="submit">Unplug</button>
                    </form>
                </div>
                <div class="package-action">
                    <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                        @csrf
                        <input hidden name="package" value="{{ $name }}"/>
                        <button class="btn-block btn-sm" {{ $package_installed == false ? 'disabled' : '' }} name="action" value="info" type="submit">Info</button>
                    </form>
                </div>
                <div class="package-action">
                    <form action="{{ route('maintenance-panel.package-setup') }}" method="post">
                        @csrf
                        <input hidden name="package" value="{{ $name }}"/>
                        <button class="btn-block btn-sm" {{ $package_installed == false ? 'disabled' : '' }} name="action" value="test" type="submit">Test</button>
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
