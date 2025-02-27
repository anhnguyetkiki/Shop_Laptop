<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <div style="width:100%; float:left; margin: 40px 0px;font-family: DejaVu Sans; line-height: 200%; font-size:12px">
        <p style="float: right; text-align: right; padding-right:20px; line-height: 140%">
            Ngày đặt hàng: {{ date_format($date_order_create, 'd-m-Y') }}<br><br>
            <span text-align: center>{!! $qrcode !!}</span>
        </p>
        <div style="float: left; margin: 0 0 1.5em 0; ">
            <strong style="font-size: 18px;">PhongVu</strong>
            <br />
            <strong>Địa chỉ:</strong> {{ $address }}.
            <br />
            <strong>Điện thoại:</strong> {{ $phone }}
            <br />
            <strong>Website:</strong> PhongVu.demo
            <br />
            <strong>Email:</strong> {{ $email }}
        </div>
        <div style="clear:both"></div>
        <table style="width: 100%">
            <tr>
                <td valign="top" style="width: 65%">
                    <h3 style="font-size: 14px;margin: 1.5em 0 1em 0;">Chi tiết đơn hàng</h3>
                    <hr style="border: none; border-top: 2px solid #0975BD;" />

                    <table style="margin: 0 0 1.5em 0;font-size: 12px;" width="100%">
                        <thead>
                            <tr>
                                <th style="width:25%;text-align: left;padding: 5px 0px">STT</th>
                                <th style="width:35%;text-align: left;padding: 5px 0px">Sản phẩm</th>
                                <th style="width:15%;text-align: right;padding: 5px 0px">Số lượng</th>
                                <th style="width:25%;text-align: right;padding: 5px 0px">Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($billdetaill_print as $key => $bd)
                                @php
                                    $totalPrice = $kh_print->payment == 'ATM' ? 0 : number_format($bd->billDetailToBill->total, 0, ',', '.');
                                @endphp

                                <tr valign="top" style="border-top: 1px solid #d9d9d9;">
                                    <td align="left" style="padding: 5px 0px">{{ $key + 1 }}</td>
                                    <td align="left" style="padding: 5px 5px 5px 0px;white-space: pre-line;">
                                        {{ $bd->sp_vi }} </td>
                                    <td align="center" style="padding: 5px 0px">{{ $bd->quantity }}</td>
                                    <td align="right" style="padding: 5px 0px">
                                        {{ number_format($bd->unit_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <h3 style="font-size: 14px;margin: 0 0 1em 0;">Thông tin thanh toán</h3>
                    <table style="font-size: 12px;width: 100%; margin: 0 0 1.5em 0;">
                        <tr>
                            <td style="padding: 5px 0px">Tổng giá sản phẩm:</td>
                            <td style="text-align:right">{{ number_format($bd->billDetailToBill->total, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;padding: 5px 0px">Phí vận chuyển:</td>
                            <td style="text-align:right;padding: 5px 0px">0</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0px"><strong>Tổng tiền:</strong></td>
                            <td style="text-align:right;padding: 5px 0px"><strong>
                                    <p>{{ $totalPrice }} VNĐ</td>
                        </tr>
                    </table>
                    <h3 style="font-size: 14px;margin: 0 0 1em 0;">Ghi chú:</h3>
                    <p style="line-height: 30px">{{ $kh_print->note }}</p>
                </td>
                <td valign="top" style="padding: 0px 20px">
                    <h3 style="font-size: 14px;margin: 1.5em 0 1em 0;">Thông tin đơn hàng</h3>
                    <hr style="border: none; border-top: 2px solid #0975BD;" />
                    <div style="margin: 0 0 1em 0; padding: 1em; border: 1px solid #d9d9d9;">
                        <strong>Mã đơn hàng:</strong><br>#{{ $kh_print->order_code }}<br>
                        <strong>Ngày đặt hàng:</strong><br>{{ date_format($date_order_create, 'd-m-Y') }}<br>
                        <strong>Phương thức thanh toán</strong><br>{{ $kq_pay }}
                        <br>
                        <strong>Phương thức vận chuyển</strong><br>Shipper
                    </div>
                    <h3 style="font-size: 14px;margin: 1.5em 0 1em 0;">Thông tin mua hàng</h3>
                    <hr style="border: none; border-top: 2px solid #0975BD;" />
                    <div style="margin: 0 0 1em 0; padding: 1em; border: 1px solid #d9d9d9;  white-space: normal;">
                        <strong>{{ $kh_print->name }}</strong><br />
                        {{ $kh_print->address }}<br />
                        Điện thoại: {{ $kh_print->phone_number }}<br />
                        Email:{{ $kh_print->email }}
                    </div>
                </td>
            </tr>
        </table><br /><br /><br />
        <p>Nếu bạn có thắc mắc, vui lòng liên hệ chúng tôi qua email <u>{{ $email }}</u> hoặc {{ $phone }}</p>
    </div>
</head>
