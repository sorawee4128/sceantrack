<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:Arial, sans-serif; color:#0f172a;">
    <div style="max-width:680px; margin:0 auto; padding:32px 16px;">
        <div style="background:#ffffff; border-radius:18px; padding:28px; border:1px solid #e2e8f0;">
            <div style="text-align:center; margin-bottom:24px;">
                <img src="{{ asset('images/logo.png') }}" style="width:72px; height:auto; margin-bottom:12px;">
                <h2 style="margin:0; font-size:22px; color:#0f172a;">
                    แจ้งความคืบหน้ารายงานตรวจศพทางนิติเวชศาสตร์
                </h2>
                <p style="margin:6px 0 0; color:#64748b;">
                    โรงพยาบาลภูมิพลอดุลยเดช กรมแพทย์ทหารอากาศ
                </p>
            </div>

            <div style="background:#f8fafc; border-radius:14px; padding:18px; margin-bottom:20px;">
                <p style="margin:0 0 8px;"><strong>เลขที่รายงาน:</strong> {{ $case->autopsy_no }}</p>
                <p style="margin:0 0 8px;"><strong>สถานีตำรวจ:</strong> {{ $case->policeStation?->name ?: '-' }}</p>
                <p style="margin:0 0 8px;"><strong>ชื่อผู้ตาย:</strong> {{ $case->scene?->deceased_name ?: '-' }}</p>
                <p style="margin:0;"><strong>สถานะรายงาน:</strong> เสร็จสมบูรณ์</p>
            </div>

            <p style="line-height:1.7; margin:0 0 16px;">
                เรียน เจ้าหน้าที่{{ $case->policeStation?->name ?: 'สถานีตำรวจ' }}
            </p>

            <p style="line-height:1.7; margin:0 0 16px;">
                ทางแผนกนิติเวช กองพยาธิกรรม โรงพยาบาลภูมิพลอดุลยเดช กรมแพทย์ทหารอากาศ
                ขอแจ้งความคืบหน้ารายงานตรวจศพทางนิติเวชศาสตร์ รายละเอียดตามไฟล์ PDF ที่แนบมาพร้อมอีเมลฉบับนี้
            </p>

            <div style="border-top:1px solid #e2e8f0; margin-top:24px; padding-top:16px; color:#64748b; font-size:14px; line-height:1.6;">
                หมายเหตุ: สามารถติดต่อขอรับเอกสารได้ที่แผนกนิติเวช กองพยาธิกรรม
                โรงพยาบาลภูมิพลอดุลยเดช ห้องหมายเลข 1 อาคาร 14 โทร 02-534-7406
            </div>
        </div>
    </div>
</body>
</html>