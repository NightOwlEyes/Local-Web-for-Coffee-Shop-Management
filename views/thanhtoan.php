<div class="row">
    <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
        <h2>Thanh toán hóa đơn</h2>
        <a href="<?= BASE_URL ?>/danhsach" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Menu
        </a>
    </div>
</div>

<div class="row">
    <!-- Order Summary -->
    <div class="col-md-7 col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Chi tiết hóa đơn</h5>
            </div>
            <div class="card-body">
                <?php if (empty($menuItems)): ?>
                    <p class="text-center">Giỏ hàng trống.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50%;">Món</th>
                                    <th scope="col" class="text-end">Đơn giá</th>
                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col" class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($menuItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($item['image'])): ?>
                                                <img src="<?= BASE_URL ?>/uploads/menu/<?= htmlspecialchars($item['image']) ?>" 
                                                     alt="<?= htmlspecialchars($item['name']) ?>" 
                                                     style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
                                            <?php endif; ?>
                                            <?= htmlspecialchars($item['name']) ?>
                                        </div>
                                    </td>
                                    <td class="text-end item-price" data-price="<?= $item['price'] ?>">
                                        <?= number_format($item['price']) ?> đ
                                    </td>
                                    <td class="text-center">
                                        <div class="input-group input-group-sm quantity-controls" style="width: 120px; margin: 0 auto;">
                                            <button type="button" class="btn btn-outline-secondary decrease-quantity" 
                                                    data-id="<?= $item['id'] ?>">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="form-control text-center item-quantity" 
                                                   value="<?= $item['quantity'] ?>" 
                                                   data-id="<?= $item['id'] ?>"
                                                   min="1" style="width: 50px">
                                            <button type="button" class="btn btn-outline-secondary increase-quantity"
                                                    data-id="<?= $item['id'] ?>">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end align-items-center">
                                            <span class="item-subtotal me-2"><?= number_format($item['subtotal']) ?> đ</span>
                                            <button type="button" class="btn btn-danger btn-sm remove-item" 
                                                    data-id="<?= $item['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="3" class="text-end fs-5">Tổng cộng:</th>
                                    <th class="text-end fs-5 text-danger fw-bold"><?= number_format($totalAmount) ?> đ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Payment Options -->
    <div class="col-md-5 col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Chọn thanh toán</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($menuItems)): ?>
                <div class="d-grid gap-3">
                    <button id="cash-payment-btn" class="btn btn-lg btn-outline-primary d-flex align-items-center justify-content-center">
                        <i class="fas fa-money-bill-wave me-2"></i>Tiền mặt
                    </button>
                    <button id="transfer-payment-btn" class="btn btn-lg btn-outline-info d-flex align-items-center justify-content-center">
                        <i class="fas fa-university me-2"></i>Chuyển khoản
                    </button>
                </div>
                <?php else: ?>
                 <p class="text-center">Vui lòng thêm món vào giỏ hàng để thanh toán.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Cash Payment Modal -->
<div class="modal fade" id="cashPaymentModal" tabindex="-1" aria-labelledby="cashPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cashPaymentModalLabel"><i class="fas fa-money-bill-wave me-2"></i>Thanh toán tiền mặt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cashPaymentForm">
                    <div class="mb-3">
                        <label class="form-label">Tổng tiền hóa đơn</label>
                        <input type="text" class="form-control form-control-lg text-danger fw-bold" id="cash_total_display" value="<?= number_format($totalAmount) ?> đ" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cash_received" class="form-label">Số tiền khách đưa (VNĐ)</label>
                        <input type="number" class="form-control form-control-lg" id="cash_received" name="cash_received" min="<?= $totalAmount ?>" step="1000" required placeholder="Nhập số tiền khách đưa">
                        <div class="invalid-feedback">Số tiền khách đưa phải lớn hơn hoặc bằng tổng tiền.</div>
                    </div>
                    <input type="hidden" id="cash_total_amount_hidden" value="<?= $totalAmount ?>">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Xác nhận thanh toán</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Payment Modal -->
<div class="modal fade" id="transferPaymentModal" tabindex="-1" aria-labelledby="transferPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferPaymentModalLabel"><i class="fas fa-university me-2"></i>Thanh toán chuyển khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4 p-3 border rounded bg-light">
                    <h5 class="mb-3">Thông tin chuyển khoản</h5>
                    <img src="<?= BASE_URL ?>/uploads/payment/qr.png" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                    <p class="mb-1"><strong>Ngân hàng:</strong> <span class="text-primary">Momo</span></p>
                    <p class="mb-1"><strong>Số tài khoản:</strong> <span class="text-primary">0706080813</span></p>
                    <p class="mb-1"><strong>Chủ tài khoản:</strong> <span class="text-primary"><?= htmlspecialchars($appName) ?></span></p>
                    <p class="mb-1"><strong>Số tiền:</strong> <span class="text-danger fw-bold fs-5"><?= number_format($totalAmount) ?> đ</span></p>
                </div>
                <form id="transferPaymentForm">
                    <input type="hidden" id="transfer_total_amount_hidden" value="<?= $totalAmount ?>">
                    <div class="alert alert-warning text-center">
                        Vui lòng xác nhận khách hàng đã chuyển khoản thành công trước khi nhấn nút.
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Xác nhận đã thanh toán</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm"> {/* Smaller modal for receipt */}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Phiếu thanh toán</h5>
                {/* No close button initially, force user to print or close explicitly */}
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="receipt-close-btn" style="display: none;"></button>
            </div>
            <div class="modal-body" id="receipt-content" style="font-family: 'Courier New', Courier, monospace; font-size: 0.9rem; line-height: 1.4;">
                <!-- Receipt content will be loaded here by JS -->
                <p class="text-center">Đang tạo phiếu...</p>
            </div>
            <div class="modal-footer justify-content-between">
                 <button type="button" class="btn btn-secondary" id="close-receipt-and-redirect">Đóng</button>
                <button type="button" class="btn btn-primary" id="print-receipt"><i class="fas fa-print me-1"></i> In phiếu</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading/Processing Overlay -->
<div class="modal fade" id="processingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-body text-center p-4">
        <div class="spinner-border text-primary mb-3" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <h5>Đang xử lý...</h5>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables passed from PHP
    const APP_NAME = '<?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?>';
    const CAFE_PHONE = '<?= htmlspecialchars($cafePhone, ENT_QUOTES, 'UTF-8') ?>';
    const CAFE_EMAIL = '<?= htmlspecialchars($cafeEmail, ENT_QUOTES, 'UTF-8') ?>';
    const STAFF_ID = '<?= htmlspecialchars($staffId, ENT_QUOTES, 'UTF-8') ?>';
    const BASE_URL_JS = '<?= BASE_URL ?>';

    // Initialize Bootstrap modals
    const cashPaymentModal = new bootstrap.Modal(document.getElementById('cashPaymentModal'));
    const transferPaymentModal = new bootstrap.Modal(document.getElementById('transferPaymentModal'));
    const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
    const processingModal = new bootstrap.Modal(document.getElementById('processingModal'));

    // Helper Functions
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const price = parseInt(row.querySelector('.item-price').dataset.price) || 0;
            const quantity = parseInt(row.querySelector('.item-quantity').value) || 0;
            const subtotal = price * quantity;
            row.querySelector('.item-subtotal').textContent = formatCurrency(subtotal);
            total += subtotal;
        });
        return total;
    }

    function updateAllPriceDisplays(total) {
        // Cập nhật tất cả các trường hiển thị giá trị
        // Tổng cộng trong bảng
        document.querySelector('tfoot .text-danger.fw-bold').textContent = formatCurrency(total);
        
        // Tổng tiền hóa đơn trong modal thanh toán tiền mặt
        document.getElementById('cash_total_amount_hidden').value = total;
        document.getElementById('cash_total_display').value = formatCurrency(total);
        
        // Cập nhật giá trị min cho input số tiền khách đưa
        document.getElementById('cash_received').min = total;
        
        // Số tiền trong modal thanh toán chuyển khoản
        document.getElementById('transfer_total_amount_hidden').value = total;
        const transferAmountDisplay = document.querySelector('.modal-body .text-danger.fw-bold.fs-5');
        if (transferAmountDisplay) {
            transferAmountDisplay.textContent = formatCurrency(total);
        }
    }

    // Cart Update Handler
    function handleQuantityUpdate(itemId, newQuantity) {
        fetch(`${BASE_URL_JS}/order/addToCartBulk`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                cart: [{
                    id: itemId,
                    quantity: newQuantity
                }]
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newTotal = calculateTotal();
                updateAllPriceDisplays(newTotal);
            } else {
                throw new Error(data.message || 'Không thể cập nhật giỏ hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message);
            location.reload();
        });
    }

    // Event Listeners for Quantity Controls
    document.querySelectorAll('.decrease-quantity, .increase-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const input = document.querySelector(`.item-quantity[data-id="${itemId}"]`);
            const currentValue = parseInt(input.value);
            const newValue = this.classList.contains('decrease-quantity') ? 
                            Math.max(1, currentValue - 1) : currentValue + 1;
            
            if (newValue !== currentValue) {
                input.value = newValue;
                handleQuantityUpdate(itemId, newValue);
            }
        });
    });

    // Manual quantity input handler
    document.querySelectorAll('.item-quantity').forEach(input => {
        input.addEventListener('change', function() {
            const value = Math.max(1, parseInt(this.value) || 1);
            this.value = value;
            handleQuantityUpdate(this.dataset.id, value);
        });
    });

    // Remove item handler
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa món này khỏi giỏ hàng?')) {
                const itemId = this.dataset.id;
                this.closest('tr').remove();
                handleQuantityUpdate(itemId, 0);
                
                if (document.querySelectorAll('tbody tr').length === 0) {
                    location.reload();
                }
            }
        });
    });

    // Simplified cash payment handling
    $('#cash-payment-btn').click(function() {
        const currentTotal = calculateTotal();
        $('#cash_total_amount_hidden').val(currentTotal);
        $('#cash_total_display').val(formatCurrency(currentTotal));
        cashPaymentModal.show();
    });

    $('#cashPaymentForm').submit(function(e) {
        e.preventDefault();
        const cashReceived = parseInt($('#cash_received').val()) || 0;
        const totalAmount = parseInt($('#cash_total_amount_hidden').val()) || 0;
        
        if (cashReceived < totalAmount) {
            alert('Số tiền khách đưa phải lớn hơn hoặc bằng tổng tiền');
            return;
        }
        
        cashPaymentModal.hide();
        processingModal.show();
        
        $.ajax({
            url: `${BASE_URL_JS}/order/process`,
            method: 'POST',
            data: {
                payment_method: 'cash',
                cash_received: cashReceived
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        throw new Error('Invalid response format');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Có lỗi xảy ra khi xử lý thanh toán');
                    processingModal.hide();
                }
            },
            error: function(xhr) {
                try {
                    const error = JSON.parse(xhr.responseText);
                    alert(error.message || 'Có lỗi xảy ra khi xử lý thanh toán');
                } catch (e) {
                    alert('Có lỗi xảy ra khi xử lý thanh toán');
                }
                processingModal.hide();
            }
        });
    });

    $('#transferPaymentForm').submit(function(e) {
        e.preventDefault();
        transferPaymentModal.hide();
        processingModal.show();
        
        $.ajax({
            url: `${BASE_URL_JS}/order/process`,
            method: 'POST',
            data: {
                payment_method: 'transfer'
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        throw new Error('Invalid response format');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Có lỗi xảy ra khi xử lý thanh toán');
                    processingModal.hide();
                }
            },
            error: function(xhr) {
                try {
                    const error = JSON.parse(xhr.responseText);
                    alert(error.message || 'Có lỗi xảy ra khi xử lý thanh toán');
                } catch (e) {
                    alert('Có lỗi xảy ra khi xử lý thanh toán');
                }
                processingModal.hide();
            }
        });
    });

    // Add transfer payment button handler
    $('#transfer-payment-btn').click(function() {
        transferPaymentModal.show();
    });

    // Update transfer payment form handler
    $('#transferPaymentForm').submit(function(e) {
        e.preventDefault();
        transferPaymentModal.hide();
        processingModal.show();
        
        $.ajax({
            url: `${BASE_URL_JS}/order/process`,
            method: 'POST',
            data: {
                payment_method: 'transfer'
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        throw new Error('Invalid response format');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Có lỗi xảy ra khi xử lý thanh toán');
                    processingModal.hide();
                }
            },
            error: function(xhr) {
                try {
                    const error = JSON.parse(xhr.responseText);
                    alert(error.message || 'Có lỗi xảy ra khi xử lý thanh toán');
                } catch (e) {
                    alert('Có lỗi xảy ra khi xử lý thanh toán');
                }
                processingModal.hide();
            }
        });
    });
});
</script>

<style>
    /* Optional: Improve receipt appearance */
    .receipt-table th, .receipt-table td {
        border: none !important;
        padding: 1px 0; /* Minimal padding */
        vertical-align: top;
    }
    .receipt-table thead th {
        border-bottom: 1px dashed #ccc !important;
        font-weight: normal;
        font-size: 0.85rem;
    }
    .receipt-totals span {
        font-size: 0.9rem;
    }
</style>