<footer class="footer mt-3 py-3 bg-light border-top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <span class="text-muted">
                    &copy; <?= date('Y') ?> <strong>Jadelyn Pharmacy</strong>. All rights reserved.
                </a>
            </div>

            <div class="col-md-6 text-center text-md-end">
                <small class="text-muted me-3">
                    <?php if (!session()->get('isLoggedIn')): ?>
                        <i class="bi bi-cpu shadow-sm"></i> Made by Takanobu Bear
                    <?php else: ?>
                        <span id="footer-clock" class="fw-medium"></span>
                    <?php endif; ?>
                </small>
                <?php if (session()->get('userRoleId') == UserRoles::ADMIN): ?>
                    <span class="badge rounded-pill bg-danger px-3">
                        <i class="bi bi-check-circle-fill me-1"></i> Administrator Account
                    </span>
                <?php endif; ?>
                <span class="badge rounded-pill bg-success px-3">
                    <i class="bi bi-check-circle-fill me-1"></i> Development
                </span>
            </div>
        </div>
    </div>
</footer>
<script>
    function updateFooterClock() {
        const clockElement = $('#footer-clock');
        if (clockElement.length === 0) return; 

        const now = new Date();
        const options = { 
            weekday: 'short', 
            month: 'short', 
            day: 'numeric', 
            hour: 'numeric', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: true 
        };

        const clockStyle = now.toLocaleString('en-US', options).replace(/,/g, '');
        clockElement.text(clockStyle);
    }

    $(document).ready(function() {
        updateFooterClock();
        setInterval(updateFooterClock, 1000); 
    });
</script>