<div class="row">
    <div class="col-12 mb-4">
        <h2>Quản lý nhân viên</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStaffModal">
            <i class="fas fa-plus"></i> Thêm nhân viên
        </button>
    </div>
</div>

<div class="row">
    <?php if (empty($staffList)): ?>
    <div class="col-12">
        <div class="alert alert-info">
            Chưa có nhân viên nào. Hãy thêm nhân viên mới.
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($staffList as $staff): ?>
    <div class="col-md-6 mb-4">
        <div class="card staff-card">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?= BASE_URL ?>/<?= !empty($staff['image']) ? 'uploads/staff/' . $staff['image'] : 'https://via.placeholder.com/300x300?text=No+Image' ?>" class="img-fluid staff-image rounded-start" alt="<?= $staff['name'] ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($staff['name']) ?></h5>
                        <p class="card-text">
                            <span class="badge bg-primary"><?= $staff['staff_id'] ?></span>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-phone me-2"></i><?= $staff['phone'] ?><br>
                                <i class="fas fa-envelope me-2"></i><?= $staff['email'] ?>
                            </small>
                        </p>
                        <div class="mt-3">
                            <a href="<?= BASE_URL ?>/nhanvien/detail?id=<?= $staff['id'] ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStaffModalLabel">Thêm nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Use standard form submission -->
                <form id="addStaffForm" action="<?= BASE_URL ?>/nhanvien/add" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Họ tên nhân viên</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dob" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_card" class="form-label">Số căn cước công dân</label>
                            <input type="text" class="form-control" id="id_card" name="id_card" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hire_date" class="form-label">Ngày vào làm</label>
                            <input type="date" class="form-control" id="hire_date" name="hire_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Hình ảnh nhân viên</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
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

<!-- View Staff Modal -->
<div class="modal fade" id="viewStaffModal" tabindex="-1" aria-labelledby="viewStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewStaffModalLabel">Chi tiết nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <img id="staff_image" src="/placeholder.svg" class="img-fluid rounded mb-3" alt="Staff Image" style="max-height: 200px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <h4 id="staff_name"></h4>
                        <p><strong>Mã nhân viên:</strong> <span id="staff_id" class="badge bg-primary"></span></p>
                        <p><strong>Tên đăng nhập:</strong> <span id="staff_username" class="fw-bold"></span></p>
                        <p><small class="text-muted">Mật khẩu được tạo tự động và không thể xem lại. Yêu cầu nhân viên đổi mật khẩu hoặc liên hệ admin để đặt lại.</small></p>
                        <hr>
                        <p><strong>Ngày sinh:</strong> <span id="staff_dob"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="staff_phone"></span></p>
                        <p><strong>Email:</strong> <span id="staff_email"></span></p>
                        <p><strong>Số CCCD:</strong> <span id="staff_id_card"></span></p>
                        <p><strong>Ngày vào làm:</strong> <span id="staff_hire_date"></span></p>
                    </div>
                </div>
                <div class="text-end">
                    <a href="#" class="btn btn-danger delete-staff" data-id="">Xóa</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Kt xem thông tin nhân viên mới có tồn tại không và hiển thị modal
        <?php if (isset($newStaffCredentials) && $newStaffCredentials): ?>
            $('#credential_staff_id').val('<?= $newStaffCredentials['staff_id'] ?>');
            $('#credential_username').val('<?= $newStaffCredentials['username'] ?>');
            $('#credential_password').val('<?= $newStaffCredentials['password'] ?>');
            var credentialsModal = new bootstrap.Modal(document.getElementById('staffCredentialsModal'));
            credentialsModal.show();
        <?php endif; ?>

        // Xử lý sự kiện click nút chi tiết
        $('.view-staff').on('click', function(e) {
            e.preventDefault();
            const staffId = $(this).data('id');
            console.log('View staff clicked, ID:', staffId);

            // Gọi AJAX để lấy thông tin nhân viên
            $.ajax({
                url: `${BASE_URL}/nhanvien/view?id=${staffId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.error) {
                        alert(response.error);
                        return;
                    }

                    // Hiển thị thông tin trong modal
                    $('#staff_name').text(response.name || 'N/A');
                    $('#staff_id').text(response.staff_id || 'N/A');
                    $('#staff_username').text(response.username || 'N/A');
                    $('#staff_dob').text(response.dob ? new Date(response.dob).toLocaleDateString('vi-VN') : 'N/A');
                    $('#staff_phone').text(response.phone || 'N/A');
                    $('#staff_email').text(response.email || 'N/A');
                    $('#staff_id_card').text(response.id_card || 'N/A');
                    $('#staff_hire_date').text(response.hire_date ? new Date(response.hire_date).toLocaleDateString('vi-VN') : 'N/A');
                    
                    // Hiển thị ảnh
                    if (response.image) {
                        $('#staff_image').attr('src', `${BASE_URL}/uploads/staff/${response.image}`);
                    } else {
                        $('#staff_image').attr('src', 'https://via.placeholder.com/300x300?text=No+Image');
                    }

                    // Lưu ID vào các nút sửa và xóa
                    $('.delete-staff').data('id', response.id);

                    // Hiển thị modal
                    $('#viewStaffModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.log('Response:', xhr.responseText);
                    alert('Đã xảy ra lỗi khi lấy thông tin nhân viên');
                }
            });
        });

        // Xử lý sự kiện click nút xóa trong modal chi tiết
        $(document).on('click', '#viewStaffModal .delete-staff', function() {
            if (confirm('Bạn có chắc chắn muốn xóa nhân viên này? Hành động này cũng sẽ xóa tài khoản đăng nhập liên kết.')) {
                const staffId = $(this).data('id');
                window.location.href = '<?= BASE_URL ?>/nhanvien/delete?id=' + staffId;
            }
        });

    });
</script>

