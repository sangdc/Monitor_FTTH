<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'views/common/layout.php';
renderHeader($currentUser, $user, 'dashboard');
showFlash();

// Get unique customers for filter dropdown
$customersStmt = $pdo->query("SELECT DISTINCT c.id, c.name 
    FROM customers c 
    INNER JOIN ftth_lines l ON l.customer_id = c.id AND l.active = 1
    ORDER BY c.name");
$filterCustomers = $customersStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <div class="page-title"><i class="fas fa-chart-line"></i> Dashboard</div>
    <div style="display:flex;align-items:center;gap:12px">
        <div class="auto-ping-control">
            <span class="live-dot"></span>
            <span style="font-size:0.75rem;color:rgba(255,255,255,0.4)">Auto Ping: <strong style="color:#4ade80"><?= htmlspecialchars((string)($pingInterval ?? '30')) ?>s</strong></span>
        </div>
        <div class="auto-ping-control">
            <i class="fas fa-sync-alt" style="color:rgba(255,255,255,0.3);font-size:0.75rem"></i>
            <select id="refreshInterval" onchange="setRefresh(this.value)" style="background:transparent;border:none;color:#94a3b8;font-size:0.75rem;cursor:pointer;font-family:'Inter',sans-serif">
                <option value="10">10s</option>
                <option value="15">15s</option>
                <option value="30" selected>30s</option>
                <option value="60">1m</option>
                <option value="0">Off</option>
            </select>
            <span id="countdown" style="font-size:0.7rem;color:rgba(255,255,255,0.3);font-family:'JetBrains Mono',monospace;min-width:24px"></span>
        </div>
        <button class="btn-primary-dark" id="btnRefresh" onclick="refreshData()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
</div>

<!-- Status Cards -->
<div class="status-grid">
    <div class="status-card total">
        <div class="count" id="c-total"><?= $stats['total'] ?? 0 ?></div>
        <div class="label">Total Lines</div>
    </div>
    <div class="status-card up">
        <div class="count" id="c-up"><?= $stats['up_count'] ?? 0 ?></div>
        <div class="label">Up</div>
    </div>
    <div class="status-card down">
        <div class="count" id="c-down"><?= $stats['down_count'] ?? 0 ?></div>
        <div class="label">Down</div>
    </div>
    <div class="status-card warning">
        <div class="count" id="c-warning"><?= $stats['warning_count'] ?? 0 ?></div>
        <div class="label">Warning</div>
    </div>
    <div class="status-card paused">
        <div class="count" id="c-unknown"><?= ($stats['paused_count'] ?? 0) + ($stats['unknown_count'] ?? 0) ?></div>
        <div class="label">Unknown</div>
    </div>
</div>

<!-- Lines Table -->
<div class="card-dark">
    <div class="card-header-dark" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <h2><i class="fas fa-list"></i> FTTH Lines Status</h2>
        <div style="display:flex;align-items:center;gap:14px">
            <!-- Customer Filter -->
            <div style="display:flex;align-items:center;gap:6px">
                <i class="fas fa-filter" style="color:rgba(255,255,255,0.25);font-size:0.72rem"></i>
                <select id="customerFilter" onchange="filterByCustomer(this.value)" style="background:#1e293b;border:1px solid rgba(255,255,255,0.1);color:#e2e8f0;padding:5px 10px;border-radius:6px;font-size:0.8rem;cursor:pointer;font-family:'Inter',sans-serif;min-width:160px">
                    <option value="">Tất cả khách hàng</option>
                    <?php foreach ($filterCustomers as $fc): ?>
                        <option value="<?= $fc['id'] ?>"><?= htmlspecialchars($fc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <span id="lastUpdate" style="font-size:0.72rem;color:rgba(255,255,255,0.3)">Cập nhật: <?= date('H:i:s') ?></span>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="tbl" id="linesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mã CH</th>
                    <th id="thCustomer">Khách hàng</th>
                    <th>Store</th>
                    <th>Địa chỉ</th>
                    <th>NCC</th>
                    <th>IP Tĩnh</th>
                    <th>Status</th>
                    <th>Response</th>
                    <th>Last Check</th>
                </tr>
            </thead>
            <tbody id="linesBody">
                <?php if (!empty($lines)): ?>
                <?php foreach ($lines as $i => $line): ?>
                <tr id="line-row-<?= $line['id'] ?>" data-customer-id="<?= $line['customer_id'] ?? '' ?>" data-status="<?= $line['status'] ?>">
                    <td class="muted row-num"><?= $i + 1 ?></td>
                    <td class="mono" style="font-size:0.78rem"><?= htmlspecialchars($line['store_code'] ?? '—') ?></td>
                    <td class="customer-col"><?= htmlspecialchars($line['customer_name_rel'] ?? $line['customer_name'] ?? '—') ?></td>
                    <td class="name-cell"><?= htmlspecialchars($line['name']) ?></td>
                    <td class="muted"><small><?= htmlspecialchars($line['branch_address'] ?? '') ?></small></td>
                    <td><span class="mono" style="font-size:0.78rem"><?= htmlspecialchars($line['provider'] ?? '—') ?></span></td>
                    <td class="mono"><?= htmlspecialchars($line['ip_address']) ?></td>
                    <td>
                        <span class="badge-status <?= $line['status'] ?>" id="status-<?= $line['id'] ?>">
                            <span class="dot"></span><?= ucfirst($line['status']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="response-time <?= ($line['avg_response_time'] ?? 0) < 50 ? 'good' : (($line['avg_response_time'] ?? 0) < 200 ? 'medium' : 'bad') ?>" id="rt-<?= $line['id'] ?>">
                            <?= $line['avg_response_time'] ? round($line['avg_response_time'], 1) . ' ms' : '—' ?>
                        </span>
                    </td>
                    <td class="muted" id="lc-<?= $line['id'] ?>"><?= $line['last_check'] ? date('d/m H:i:s', strtotime($line['last_check'])) : 'Never' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr><td colspan="10" class="empty-state"><i class="fas fa-satellite-dish"></i> Chưa có line nào. <a href="?action=lines" style="color:#06b6d4">Thêm ngay</a></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
let refreshTimer = null;
let cdTimer = null;
let secsLeft = 0;

// ---- Customer filter ----
function filterByCustomer(customerId) {
    const rows = document.querySelectorAll('#linesBody tr[data-customer-id]');
    const thCustomer = document.getElementById('thCustomer');
    let visibleIdx = 0;

    // Show/hide KH column
    if (customerId) {
        thCustomer.style.display = 'none';
        document.querySelectorAll('.customer-col').forEach(c => c.style.display = 'none');
    } else {
        thCustomer.style.display = '';
        document.querySelectorAll('.customer-col').forEach(c => c.style.display = '');
    }

    // Filter rows
    rows.forEach(row => {
        if (!customerId || row.dataset.customerId === customerId) {
            row.style.display = '';
            visibleIdx++;
            row.querySelector('.row-num').textContent = visibleIdx;
        } else {
            row.style.display = 'none';
        }
    });

    // Save preference
    localStorage.setItem('ftth_customer_filter', customerId);
}

function refreshData() {
    const btn = document.getElementById('btnRefresh');
    btn.innerHTML = '<i class="fas fa-spinner spin"></i> Loading...';
    btn.disabled = true;
    
    fetch('?action=api_refresh_status')
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
            btn.disabled = false;
            if (!data.success) return;

            // Update counters
            document.getElementById('c-total').textContent = data.stats.total;
            document.getElementById('c-up').textContent = data.stats.up_count;
            document.getElementById('c-down').textContent = data.stats.down_count;
            document.getElementById('c-warning').textContent = data.stats.warning_count;
            document.getElementById('c-unknown').textContent = parseInt(data.stats.paused_count) + parseInt(data.stats.unknown_count);

            // Update each row
            data.lines.forEach((line, i) => {
                const row = document.getElementById('line-row-' + line.id);
                if (!row) return;
                
                // Update data-status for sorting
                row.dataset.status = line.status;
                
                // Status badge
                const badge = document.getElementById('status-' + line.id);
                if (badge) {
                    badge.className = 'badge-status ' + line.status;
                    badge.innerHTML = '<span class="dot"></span>' + line.status.charAt(0).toUpperCase() + line.status.slice(1);
                }
                
                // Response time
                const rt = document.getElementById('rt-' + line.id);
                if (rt) {
                    const ms = parseFloat(line.avg_response_time) || 0;
                    rt.textContent = ms > 0 ? ms.toFixed(1) + ' ms' : '—';
                    rt.className = 'response-time ' + (ms < 50 ? 'good' : (ms < 200 ? 'medium' : 'bad'));
                }
                
                // Last check
                const lc = document.getElementById('lc-' + line.id);
                if (lc) lc.textContent = line.last_check_fmt || 'Never';
            });

            // Re-sort by status priority
            sortTableByStatus();

            document.getElementById('lastUpdate').textContent = 'Cập nhật: ' + data.time;
        })
        .catch(() => {
            btn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
            btn.disabled = false;
        });
}

// Sort: down > warning > unknown > paused > up
function sortTableByStatus() {
    const tbody = document.getElementById('linesBody');
    const rows = Array.from(tbody.querySelectorAll('tr[data-status]'));
    const priority = { 'down': 0, 'warning': 1, 'unknown': 2, 'paused': 3, 'up': 4 };
    
    rows.sort((a, b) => {
        const pa = priority[a.dataset.status] ?? 5;
        const pb = priority[b.dataset.status] ?? 5;
        return pa - pb;
    });
    
    rows.forEach((row, i) => {
        tbody.appendChild(row);
        row.querySelector('.row-num').textContent = i + 1;
    });

    // Re-apply customer filter
    const cf = document.getElementById('customerFilter').value;
    if (cf) filterByCustomer(cf);
}

function setRefresh(secs) {
    secs = parseInt(secs);
    clearInterval(refreshTimer);
    clearInterval(cdTimer);
    localStorage.setItem('ftth_refresh', secs);
    
    const cdEl = document.getElementById('countdown');
    if (secs === 0) { cdEl.textContent = ''; return; }
    
    secsLeft = secs;
    cdEl.textContent = secsLeft + 's';
    cdTimer = setInterval(() => {
        secsLeft--;
        cdEl.textContent = secsLeft > 0 ? secsLeft + 's' : '';
    }, 1000);
    
    refreshTimer = setInterval(() => {
        refreshData();
        secsLeft = secs;
    }, secs * 1000);
}

// Init
(function() {
    const saved = localStorage.getItem('ftth_refresh');
    if (saved !== null) document.getElementById('refreshInterval').value = saved;
    setRefresh(document.getElementById('refreshInterval').value);

    // Restore customer filter
    const savedCustomer = localStorage.getItem('ftth_customer_filter');
    if (savedCustomer) {
        document.getElementById('customerFilter').value = savedCustomer;
        filterByCustomer(savedCustomer);
    }

    // Initial sort
    sortTableByStatus();
})();
</script>

<?php renderFooter(); ?>
