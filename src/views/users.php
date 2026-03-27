<?php require_once 'views/common/layout.php'; renderHeader($currentUser, $user, 'users'); showFlash(); ?>

<div class="page-header">
    <div class="page-title"><i class="fas fa-users-cog"></i> Quản lý Users</div>
    <button class="btn-primary-dark" onclick="showAddModal()">
        <i class="fas fa-user-plus"></i> Thêm User
    </button>
</div>

<div class="card-dark" style="overflow-x:auto">
    <table class="tbl" style="min-width:800px">
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Username</th>
                <th>Tên</th>
                <th>Email</th>
                <th style="width:120px">Vai trò</th>
                <th style="width:150px">Đăng nhập cuối</th>
                <th style="width:90px">Trạng thái</th>
                <th style="width:80px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users_list as $i => $u): ?>
            <tr style="<?= !$u['active'] ? 'opacity:0.4' : '' ?>">
                <td><?= $i + 1 ?></td>
                <td class="mono" style="color:#67e8f9"><?= htmlspecialchars($u['username']) ?></td>
                <td class="name-cell"><?= htmlspecialchars($u['name']) ?></td>
                <td class="muted"><?= htmlspecialchars($u['email'] ?? '—') ?></td>
                <td>
                    <?php if ($u['role'] === 'admin'): ?>
                        <span class="badge-status up"><span class="dot"></span>Admin</span>
                    <?php elseif ($u['role'] === 'viewer'): ?>
                        <span class="badge-status warning"><span class="dot"></span>Viewer</span>
                    <?php else: ?>
                        <span class="badge-status unknown"><span class="dot"></span>User</span>
                    <?php endif; ?>
                </td>
                <td class="muted">
                    <?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : '—' ?>
                </td>
                <td>
                    <?php if ($u['active']): ?>
                        <span style="color:#4ade80;font-size:0.78rem"><i class="fas fa-check-circle"></i> Active</span>
                    <?php else: ?>
                        <span style="color:#f87171;font-size:0.78rem"><i class="fas fa-ban"></i> Disabled</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <div style="display:flex;gap:6px;justify-content:center">
                            <button style="background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.25);color:#67e8f9;border-radius:6px;padding:5px 8px;cursor:pointer;font-size:0.75rem" onclick='editUser(<?= json_encode($u) ?>)' title="Sửa">
                                <i class="fas fa-pen"></i>
                            </button>
                            <?php if ($u['active']): ?>
                                <a href="?action=toggle_user&id=<?= $u['id'] ?>&active=0" style="background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.25);color:#f87171;border-radius:6px;padding:5px 8px;font-size:0.75rem;text-decoration:none;display:inline-flex;align-items:center" title="Disable" onclick="return confirm('Vô hiệu hóa user này?')">
                                    <i class="fas fa-user-slash"></i>
                                </a>
                            <?php else: ?>
                                <a href="?action=toggle_user&id=<?= $u['id'] ?>&active=1" style="background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.25);color:#4ade80;border-radius:6px;padding:5px 8px;font-size:0.75rem;text-decoration:none;display:inline-flex;align-items:center" title="Enable">
                                    <i class="fas fa-user-check"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span style="font-size:0.7rem;color:rgba(255,255,255,0.25)">Bạn</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add/Edit Modal -->
<div id="userModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);z-index:1000;align-items:center;justify-content:center">
    <div class="card-dark" style="width:500px;max-width:90vw;margin:auto;position:relative">
        <div class="card-header-dark" style="display:flex;justify-content:space-between;align-items:center">
            <h2 id="modalTitle"><i class="fas fa-user-plus"></i> Thêm User</h2>
            <button onclick="closeModal()" style="background:none;border:none;color:rgba(255,255,255,0.5);cursor:pointer;font-size:1.2rem">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div style="padding:20px">
            <form method="POST" action="?action=save_user" id="userForm">
                <input type="hidden" name="id" id="userId">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="userUsername" required minlength="3">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên hiển thị</label>
                    <input type="text" class="form-control" name="name" id="userName" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="userEmail">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu <small id="passHint" style="color:rgba(255,255,255,0.3)"></small></label>
                    <input type="password" class="form-control" name="password" id="userPassword" minlength="6">
                </div>
                <div class="mb-3">
                    <label class="form-label">Vai trò chính</label>
                    <select class="form-select" name="role" id="userRole" onchange="autoSelectPermissions(this.value)">
                        <option value="viewer">Viewer — Chỉ xem Dashboard, Lines, KH</option>
                        <option value="user">User — Xem + Quản lý Lines/KH</option>
                        <option value="admin">Admin — Full quyền</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Quyền chi tiết</label>
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:8px;padding:12px;display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <?php foreach ($all_permissions as $p): ?>
                        <label style="display:flex;align-items:center;gap:8px;font-size:0.8rem;cursor:pointer">
                            <input type="checkbox" name="permissions[]" value="<?= $p['id'] ?>" class="perm-check" data-name="<?= $p['name'] ?>">
                            <span><?= htmlspecialchars($p['description'] ?: $p['name']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px">
                    <button type="button" class="btn-secondary-dark" onclick="closeModal()">Hủy</button>
                    <button type="submit" class="btn-primary-dark" style="padding:10px 28px">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showAddModal() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus"></i> Thêm User';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('userUsername').readOnly = false;
    document.getElementById('userPassword').required = true;
    document.getElementById('passHint').textContent = '(bắt buộc)';
    document.getElementById('userModal').style.display = 'flex';
}

function editUser(u) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit"></i> Sửa User';
    document.getElementById('userId').value = u.id;
    document.getElementById('userUsername').value = u.username;
    document.getElementById('userUsername').readOnly = true;
    document.getElementById('userName').value = u.name;
    document.getElementById('userEmail').value = u.email || '';
    document.getElementById('userRole').value = u.role;
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = false;
    document.getElementById('passHint').textContent = '(để trống nếu không đổi)';
    
    // Set permissions
    const perms = u.permissions || [];
    document.querySelectorAll('.perm-check').forEach(cb => {
        cb.checked = perms.includes(parseInt(cb.value));
    });

    document.getElementById('userModal').style.display = 'flex';
}

function autoSelectPermissions(role) {
    const checks = document.querySelectorAll('.perm-check');
    checks.forEach(cb => {
        const name = cb.dataset.name;
        if (role === 'admin') {
            cb.checked = true;
        } else if (role === 'user') {
            cb.checked = ['view_dashboard', 'manage_lines', 'manage_customers', 'view_reports'].includes(name);
        } else if (role === 'viewer') {
            cb.checked = ['view_dashboard', 'view_reports'].includes(name);
        }
    });
}

function closeModal() {
    document.getElementById('userModal').style.display = 'none';
}

document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

<?php renderFooter(); ?>
