<div class="row">
    <div class="col-12 mb-4">
        <h2>Chi tiết đơn hàng</h2>
        <a href="<?= BASE_URL ?>/doanhthu" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Phiếu thanh toán <?= APP_NAME ?></h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h4>Phiếu thanh toán <?= APP_NAME ?></h4>
                    <p class="mb-1">MP: <?= $order['order_id'] ?></p>
                    <hr>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Giá bán (Có VAT)</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td>
                                    <?= $item['menu_name'] ?><br>
                                    <?= number_format($item['price']) ?> VNĐ
                                </td>
                                <td class="text-center"><?= $item['quantity'] ?></td>
                                <td class="text-end"><?= number_format($item['subtotal']) ?> VNĐ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-7">Phải thanh toán:</div>
                    <div class="col-5 text-end"><?= number_format($order['total_amount']) ?> VNĐ</div>
                </div>
                
                <?php if ($order['payment_method'] === 'cash'): ?>
                <div class="row">
                    <div class="col-7">Số tiền khách đưa:</div>
                    <div class="col-5 text-end"><?= number_format($order['cash_received']) ?> VNĐ</div>
                </div>
                <div class="row">
                    <div class="col-7">Số tiền trả khách:</div>
                    <div class="col-5 text-end"><?= number_format($order['cash_returned']) ?> VNĐ</div>
                </div>
                <?php else: ?>
                <div class="row">
                    <div class="col-7">Hình thức thanh toán:</div>
                    <div class="col-5 text-end">Chuyển khoản</div>
                </div>
                <?php endif; ?>
                
                <hr>
                
                <div class="row">
                    <div class="col-12">
                        <p class="mb-1">Thời gian: <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
                        <p class="mb-1">NV: <?= $order['staff_id'] ?></p>
                        <p class="mb-1">SĐT: <?= CAFE_PHONE ?></p>
                        <p class="mb-1">Email: <?= CAFE_EMAIL ?></p>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> In phiếu
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

