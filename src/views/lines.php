<?php require_once 'views/common/layout.php';
renderHeader($currentUser, $user, 'lines');
showFlash();

// Get customer list for filter
$filterCustomers = $pdo->query("SELECT DISTINCT c.id, c.name FROM customers c INNER JOIN ftth_lines l ON l.customer_id = c.id AND l.active = 1 ORDER BY c.name")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .app-content {
        max-width: 1800px !important;
    }
</style>

<div class="page-header">
    <div class="page-title"><i class="fas fa-network-wired"></i> Quản lý</div>
    <div style="display:flex;align-items:center;gap:10px">
        <!-- Search Mã CH -->
        <div style="position:relative">
            <i class="fas fa-search"
                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.25);font-size:0.72rem"></i>
            <input type="text" id="searchStoreCode" placeholder="Tìm Mã CH..." oninput="filterTable()"
                style="background:#1e293b;border:1px solid rgba(255,255,255,0.1);color:#e2e8f0;padding:6px 10px 6px 30px;border-radius:6px;font-size:0.8rem;width:130px;font-family:'JetBrains Mono',monospace">
        </div>
        <!-- Customer filter -->
        <select id="customerFilter" onchange="filterTable()"
            style="background:#1e293b;border:1px solid rgba(255,255,255,0.1);color:#e2e8f0;padding:6px 10px;border-radius:6px;font-size:0.8rem;cursor:pointer;font-family:'Inter',sans-serif;min-width:150px">
            <option value="">Khách Hàng</option>
            <?php foreach ($filterCustomers as $fc): ?>
                <option value="<?= $fc['id'] ?>"><?= htmlspecialchars($fc['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if ($user->hasPermission($currentUser['id'], 'manage_lines')): ?>
            <button class="btn-primary-dark" data-bs-toggle="modal" data-bs-target="#lineModal" onclick="resetLineForm()">
                <i class="fas fa-plus"></i> Thêm Line
            </button>
        <?php endif; ?>
    </div>
</div>

<div class="card-dark" style="padding:0">
    <?php if (!empty($lines)): ?>
        <div style="overflow-x:auto">
            <table class="tbl tbl-full" id="linesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable" onclick="sortTable('scode')" id="sort-scode">Mã Kênh <i class="fas fa-sort"
                                style="opacity:0.3;font-size:0.55rem"></i></th>
                        <th class="hide-mobile sortable" id="thKH" onclick="sortTable('customer')">Khách hàng <i
                                class="fas fa-sort" style="opacity:0.3;font-size:0.55rem"></i></th>
                        <th>Line Name</th>
                        <th class="hide-mobile">Địa chỉ</th>
                        <th class="hide-mobile">NCC</th>
                        <th class="hide-mobile">Account</th>
                        <th class="hide-mobile">IP</th>
                        <th class="sortable" onclick="sortTable('iptype')" id="sort-iptype">Loại <i class="fas fa-sort"
                                style="opacity:0.3;font-size:0.55rem"></i></th>
                        <th class="hide-mobile">SĐT</th>
                        <th class="hide-mobile">Kỹ thuật</th>
                        <th class="hide-mobile">On Net</th>
                        <th class="hide-mobile sortable active-sort" onclick="sortTable('expiry')" id="sort-expiry">Hạn dùng
                            <i class="fas fa-sort-up" style="opacity:0.6;font-size:0.55rem"></i>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="linesBody">
                    <?php foreach ($lines as $i => $line): ?>
                        <tr data-cid="<?= $line['customer_id'] ?? '' ?>"
                            data-scode="<?= htmlspecialchars(strtolower($line['store_code'] ?? '')) ?>"
                            data-customer="<?= htmlspecialchars(strtolower($line['customer_name_rel'] ?? $line['customer_name'] ?? '')) ?>"
                            data-iptype="<?= htmlspecialchars($line['ip_type'] ?? 'static') ?>"
                            data-expiry="<?= $line['expiry_date'] ?? '9999-12-31' ?>">
                            <td class="rn"><?= $i + 1 ?></td>
                            <td class="mono"><?= htmlspecialchars($line['store_code'] ?? '') ?: '—' ?></td>
                            <td class="kh-col nw hide-mobile">
                                <?= htmlspecialchars($line['customer_name_rel'] ?? $line['customer_name'] ?? '—') ?>
                            </td>
                            <td class="nw"><strong><?= htmlspecialchars($line['name']) ?></strong></td>
                            <td class="dim hide-mobile" style="min-width:140px">
                                <?= htmlspecialchars($line['branch_address'] ?? '') ?>
                            </td>
                            <td class="mono nw hide-mobile"><?= htmlspecialchars($line['provider'] ?? '') ?: '—' ?></td>
                            <td class="mono hide-mobile"><?= htmlspecialchars($line['isp_account'] ?? '') ?: '—' ?></td>
                            <td class="mono nw hide-mobile"><?= htmlspecialchars($line['ip_address'] ?? '') ?: '—' ?></td>
                            <td class="nw" style="text-align:center">
                                <?php
                                $ipType = $line['ip_type'] ?? 'static';
                                $typeColors = ['static' => '#22c55e', 'dynamic' => '#f59e0b', 'sim' => '#8b5cf6'];
                                $typeLabels = ['static' => 'Tĩnh', 'dynamic' => 'Động', 'sim' => 'SIM'];
                                $bgColor = $typeColors[$ipType] ?? '#64748b';
                                $label = $typeLabels[$ipType] ?? htmlspecialchars($ipType);
                                ?>
                                <span
                                    style="background:<?= $bgColor ?>;color:#000;padding:2px 8px;border-radius:4px;font-size:0.65rem;font-weight:600"><?= $label ?></span>
                            </td>
                            <td class="dim nw hide-mobile"><?= htmlspecialchars($line['phone'] ?? '') ?: '—' ?></td>
                            <td class="dim nw hide-mobile"><?= htmlspecialchars($line['regional_contact'] ?? '') ?: '—' ?></td>
                            <td class="dim nw hide-mobile">
                                <?= $line['on_net'] ? date('d/m/Y', strtotime($line['on_net'])) : '—' ?>
                            </td>
                            <td class="nw hide-mobile">
                                <?php if ($line['expiry_date']):
                                    $exp = strtotime($line['expiry_date']);
                                    $d = floor(($exp - time()) / 86400);
                                    $cl = $d <= 35 ? '#f87171' : ($d <= 45 ? '#fbbf24' : 'rgba(255,255,255,0.45)'); ?><span
                                        style="color:<?= $cl ?>"><?= date('d/m/Y', $exp) ?></span><?php else: ?>—<?php endif; ?>
                            </td>

                            <?php if ($user->hasPermission($currentUser['id'], 'manage_lines')): ?>
                                <td>
                                    <div style="display:flex;gap:4px">
                                        <button class="btn-sm-icon edit"
                                            onclick="editLine(<?= htmlspecialchars(json_encode($line)) ?>)" title="Sửa"><i
                                                class="fas fa-pen"></i></button>
                                        <a href="?action=delete_line&id=<?= $line['id'] ?>" class="btn-sm-icon delete"
                                            onclick="return confirm('Xóa line này?')" title="Xóa"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            <?php else: ?>
                                <td></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div id="paginationBar" style="display:none;padding:12px 16px;border-top:1px solid rgba(255,255,255,0.06);display:flex;justify-content:space-between;align-items:center">
            <span id="pageInfo" style="font-size:0.75rem;color:rgba(255,255,255,0.4)"></span>
            <div id="pageButtons" style="display:flex;gap:4px"></div>
        </div>
    <?php else: ?>
        <div class="empty-state"><i class="fas fa-network-wired"></i>
            <p>Chưa có line FTTH nào</p>
        </div>
    <?php endif; ?>
</div>

<!-- Line Modal -->
<div class="modal fade" id="lineModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lineModalTitle">Thêm FTTH Line</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?action=save_line" id="lineForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="lineId">
                    <input type="hidden" name="customer_id" id="lineCustomerId">
                    <div class="row">
                        <div class="col-md-8 mb-3" style="position:relative">
                            <label class="form-label">Khách hàng *</label>
                            <input type="text" class="form-control" id="customerSearch" required
                                placeholder="Gõ tên khách hàng để tìm..." autocomplete="off"
                                oninput="searchCustomer(this.value)" onfocus="searchCustomer(this.value)">
                            <div id="customerDropdown" class="search-dropdown" style="display:none"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mã CH</label>
                            <input type="text" class="form-control" name="store_code" id="lineStoreCode"
                                placeholder="VD: CH-001">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Store (Tên line) *</label>
                            <input type="text" class="form-control" name="name" id="lineName" required
                                placeholder="VD: Chi nhánh Quận 1">
                        </div>
                        <div class="col-md-7 mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="branch_address" id="lineBranchAddress"
                                placeholder="VD: 123 Nguyễn Huệ, P.Bến Nghé, Q.1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">NCC</label>
                            <select class="form-select" name="provider" id="lineProvider">
                                <option value="">— Chọn —</option>
                                <option value="VNPT">VNPT</option>
                                <option value="FPT">FPT</option>
                                <option value="Viettel">Viettel</option>
                                <option value="CMC">CMC</option>
                                <option value="SCTV">SCTV</option>
                                <option value="Netnam">Netnam</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Account ISP</label>
                            <input type="text" class="form-control" name="isp_account" id="lineAccount"
                                placeholder="Tài khoản ISP"
                                style="font-family:'JetBrains Mono',monospace;font-size:0.85rem">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">IP</label>
                            <input type="text" class="form-control" name="ip_address" id="lineIP"
                                placeholder="VD: 113.161.x.x" style="font-family:'JetBrains Mono',monospace">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Loại kết nối</label>
                            <input type="hidden" name="ip_type" id="lineIpType" value="static">
                            <div style="display:flex;gap:8px;flex-wrap:wrap">
                                <label class="ip-type-btn active" data-val="static"
                                    onclick="selectIpType('static',this)">
                                    <i class="fas fa-thumbtack"></i> IP Tĩnh
                                </label>
                                <label class="ip-type-btn" data-val="dynamic" onclick="selectIpType('dynamic',this)">
                                    <i class="fas fa-random"></i> IP Động
                                </label>
                                <label class="ip-type-btn" data-val="sim" onclick="selectIpType('sim',this)">
                                    <i class="fas fa-sim-card"></i> SIM 4G/5G
                                </label>
                                <label class="ip-type-btn" data-val="other" onclick="selectIpType('other',this)">
                                    <i class="fas fa-ellipsis-h"></i> Khác
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3" id="customTypeWrap" style="display:none">
                            <label class="form-label">Nhập loại kết nối</label>
                            <input type="text" class="form-control" id="lineCustomType"
                                placeholder="VD: Leased Line, VSAT...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">SĐT cửa hàng</label>
                            <input type="text" class="form-control" name="phone" id="linePhone"
                                placeholder="028 xxxx xxxx">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Liên lạc KT khu vực</label>
                            <input type="text" class="form-control" name="regional_contact" id="lineRegContact"
                                placeholder="SĐT / Tên">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Ngày hết hạn</label>
                            <input type="date" class="form-control" name="expiry_date" id="lineExpiry">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Ngày On net</label>
                            <input type="date" class="form-control" name="on_net" id="lineOnNet">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mã hợp đồng</label>
                            <input type="text" class="form-control" name="contract_id" id="lineContract">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">OLT/PON</label>
                            <input type="text" class="form-control" name="olt_info" id="lineOLT"
                                placeholder="OLT-HCM-01/PON 3/1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phương thức</label>
                            <select class="form-select" name="check_method" id="lineMethod">
                                <option value="ping">Ping (ICMP)</option>
                                <option value="http">HTTP</option>
                                <option value="tcp">TCP</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="notes" id="lineNotes" rows="2"
                            placeholder="Ghi chú thêm..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-dark" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn-primary-dark"><i class="fas fa-save"></i> Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .tbl-full {
        width: 100%;
    }

    .tbl-full th,
    .tbl-full td {
        padding: 6px 8px;
        font-size: 0.75rem;
        vertical-align: middle;
    }

    .tbl-full th {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        white-space: nowrap;
    }

    .tbl-full .mono {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.72rem;
    }

    .tbl-full .dim {
        color: rgba(255, 255, 255, 0.4);
        font-size: 0.72rem;
    }

    .tbl-full .nw {
        white-space: nowrap;
    }

    .tbl-full .rn {
        color: rgba(255, 255, 255, 0.25);
    }

    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0 0 8px 8px;
        max-height: 220px;
        overflow-y: auto;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
    }

    .search-dropdown .dd-item {
        padding: 10px 14px;
        cursor: pointer;
        font-size: 0.88rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        transition: background 0.15s;
    }

    .search-dropdown .dd-item:hover {
        background: rgba(6, 182, 212, 0.12);
    }

    .search-dropdown .dd-sub {
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.35);
        margin-top: 2px;
    }

    .search-dropdown .dd-empty {
        padding: 14px;
        text-align: center;
        color: rgba(255, 255, 255, 0.3);
        font-size: 0.82rem;
    }

    .sortable {
        cursor: pointer;
        user-select: none;
    }

    .sortable:hover {
        color: #06b6d4;
    }

    .sortable.active-sort { color: #06b6d4; }

    .page-btn {
        padding: 4px 10px; border-radius: 4px; cursor: pointer;
        font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.1);
        background: transparent; color: rgba(255,255,255,0.5);
        transition: all 0.15s;
    }
    .page-btn:hover { border-color: #06b6d4; color: #06b6d4; }
    .page-btn.active { background: #06b6d4; color: #000; border-color: #06b6d4; font-weight: 600; }
    .page-btn:disabled { opacity: 0.3; cursor: default; }

    .ip-type-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.82rem;
        font-weight: 500;
        border: 2px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.03);
        color: rgba(255, 255, 255, 0.5);
        transition: all 0.2s;
    }

    .ip-type-btn:hover {
        border-color: rgba(255, 255, 255, 0.25);
        color: rgba(255, 255, 255, 0.8);
    }

    .ip-type-btn.active[data-val="static"] {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.12);
        color: #4ade80;
    }

    .ip-type-btn.active[data-val="dynamic"] {
        border-color: #f59e0b;
        background: rgba(245, 158, 11, 0.12);
        color: #fbbf24;
    }

    .ip-type-btn.active[data-val="sim"] {
        border-color: #8b5cf6;
        background: rgba(139, 92, 246, 0.12);
        color: #a78bfa;
    }

    .ip-type-btn.active[data-val="other"] {
        border-color: #64748b;
        background: rgba(100, 116, 139, 0.12);
        color: #94a3b8;
    }
</style>

<script>
    const allCustomers = <?= json_encode($customers_list) ?>;
    let ddVisible = false;
    let currentSort = 'expiry';
    let sortAsc = true;
    const PAGE_SIZE = 50;
    let currentPage = 1;

    function getVisibleRows() {
        return Array.from(document.querySelectorAll('#linesBody tr[data-cid]')).filter(r => r.dataset.filtered !== 'true');
    }

    function renderPagination() {
        const allRows = Array.from(document.querySelectorAll('#linesBody tr[data-cid]'));
        const rows = allRows.filter(r => r.dataset.filtered !== 'true');
        const total = rows.length;
        const bar = document.getElementById('paginationBar');
        const info = document.getElementById('pageInfo');
        const btns = document.getElementById('pageButtons');
        
        // Hide ALL rows first (including filtered ones)
        allRows.forEach(r => r.style.display = 'none');
        
        if (total <= PAGE_SIZE) {
            bar.style.display = 'none';
            rows.forEach((r, i) => { r.style.display = ''; r.querySelector('.rn').textContent = i + 1; });
            return;
        }
        
        bar.style.display = 'flex';
        const totalPages = Math.ceil(total / PAGE_SIZE);
        if (currentPage > totalPages) currentPage = totalPages;
        
        const start = (currentPage - 1) * PAGE_SIZE;
        const end = start + PAGE_SIZE;
        
        rows.forEach((r, i) => {
            r.style.display = (i >= start && i < end) ? '' : 'none';
            r.querySelector('.rn').textContent = i + 1;
        });
        
        info.textContent = `Hi\u1EC3n ${start+1}-${Math.min(end,total)} / ${total} lines`;
        
        let html = `<button class="page-btn" onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}><i class="fas fa-chevron-left"></i></button>`;
        for (let p = 1; p <= totalPages; p++) {
            if (totalPages > 7 && p > 2 && p < totalPages - 1 && Math.abs(p - currentPage) > 1) {
                if (p === 3 || p === totalPages - 2) html += '<span style="color:rgba(255,255,255,0.3);padding:0 4px">...</span>';
                continue;
            }
            html += `<button class="page-btn ${p===currentPage?'active':''}" onclick="goPage(${p})">${p}</button>`;
        }
        html += `<button class="page-btn" onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}><i class="fas fa-chevron-right"></i></button>`;
        btns.innerHTML = html;
    }

    function goPage(p) {
        const rows = getVisibleRows();
        const totalPages = Math.ceil(rows.length / PAGE_SIZE);
        if (p < 1 || p > totalPages) return;
        currentPage = p;
        renderPagination();
    }

    function sortTable(col) {
        const tbody = document.getElementById('linesBody');
        const rows = Array.from(tbody.querySelectorAll('tr[data-cid]'));

        // Toggle direction
        if (currentSort === col) { sortAsc = !sortAsc; } else { currentSort = col; sortAsc = true; }

        rows.sort((a, b) => {
            let va = '', vb = '';
            if (col === 'scode') {
                va = parseInt(a.dataset.scode) || 99999;
                vb = parseInt(b.dataset.scode) || 99999;
                return sortAsc ? va - vb : vb - va;
            } else if (col === 'customer') {
                va = a.dataset.customer || 'zzz';
                vb = b.dataset.customer || 'zzz';
            } else if (col === 'iptype') {
                const order = { static: 0, dynamic: 1, sim: 2 };
                va = order[a.dataset.iptype] ?? 3;
                vb = order[b.dataset.iptype] ?? 3;
                return sortAsc ? va - vb : vb - va;
            } else if (col === 'expiry') {
                va = a.dataset.expiry || '9999-12-31';
                vb = b.dataset.expiry || '9999-12-31';
            }
            const cmp = va < vb ? -1 : (va > vb ? 1 : 0);
            return sortAsc ? cmp : -cmp;
        });

        rows.forEach((r, i) => {
            tbody.appendChild(r);
            r.querySelector('.rn').textContent = i + 1;
        });

        // Update header icons
        document.querySelectorAll('.sortable i').forEach(i => { i.className = 'fas fa-sort'; i.style.opacity = '0.3'; });
        document.querySelectorAll('.sortable').forEach(th => th.classList.remove('active-sort'));
        const th = document.getElementById('sort-' + col);
        if (th) {
            th.classList.add('active-sort');
            const icon = th.querySelector('i');
            icon.className = sortAsc ? 'fas fa-sort-up' : 'fas fa-sort-down';
            icon.style.opacity = '0.6';
        }

        // Re-apply customer filter then paginate
        currentPage = 1;
        const cf = document.getElementById('customerFilter').value;
        const sc = document.getElementById('searchStoreCode').value;
        if (cf || sc) filterTable(); else renderPagination();
    }

    function filterTable() {
        const cid = document.getElementById('customerFilter').value;
        const scode = document.getElementById('searchStoreCode').value.toLowerCase().trim();
        const rows = document.querySelectorAll('#linesBody tr[data-cid]');

        // Toggle KH column visibility
        const show = !cid;
        document.getElementById('thKH').style.display = show ? '' : 'none';
        document.querySelectorAll('.kh-col').forEach(c => c.style.display = show ? '' : 'none');

        rows.forEach(row => {
            const matchCust = !cid || row.dataset.cid === cid;
            const matchCode = !scode || (row.dataset.scode && row.dataset.scode.includes(scode));
            row.dataset.filtered = (matchCust && matchCode) ? 'false' : 'true';
            row.style.display = '';
        });

        currentPage = 1;
        renderPagination();
        localStorage.setItem('ftth_lines_customer', cid);
    }

    function searchCustomer(query) {
        const dd = document.getElementById('customerDropdown');
        query = query.toLowerCase().trim();
        const filtered = query === '' ? allCustomers : allCustomers.filter(c =>
            c.name.toLowerCase().includes(query) || (c.contact_person && c.contact_person.toLowerCase().includes(query)) || (c.phone && c.phone.includes(query))
        );
        if (filtered.length === 0) {
            dd.innerHTML = '<div class="dd-empty">Không tìm thấy — <a href="?action=customers" style="color:#06b6d4">Thêm KH mới</a></div>';
        } else {
            dd.innerHTML = filtered.map(c =>
                `<div class="dd-item" onclick="selectCustomer(${c.id}, '${c.name.replace(/'/g, "\\'")}')"><div>${c.name}</div>${c.contact_person ? `<div class="dd-sub"><i class="fas fa-user" style="width:12px"></i> ${c.contact_person}</div>` : ''}</div>`
            ).join('');
        }
        dd.style.display = 'block'; ddVisible = true;
    }

    function selectCustomer(id, name) {
        document.getElementById('customerSearch').value = name;
        document.getElementById('lineCustomerId').value = id;
        document.getElementById('customerDropdown').style.display = 'none'; ddVisible = false;
    }

    document.addEventListener('click', e => {
        if (ddVisible && !e.target.closest('#customerSearch') && !e.target.closest('#customerDropdown')) {
            document.getElementById('customerDropdown').style.display = 'none'; ddVisible = false;
        }
    });

    function resetLineForm() {
        document.getElementById('lineModalTitle').textContent = 'Thêm FTTH Line';
        ['lineId', 'lineName', 'lineIP', 'lineContract', 'lineOLT', 'lineNotes', 'lineBranchAddress', 'lineCustomerId', 'lineStoreCode', 'lineAccount', 'linePhone', 'lineRegContact', 'lineExpiry', 'lineOnNet'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('customerSearch').value = '';
        document.getElementById('lineMethod').value = 'ping';
        document.getElementById('lineProvider').value = '';
        document.getElementById('lineIpType').value = 'static';
        document.getElementById('lineCustomType').value = '';
        selectIpType('static');
    }

    function selectIpType(val, btn) {
        document.getElementById('lineIpType').value = val;
        document.querySelectorAll('.ip-type-btn').forEach(b => b.classList.remove('active'));
        const target = btn || document.querySelector('.ip-type-btn[data-val="' + val + '"]');
        if (target) target.classList.add('active');

        document.getElementById('customTypeWrap').style.display = val === 'other' ? '' : 'none';
        const ipField = document.getElementById('lineIP');
        if (val === 'static') {
            ipField.setAttribute('required', 'required');
            ipField.placeholder = 'VD: 113.161.x.x';
        } else {
            ipField.removeAttribute('required');
            ipField.placeholder = 'Tuỳ chọn';
        }
    }

    function editLine(l) {
        document.getElementById('lineModalTitle').textContent = 'Sửa FTTH Line';
        document.getElementById('lineId').value = l.id;
        document.getElementById('lineName').value = l.name;
        document.getElementById('lineStoreCode').value = l.store_code || '';
        document.getElementById('lineIP').value = l.ip_address || '';
        document.getElementById('lineProvider').value = l.provider || '';
        document.getElementById('lineAccount').value = l.isp_account || '';
        document.getElementById('lineMethod').value = l.check_method;
        document.getElementById('lineContract').value = l.contract_id || '';
        document.getElementById('lineOLT').value = l.olt_info || '';
        document.getElementById('lineNotes').value = l.notes || '';
        document.getElementById('lineBranchAddress').value = l.branch_address || '';
        document.getElementById('linePhone').value = l.phone || '';
        document.getElementById('lineRegContact').value = l.regional_contact || '';
        document.getElementById('lineOnNet').value = l.on_net || '';
        document.getElementById('lineExpiry').value = l.expiry_date || '';
        // Set ip_type buttons
        const ipType = l.ip_type || 'static';
        if (['static', 'dynamic', 'sim', 'other'].includes(ipType)) {
            selectIpType(ipType);
            document.getElementById('lineCustomType').value = '';
        } else {
            selectIpType('other');
            document.getElementById('lineCustomType').value = ipType;
        }
        if (l.customer_id) {
            document.getElementById('lineCustomerId').value = l.customer_id;
            document.getElementById('customerSearch').value = l.customer_name_rel || l.customer_name || '';
        } else { document.getElementById('customerSearch').value = ''; }
        new bootstrap.Modal(document.getElementById('lineModal')).show();
    }

    // Before submit: merge custom type into ip_type hidden input
    document.getElementById('lineForm')?.addEventListener('submit', function () {
        const hidden = document.getElementById('lineIpType');
        if (hidden.value === 'other') {
            const custom = document.getElementById('lineCustomType').value.trim();
            if (custom) hidden.value = custom;
        }
    });

    // Init: restore filter + pagination
    (function () {
        const saved = localStorage.getItem('ftth_lines_customer');
        if (saved) { document.getElementById('customerFilter').value = saved; filterTable(); }
        else { renderPagination(); }
    })();
</script>

<?php renderFooter(); ?>