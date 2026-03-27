<?php require_once 'views/common/layout.php'; renderHeader($currentUser, $user, 'customers'); showFlash(); ?>

<div class="page-header">
    <div class="page-title"><i class="fas fa-building"></i> Quản lý Khách hàng</div>
    <?php if ($user->hasPermission($currentUser['id'], 'manage_customers')): ?>
    <button class="btn-primary-dark" data-bs-toggle="modal" data-bs-target="#customerModal" onclick="resetForm()">
        <i class="fas fa-plus"></i> Thêm khách hàng
    </button>
    <?php endif; ?>
</div>

<div class="card-dark">
    <?php if (!empty($customers_list)): ?>
    <div style="overflow-x:auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên khách hàng</th>
                    <th>Người liên hệ</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Chi nhánh</th>
                    <th>Lines</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers_list as $i => $c): ?>
                <tr id="cr-<?= $c['id'] ?>">
                    <td class="muted"><?= $i + 1 ?></td>
                    <td class="name-cell"><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['contact_person'] ?? '—') ?></td>
                    <td class="mono"><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
                    <td class="muted"><?= htmlspecialchars($c['email'] ?? '—') ?></td>
                    <td>
                        <button class="branch-toggle" onclick="toggleBranches(<?= $c['id'] ?>, this)" title="Xem chi nhánh">
                            <i class="fas fa-sitemap"></i>
                            <span class="branch-count"><?= $c['branch_count'] ?></span>
                            <i class="fas fa-chevron-down toggle-arrow"></i>
                        </button>
                    </td>
                    <td><span class="mono"><?= $c['line_count'] ?></span></td>
                    <?php if ($user->hasPermission($currentUser['id'], 'manage_customers')): ?>
                    <td>
                        <div class="actions-cell">
                            <button class="btn-sm-icon edit" onclick="editCustomer(<?= htmlspecialchars(json_encode($c)) ?>)" title="Sửa">
                                <i class="fas fa-pen"></i>
                            </button>
                            <a href="?action=delete_customer&id=<?= $c['id'] ?>" class="btn-sm-icon delete" onclick="return confirm('Xóa khách hàng này?')" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                    <?php else: ?>
                    <td></td>
                    <?php endif; ?>
                </tr>
                <!-- Branch sub-row (hidden by default) -->
                <tr id="br-<?= $c['id'] ?>" class="branch-row" style="display:none">
                    <td colspan="8" style="padding:0">
                        <div class="branch-panel" id="bp-<?= $c['id'] ?>">
                            <div class="branch-loading"><i class="fas fa-spinner spin"></i> Đang tải...</div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-building"></i>
        <p>Chưa có khách hàng nào</p>
    </div>
    <?php endif; ?>
</div>

<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Thêm khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?action=save_customer">
                <div class="modal-body">
                    <input type="hidden" name="id" id="custId">
                    <div class="mb-3">
                        <label class="form-label">Tên khách hàng *</label>
                        <input type="text" class="form-control" name="name" id="custName" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Người liên hệ</label>
                            <input type="text" class="form-control" name="contact_person" id="custContact">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Điện thoại</label>
                            <input type="text" class="form-control" name="phone" id="custPhone">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="custEmail">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="notes" id="custNotes" rows="2"></textarea>
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

<style>
.branch-toggle {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(6,182,212,0.08); border: 1px solid rgba(6,182,212,0.2);
    color: #67e8f9; padding: 4px 10px; border-radius: 6px;
    cursor: pointer; font-size: 0.78rem; transition: all 0.2s;
    font-family: 'Inter', sans-serif;
}
.branch-toggle:hover { background: rgba(6,182,212,0.15); border-color: rgba(6,182,212,0.4); }
.branch-toggle.active { background: rgba(6,182,212,0.18); border-color: #06b6d4; }
.branch-toggle .toggle-arrow { font-size: 0.6rem; transition: transform 0.2s; color: rgba(255,255,255,0.3); }
.branch-toggle.active .toggle-arrow { transform: rotate(180deg); }
.branch-count { font-weight: 600; }

.branch-panel {
    background: rgba(6,182,212,0.04);
    border-left: 3px solid rgba(6,182,212,0.3);
    margin: 0; padding: 12px 20px;
}
.branch-loading { color: rgba(255,255,255,0.35); font-size: 0.8rem; padding: 8px 0; }

.branch-sub-table { width: 100%; border-collapse: collapse; }
.branch-sub-table th {
    font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;
    color: rgba(255,255,255,0.35); padding: 6px 10px; text-align: left;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.branch-sub-table td {
    padding: 8px 10px; font-size: 0.8rem; color: rgba(255,255,255,0.7);
    border-bottom: 1px solid rgba(255,255,255,0.03);
}
.branch-sub-table tr:last-child td { border-bottom: none; }
.branch-sub-table .line-badge {
    background: rgba(6,182,212,0.15); color: #67e8f9;
    padding: 2px 8px; border-radius: 10px; font-size: 0.72rem; font-weight: 600;
}
.branch-empty { color: rgba(255,255,255,0.25); font-size: 0.8rem; font-style: italic; }
</style>

<script>
const branchCache = {};

function toggleBranches(customerId, btn) {
    const row = document.getElementById('br-' + customerId);
    const isVisible = row.style.display !== 'none';

    if (isVisible) {
        row.style.display = 'none';
        btn.classList.remove('active');
        return;
    }

    row.style.display = '';
    btn.classList.add('active');

    // Load data if not cached
    if (!branchCache[customerId]) {
        const panel = document.getElementById('bp-' + customerId);
        panel.innerHTML = '<div class="branch-loading"><i class="fas fa-spinner spin"></i> Đang tải...</div>';

        fetch('?action=api_customer_branches&customer_id=' + customerId)
            .then(r => r.json())
            .then(data => {
                branchCache[customerId] = data.branches;
                renderBranches(customerId, data.branches);
            })
            .catch(() => {
                panel.innerHTML = '<div class="branch-empty">Lỗi tải dữ liệu</div>';
            });
    }
}

function renderBranches(customerId, branches) {
    const panel = document.getElementById('bp-' + customerId);

    if (!branches || branches.length === 0) {
        panel.innerHTML = '<div class="branch-empty">Chưa có chi nhánh nào</div>';
        return;
    }

    let html = `<table class="branch-sub-table">
        <thead><tr>
            <th>Mã CH</th>
            <th>Tên chi nhánh</th>
            <th>Địa chỉ</th>
            <th>SĐT cửa hàng</th>
            <th>Số lines</th>
        </tr></thead><tbody>`;

    branches.forEach(b => {
        html += `<tr>
            <td class="mono" style="color:#67e8f9">${escHtml(b.store_code || '—')}</td>
            <td><strong>${escHtml(b.branch_name)}</strong></td>
            <td style="color:rgba(255,255,255,0.4)">${escHtml(b.address || '—')}</td>
            <td style="font-family:'JetBrains Mono',monospace;font-size:0.76rem">${escHtml(b.phone || '—')}</td>
            <td><span class="line-badge">${b.line_count}</span></td>
        </tr>`;
    });

    html += '</tbody></table>';
    panel.innerHTML = html;
}

function escHtml(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}

function resetForm() {
    document.getElementById('modalTitle').textContent = 'Thêm khách hàng';
    document.getElementById('custId').value = '';
    document.getElementById('custName').value = '';
    document.getElementById('custContact').value = '';
    document.getElementById('custPhone').value = '';
    document.getElementById('custEmail').value = '';
    document.getElementById('custNotes').value = '';
}

function editCustomer(c) {
    document.getElementById('modalTitle').textContent = 'Sửa khách hàng';
    document.getElementById('custId').value = c.id;
    document.getElementById('custName').value = c.name;
    document.getElementById('custContact').value = c.contact_person || '';
    document.getElementById('custPhone').value = c.phone || '';
    document.getElementById('custEmail').value = c.email || '';
    document.getElementById('custNotes').value = c.notes || '';
    new bootstrap.Modal(document.getElementById('customerModal')).show();
}
</script>

<?php renderFooter(); ?>
