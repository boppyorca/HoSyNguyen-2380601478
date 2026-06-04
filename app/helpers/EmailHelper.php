<?php
class EmailHelper
{
    private static function getBaseTemplate($title, $content)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . htmlspecialchars($title) . '</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #0a192f; font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; color: #e2e8f0;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #172a45; border: 1px solid #233554; border-radius: 14px; margin-top: 40px; margin-bottom: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); overflow: hidden;">
                <!-- Header -->
                <tr>
                    <td align="center" style="padding: 30px 20px; background: linear-gradient(135deg, #172a45, #1f3554); border-bottom: 1px solid #233554;">
                        <h1 style="margin: 0; color: #f5a623; font-size: 26px; font-weight: 700; letter-spacing: 1px;">
                            <span style="vertical-align: middle;">🛒</span> Shop Manager
                        </h1>
                    </td>
                </tr>
                <!-- Body Content -->
                <tr>
                    <td style="padding: 40px 30px; font-size: 16px; line-height: 1.6; color: #cbd5e1;">
                        ' . $content . '
                    </td>
                </tr>
                <!-- Footer -->
                <tr>
                    <td align="center" style="padding: 25px 20px; background-color: #0f2038; border-top: 1px solid #233554; font-size: 13px; color: #8892b0;">
                        <p style="margin: 0 0 5px 0;">Cảm ơn bạn đã đồng hành cùng Shop Manager!</p>
                        <p style="margin: 0;">Đây là email tự động, vui lòng không trả lời thư này.</p>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    }

    public static function sendOrderSuccessEmail($toEmail, $orderId, $customerName, $totalAmount, $items)
    {
        $subject = "Đặt hàng thành công - Đơn hàng #" . $orderId;
        
        $itemsTable = '<table border="0" cellpadding="10" cellspacing="0" width="100%" style="border-collapse: collapse; margin-top: 20px; margin-bottom: 25px; background-color: #1b2f4c; border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background-color: #233554; color: #ffffff; text-align: left; font-weight: bold; font-size: 14px;">
                    <th style="padding: 12px;">Sản phẩm</th>
                    <th style="padding: 12px; text-align: center;">SL</th>
                    <th style="padding: 12px; text-align: right;">Đơn giá</th>
                    <th style="padding: 12px; text-align: right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>';
            
        foreach ($items as $item) {
            $subtotal = $item['quantity'] * $item['price'];
            $itemsTable .= '
                <tr style="border-bottom: 1px solid #233554; font-size: 14px;">
                    <td style="padding: 12px; color: #e2e8f0;">' . htmlspecialchars($item['name']) . '</td>
                    <td style="padding: 12px; text-align: center; color: #e2e8f0;">' . $item['quantity'] . '</td>
                    <td style="padding: 12px; text-align: right; color: #e2e8f0;">' . number_format($item['price'], 0, ',', '.') . 'đ</td>
                    <td style="padding: 12px; text-align: right; color: #f5a623; font-weight: bold;">' . number_format($subtotal, 0, ',', '.') . 'đ</td>
                </tr>';
        }
        
        $itemsTable .= '
            </tbody>
        </table>';

        $content = '
        <h2 style="color: #ffffff; margin-top: 0; font-size: 20px; font-weight: 600;">Xin chào ' . htmlspecialchars($customerName) . ',</h2>
        <p>Đơn hàng **#' . $orderId . '** của bạn đã được đặt thành công và đang chờ xử lý.</p>
        
        ' . $itemsTable . '
        
        <div style="background-color: #1b2f4c; padding: 15px; border-radius: 8px; border: 1px solid #233554; margin-bottom: 30px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="font-size: 16px; color: #cbd5e1; font-weight: bold;">Tổng cộng thanh toán:</td>
                    <td style="font-size: 18px; color: #f5a623; font-weight: bold; text-align: right;">' . number_format($totalAmount, 0, ',', '.') . 'đ</td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="http://localhost:8080/webbanhang/Order/show/' . $orderId . '" style="background: linear-gradient(135deg, #f5a623, #d97706); color: #0a192f; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 15px rgba(245, 166, 35, 0.3);">Xem chi tiết đơn hàng</a>
        </div>';

        $body = self::getBaseTemplate($subject, $content);
        return self::send($toEmail, $subject, $body);
    }

    public static function sendOrderCancelledEmail($toEmail, $orderId, $customerName, $totalAmount)
    {
        $subject = "Đơn hàng đã hủy thành công - Đơn hàng #" . $orderId;
        
        $content = '
        <h2 style="color: #ffffff; margin-top: 0; font-size: 20px; font-weight: 600;">Xin chào ' . htmlspecialchars($customerName) . ',</h2>
        <p>Chúng tôi xác nhận đơn hàng **#' . $orderId . '** trị giá **' . number_format($totalAmount, 0, ',', '.') . 'đ** đã được hủy thành công theo yêu cầu.</p>
        
        <p style="color: #ea868f; background-color: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); padding: 12px; border-radius: 8px; font-size: 14px; margin-top: 20px; margin-bottom: 30px;">
            ℹ️ Nếu bạn đã thanh toán qua hình thức chuyển khoản trước, vui lòng gửi yêu cầu trả hàng/hoàn tiền hoặc liên hệ CSKH để được xử lý hoàn tiền sớm nhất.
        </p>

        <div style="text-align: center; margin-top: 20px;">
            <a href="http://localhost:8080/webbanhang/Order/myOrders" style="background-color: #233554; border: 1px solid #30476e; color: #e2e8f0; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block;">Xem lịch sử mua hàng</a>
        </div>';

        $body = self::getBaseTemplate($subject, $content);
        return self::send($toEmail, $subject, $body);
    }

    public static function sendRefundSuccessEmail($toEmail, $orderId, $customerName, $refundAmount)
    {
        $subject = "Hoàn tiền thành công - Đơn hàng #" . $orderId;

        $content = '
        <h2 style="color: #ffffff; margin-top: 0; font-size: 20px; font-weight: 600;">Xin chào ' . htmlspecialchars($customerName) . ',</h2>
        <p>Yêu cầu trả hàng cho đơn hàng **#' . $orderId . '** đã được Admin phê duyệt thành công.</p>
        
        <div style="background-color: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2); padding: 20px; border-radius: 8px; margin-top: 20px; margin-bottom: 30px; text-align: center;">
            <div style="font-size: 14px; color: #cbd5e1; margin-bottom: 5px;">Số tiền hoàn trả:</div>
            <div style="font-size: 24px; color: #75b798; font-weight: bold;">' . number_format($refundAmount, 0, ',', '.') . 'đ</div>
            <div style="font-size: 13px; color: #8892b0; margin-top: 5px;">Hình thức: Hoàn tiền qua tài khoản ngân hàng / Ví điện tử</div>
        </div>

        <p>Số tiền hoàn trả đã được thực thi chuyển khoản. Quý khách vui lòng kiểm tra tài khoản ngân hàng hoặc ví điện tử đã liên kết mua hàng.</p>

        <div style="text-align: center; margin-top: 35px;">
            <a href="http://localhost:8080/webbanhang/Order/show/' . $orderId . '" style="background: linear-gradient(135deg, #f5a623, #d97706); color: #0a192f; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 15px rgba(245, 166, 35, 0.3);">Xem chi tiết đơn hàng</a>
        </div>';

        $body = self::getBaseTemplate($subject, $content);
        return self::send($toEmail, $subject, $body);
    }

    private static function send($to, $subject, $body)
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Shop Manager <honguyndeptroai@gmail.com>" . "\r\n";
        
        return mail($to, $subject, $body, $headers, "-fhonguyndeptroai@gmail.com");
    }
}
