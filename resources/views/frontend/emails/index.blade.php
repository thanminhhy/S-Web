<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-text-size-adjust: none;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #2d3748;
            padding: 25px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }

        .content {
            padding: 30px;
        }

        .greeting {
            font-size: 16px;
            color: #333333;
            margin-bottom: 20px;
        }

        .order-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .order-info td {
            padding: 4px 0;
            font-size: 14px;
            color: #4a5568;
        }

        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .table-items th {
            background-color: #edf2f7;
            color: #2d3748;
            text-align: left;
            padding: 10px;
            font-size: 13px;
            border-bottom: 2px solid #cbd5e0;
        }

        .table-items td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #2d3748;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            font-size: 16px;
            color: #e53e3e;
            border-top: 2px solid #cbd5e0;
        }

        .footer {
            background-color: #f7fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
        }

        .btn-status {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3182ce;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f4f4f7; padding: 20px 0;">
        <tr>
            <td align="center">
                <div class="container">

                    <!-- HEADER -->
                    <div class="header">
                        <h1>CẢM ƠN BẠN ĐÃ ĐẶT HÀNG!</h1>
                    </div>

                    <!-- CONTENT -->
                    <div class="content">
                        <p class="greeting">
                            Xin chào <strong>{{ $order->user->name ?? 'Quý khách' }}</strong>,<br>
                            Đơn hàng của bạn đã được hệ thống tiếp nhận và đang trong quá trình xử lý.
                        </p>

                        <!-- MÃ ĐƠN HÀNG & THÔNG TIN -->
                        <div class="order-info">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td><strong>Mã đơn hàng:</strong> #{{ $order->id }}</td>
                                    <td class="text-right"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Địa chỉ giao hàng:</strong> {{ $order->address ?? 'N/A' }}</td>
                                    <td class="text-right"><strong>Số điện thoại:</strong> {{ $order->phone ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- CHI TIẾT SẢN PHẨM -->
                        <table class="table-items">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-right">Đơn giá</th>
                                    <th class="text-right">Thuế Môi trường</th>
                                    <th class="text-right">Phí Ship</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class='text-center'>
                                        {{ $item->name ?? 'Sản phẩm #' . $item->product_id }}
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    <td class="text-right">{{ number_format($item->quantity * 2000, 0, ',', '.') }}đ</td>
                                    <td class="text-right">{{ number_format($order->total_price <= 500000 ? 30000 : 0, 0, ',', '.') }}đ</td>
                                    <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach

                                <!-- TỔNG TIỀN -->
                                <tr class="total-row">
                                    <td colspan="5" class="text-right"><strong>TỔNG CỘNG:</strong></td>
                                    <td class="text-right"><strong>{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <p style="font-size: 14px; color: #718096; line-height: 1.5;">
                            Nếu có bất kỳ thắc mắc nào về đơn hàng, bạn có thể phản hồi trực tiếp qua email này hoặc liên hệ hotline chăm sóc khách hàng của chúng tôi.
                        </p>

                        <div style="text-align: center;">
                            <a href="{{ url('/') }}" class="btn-status">Tiếp tục mua sắm</a>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="footer">
                        <p>© {{ date('Y') }} Website Bán Hàng. All rights reserved.</p>
                        <p>Đây là email tự động, vui lòng không phản hồi nếu không có thắc mắc.</p>
                    </div>

                </div>
            </td>
        </tr>
    </table>

</body>

</html>