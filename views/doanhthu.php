<div class="row">
    <div class="col-12 mb-4">
        <h2>Doanh thu</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Thống kê doanh thu</h5>
                <form method="get" action="<?= BASE_URL ?>/doanhthu/monthly">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="day" class="form-label">Ngày</label>
                            <select class="form-select" id="day" name="day">
                                <option value="">Chọn ngày</option>
                                <?php for ($i = 1; $i <= 31; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="month" class="form-label">Tháng</label>
                            <select class="form-select" id="month" name="month">
                                <option value="">Chọn tháng</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == date('m') ? 'selected' : '' ?>>
                                    Tháng <?= $i ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="year" class="form-label">Năm</label>
                            <select class="form-select" id="year" name="year">
                                <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                <option value="<?= $i ?>" <?= $i == date('Y') ? 'selected' : '' ?>>
                                    Năm <?= $i ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Xem doanh thu</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tìm kiếm đơn hàng</h5>
                <form method="get" action="<?= BASE_URL ?>/hoadon">
                    <div class="mb-3">
                        <label for="order_id" class="form-label">Mã đơn hàng</label>
                        <input type="text" class="form-control" id="order_id" name="id" placeholder="Nhập mã đơn hàng">
                    </div>
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tổng quan</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title">Tổng số đơn hàng</h6>
                                <h2><?= isset($revenue) ? number_format($revenue['total_orders']) : '0' ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title">Tổng doanh thu</h6>
                                <h2><?= isset($revenue) ? number_format($revenue['total_revenue']) : '0' ?> VNĐ</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="card-title">Doanh thu tiền mặt</h6>
                                <h2><?= isset($revenue) ? number_format($revenue['cash_revenue']) : '0' ?> VNĐ</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title">Doanh thu chuyển khoản</h6>
                                <h2><?= isset($revenue) ? number_format($revenue['transfer_revenue']) : '0' ?> VNĐ</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Danh sách đơn hàng</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Nhân viên</th>
                                <th>Tổng tiền</th>
                                <th>Phương thức thanh toán</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($orders) && !empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                                    <td><?= htmlspecialchars($order['staff_name']) ?></td>
                                    <td class="text-end"><?= number_format($order['total_amount']) ?> VNĐ</td>
                                    <td><?= $order['payment_method'] === 'cash' ? 'Tiền mặt' : 'Chuyển khoản' ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/hoadon?id=<?= $order['order_id'] ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailModalLabel">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Mã đơn hàng:</strong> <span id="detail_order_id"></span></p>
                        <p><strong>Nhân viên:</strong> <span id="detail_staff_name"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ngày tạo:</strong> <span id="detail_order_date"></span></p>
                        <p><strong>Phương thức thanh toán:</strong> <span id="detail_payment_method"></span></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên món</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsList">
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <h4>Tổng tiền: <span id="detail_total_amount" class="text-danger"></span></h4>
                </div>
            </div>
        </div>
    </div>
</div>

