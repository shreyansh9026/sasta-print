<?php
// ── PDF Invoice Service ────────────────────────────────────────────────────────
class InvoiceService {

    public static function generate(array $order, array $items): string {
        $html = self::template($order, $items);
        $fileName = 'invoice_' . $order['id'] . '_' . time() . '.pdf';
        $filePath = STORAGE_PATH . '/invoices/' . $fileName;

        if (!is_dir(STORAGE_PATH . '/invoices')) {
            mkdir(STORAGE_PATH . '/invoices', 0755, true);
        }

        if (!class_exists('Dompdf\Dompdf')) {
            // Fallback: save as HTML if dompdf not installed
            file_put_contents(str_replace('.pdf', '.html', $filePath), $html);
            return str_replace('.pdf', '.html', $filePath);
        }

        $dompdf = new \Dompdf\Dompdf(['enable_remote' => true]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        file_put_contents($filePath, $dompdf->output());
        return $filePath;
    }

    private static function template(array $order, array $items): string {
        $rows = '';
        foreach ($items as $item) {
            $rows .= "<tr>
                <td>{$item['product_name']}</td>
                <td style='text-align:center;'>{$item['quantity']}</td>
                <td style='text-align:center;'>" . ($item['size'] ?? '—') . "</td>
                <td style='text-align:center;'>" . ($item['material'] ?? '—') . "</td>
                <td style='text-align:right;'>₹" . number_format($item['price'], 2) . "</td>
            </tr>";
        }

        return "<!DOCTYPE html><html><head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1e293b; }
            .header { background: #6c63ff; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
            .header h1 { margin: 0; font-size: 24px; }
            .meta { display: flex; justify-content: space-between; margin-bottom: 20px; }
            .meta div { background: #f8fafc; padding: 12px; border-radius: 6px; flex: 1; margin: 0 4px; }
            table { width: 100%; border-collapse: collapse; }
            th { background: #f1f5f9; padding: 10px; text-align: left; }
            td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
            .total { text-align: right; font-size: 16px; font-weight: bold; margin-top: 12px; }
            .footer { margin-top: 40px; text-align: center; color: #94a3b8; font-size: 11px; }
        </style></head><body>
        <div class='header'>
            <h1>🖨️ " . APP_NAME . "</h1>
            <p style='margin:4px 0 0;opacity:0.85;'>Tax Invoice</p>
        </div>
        <div class='meta'>
            <div><strong>Invoice #:</strong> INV-{$order['id']}<br><strong>Date:</strong> " . date('d M Y', strtotime($order['created_at'])) . "</div>
            <div><strong>Status:</strong> " . strtoupper($order['status']) . "<br><strong>Payment:</strong> " . strtoupper($order['payment_status'] ?? 'pending') . "</div>
            <div><strong>Ship To:</strong><br>" . nl2br(htmlspecialchars($order['shipping_address'] ?? '')) . "</div>
        </div>
        <table>
            <thead><tr>
                <th>Product</th><th style='text-align:center;'>Qty</th>
                <th style='text-align:center;'>Size</th><th style='text-align:center;'>Material</th>
                <th style='text-align:right;'>Price</th>
            </tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        <div class='total'>
            " . (!empty($order['discount_amount']) && $order['discount_amount'] > 0
                ? "<div>Discount: -₹" . number_format($order['discount_amount'], 2) . "</div>" : "") . "
            <div>Total: ₹" . number_format($order['total_amount'], 2) . "</div>
        </div>
        <div class='footer'>Thank you for choosing " . APP_NAME . " | support@sastaprint.com</div>
        </body></html>";
    }
}
