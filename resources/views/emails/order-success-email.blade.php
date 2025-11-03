@php
    use App\Models\Product;
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Xác nhận đơn hàng</title>
</head>
<body style="background-color:#f4f4f4; margin:0; padding:20px; font-family:Arial, sans-serif;">

  <table align="center" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:10px; padding:20px;">
    <tr>
      <td align="center">
        <img src="https://i.ibb.co/fzLv84Q6/logo.png" alt="Logo" width="150" style="margin-bottom:20px;" />
      </td>
    </tr>
    <tr>
      <td align="center">
        <img src="https://i.postimg.cc/T3j8LqhC/icon-success.png" alt="Icon" width="60" style="margin-bottom:16px;" />
      </td>
    </tr>
    <tr>
      <td align="center" style="color:#222; font-size:20px; font-weight:bold; padding-bottom:10px;">
        Cảm ơn bạn đã đặt hàng
      </td>
    </tr>
    <tr>
      <td align="center" style="color:#555; font-size:14px; padding-bottom:20px;">
        Một email xác nhận đã được gửi tới <strong>{{ $order->customer_name }}</strong><br />
        Xin vui lòng kiểm tra email của bạn.
      </td>
    </tr>
    <tr>
      <td style="padding:16px; background-color:#f9f9f9; border-radius:8px;">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="font-size:14px; font-weight:bold;">
              Đơn hàng #{{ $order->id }}
            </td>
            <td align="right" style="font-size:13px;">
              <a href="#" style="color:#007bff; text-decoration:none;">Xem chi tiết &gt;</a>
            </td>
          </tr>
        </table>

        @foreach($cart as $item)
          @php
              $product = Product::find($item['product_id']);
          @endphp
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;">
            <tr>
              <td width="80" valign="top">
                <img src="https://cdn.techcent.vn/product_images/maychieu.jpg" width="80" style="border-radius:6px;" />
              </td>
              <td style="padding-left:10px;">
                <div style="font-weight:bold; font-size:14px;">{{ $product->name }}</div>
                <div style="font-size:13px; color:#777;">{{ $product->description }}</div>
                <div style="font-size:13px; color:#555;">Kích thước: {{ $item['size_id'] }} x SL: {{ $item['quantity'] }}</div>
                <div style="font-size:14px; color:#000; margin-top:4px;">{{ number_format($item['price'], 0, ',', '.') }}₫</div>
              </td>
            </tr>
          </table>
        @endforeach

        <table width="100%" cellpadding="0" cellspacing="0" style="border-top:1px dashed #ccc; margin-top:16px; padding-top:12px;">
          <tr>
            <td style="font-size:14px;">Khuyến mãi</td>
            <td align="right" style="font-size:14px;">0₫</td>
          </tr>
          <tr>
            <td style="font-size:14px;">Tạm tính</td>
            <td align="right" style="font-size:14px;">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
          </tr>
          <tr>
            <td style="font-size:14px;">Phí vận chuyển</td>
            <td align="right" style="font-size:14px;">Miễn phí</td>
          </tr>
          <tr>
            <td style="font-size:14px; font-weight:bold; color:#007bff;">Tổng cộng</td>
            <td align="right" style="font-size:14px; font-weight:bold; color:#007bff;">
              {{ number_format($order->total_price, 0, ',', '.') }}₫
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td style="padding-top:20px;">
        <div style="font-size:15px; font-weight:bold; margin-bottom:4px;">Thông tin mua hàng</div>
        <div style="font-size:14px;">Họ tên: {{ $order->customer_name }}</div>
        <div style="font-size:14px;">Điện thoại: {{ $order->phone }}</div>
        <div style="font-size:14px;">Địa chỉ: {{ $order->shipping_address }}</div>
        <div style="font-size:14px;">Ghi chú: {{ $order->notes }}</div>
      </td>
    </tr>

  </table>
</body>
</html>
