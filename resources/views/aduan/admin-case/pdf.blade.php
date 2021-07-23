<?php
    use App\Ref;
    use App\User;
    use App\Aduan\AdminCase;
?>
<title>Maklumat Aduan</title>
    <h4>Cara Terima</h4>
        <table style="width:800px">
            <tbody>
                <tr>
                    <td style="width:50%">No. Aduan : {{ $mAdminCase->CA_CASEID }}</td>
                    <td style="width:50%">Tarikh Terima : {{ $mAdminCase->CA_RCVDT != '' ? date('d-m-Y h:i A', strtotime($mAdminCase->CA_RCVDT)) : ''}}</td>
                </tr>
                <tr>
                    <td style="width:50%">Penerima : </td>
                    <td style="width:50%">Cara Penerimaan : {{ $mAdminCase->CA_RCVTYP!= '' ? Ref::GetDescr('259', $mAdminCase->CA_RCVTYP, 'ms') : ''}}</td>
                </tr>
            </tbody>
        </table>
    <h4>Maklumat Pengadu</h4>
    <p>
        No. Kad Pengenalan/Pasport : {{ $mAdminCase->CA_DOCNO }}<br>
        Nama : {{ $mAdminCase->CA_NAME }}<br>
        Emel : {{ $mAdminCase->CA_EMAIL }}<br>
        Umur : {{ $mAdminCase->CA_AGE }}<br>
        Jantina : {{ $mAdminCase->CA_SEXCD != '' ? Ref::GetDescr('202', $mAdminCase->CA_SEXCD, 'ms') : ''}}<br>
        Bangsa : {{ $mAdminCase->CA_RACECD != '' ? Ref::GetDescr('580', $mAdminCase->CA_RACECD, 'ms') : '' }}<br>
        Warganegara: {{ $mAdminCase->CA_NATCD != '' ? Ref::GetDescr('947', $mAdminCase->CA_NATCD, 'ms') : ''}}<br>
        No. Telefon (Bimbit) : {{ $mAdminCase->CA_MOBILENO }}<br>
        No. Telefon (Rumah) : {{ $mAdminCase->CA_TELNO }}<br>
        No. Faks : {{ $mAdminCase->CA_FAXNO }}<br>
        Alamat : {{ $mAdminCase->CA_ADDR }}<br>
    </p>
    <h4>Maklumat Aduan</h4>
    <p>
        Kategori : {{ $mAdminCase->CA_CMPLCAT }}<br>
        Subkategori : {{ $mAdminCase->CA_CMPLCD }}<br>
        Jenis Premis : {{ $mAdminCase->CA_AGAINST_PREMISE }}<br>
        No. Telefon (Pejabat) : {{ $mAdminCase->CA_AGAINST_TELNO }}<br>
        No. Telefon (Bimbit) : {{ $mAdminCase->CA_AGAINST_MOBILENO }}<br>
        Emel : {{ $mAdminCase->CA_AGAINST_EMAIL }}<br>
        No. Faks : {{ $mAdminCase->CA_AGAINST_FAXNO }}<br>
        Alamat : {{ $mAdminCase->CA_AGAINSTADD }}<br>
        Keterangan Aduan : {{ $mAdminCase->CA_SUMMARY }}<br>
    </p>