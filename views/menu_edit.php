<?php require_once 'layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Chỉnh sửa món: <?= htmlspecialchars($menuItem['name']) ?></h4>
                    <a href="<?= BASE_URL ?>/danhsach" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại Menu
                    </a>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/menu/edit" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $menuItem['id'] ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên món</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($menuItem['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Giá (VNĐ)</label>
                            <input type="text" class="form-control" id="price" name="price" value="<?= htmlspecialchars($menuItem['price']) ?>" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($menuItem['description']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <?php if ($menuItem['image']): ?>
                                <div class="mt-2">
                                    <p class="mb-1 small">Ảnh hiện tại:</p>
                                    <img src="<?= BASE_URL ?>/uploads/menu/<?= htmlspecialchars($menuItem['image']) ?>"
                                         class="img-thumbnail"
                                         style="max-height: 100px;">
                                </div>
                            <?php else: ?>
                                <p class="mt-2 mb-1 small">Chưa có ảnh.</p>
                            <?php endif; ?>
                        </div>

                        <div class="text-end">
                            <a href="<?= BASE_URL ?>/danhsach" class="btn btn-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
