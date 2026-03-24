<?php
// ── Mail Service ───────────────────────────────────────────────────────────────
class MailService {
    private static function mailer(): object {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            throw new RuntimeException('PHPMailer not installed. Run: composer install');
        }
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = MAIL_PORT;
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->isHTML(true);
        return $mail;
    }

    /** Send order confirmation email */
    public static function sendOrderConfirmation(array $order, array $items): bool {
        if (empty(MAIL_USERNAME)) return false; // not configured
        try {
            $mail = self::mailer();
            $to   = $order['guest_email'] ?? '';
            if ($order['user_id']) {
                $userModel = new User();
                $user = $userModel->findById((int)$order['user_id']);
                $to   = $user['email'] ?? $to;
                $name = $user['name']  ?? 'Customer';
            } else {
                $name = 'Customer';
            }
            $mail->addAddress($to, $name);
            $mail->Subject = 'Order #' . $order['id'] . ' Confirmed — ' . APP_NAME;
            $mail->Body    = self::orderConfirmationTemplate($order, $items, $name);
            $mail->send();
            Logger::info('Order confirmation email sent', ['order_id' => $order['id'], 'to' => $to]);
            return true;
        } catch (Exception $e) {
            Logger::error('Mail send failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /** Send order status update */
    public static function sendStatusUpdate(array $order, string $newStatus): bool {
        if (empty(MAIL_USERNAME)) return false;
        try {
            $mail = self::mailer();
            $userModel = new User();
            $user = $order['user_id'] ? $userModel->findById((int)$order['user_id']) : null;
            $to   = $user['email'] ?? $order['guest_email'] ?? '';
            if (!$to) return false;
            $mail->addAddress($to, $user['name'] ?? 'Customer');
            $mail->Subject = 'Your order #' . $order['id'] . ' is now ' . strtoupper($newStatus);
            $mail->Body    = self::statusUpdateTemplate($order, $newStatus, $user['name'] ?? 'Customer');
            $mail->send();
            return true;
        } catch (Exception $e) {
            Logger::error('Status email failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private static function orderConfirmationTemplate(array $order, array $items, string $name): string {
        $itemsHtml = '';
        foreach ($items as $item) {
            $itemsHtml .= "<tr>
                <td style='padding:8px;border-bottom:1px solid #eee;'>{$item['product_name']}</td>
                <td style='padding:8px;border-bottom:1px solid #eee;text-align:center;'>{$item['quantity']}</td>
                <td style='padding:8px;border-bottom:1px solid #eee;text-align:right;'>₹" . number_format($item['price'], 2) . "</td>
            </tr>";
        }
        return "
        <div style='font-family:Inter,sans-serif;max-width:600px;margin:auto;background:#f9fafb;padding:2rem;'>
            <div style='background:#6c63ff;padding:1.5rem;border-radius:12px;text-align:center;color:white;'>
                <h1 style='margin:0;'>🖨️ " . APP_NAME . "</h1>
                <p style='margin:0.5rem 0 0;opacity:0.85;'>Order Confirmed!</p>
            </div>
            <div style='background:white;padding:2rem;border-radius:12px;margin-top:1rem;'>
                <p>Hi <b>{$name}</b>,</p>
                <p>Thank you for your order! Here's a summary:</p>
                <p><strong>Order #:</strong> {$order['id']}</p>
                <table style='width:100%;border-collapse:collapse;margin:1rem 0;'>
                    <thead><tr style='background:#f3f4f6;'>
                        <th style='padding:8px;text-align:left;'>Product</th>
                        <th style='padding:8px;text-align:center;'>Qty</th>
                        <th style='padding:8px;text-align:right;'>Price</th>
                    </tr></thead>
                    <tbody>{$itemsHtml}</tbody>
                    <tfoot><tr>
                        <td colspan='2' style='padding:8px;font-weight:bold;'>Total</td>
                        <td style='padding:8px;text-align:right;font-weight:bold;'>₹" . number_format($order['total_amount'], 2) . "</td>
                    </tr></tfoot>
                </table>
                <p>We'll notify you when your order ships. Thank you for choosing " . APP_NAME . "!</p>
            </div>
        </div>";
    }

    private static function statusUpdateTemplate(array $order, string $status, string $name): string {
        $icons = ['processing' => '⚙️', 'shipped' => '🚚', 'delivered' => '✅', 'cancelled' => '❌'];
        $icon  = $icons[$status] ?? '📦';
        return "
        <div style='font-family:Inter,sans-serif;max-width:600px;margin:auto;background:#f9fafb;padding:2rem;'>
            <div style='background:#6c63ff;padding:1.5rem;border-radius:12px;text-align:center;color:white;'>
                <h1 style='margin:0;'>🖨️ " . APP_NAME . "</h1>
            </div>
            <div style='background:white;padding:2rem;border-radius:12px;margin-top:1rem;'>
                <h2>{$icon} Order #{$order['id']} — " . ucfirst($status) . "</h2>
                <p>Hi <b>{$name}</b>, your order status has been updated to <strong>" . strtoupper($status) . "</strong>.</p>
                <p>Thank you for shopping with " . APP_NAME . "!</p>
            </div>
        </div>";
    }
}
