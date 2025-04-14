<?php require_once 'layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Chỉnh sửa nhân viên: <?= htmlspecialchars($staff['name']) ?></h4>
                    <a href="<?= BASE_URL ?>/nhanvien/detail?id=<?= $staff['id'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại chi tiết
                    </a>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/nhanvien/edit" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $staff['id'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ tên nhân viên</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($staff['name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dob" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($staff['dob']) ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($staff['phone']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_card" class="form-label">Số căn cước công dân</label>
                                <input type="text" class="form-control" id="id_card" name="id_card" value="<?= htmlspecialchars($staff['id_card']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="hire_date" class="form-label">Ngày vào làm</label>
                                <input type="date" class="form-control" id="hire_date" name="hire_date" value="<?= htmlspecialchars($staff['hire_date']) ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu mới (Để trống nếu không đổi)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu mới">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Hình ảnh nhân viên</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <?php if ($staff['image']): ?>
                                    <div class="mt-2">
                                        <p class="mb-1 small">Ảnh hiện tại:</p>
                                        <img src="<?= BASE_URL ?>/uploads/staff/<?= htmlspecialchars($staff['image']) ?>"
                                             class="img-thumbnail"
                                             style="max-height: 100px;">
                                    </div>
                                <?php else: ?>
                                    <p class="mt-2 mb-1 small">Chưa có ảnh.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="<?= BASE_URL ?>/nhanvien/detail?id=<?= $staff['id'] ?>" class="btn btn-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
