<?php require_once 'views/common/layout.php'; renderHeader($currentUser, 'customers'); showFlash(); ?>

<div class="page-header">
    <div class="page-title">
        <a href="?action=customers" style="color:rgba(255,255,255,0.4);text-decoration:none"><i class="fas fa-building"></i></a>
        <i class="fas fa-chevron-right" style="font-size:0.7rem;color:rgba(255,255,255,0.2)"></i>
        <?= htmlspecialchars($customerData['name']) ?>
        <span style="font-size:0.85rem;color:rgba(255,255,255,0.4);font-weight:400">— Chi nhánh</span>
    </div>
    <button class="btn-primary-dark" data-bs-toggle="modal" data-bs-target="#branchModal" onclick="resetBranchForm()">
        <i class="fas fa-plus"></i> Thêm chi nhánh
    </button>
</div>

<div class="card-dark">
    <?php if (!empty($branches)): ?>
    <div style="overflow-x:auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên chi nhánh</th>
                    <th>Địa chỉ</th>
                    <th>Người liên hệ</th>
                    <th>Điện thoại</th>
                    <th>Lines</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($branches as $i => $b): ?>
                <tr>
                    <td class="muted"><?= $i + 1 ?></td>
                    <td class="name-cell"><?= htmlspecialchars($b['branch_name']) ?></td>
                    <td class="muted"><?= htmlspecialchars($b['address'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($b['contact_person'] ?? '—') ?></td>
                    <td class="mono"><?= htmlspecialchars($b['phone'] ?? '—') ?></td>
                    <td><span class="mono"><?= $b['line_count'] ?></span></td>
                    <td>
                        <div class="actions-cell">
                            <button class="btn-sm-icon edit" onclick="editBranch(<?= htmlspecialchars(json_encode($b)) ?>)" title="Sửa">
                                <i class="fas fa-pen"></i>
                            </button>
                            <a href="?action=delete_branch&id=<?= $b['id'] ?>" class="btn-sm-icon delete" onclick="return confirm('Xóa chi nhánh này?')" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-sitemap"></i>
        <p>Chưa có chi nhánh nào</p>
    </div>
    <?php endif; ?>
</div>

<!-- Branch Modal -->
<div class="modal fade" id="branchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="branchModalTitle">Thêm chi nhánh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?action=save_branch">
                <div class="modal-body">
                    <input type="hidden" name="id" id="branchId">
                    <input type="hidden" name="customer_id" value="<?= $customerData['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Tên chi nhánh *</label>
                        <input type="text" class="form-control" name="branch_name" id="branchName" required placeholder="VD: Chi nhánh Quận 1, VP Hà Nội...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" name="address" id="branchAddress" placeholder="Số nhà, đường, quận/huyện, tỉnh/TP">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Người liên hệ</label>
                            <input type="text" class="form-control" name="contact_person" id="branchContact">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Điện thoại</label>
                            <input type="text" class="form-control" name="phone" id="branchPhone">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-dark" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn-primary-dark">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetBranchForm() {
    document.getElementById('branchModalTitle').textContent = 'Thêm chi nhánh';
    document.getElementById('branchId').value = '';
    document.getElementById('branchName').value = '';
    document.getElementById('branchAddress').value = '';
    document.getElementById('branchContact').value = '';
    document.getElementById('branchPhone').value = '';
}

function editBranch(b) {
    document.getElementById('branchModalTitle').textContent = 'Sửa chi nhánh';
    document.getElementById('branchId').value = b.id;
    document.getElementById('branchName').value = b.branch_name;
    document.getElementById('branchAddress').value = b.address || '';
    document.getElementById('branchContact').value = b.contact_person || '';
    document.getElementById('branchPhone').value = b.phone || '';
    new bootstrap.Modal(document.getElementById('branchModal')).show();
}
</script>

<?php renderFooter(); ?>
