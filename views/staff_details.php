// ...existing code...
<div class="card">
    <div class="card-body">
        // ...existing staff details...
        
        <div class="mt-4">
            <a href="<?= BASE_URL ?>/staff" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="<?= BASE_URL ?>/staff/delete?id=<?= htmlspecialchars($staff['staff_id']) ?>" 
               class="btn btn-danger"
               onclick="return confirm('Bạn có chắc chắn muốn xóa nhân viên này không?');">
                <i class="fas fa-trash"></i> Xóa nhân viên
            </a>
        </div>
    </div>
</div>
// ...existing code...