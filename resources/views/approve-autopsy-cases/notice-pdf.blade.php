<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: 'Sarabun';
            src: url("{{ storage_path('fonts/Sarabun-Regular.ttf') }}") format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url("{{ storage_path('fonts/Sarabun-Bold.ttf') }}") format('truetype');
            font-weight: bold;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 18px;
            color: #111;
            margin: 0;
        }

        .page {
            padding: 28px 34px;
            position: relative;
            min-height: 1000px;
        }

        .header-box {
            border: 1.5px solid #111;
            border-radius: 14px;
            padding: 10px 16px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 70px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            line-height: 1.35;
        }

        .title-main {
            font-size: 24px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .info-table td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            text-decoration: underline;
            white-space: nowrap;
        }

        .line {
            border-top: 1px solid #111;
            margin: 14px 0;
        }

        .content {
            line-height: 1.55;
            margin-top: 12px;
        }

        .indent {
            margin-left: 58px;
        }

        .footer-note {
            position: absolute;
            bottom: 42px;
            left: 34px;
            right: 34px;
            line-height: 1.45;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="header-box">
        <table class="header-table">
            <tr>
                <td style="width:90px;">
                    <img src="{{ public_path('images/logo.png') }}" class="logo">
                </td>
                <td class="title">
                    <div class="title-main">แจ้งความคืบหน้ารายงานตรวจศพทางนิติเวชศาสตร์</div>
                    <div>แผนกนิติเวช กองพยาธิกรรม โรงพยาบาลภูมิพลอดุลยเดช กรมแพทย์ทหารอากาศ</div>
                    <div>ห้องหมายเลข 1 อาคาร 14 รพ.ภูมิพลอดุลยเดช แขวงคลองถนน เขตสายไหม กทม. โทร 02-534-7406</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">เลขที่รายงาน</td>
            <td>{{ $case->autopsy_no ?: '-' }}</td>
            <td class="label">สถานีตำรวจที่นำส่ง</td>
            <td>{{ $case->policeStation?->name ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">ชื่อผู้ตาย</td>
            <td>{{ $case->scene?->deceased_name ?: '-' }}</td>
            <td class="label">อายุ</td>
            <td>{{ $case->scene?->age ?: '-' }} ปี</td>
        </tr>
        <tr>
            <td class="label">สัญชาติ</td>
            <td>{{ $case->scene?->nationality ?: '-' }}</td>
            <td class="label">วันที่ผ่าพิสูจน์</td>
            <td>{{ optional($case->autopsy_date)->format('d/m/Y') ?: '-' }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="content">
        <p class="bold">
            เรียน เจ้าหน้าที่{{ $case->policeStation?->name ?: 'สถานีตำรวจ' }}
        </p>

        <p>
            ทางแผนกนิติเวช กองพยาธิกรรม โรงพยาบาลภูมิพลอดุลยเดช กรมแพทย์ทหารอากาศ
            ขอแจ้งความคืบหน้ารายงานตรวจศพทางนิติเวชศาสตร์ ดังนี้
        </p>

        <div class="indent">
            <div class="bold">รายละเอียดคดี</div>
            <div>สถานะรายงาน : สถานะเสร็จสมบูรณ์</div>
        </div>
    </div>

    <div class="footer-note">
        <div class="bold">หมายเหตุ</div>
        <div>
            สามารถติดต่อขอรับเอกสารได้ที่แผนกนิติเวช กองพยาธิกรรม โรงพยาบาลภูมิพลอดุลยเดช
            กรมแพทย์ทหารอากาศ ห้องหมายเลข 1 อาคาร 14 รพ.ภูมิพลอดุลยเดช แขวงคลองถนน
            เขตสายไหม กทม. โทร 02-534-7406
        </div>
    </div>
</div>
</body>
</html>