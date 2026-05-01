<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<style>
    * {
        font-family: thsarabun !important;
    }

    body {
        font-size: 18px;
        color: #111827;
        margin: 0;
    }

    .page-break {
        page-break-after: always;
    }

    .header {
        text-align: center;
    }

    .logo {
        width: 90px;
        margin-bottom: 8px;
    }

    h1, h2, h3 {
        margin: 0;
        line-height: 1.25;
    }

    .divider {
        border-top: 2px dotted #111;
        margin: 24px 0 18px;
    }

    .meta {
        width: 100%;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .meta td:last-child {
        text-align: right;
    }

    table.list {
        width: 100%;
        border-collapse: collapse;
    }

    table.list th {
        background: #eee;
        border-top: 3px solid #111;
        border-bottom: 1px solid #111;
        padding: 6px;
        text-align: center;
        font-weight: bold;
    }

    table.list td {
        padding: 4px 6px;
        text-align: center;
    }

    .summary {
        border-top: 3px solid #111;
        border-bottom: 3px solid #111;
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 32mm;
    }

    .summary td {
        padding: 6px;
        font-weight: bold;
    }

    .summary .amount {
        width: 160px;
        background: #eee;
        font-size: 28px;
        text-align: right;
    }

    .signatures {
        width: 100%;
        text-align: center;
        border-collapse: collapse;
    }

    .signatures td {
        width: 50%;
        vertical-align: top;
        line-height: 1.35;
    }

    .line {
        display: inline-block;
        width: 180px;
        border-top: 3px solid #111;
        padding-top: 8px;
    }
</style>
</head>
<body>

@foreach ($rows as $row)
    @php
        $total = $row['total'];
        $user = $row['user'];
        $cases = $row['cases'];
        $footerName = 'footer' . $loop->iteration;
    @endphp

    <htmlpagefooter name="{{ $footerName }}">
        <table class="summary">
            <tr>
                <td style="text-align:right;">รวมรายได้สุทธิ</td>
                <td class="amount">{{ number_format($total) }}</td>
                <td style="width:40px;">บาท</td>
            </tr>
        </table>

        <table class="signatures">
            <tr>
                <td>
                    <span class="line">ลงชื่อผู้จ่ายเงิน</span><br>
                    ( นาวาอากาศเอกไพโรจน์ จอมไธสง ) ผอ.<br>
                    กพก.รพ.ภูมิพลอดุลยเดช พอ
                </td>
                <td>
                    <span class="line">ลงชื่อผู้รับเงิน</span><br>
                    ( {{ $user->displayName() }} )
                    @if ($user->hasRole('doctor'))
                        <br>พยาธิแพทย์
                    @endif
                </td>
            </tr>
        </table>
    </htmlpagefooter>

    <sethtmlpagefooter name="{{ $footerName }}" value="on" />

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <h2>โรงพยาบาลภูมิพลอดุลยเดช กรมแพทย์ทหารอากาศ</h2> <br>
        <h3>เงินค่าตอบแทน</h3>
    </div>

    <div class="divider"></div>

    <table class="meta">
        <tr>
            <td>ชื่อ{{ $roleLabel }}: {{ $user->displayName() }}</td>
            <td>ค่าตอบแทนประจำเดือน {{ $monthText }}</td>
        </tr>
    </table>

    <table class="list">
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>วันที่ชันสูตรพลิกศพ</th>
                <th>หมายเลขชันสูตรพลิกศพ</th>
                <th>จำนวน</th>
                <th>ราคา/หน่วย</th>
                <th>รวมเป็นเงิน</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cases as $case)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($case->case_date)->format('d/m/Y') }}</td>
                    <td>{{ $case->scene_no }}</td>
                    <td>1</td>
                    <td>{{ number_format($rate) }}</td>
                    <td>{{ number_format($rate) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (! $loop->last)
        <div class="page-break"></div>
    @endif
@endforeach

</body>
</html>