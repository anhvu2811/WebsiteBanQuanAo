<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Thanh toán thành công</title>
  <style>
    :root{
      --bg:#f5f7fa;
      --card:#ffffff;
      --accent:#6c5ce7;
      --muted:#6b7280;
      --success:#16a34a;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
      background:var(--bg);
      color:#0f172a;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      display:flex;
      align-items:center;
      justify-content:center;
      min-height:100vh;
      padding:24px;
    }

    .container{
      width:100%;
      max-width:920px;
      background:var(--card);
      border-radius:12px;
      box-shadow:0 10px 30px rgba(2,6,23,0.08);
      overflow:hidden;
      display:grid;
      grid-template-columns: 1fr 380px;
      gap:0;
    }

    /* Banner left */
    .left {
      padding:28px 32px;
      display:flex;
      flex-direction:column;
      gap:18px;
    }

    .banner{
      width:100%;
      height:160px;
      border-radius:10px;
      background-position:center;
      background-size:cover;
      background-repeat:no-repeat;
      box-shadow: inset 0 -2px 10px rgba(0,0,0,0.06);
    }

    .title{
      display:flex;
      align-items:center;
      gap:12px;
    }
    .check {
      width:54px;
      height:54px;
      border-radius:12px;
      background:linear-gradient(135deg, #34d399, #10b981);
      display:flex;
      align-items:center;
      justify-content:center;
      color:white;
      font-weight:700;
      font-size:22px;
      box-shadow:0 6px 18px rgba(16,185,129,0.18);
    }
    h1{font-size:20px;margin:0}
    p.lead{margin:0;color:var(--muted)}

    .meta{
      margin-top:6px;
      padding:12px;
      border-radius:10px;
      background:#fafafa;
      font-size:14px;
      color:#111827;
    }
    .meta .row{display:flex;justify-content:space-between;gap:12px;margin-bottom:8px}
    .meta .label{color:var(--muted);font-size:13px}

    /* Right */
    .right {
      padding:28px;
      background:linear-gradient(180deg,#fbfdff,#f7f8ff);
      display:flex;
      flex-direction:column;
      gap:16px;
      align-items:center;
      justify-content:center;
    }
    .amount{
      font-size:28px;
      font-weight:700;
      color:var(--accent);
    }
    .btn{
      display:inline-block;
      padding:12px 18px;
      background:var(--accent);
      color:white;
      text-decoration:none;
      border-radius:10px;
      font-weight:600;
      box-shadow:0 8px 20px rgba(108,92,231,0.14);
    }
    .btn.secondary{
      background:transparent;
      color:var(--accent);
      border:1px solid rgba(108,92,231,0.14);
    }

    .small{font-size:13px;color:var(--muted)}
    .session{word-break:break-all; font-family:monospace; font-size:13px; background:#fff; padding:8px; border-radius:8px; border:1px solid #eef2ff}
    
    @media(max-width:880px){
      .container{grid-template-columns:1fr; padding:0}
      .right{order:2}
    }
  </style>
</head>
<body>
  <div class="container" role="main">
    <div class="left">
      <!-- Banner: thay đường dẫn ảnh ở public/images/success-banner.jpg -->
      <div class="banner" style="background-image: url('{{ asset('images/success-banner.jpg') }}');"></div>

      <div class="title">
        <div class="check">✓</div>
        <div>
          <h1>Thanh toán thành công</h1>
          <p class="lead">Cảm ơn bạn — đơn hàng đã được xử lý và thanh toán thành công.</p>
        </div>
      </div>

      <div class="meta" aria-labelledby="order-meta">
        <div class="row"><div class="label">Session ID</div><div>{{ $session_id ?? 'N/A' }}</div></div>
        <div class="row"><div class="label">Sản phẩm</div><div>Demo T-shirt ×1</div></div>
        <div class="row"><div class="label">Trạng thái</div><div style="color:var(--success); font-weight:600">Succeeded</div></div>
        <div class="row"><div class="label">Ngày</div><div>{{ now()->format('d/m/Y H:i') }}</div></div>
        <div style="margin-top:8px" class="small">Bạn sẽ nhận email xác nhận (nếu đã cung cấp email). Lưu lại <strong>Session ID</strong nếu cần tra cứu.</div>
      </div>

      <div style="display:flex;gap:12px">
        <a class="btn secondary" href="{{ url('/') }}">Quay về cửa hàng</a>
        <a class="btn" href="{{ route('checkout') }}">Mua tiếp</a>
      </div>
    </div>

    <aside class="right" aria-label="payment-summary">
      <div class="small">Tổng thanh toán</div>
      <div class="amount">$20.00</div>

      <div style="width:100%; margin-top:6px">
        <div class="small">Mã phiên</div>
        <div class="session">{{ $session_id ?? 'N/A' }}</div>
      </div>

      <a class="btn" href="{{ url('/orders') }}" style="margin-top:18px">Xem đơn hàng của tôi</a>
      <div class="small" style="margin-top:6px">Bạn có thể xem chi tiết giao dịch ở Stripe Dashboard → Payments.</div>
    </aside>
  </div>
</body>
</html>
