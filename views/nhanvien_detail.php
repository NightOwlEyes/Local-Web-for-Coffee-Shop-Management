<?php require_once 'layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Chi tiết nhân viên</h4>
                    <div>
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <a href="<?= BASE_URL ?>/nhanvien/delete?id=<?= $staff['id'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này không? Hành động này không thể hoàn tác.');">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/nhanvien/edit?id=<?= $staff['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <a href="<?= BASE_URL ?>/nhanvien" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <?php if ($staff['image']): ?>
                                <img src="<?= BASE_URL ?>/uploads/staff/<?= $staff['image'] ?>" 
                                     class="img-thumbnail mb-3" 
                                     style="max-width: 200px;">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/200x200?text=No+Image" 
                                     class="img-thumbnail mb-3" 
                                     style="max-width: 200px;">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <table class="table">
                                <tr>
                                    <th style="width: 30%">Mã nhân viên:</th>
                                    <td><?= htmlspecialchars($staff['staff_id']) ?></td>
                                </tr>
                                <tr>
                                    <th>Tên đăng nhập:</th>
                                    <td><?= htmlspecialchars($staff['username']) ?></td>
                                </tr>
                                <tr>
                                    <th>Họ và tên:</th>
                                    <td><?= htmlspecialchars($staff['name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Ngày sinh:</th>
                                    <td><?= date('d/m/Y', strtotime($staff['dob'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại:</th>
                                    <td><?= htmlspecialchars($staff['phone']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?= htmlspecialchars($staff['email']) ?></td>
                                </tr>
                                <tr>
                                    <th>Số CCCD:</th>
                                    <td><?= htmlspecialchars($staff['id_card']) ?></td>
                                </tr>
                                <tr>
                                    <th>Ngày vào làm:</th>
                                    <td><?= date('d/m/Y', strtotime($staff['hire_date'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>