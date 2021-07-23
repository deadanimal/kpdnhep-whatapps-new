<?php
$filename = date('Ymd_His').'.xls';
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $filename);
$fp = fopen('php://output', 'w');
?>
<table class="table table-striped table-bordered table-hover" style="width: 100%" border="1">
    <thead>
        <tr>
            <th>Sumber Aduan</th>
            <th>Jumlah Aduan Diterima</th>
            <th>Aduan Menghasilkan Kes</th>
            <th>Kes Disiasat Oleh SAS</th>
            <th>Kes Disiasat Oleh Unit/Negeri/Cawangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total1 = 0;
        $total2 = 0;
        ?>
        @foreach ($result as $key => $value)
        <tr>
            <td>{{ $key }}</td>
            <?php
            $total3 = 0;
            $total4 = 0;
            ?>
            @foreach($value as $key => $val)
            <td>{{ $val->bilangankes }}</td>
            <?php
            if ($key == 0)
                $total3 += $val->bilangankes;
            elseif ($key == 1)
                $total4 += $val->bilangankes;
            ?>
            @endforeach
            <?php
            $total1 += $total3;
            $total2 += $total4;
            ?>
            <td>0</td>
            <td>0</td>
        </tr>
        @endforeach
        <tr>
            <td>Jumlah</td>
            <td>{{ $total1 }}</td>
            <td>{{ $total2 }}</td>
            <td>{{ 0 }}</td>
            <td>{{ 0 }}</td>
        </tr>
    </tbody>
</table>
<?php 
exit;
fclose($fp);
?>