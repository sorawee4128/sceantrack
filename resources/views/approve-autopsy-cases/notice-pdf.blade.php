<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<style>
    * {
        font-family: thsarabun !important;
        font-size: 18px;
    }

    body {
        margin: 0;
        color: #111;
    }

    .header-box {
        border: 1.5px solid #000;
        border-radius: 12px;
        padding: 12px 16px;
    }

    .header-table {
        width: 100%;
    }

    .logo {
        width: 70px;
    }

    .title {
        text-align: center;
        line-height: 1.4;
    }

    .title-main {
        font-size: 22px;
        font-weight: bold;
    }

    .sub-title {
        font-size: 16px;
    }

    .info-table {
        width: 100%;
        margin-top: 18px;
        border-collapse: collapse;
    }

    .info-table td {
        padding: 4px 6px;
        vertical-align: top;
    }

    .label {
        font-weight: bold;
        width: 140px;
    }

    .line {
        border-bottom: 1px solid #000;
        display: inline-block;
        min-width: 120px;
    }

    .divider {
        border-top: 1px solid #000;
        margin: 16px 0;
    }

    .content {
        margin-top: 10px;
        line-height: 1.6;
    }

    .indent {
        margin-left: 50px;
    }

    .section-title {
        font-weight: bold;
        margin-top: 10px;
    }

    .bold {
        font-weight: bold;
    }
</style>
</head>

<body>

<!-- HEADER -->
<div class="header-box">
    <table class="header-table">
        <tr>
            <td style="width:90px;">
                <img src="{{ public_path('images/logo.png') }}" class="logo">
            </td>
            <td class="title">
                <div class="title-main">แจ้งความคืบหน้ารายงานตรวจศพทางนิติเวชศาสตร์</div>
                <div class="sub-title">แผนกนิติเวช กองพยาธิกรรม โรงพยาบาลภูมิพลอดุลยเดช</div>
                <div class="sub-title">กรมแพทย์ทหารอากาศ โทร 02-534-7406</div>
            </td>
        </tr>
    </table>
</div>

<!-- INFO -->
<table class="info-table">
    <tr>
        <td class="label">เลขที่รายงาน</td>
        <td><span class="line">{{ $case->autopsy_no ?: '-' }}</span></td>

        <td class="label">สถานีตำรวจ</td>
        <td><span class="line">{{ $case->policeStation?->name ?: '-' }}</span></td>
    </tr>

    <tr>
        <td class="label">ชื่อผู้ตาย</td>
        <td><span class="line">{{ $case->scene?->deceased_name ?: '-' }}</span></td>

        <td class="label">อายุ</td>
        <td><span class="line">{{ $case->scene?->age ?: '-' }}</span> ปี</td>
    </tr>

    <tr>
        <td class="label">สัญชาติ</td>
        <td><span class="line">{{ $case->scene?->nationality ?: '-' }}</span></td>

        <td class="label">วันที่ผ่าพิสูจน์</td>
        <td><span class="line">{{ optional($case->autopsy_date)->format('d/m/Y') ?: '-' }}</span></td>
    </tr>
</table>

<div class="divider"></div>

<!-- CONTENT -->
<div class="content">
    <p class="bold">
        เรียน เจ้าหน้าที่{{ $case->policeStation?->name ?: 'สถานีตำรวจ' }}
    </p>

    <p>
        ทางแผนกนิติเวช กองพยาธิกรรม โรงพยาบาลภูมิพลอดุลยเดช กรมแพทย์ทหารอากาศ
        ขอแจ้งความคืบหน้ารายงานตรวจศพทางนิติเวชศาสตร์ ดังนี้
    </p>

    <div class="indent">
        <div class="section-title">รายละเอียดคดี</div>
        <div>สถานะรายงาน : <span class="bold">เสร็จสมบูรณ์</span></div>
    </div>
</div>

</body>
</html>