@extends('layouts.app', ['title' => 'รายงานข้อมูลการชันสูตรศพ'])

@section('content')

<style>
.report-page {
    width: 100%;
    margin: 0;
    padding: 24px 32px;
}

.report-filter {
    width: 100%;
    background: linear-gradient(135deg, #eef2ff, #fdf4ff);
    border-radius: 28px;
    padding: 28px;
    box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
}

.report-filter-form {
    display: grid;
    grid-template-columns: 260px repeat(5, minmax(120px, 1fr));
    gap: 14px;
    align-items: center;
}

.report-month {
    width: 100%;
    height: 56px;
    border: 0;
    border-radius: 18px;
    padding: 0 22px;
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    background: #fff;
    box-shadow: 0 10px 25px rgba(15, 23, 42, .12);
}

.report-tab {
    width: 100%;
    height: 56px;
    min-width: 0;
    border-radius: 18px;
    border: 0;
    padding: 0 14px;
    font-size: 16px;
    font-weight: 900;
    cursor: pointer;
    transition: .2s;
    white-space: nowrap;
    box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
}

.report-tab:hover {
    transform: translateY(-2px);
}

.report-tab.active {
    color: #fff;
}

.tab-police { background: #e0f2fe; color: #0369a1; }
.tab-police.active { background: linear-gradient(135deg, #0ea5e9, #2563eb); }

.tab-type { background: #dcfce7; color: #047857; }
.tab-type.active { background: linear-gradient(135deg, #10b981, #059669); }

.tab-doctor { background: #ede9fe; color: #6d28d9; }
.tab-doctor.active { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.tab-assistant { background: #ffedd5; color: #c2410c; }
.tab-assistant.active { background: linear-gradient(135deg, #f59e0b, #ea580c); }

.tab-management { background: #ffe4e6; color: #be123c; }
.tab-management.active { background: linear-gradient(135deg, #f43f5e, #db2777); }

.report-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-top: 24px;
}

.summary-card {
    border-radius: 26px;
    padding: 26px;
    color: #fff;
    box-shadow: 0 18px 45px rgba(15, 23, 42, .12);
}

.summary-card.total { background: linear-gradient(135deg, #0f172a, #334155); }
.summary-card.draft { background: linear-gradient(135deg, #f59e0b, #ea580c); }
.summary-card.submitted { background: linear-gradient(135deg, #10b981, #059669); }

.summary-label {
    font-size: 15px;
    font-weight: 800;
    opacity: .85;
}

.summary-value {
    margin-top: 10px;
    font-size: 46px;
    font-weight: 950;
    line-height: 1;
}

.report-panel {
    margin-top: 24px;
    background: #fff;
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 18px 45px rgba(15, 23, 42, .09);
    border: 1px solid #e2e8f0;
}

.report-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 26px 30px;
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border-bottom: 1px solid #e2e8f0;
}

.report-kicker {
    font-size: 13px;
    font-weight: 900;
    color: #94a3b8;
}

.report-title {
    margin-top: 4px;
    font-size: 30px;
    font-weight: 950;
    color: #0f172a;
}

.report-badge {
    background: #0f172a;
    color: #fff;
    border-radius: 18px;
    padding: 14px 22px;
    font-weight: 950;
    box-shadow: 0 12px 28px rgba(15, 23, 42, .22);
}

.chart-box {
    padding: 28px;
    height: 460px;
}

.report-table-wrap {
    padding: 28px;
    overflow-x: auto;
}

.report-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    overflow: hidden;
    border-radius: 18px;
    font-size: 15px;
}

.report-table thead th {
    background: #f1f5f9;
    color: #64748b;
    font-weight: 950;
    padding: 16px 18px;
    text-align: left;
}

.report-table tbody td {
    padding: 16px 18px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-weight: 700;
}

.report-table tbody tr:hover {
    background: #eef2ff;
}

.report-link {
    color: #2563eb;
    font-weight: 950;
    text-decoration: none;
}

.status-pill {
    display: inline-block;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 950;
}

.status-submitted {
    background: #dcfce7;
    color: #047857;
}

.status-draft {
    background: #ffedd5;
    color: #c2410c;
}

.pagination-wrap {
    padding: 0 28px 28px;
}

@media (max-width: 1200px) {
    .report-filter-form {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .report-page {
        padding: 16px;
    }

    .report-filter-form {
        grid-template-columns: 1fr;
    }

    .report-summary {
        grid-template-columns: 1fr;
    }

    .report-panel-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .report-title {
        font-size: 24px;
    }
}
</style>

<div class="card report-page">

    <section class="report-filter">
        <form method="GET" class="report-filter-form">
            <select name="month" onchange="this.form.submit()" class="report-month">
                <option value="">ทั้งหมด (เดือน)</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected(request('month') == $m)>
                        {{ \Carbon\Carbon::create()->month($m)->locale('th')->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            @php
                $tabs = [
                    'police_station' => ['label' => 'สถานีตำรวจ', 'class' => 'tab-police'],
                    'notification_type' => ['label' => 'ประเภทคดี', 'class' => 'tab-type'],
                    'doctor' => ['label' => 'แพทย์ผู้ตรวจ', 'class' => 'tab-doctor'],
                    'assistant' => ['label' => 'ผู้ช่วยแพทย์', 'class' => 'tab-assistant'],
                    'management' => ['label' => 'การจัดการ', 'class' => 'tab-management'],
                ];
            @endphp

            @foreach ($tabs as $key => $tab)
                <button type="submit"
                        name="chart_type"
                        value="{{ $key }}"
                        class="report-tab {{ $tab['class'] }} {{ $chartType === $key ? 'active' : '' }}">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </form>
    </section>

    <section class="report-summary">
        <div class="summary-card total">
            <div class="summary-label">ทั้งหมด</div>
            <div class="summary-value">{{ number_format($summary['total']) }}</div>
        </div>

        <div class="summary-card draft">
            <div class="summary-label">แบบร่าง</div>
            <div class="summary-value">{{ number_format($summary['draft']) }}</div>
        </div>

        <div class="summary-card submitted">
            <div class="summary-label">ส่งแล้ว</div>
            <div class="summary-value">{{ number_format($summary['submitted']) }}</div>
        </div>
    </section>

    <section class="report-panel">
        <div class="report-panel-header">
            <div>
                <div class="report-kicker">รายงานกราฟ</div>
                <div class="report-title">{{ $chartTitle }}</div>
            </div>

            <div class="report-badge">
                รวม {{ number_format($summary['total']) }} รายการ
            </div>
        </div>

        <div class="chart-box">
            <canvas id="notificationTypeChart"></canvas>
        </div>
    </section>

    <section class="report-panel">
        <div class="report-panel-header">
            <div class="report-title">รายการข้อมูลการชันสูตรศพ</div>
        </div>

        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>หมายเลขชันสูตรศพ</th>
                        <th>วันที่</th>
                        <th>ผู้ปฏิบัติงาน</th>
                        <th>ประเภทที่แจ้ง</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($cases as $case)
                        <tr>
                            <td>
                                <a href="{{ route('scene-cases.show', $case) }}" class="report-link">
                                    {{ $case->scene_no }}
                                </a>
                            </td>
                            <td>{{ optional($case->case_date)->format('d/m/Y') }}</td>
                            <td>{{ $case->doctor?->displayName() }} / {{ $case->assistant?->displayName() }}</td>
                            <td>{{ $case->notificationType?->name }}</td>
                            <td>
                                <span class="status-pill {{ $case->status->value === 'submitted' ? 'status-submitted' : 'status-draft' }}">
                                    {{ $case->status->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:32px; color:#64748b;">
                                ไม่พบข้อมูลในช่วงวันที่ที่เลือก
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $cases->links() }}
        </div>
    </section>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chartByNotificationType);

    const labels = chartData.map(item => item.label);
    const totals = chartData.map(item => item.total);

    const ctx = document.getElementById('notificationTypeChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'จำนวนรายการ',
                data: totals,
                backgroundColor: [
                    '#0ea5e9',
                    '#10b981',
                    '#8b5cf6',
                    '#f59e0b',
                    '#f43f5e',
                    '#14b8a6',
                    '#6366f1',
                    '#ec4899',
                    '#64748b',
                ],
                borderRadius: 16,
                maxBarThickness: 120,
                categoryPercentage: 0.55,
                barPercentage: 0.85,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 14,
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' รายการ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: '#e2e8f0' },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });
});
</script>
@endpush