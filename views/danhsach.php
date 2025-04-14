<div class="row">
    <div class="col-12 mb-4">
        <h2>Menu <?= APP_NAME ?></h2>
        <p>
            <?php if ($userRole === 'admin'): ?>
                <span class="badge bg-danger">Admin</span> <?= $username ?>
            <?php else: ?>
                <span class="badge bg-primary">Nhân viên</span> <?= $staffName ?> (<?= $staffId ?>)
            <?php endif; ?>
        </p>
    </div>
</div>


<div class="row mb-4">
    <div class="col-md-8">
        <?php if ($userRole === 'admin'): ?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                <i class="fas fa-plus"></i> Thêm món
            </button>
        <?php endif; ?>
        
        <?php if ($userRole !== 'admin'): ?>
            <div></div>
        <?php endif; ?>
    </div>
    <div class="col-md-4">
        <form action="<?= BASE_URL ?>/danhsach" method="GET" class="input-group">
            <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Tìm kiếm món...">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> Tìm
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cập nhật số lượng giỏ hàng khi tải trang
    fetch('<?= BASE_URL ?>/order/getCartCount')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cartCount').textContent = data.count || 0;
        });

    // Phần còn lại của mã script hiện tại...
    function performSearch() {
        const searchTerm = document.getElementById('searchInput').value;
        
        fetch(`${BASE_URL}/menu/search?term=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                // Xóa các món ăn hiện tại
                const menuContainer = document.getElementById('menuContainer') || document.getElementById('staffMenuContainer');
                if (!menuContainer) return;
                
                menuContainer.innerHTML = '';
                
                // Tạo HTML cho kết quả tìm kiếm
                data.forEach(item => {
                    const itemHtml = createMenuItemHtml(item);
                    menuContainer.innerHTML += itemHtml;
                });
            })
            .catch(error => console.error('Lỗi:', error));
    }

    function createMenuItemHtml(item) {
        const userRole = '<?= $userRole ?>';
        const imageUrl = item.image 
            ? '<?= BASE_URL ?>/uploads/menu/' + item.image 
            : 'https://via.placeholder.com/300x200?text=No+Image';
        
        if (userRole === 'admin') {
            return `
                <div class="col-md-3 mb-4 menu-item-card">
                    <div class="card menu-item">
                        <img src="${imageUrl}" class="card-img-top menu-image" alt="${item.name}">
                        <div class="card-body">
                            <h5 class="card-title">${item.name}</h5>
                            <p class="card-text text-danger fw-bold">${Number(item.price).toLocaleString()} VNĐ</p>
                            <p class="card-text small">${item.description || ''}</p>
                            <a href="<?= BASE_URL ?>/menu/edit?id=${item.id}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <form action="<?= BASE_URL ?>/menu/delete" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa món này?')">
                                <input type="hidden" name="id" value="${item.id}">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>`;
        } else {
            return `
                <div class="col-md-3 mb-4 menu-item-card">
                    <div class="card menu-item">
                        <img src="${imageUrl}" class="card-img-top menu-image" alt="${item.name}">
                        <div class="card-body">
                            <h5 class="card-title">${item.name}</h5>
                            <p class="card-text text-danger fw-bold">${Number(item.price).toLocaleString()} VNĐ</p>
                            <div class="form-check">
                                <input class="form-check-input menu-select" type="checkbox" value="${item.id}" id="menu_${item.id}" data-name="${item.name}" data-price="${item.price}">
                                <label class="form-check-label" for="menu_${item.id}">
                                    Chọn món
                                </label>
                            </div>
                        </div>
                    </div>
                </div>`;
        }
    }

    // Thêm sự kiện cho phím Enter trong ô tìm kiếm
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
});
</script>

<?php if ($userRole === 'admin'): ?>
<!-- Giao diện Admin -->

<div class="row" id="menuContainer">
    <?php foreach ($menuItems as $item): ?>
    <div class="col-md-3 mb-4 menu-item-card" data-name="<?= strtolower(htmlspecialchars($item['name'])) ?>">
        <div class="card menu-item">
            <img src="<?= BASE_URL ?>/<?= !empty($item['image']) ? 'uploads/menu/' . htmlspecialchars($item['image']) : 'https://via.placeholder.com/300x200?text=No+Image' ?>" class="card-img-top menu-image" alt="<?= htmlspecialchars($item['name']) ?>">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                <p class="card-text text-danger fw-bold"><?= number_format($item['price']) ?> VNĐ</p>
                <p class="card-text small"><?= htmlspecialchars($item['description']) ?></p>
                <a href="<?= BASE_URL ?>/menu/edit?id=<?= $item['id'] ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <form action="<?= BASE_URL ?>/menu/delete" method="POST" class="d-inline" 
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa món này?')">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal Thêm Món -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMenuModalLabel">Thêm món</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addMenuForm" action="<?= BASE_URL ?>/menu/add" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên món</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Giá (VNĐ)</label>
                        <input type="number" class="form-control" id="price" name="price" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<!-- Giao diện Nhân viên -->
<div class="row">
    <div class="col-md-12" id="staffMenuContainer">
        <div class="row">
            <?php foreach ($menuItems as $item): ?>
            <div class="col-md-3 mb-4 menu-item-card" data-name="<?= strtolower(htmlspecialchars($item['name'])) ?>">
                <div class="card menu-item h-100">
                    <img src="<?= !empty($item['image']) ? BASE_URL . '/uploads/menu/' . htmlspecialchars($item['image']) : 'https://via.placeholder.com/300x200?text=No+Image' ?>" 
                         class="card-img-top menu-image" 
                         alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                        <p class="card-text text-danger fw-bold"><?= number_format($item['price']) ?> VNĐ</p>
                        <button type="button" class="btn btn-primary w-100 mt-auto add-to-cart" 
                                data-id="<?= $item['id'] ?>"
                                data-name="<?= htmlspecialchars($item['name']) ?>"
                                data-price="<?= $item['price'] ?>">
                            <i class="fas fa-plus"></i> Thêm vào hóa đơn
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal Số Lượng -->
<div class="modal fade" id="quantityModal" tabindex="-1" aria-labelledby="quantityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quantityModalLabel">Chọn số lượng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 id="modalItemName" class="text-center mb-3"></h6>
                <div class="input-group mb-3">
                    <button class="btn btn-outline-secondary" type="button" id="decreaseQuantity">-</button>
                    <input type="number" class="form-control text-center" id="itemQuantity" value="1" min="1" step="1">
                    <button class="btn btn-outline-secondary" type="button" id="increaseQuantity">+</button>
                </div>
                <input type="hidden" id="modalItemId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmAddToCart">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo modal
    const quantityModal = new bootstrap.Modal(document.getElementById('quantityModal'));
    
    // Lấy tất cả các nút "Thêm vào hóa đơn" và thêm sự kiện click
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            
            document.getElementById('modalItemId').value = itemId;
            document.getElementById('modalItemName').textContent = itemName;
            document.getElementById('itemQuantity').value = 1;
            
            quantityModal.show();
        });
    });

    // Điều khiển số lượng
    document.getElementById('decreaseQuantity').addEventListener('click', function() {
        const input = document.getElementById('itemQuantity');
        const value = parseInt(input.value);
        if (value > 1) input.value = value - 1;
    });

    document.getElementById('increaseQuantity').addEventListener('click', function() {
        const input = document.getElementById('itemQuantity');
        const value = parseInt(input.value);
        input.value = value + 1;
    });

    // Xử lý kiểm tra dữ liệu nhập vào số lượng
    document.getElementById('itemQuantity').addEventListener('input', function() {
        let value = this.value.replace(/[^0-9]/g, '');
        this.value = value ? Math.max(1, parseInt(value)) : 1;
    });

    // Xử lý gửi form
    document.getElementById('confirmAddToCart').addEventListener('click', function() {
        const itemId = document.getElementById('modalItemId').value;
        const quantity = parseInt(document.getElementById('itemQuantity').value);

        if (!itemId || isNaN(quantity) || quantity < 1) {
            alert('Dữ liệu không hợp lệ');
            return;
        }

        this.disabled = true;
        
        fetch('<?= BASE_URL ?>/order/addToCartBulk', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cart: [{
                    id: itemId,
                    quantity: quantity
                }]
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật số lượng giỏ hàng
                fetch('<?= BASE_URL ?>/order/getCartCount')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('cartCount').textContent = data.count || 0;
                    });
                
                quantityModal.hide();
            } else {
                alert(data.message || 'Không thể thêm vào giỏ hàng');
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Lỗi khi thêm vào giỏ hàng');
        })
        .finally(() => {
            this.disabled = false;
        });
    });
});
</script>
<?php endif; ?>