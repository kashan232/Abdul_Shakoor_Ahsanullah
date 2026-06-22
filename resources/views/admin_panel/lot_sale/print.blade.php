<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>رسید</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your CSS here */
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Dynamic content will go here -->
        <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: bold; line-height: 1.4;">
                <div style="text-align: right;">
                    <div style="color: #dc3545; font-size: 14px; font-family: sans-serif; margin-bottom: 2px;">A.U & BROTHERS</div>
                    <div>احسان اللہ: <span dir="ltr">0300-9378192</span></div>
                    <div><span dir="ltr">0313-9378192</span></div>
                </div>
                <div style="text-align: left; margin-top: 15px;">
                    <div>امان اللہ: <span dir="ltr">0321-3045286</span></div>
                    <div>عطاء اللہ: <span dir="ltr">0308-3002916</span></div>
                    <div>سیف اللہ: <span dir="ltr">0334-9378192</span></div>
                </div>
            </div>
            <h2 style="margin: 5px 0 0 0; font-size: 24px; font-weight: 900; line-height: 1.2; color: #000;">ماما عبد الشکور احسان اللہ اینڈ کو</h2>
            <div style="font-size: 14px; font-weight: bold; margin-top: 2px;">آلو، پیاز، لہسن کمیشن ایجنٹس</div>
            <div style="font-size: 12px; margin-top: 3px;">دکان نمبر 227-226-200 نیو سبزی مارکیٹ ہالا ناکہ حیدرآباد</div>
        </div>

        <div class="header-info row">
            <div class="col">نام: {{ $customerName }}</div>
            <div class="col text-start">
                <div>تاریخ: {{ $startDate }} سے</div>
                <div>{{ $endDate }} تک</div>
            </div>
        </div>

        <div class="section-title">تتاریخ: {{ $startDate }}</div>

        <table>
            <thead>
                <tr>
                    <th>نگ</th>
                    <th>وزن</th>
                    <th>ریٹ</th>
                    <th>نامہ رقم</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->weight }}</td>
                        <td>{{ $sale->rate }}</td>
                        <td>{{ $sale->amount }}</td>
                        <td>{{ $sale->total }}</td>
                    </tr>
                @endforeach
                <tr class="totals">
                    <td colspan="3">ٹوٹل</td>
                    <td>{{ $totalAmount }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer-note">
            <p>پچھلا بیلنس: {{ $previousBalance }} PKR</p>
            <p>کل رقم: {{ $totalAmount }} PKR</p>
            <p>ریکاوری: {{ $totalRecovery }} PKR</p>
            <p>بقایا بیلنس: {{ $remainingBalance }} PKR</p>
        </div>
        <div style="margin-top: 15px; padding-top: 10px; border-top: 2px solid black; font-size: 12px; text-align: center; direction: ltr;">
            <strong>Powered by ProWave Technologies</strong> | 03173859647
        </div>
    </div>
</body>
</html>
