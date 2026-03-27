<?php require_once 'views/common/layout.php'; renderHeader($currentUser, 'settings'); showFlash(); ?>

<div class="page-header">
    <div class="page-title"><i class="fas fa-cog"></i> Cài đặt hệ thống</div>
</div>

<form method="POST" action="?action=save_settings">
<div class="row" style="gap:20px 0">

    <!-- Ping Settings -->
    <div class="col-md-6">
        <div class="card-dark" style="height:100%">
            <div class="card-header-dark">
                <h2><i class="fas fa-satellite-dish"></i> Cấu hình Ping</h2>
            </div>
            <div style="padding:20px">
                <div class="mb-3">
                    <label class="form-label">Khoảng thời gian ping (giây)</label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input type="number" class="form-control" name="ping_interval" 
                            value="<?= htmlspecialchars($settings['ping_interval'] ?? '30') ?>" 
                            min="5" max="3600" style="width:120px">
                        <span style="font-size:0.78rem;color:rgba(255,255,255,0.4)">giây (tối thiểu 5s)</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Trạng thái Worker</label>
                    <div id="workerStatus" style="display:flex;align-items:center;gap:8px">
                        <span class="badge-status up" id="workerBadge"><span class="dot"></span>Running</span>
                        <span style="font-size:0.72rem;color:rgba(255,255,255,0.35)" id="workerInfo">Container: ftth_cron</span>
                    </div>
                </div>
                <div class="mb-0" style="background:rgba(6,182,212,0.05);border:1px solid rgba(6,182,212,0.1);border-radius:10px;padding:12px">
                    <div style="font-size:0.78rem;color:rgba(255,255,255,0.5)">
                        <i class="fas fa-info-circle" style="color:#06b6d4"></i> 
                        Hệ thống tự động ping tất cả các line FTTH theo khoảng thời gian đã cấu hình.<br>
                        Thay đổi sẽ có hiệu lực ngay trong chu kỳ ping tiếp theo.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timezone -->
    <div class="col-md-6">
        <div class="card-dark" style="height:100%">
            <div class="card-header-dark">
                <h2><i class="fas fa-clock"></i> Múi giờ</h2>
            </div>
            <div style="padding:20px">
                <div class="mb-3">
                    <label class="form-label">Timezone</label>
                    <select class="form-select" name="timezone">
                        <option value="Asia/Ho_Chi_Minh" <?= ($settings['timezone'] ?? '') === 'Asia/Ho_Chi_Minh' ? 'selected' : '' ?>>Asia/Ho_Chi_Minh (UTC+7)</option>
                        <option value="Asia/Bangkok" <?= ($settings['timezone'] ?? '') === 'Asia/Bangkok' ? 'selected' : '' ?>>Asia/Bangkok (UTC+7)</option>
                        <option value="Asia/Singapore" <?= ($settings['timezone'] ?? '') === 'Asia/Singapore' ? 'selected' : '' ?>>Asia/Singapore (UTC+8)</option>
                        <option value="UTC" <?= ($settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label">Thời gian hiện tại</label>
                    <div class="mono" style="font-size:1.1rem;color:#06b6d4"><?= date('d/m/Y H:i:s') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Telegram -->
    <div class="col-12" style="margin-top:20px">
        <div class="card-dark">
            <div class="card-header-dark">
                <h2><i class="fab fa-telegram"></i> Thông báo Telegram</h2>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <span style="font-size:0.78rem;color:rgba(255,255,255,0.5)">Bật/Tắt</span>
                    <input type="hidden" name="telegram_enabled" value="0">
                    <input type="checkbox" name="telegram_enabled" value="1" 
                        <?= ($settings['telegram_enabled'] ?? '0') === '1' ? 'checked' : '' ?>
                        style="width:18px;height:18px;accent-color:#06b6d4;cursor:pointer">
                </label>
            </div>
            <div style="padding:20px">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bot Token <small style="color:rgba(255,255,255,0.3)">(từ @BotFather)</small></label>
                        <input type="text" class="form-control" name="telegram_bot_token" 
                            value="<?= htmlspecialchars($settings['telegram_bot_token'] ?? '') ?>"
                            placeholder="123456:ABC-DEF..." style="font-family:'JetBrains Mono',monospace;font-size:0.82rem">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Chat ID</label>
                        <input type="text" class="form-control" name="telegram_chat_id" 
                            value="<?= htmlspecialchars($settings['telegram_chat_id'] ?? '') ?>"
                            placeholder="-100xxxxxxxxxx" style="font-family:'JetBrains Mono',monospace;font-size:0.82rem">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn-primary-dark w-100" onclick="testTelegram()" id="btnTestTele">
                            <i class="fas fa-paper-plane"></i> Test
                        </button>
                    </div>
                </div>
                <div style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.1);border-radius:10px;padding:12px">
                    <div style="font-size:0.78rem;color:rgba(255,255,255,0.5)">
                        <i class="fas fa-bell" style="color:#f59e0b"></i> 
                        Khi bật, hệ thống sẽ gửi thông báo Telegram khi:
                        <ul style="margin:6px 0 0;padding-left:20px;color:rgba(255,255,255,0.4)">
                            <li>🔴 Line FTTH bị <strong style="color:#f87171">DOWN</strong></li>
                            <li>🟢 Line FTTH <strong style="color:#4ade80">RECOVERED</strong> (phục hồi)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="margin-top:24px;text-align:right">
    <button type="submit" class="btn-primary-dark" style="padding:12px 32px">
        <i class="fas fa-save"></i> Lưu cài đặt
    </button>
</div>
</form>

<!-- Change Password (separate form) -->
<div style="margin-top:30px">
    <div class="card-dark">
        <div class="card-header-dark">
            <h2><i class="fas fa-key"></i> Đổi mật khẩu</h2>
        </div>
        <div style="padding:20px">
            <form method="POST" action="?action=change_password" id="formChangePass">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="new_password" required minlength="6" id="newPass">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" name="confirm_password" required minlength="6" id="confirmPass">
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:16px">
                    <button type="submit" class="btn-primary-dark" style="padding:10px 28px">
                        <i class="fas fa-lock"></i> Đổi mật khẩu
                    </button>
                    <span id="passMsg" style="font-size:0.8rem"></span>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('formChangePass').addEventListener('submit', function(e) {
    const np = document.getElementById('newPass').value;
    const cp = document.getElementById('confirmPass').value;
    if (np !== cp) {
        e.preventDefault();
        document.getElementById('passMsg').innerHTML = '<span style="color:#f87171"><i class="fas fa-times-circle"></i> Mật khẩu xác nhận không khớp!</span>';
    }
});
</script>

<script>
function testTelegram() {
    const btn = document.getElementById('btnTestTele');
    btn.innerHTML = '<i class="fas fa-spinner spin"></i>';
    btn.disabled = true;
    
    const token = document.querySelector('[name=telegram_bot_token]').value;
    const chatId = document.querySelector('[name=telegram_chat_id]').value;
    
    fetch('?action=api_test_telegram&token=' + encodeURIComponent(token) + '&chat_id=' + encodeURIComponent(chatId))
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Test';
            btn.disabled = false;
            alert(data.success ? '✅ Gửi thành công! Kiểm tra Telegram.' : '❌ Lỗi: ' + (data.error || 'Unknown'));
        })
        .catch(() => {
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Test';
            btn.disabled = false;
            alert('❌ Lỗi kết nối');
        });
}
</script>

<?php renderFooter(); ?>
