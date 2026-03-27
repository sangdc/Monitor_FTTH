<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Login - FTTH Monitor</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="FTTH Line Monitoring System - Monitor your fiber optic lines in real-time">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/pages/login.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body class="login-body">

    <!-- Animated Background Particles -->
    <canvas id="particles-canvas"></canvas>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-network-wired"></i>
                </div>
                <h1>FTTH Monitor</h1>
                <p>Hệ thống giám sát đường truyền FTTH</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <div class="form-group">
                    <label for="username">Tên đăng nhập</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text"
                               id="username"
                               name="username"
                               class="form-control"
                               placeholder="Nhập username"
                               required
                               autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control"
                               placeholder="Nhập mật khẩu"
                               required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me" value="1">
                        <label class="form-check-label" for="rememberMe">
                            <i class="fas fa-mobile-alt me-1"></i>Ghi nhớ đăng nhập
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="btn-text">Đăng nhập</span>
                    <i class="fas fa-arrow-right btn-text"></i>
                    <div class="spinner"></div>
                </button>
            </form>

            <div class="login-footer">
                <div class="status-indicators">
                    <span class="status-dot up"></span>
                    <span class="status-dot warning"></span>
                    <span class="status-dot down"></span>
                    <span class="status-label">Network Monitoring Active</span>
                </div>
            </div>
        </div>
    </div>

    <div class="brand-footer">
        <p>© 2026 FTTH Monitor — Fiber To The Home Monitoring System</p>
    </div>

    <script>
        function togglePassword() {
            const password   = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function () {
            document.getElementById('loginBtn').classList.add('loading');
        });

        // Animated Particles Background
        (function() {
            const canvas = document.getElementById('particles-canvas');
            const ctx = canvas.getContext('2d');
            let particles = [];
            const PARTICLE_COUNT = 60;
            const CONNECTION_DISTANCE = 150;

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resize();
            window.addEventListener('resize', resize);

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.vx = (Math.random() - 0.5) * 0.5;
                    this.vy = (Math.random() - 0.5) * 0.5;
                    this.radius = Math.random() * 2 + 1;
                    this.opacity = Math.random() * 0.5 + 0.2;
                }

                update() {
                    this.x += this.vx;
                    this.y += this.vy;
                    if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                    if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
                }

                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(6, 182, 212, ${this.opacity})`;
                    ctx.fill();
                }
            }

            for (let i = 0; i < PARTICLE_COUNT; i++) {
                particles.push(new Particle());
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Draw connections
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < CONNECTION_DISTANCE) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = `rgba(6, 182, 212, ${0.15 * (1 - dist / CONNECTION_DISTANCE)})`;
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }
                }

                particles.forEach(p => {
                    p.update();
                    p.draw();
                });

                requestAnimationFrame(animate);
            }

            animate();
        })();
    </script>
</body>
</html>
