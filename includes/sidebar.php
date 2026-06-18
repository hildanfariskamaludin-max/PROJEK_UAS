<div class="col-md-2 bg-dark text-white p-3" style="min-height: 100vh;">
    <h3 class="text-center my-4" style="font-weight: 500;">PS Rental</h3>
    <hr class="text-secondary">
    
    <ul class="nav flex-column gap-2">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link text-white d-flex align-items-center gap-2">
                <i class="fa fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="data_ps.php" class="nav-link text-white d-flex align-items-center gap-2">
                <i class="fa fa-gamepad"></i> Data PS
            </a>
        </li>
        <li class="nav-item">
            <a href="data_pelanggan.php" class="nav-link text-white d-flex align-items-center gap-2">
                <i class="fa fa-users"></i> Data Pelanggan
            </a>
        </li>
    </ul>

    <div style="position: absolute; bottom: 20px; width: 14%;">
        <a href="logout.php" class="btn btn-danger w-100 text-start d-flex align-items-center gap-2" onclick="return confirm('Yakin ingin keluar dari sistem kasir, Bang?')">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var path = window.location.pathname.split("/").pop();
        if (path === '' || path === 'index.php') {
            path = 'dashboard.php';
        }
        var targetLink = document.querySelector('.nav-link[href="' + path + '"]');
        if (targetLink) {
            // Beri warna latar agak terang pada menu yang sedang aktif
            targetLink.style.backgroundColor = '#2c3e50';
            targetLink.style.borderRadius = '5px';
        }
    });
</script>