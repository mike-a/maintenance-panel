<div class="group_item">
    <form action="{{ route('maintenance-panel.setup') }}" method="post">
        @csrf
        <button class="btn-block" name="action" value="compile" type="submit">Compile project</button>
    </form>
</div>

<div class="group_item">
    <form action="{{ route('maintenance-panel.setup') }}" method="post">
        @csrf
        <button class="btn-block" name="action" value="dump" type="submit">Dump</button>
    </form>
</div>

<div class="group_item">
    <form action="{{ route('maintenance-panel.setup') }}" method="post">
        @csrf
        <button class="btn-block" name="action" value="update_project" type="submit">Project update</button>
    </form>
</div>
