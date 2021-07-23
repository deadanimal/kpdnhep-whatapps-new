<div>
    <ul class="nav nav-tabs">
        <li <?php if ($tab == '1') echo "class='active'"; ?>>
            <a href="{{ url('admin-case/create') }}"><span>Borang Aduan</span></a>
        </li>
        <li <?php if ($tab == '2') echo "class='active'"; ?>>
            <a href="{{ url('admin-case/create') }}"><span>Lampiran</span></a>
        </li>
    </ul>
</div>