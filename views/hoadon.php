<div class="bill-container" style="width: 300px; margin: 20px auto; font-family: 'Courier New', monospace; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <div class="text-center mb-3">
        <h5 class="fw-bold mb-1">Phiếu thanh toán <?= APP_NAME ?></h5>
        <p class="mb-1">MP: <?= $orderData['bill_number'] ?></p>
        <div class="border-bottom border-dark my-2"></div>
    </div>
    
    <div class="mb-3">
        <p class="mb-2">Giá bán&nbsp;&nbsp;SL&nbsp;&nbsp;Thành tiền</p>
        <?php foreach ($orderData['items'] as $item): ?>
        <div class="mb-2">
            <div><?= htmlspecialchars($item['name']) ?></div>
            <div class="d-flex justify-content-between">
                <span><?= number_format($item['price']) ?>vnđ</span>
                <span class="mx-3"><?= $item['quantity'] ?></span> <!-- Added quantity (SL) -->
                <span><?= number_format($item['subtotal']) ?>vnđ</span>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="border-bottom border-dark my-2"></div>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <span>Phải thanh toán:</span>
            <span><?= number_format($orderData['total_amount']) ?>vnđ</span>
        </div>
        <?php if ($orderData['payment_method'] === 'cash'): ?>
        <div class="d-flex justify-content-between">
            <span>Số tiền khách đưa:</span>
            <span><?= number_format($orderData['cash_received']) ?>vnđ</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Số tiền trả khách:</span>
            <span><?= number_format($orderData['cash_returned']) ?>vnđ</span>
        </div>
        <?php else: ?>
        <div class="d-flex justify-content-between">
            <span>Hình thức thanh toán:</span>
            <span>Chuyển khoản</span>
        </div>
        <?php endif; ?>
        <div class="border-bottom border-dark my-2"></div>
    </div>

    <div class="small">
        <p class="mb-1">Thời gian: <?= date('d/m/Y H:i', strtotime($orderData['created_at'])) ?></p>
        <p class="mb-1">NV: <?= htmlspecialchars($orderData['staff_id']) ?></p>
        <p class="mb-1">Vui lòng kiểm tra hóa đơn, mọi góp ý phản hồi quý khách liên hệ:</p>
        <p class="mb-1">SĐT: <?= htmlspecialchars($cafePhone) ?></p>
        <p class="mb-0">Email: <?= htmlspecialchars($cafeEmail) ?></p>
    </div>

    <div class="text-center mt-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm mb-2">
            <i class="fas fa-print"></i> In hóa đơn
        </button>
        <a href="<?= BASE_URL ?>/danhsach" class="btn btn-secondary btn-sm mb-2 ms-3">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<style>
@media print {
    .btn { display: none; }
    .bill-container {
        width: 80mm;
        margin: 0;
        padding: 10px;
        background-color: white !important;
        box-shadow: none !important;
    }
}
</style>
