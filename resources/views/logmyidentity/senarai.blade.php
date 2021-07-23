@extends('layouts.main')
<?php
    use App\Ref;
?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h2>Laporan Log MyIdentity</h2>
                <div class="ibox-content">
                    {{-- Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) --}}
                    <form method="GET" id="search-form" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('FileLog', 'Using log file:', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <select name="FileLog" class="form-control input-sm">
                                            <?php
                                            $list = storage_path('myidentity');
                                            // Open a directory, and read its contents
                                            if (is_dir($list)) {
                                                if ($dh = opendir($list)) {
                                                    while (($file = readdir($dh)) !== false) {
                                                        if (substr($file, 0, 13) == 'logmyidentity') {
                                                            //echo "<option value='" . base64_encode($file) . "'>$file</option>";
                                                            if (base64_encode($file) == $request->FileLog) {
                                                                echo "<option value='" . base64_encode($file) . "' selected='selected'>$file</option>";
                                                            } else {
                                                                echo "<option value='" . base64_encode($file) . "'>$file</option>";
                                                            }
                                                        }
                                                    }
                                                    closedir($dh);
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('UserId', 'UserId', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::text('UserId', old('UserId'), ['class' => 'form-control input-sm']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('TransactionCode', 'TransactionCode', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('TransactionCode', Ref::GetList('1265', true), old('TransactionCode'), ['class' => 'form-control input-sm', 'id' => 'TransactionCode']) }}
                                    </div>
                                </div>
                                <div class="form-group" id="data_5">
                                    {{ Form::label('RequestDateTime', 'Request Date Time', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        <div class="col-sm-12 input-daterange  input-group" id="data_5">
                                            <!--<input type="text" class="input-sm form-control" name="RequestDateTime"/>-->
                                            {{ Form::text('RequestDateTime', old('RequestDateTime'), ['class' => 'form-control input-sm']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('StatusPengadu', 'Status Pengadu', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('StatusPengadu', Ref::GetList('1233', true), old('StatusPengadu'), ['class' => 'form-control input-sm', 'id' => 'StatusPengadu']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('ReplyIndicator', 'Reply Indicator', ['class' => 'col-sm-4 control-label']) }}
                                    <div class="col-sm-8">
                                        {{ Form::select('ReplyIndicator', Ref::GetList('1268', true), old('ReplyIndicator'), ['class' => 'form-control input-sm', 'id' => 'ReplyIndicator']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <table>
                                    <tr>
                                        <td colspan="2">Transaction Code:</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>T2 - Pengguna Agensi, T7 = Pengguna Awam</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Request Indicator:</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>A - Basic/non-basic data, C - Basic/non-basic data/photo</td>
                                    </tr>
                                    <tr>
                                        <td>Status Pengadu:</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>1 - Warganegara
                                            <br>2 - Pemastautin Tetap
                                            <br>3 - Bukan Warganegara
                                            <br>4 - Nama tidak sepadan dengan Kad Pengenalan
                                            <br>5 - No. KP tidak sah
                                            <br>6 - Individu direkodkan telah meninggal dunia
                                            <!--<br>7 - Masalah Teknikal-->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group" align="center">
                            {{ Form::submit('Carian', ['class' => 'btn btn-primary btn-sm']) }}
                            {{ link_to('logmyidentity/carian', 'Semula', ['class' => 'btn btn-default btn-sm']) }}
                        </div>
                    {{-- Form::close() --}}
                    </form>
                    <div class="table-responsive">
                        <table id="log-table" 
                            class="table table-striped table-bordered table-hover dataTables-example" 
                            style="width: 100%">
                            <thead>
                            <tr>
                                <th>
                                    <div><span>#</span></div>
                                </th>
                                <th>
                                    <div><span>IP Address</span></div>
                                </th>
                                <!-- th>
                                    <div><span>Agency Code</span></div>
                                </th -->
                                <!-- th>
                                    <div><span>Branch Code</span></div>
                                </th -->
                                <th>
                                    <div><span>User Id</span></div>
                                </th>
                                <th>
                                    <div><span>Transaction Code</span></div>
                                </th>
                                <th>
                                    <div><span>Request Date Time</span></div>
                                </th>
                                <th>
                                    <div><span>Search No.kp</span></div>
                                </th>
                                <th>
                                    <div><span>Search Name</span></div>
                                </th>
                                <th>
                                    <div><span>Request Indicator</span></div>
                                </th>
                                <th>
                                    <div><span>Reply Date Time</span></div>
                                </th>
                                <th>
                                    <div><span>Reply Indicator</span></div>
                                </th>
                                <!-- th>
                                    <div><span>Status Pengadu</span></div>
                                </th -->
                                <th>
                                    <div><span>Nama MyIdentity</span></div>
                                </th>
                                <!-- th>
                                    <div><span>Nama Pengadu</span></div>
                                </th -->
                                <th>
                                    <div><span>Status Pengadu</span></div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($rows))
                                @foreach($rows as $row)
                                    <?php
                                    echo "<tr>";
                                    echo "<td>$bil</td>";
                                    echo "<td>" . (isset($row[0]) ? $row[0] : '') . "</td>";
                                    // echo "<td>" . (isset($row[1]) ? $row[1] : '') . "</td>";
                                    // echo "<td>" . (isset($row[2]) ? $row[2] : '') . "</td>";
                                    echo "<td>" . (isset($row[3]) ? base64_decode($row[3]) : '') . "</td>";
                                    echo "<td>" . (isset($row[4]) ? $row[4] : '') . "</td>";
                                    echo "<td>" . (isset($row[5]) ? $row[5] : '') . "</td>";
                                    echo "<td>" . (isset($row[6]) ? base64_decode($row[6]) : '') . "</td>";
                                    echo "<td>" . (isset($row[7]) ? $row[7] : '') . "</td>";
                                    echo "<td>" . (isset($row[8]) ? $row[8] : '') . "</td>";
                                    echo "<td>" . (isset($row[9]) ? $row[9] : '') . "</td>";
                                    echo "<td>" . (isset($row[10]) ? $row[10] : '') . "</td>";
                                    echo "<td>" . (isset($row[11]) ? $row[11] : '') . "</td>";
                                    echo "<td>" . (isset($row[12]) ? $row[12] : '') . "</td>";
                                    // echo "<td>" . (isset($row[13]) ? $row[13] : '') . "</td>";
                                    echo "</tr>";
                                    $bil++;
                                    ?>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="table-responsive">
    <table>
        <tr>
            <td>Transaction Code:</td>
            <td>T2 - Pengguna Agensi, T7 = Pengguna Awam</td>
            <td rowspan="4">Carian berdasarkan
                <form method="POST" id="search-form" class="form-horizontal">
                    <table>
                        <tr>
                            <td>Using log file:</td>
                            <td>
                                <select name="FileLog" class ="form-control input-sm">
                                    <?php
    //                                    $list = storage_path('myidentity');
    //                                    // Open a directory, and read its contents
    //                                    if (is_dir($list)) {
    //                                        if ($dh = opendir($list)) {
    //                                            while (($file = readdir($dh)) !== false) {
    //                                                if (substr($file, 0, 13) == 'logmyidentity') {
    //                                                    echo "<option value='" . base64_encode($file) . "'>$file</option>";
    //                                                }
    //                                            }
    //                                            closedir($dh);
    //                                        }
    //                                    }
    ?>
            </select></td>
    </tr>
    <tr>
        <td>UserId:</td>
        <td><input type="text" name="UserId" value="<?php echo ''; ?>"/></td>
                        </tr>
                        <tr>
                            <td>TransactionCode:</td>
                            <td>
                            <div class="col-sm-9">
                                {{ Form::select('TransactionCode', Ref::GetList('1265', true), null, ['class' => 'form-control input-sm', 'id' => 'TransactionCode']) }}
    @if ($errors->has('TransactionCode'))
        <span class="help-block"><strong>{{ $errors->first('TransactionCode') }}</strong></span>
                                @endif
            </div>
                <select name="TransactionCode"/>
                    <option value="">Sila pilih</option>
                    <option <?php // if ($getTransactionCode=='T2') echo 'selected'  ?> value="T2">T2</option>
                                    <option <?php // if ($getTransactionCode=='T7') echo 'selected'  ?> value="T7">T7</option>
                                </select>
                        </td>
                        </tr>
                        <tr>
                            <td>RequestDateTime:</td>
                            <td>
                                <input type="text" size="8" name="RequestDateTime" value="<?php // echo $getRequestDateTime ?>" class="datepicker"/>
                                Hours:
                                <select name="RequestHours">
                                    <option value=""></option>
                                    <?php
    // for ($h=0;$h<=23;$h++) {
    //                                          $hrs=($h<10)?"0".$h:$h;
    //                                          $selected=($getRequestHours==$hrs)?"selected":"";
    //                                          echo "<option $selected value='".$hrs."'>".$hrs."</option>";
    //                                      }
    ?>
            </select>
            Minutes:
            <select name="RequestMinutes">
                <option value=""></option>
<?php
    // for ($m=0;$m<=59;$m++) {
    //                                          $mnt=($m<10)?"0".$m:$m;
    //                                          $selected=($getRequestMinutes==$mnt)?"selected":"";
    //                                          echo "<option $selected value='".$mnt."'>".$mnt."</option>";
    //                                      }
    ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Search No.KP:</td>
        <td><input type="text" name="SearchKP" value="<?php // echo $getSearchKP ?>"/></td>
                        </tr>
                        <tr>
                            <td>ReplyDateTime:</td>
                            <td>
                                <input type="text" size="8" name="ReplyDateTime" value="<?php // echo $getReplyDateTime?>" class="datepicker"/>
                                Hours:
                                <select name="ReplyHours">
                                    <option value=""></option>
<?php
    // for ($h=0;$h<=23;$h++) {
    //                                            $hrs=($h<10)?"0".$h:$h;
    //                                            $selected=($getReplyHours==$hrs)?"selected":"";
    //                                            echo "<option $selected value='".$hrs."'>".$hrs."</option>";
    //                                        }
    ?>
            </select>
            Minutes:
            <select name="ReplyMinutes">
                <option value=""></option>
<?php
    // for ($m=0;$m<=59;$m++) {
    //                                            $mnt=($m<10)?"0".$m:$m;
    //                                            $selected=($getReplyMinutes==$mnt)?"selected":"";
    //                                            echo "<option $selected value='".$mnt."'>".$mnt."</option>";
    //                                        }
    ?>
            </select>

        </td>
    </tr>
    <tr>
        <td>ReplyIndicator:</td>
        <td>
            <select name="ReplyIndicator"/>
    <option value="">Sila pilih</option>
    <option <?php // if ($getReplyIndicator=='0') echo 'selected' ?> value="0">0</option>
                        <option <?php // if ($getReplyIndicator=='1') echo 'selected' ?> value="1">1</option>
                        <option <?php // if ($getReplyIndicator=='2') echo 'selected' ?> value="2">2</option>
                        </select>
                        </td>
                        </tr>
                        <tr>
                            <td>StatusPengadu:</td>
                            <td>
                                <select name="StatusPengadu"/>
                        <option value="">Sila pilih</option>
                        <option <?php // if ($getStatusPengadu=='1') echo 'selected' ?> value="1">1</option>
                        <option <?php // if ($getStatusPengadu=='2') echo 'selected' ?> value="2">2</option>
                        <option <?php // if ($getStatusPengadu=='3') echo 'selected' ?> value="3">3</option>
                        <option <?php // if ($getStatusPengadu=='4') echo 'selected' ?> value="4">4</option>
                        <option <?php // if ($getStatusPengadu=='5') echo 'selected' ?> value="5">5</option>
                        <option <?php // if ($getStatusPengadu=='6') echo 'selected' ?> value="6">6</option>
                        <option <?php // if ($getStatusPengadu=='7') echo 'selected' ?> value="7">7</option>
                        </select>
                        </td>
                        </tr>
                    </table>
                    <input type="submit" name="cari" value="Cari"/>
                </form>
            </td>
        </tr>
        <tr>
            <td>Request Indicator:</td>
            <td>A - Basic/non-basic data, C - Basic/non-basic data/photo</td>
        </tr>
        <tr>
            <td>Reply Indicator:</td>
            <td>0 - Error, 1 - Successful, 2 - Alert</td>
        </tr>
        <tr>
            <td>Status Pengadu:</td>
            <td>1 - Warganegara
                <br>2 - Pemastautin Tetap
                <br>3 - Bukan Warganegara
                <br>4 - Nama tidak sepadan dengan Kad Pengenalan
                <br>5 - No. KP tidak sah
                <br>6 - Individu direkodkan telah meninggal dunia
                <br>7 - Masalah Teknikal
            </td>
        </tr>
    </table>
</div>-->
    <!--<section class=''>
        <div class='container'>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" style="width: 90%">
                    <thead>
                        <tr>
                            <th><div><span>#</span></div></th>
                            <th><div><span>IP Address</span></div></th>
                            <th><div><span>Agency Code</span></div></th>
                            <th><div><span>Branch Code</span></div></th>
                            <th><div><span>User Id</span></div></th>
                            <th><div><span>Transaction Code</span></div></th>
                            <th><div><span>Request Date Time</span></div></th>
                            <th><div><span>Search No.kp</span></div></th>
                            <th><div><span>Request Indicator</span></div></th>
                            <th><div><span>Reply Date Time</span></div></th>
                            <th><div><span>Reply Indicator</span></div></th>

                    </thead>
                </table>
            </div>
        </div>
    </section>-->
@stop
@section('script_datatable')
    <script type="text/javascript">
        $('#data_5 .input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            pickTime: true,
            todayBtn: "linked",
            todayHighlight: true
        });
        $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});

    </script>

@stop