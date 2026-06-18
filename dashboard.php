<?php
// 1. SCRIPT SAKTI: Paksa tampilkan eror jika ada yang salah ketik
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Load file template atas dan proteksi login
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<div class="col-md-10 p-4">
    <h2 class="mb-4" style="font-weight: 500;"></h2>
    
    <div class="card p-4 shadow-sm border-0 bg-white">
        <h4 class="mb-3 text-secondary" style="font-weight: 500;">Daftar Transaksi Aktif</h4>
        
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTransaksi">
                <i class="fa fa-plus me-1"></i> Tambah Transaksi
            </button>
            <div id="tombolExport"></div>
        </div>

        <div class="table-responsive">
            <table id="tabelTransaksi" class="table table-striped table-hover align-middle" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Jenis PS</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query dinamis mengambil data dari database rentalPS
                    $query_tampil = "SELECT transaksi.*, pelanggan.nama_pelanggan, unit_ps.jenis_ps 
                                     FROM transaksi 
                                     JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id
                                     JOIN unit_ps ON transaksi.id_unit_ps = unit_ps.id
                                     ORDER BY transaksi.id DESC";
                    
                    $result_tampil = mysqli_query($koneksi, $query_tampil);

                    if ($result_tampil && mysqli_num_rows($result_tampil) > 0) {
                        while ($row = mysqli_fetch_assoc($result_tampil)) {
                            $jam_mulai   = date('H:i', strtotime($row['jam_mulai']));
                            $jam_selesai = date('H:i', strtotime($row['jam_selesai']));
                            ?>
                            <tr>
                                <td><?php echo $row['kode_transaksi']; ?></td>
                                <td><?php echo $row['nama_pelanggan']; ?></td>
                                <td><span class="badge bg-secondary"><?php echo $row['jenis_ps']; ?></span></td>
                                <td><?php echo $jam_mulai; ?> WIB</td>
                                <td><?php echo $jam_selesai; ?> WIB</td>
                                <td>Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                <td>
                                    <a href="uploads/ttd/<?php echo $row['ttd_digital']; ?>" target="_blank" class="btn btn-info btn-sm text-white">
                                        <i class="fa fa-eye"></i> TTD
                                    </a>
                                    
                                    <?php if ($row['status_transaksi'] == 'berjalan') { ?>
                                        <a href="modul/selesai_transaksi.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm text-dark" onclick="return confirm('Yakin pelanggan ini sudah selesai main?')">
                                            <i class="fa fa-check"></i> Selesai
                                        </a>
                                    <?php } else { ?>
                                        <span class="badge bg-success"><i class="fa fa-check-double"></i> Sukse</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTransaksi" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 500;">Form Tambah Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="modul/proses_transaksi.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Customer</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan nama pelanggan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Unit PS</label>
                        <select class="form-select" name="id_ps" required>
                            <option value=""> Pilih Unit PS </option>
                            <?php
            
                            $query_ps = mysqli_query($koneksi, "SELECT * FROM unit_ps WHERE status = 'ready'");
                            while($ps = mysqli_fetch_assoc($query_ps)) {
                                echo "<option value='".$ps['id']."'>".$ps['jenis_ps']." (".$ps['nomor_seri'].") - Rp ".number_format($ps['harga_per_jam'], 0, ',', '.')."/jam</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi Rental </label>
                        <input type="number" class="form-control" name="durasi" min="1" max="24" placeholder="0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-block">Signature Canvas </label>
                        <div class="canvas-container rounded">
                            <canvas id="signaturePad"></canvas>
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-sm btn-secondary" id="clearCanvas">Hapus TTD</button>
                        </div>
                        <input type="hidden" name="ttd_image" id="ttd_image" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 py-2">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTables + Tombol Konversi
        var table = $('#tabelTransaksi').DataTable({
            buttons: [
                { extend: 'excel', className: 'btn btn-success btn-sm' },
                { extend: 'pdf', className: 'btn btn-danger btn-sm' },
                { extend: 'print', className: 'btn btn-dark btn-sm' }
            ],
            "order": [[0, "desc"]] // Mengurutkan dari transaksi terbaru
        });
        table.buttons().container().appendTo('#tombolExport');

        // 2. LOGIKA FIX CANVAS TTD (Nunggu Modal Selesai Transisi Fade-in)
        const canvas = document.getElementById('signaturePad');
        const ctx = canvas.getContext('2d');
        let drawing = false;

        function resizeCanvas() {
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = 200; 
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#000000';
        }

        // Jalankan resize saat modal terbuka penuh
        $('#modalTransaksi').on('shown.bs.modal', function () {
            resizeCanvas();
        });

        // 3. EVENT DETEKSI CORETAN MOUSE
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        // EVENT DETEKSI SENTUHAN (Untuk HP / Android Kasir)
        canvas.addEventListener('touchstart', function(e) {
            var touch = e.touches[0];
            var mouseEvent = new MouseEvent("mousedown", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }, false);
        
        canvas.addEventListener('touchend', function(e) {
            var mouseEvent = new MouseEvent("mouseup", {});
            canvas.dispatchEvent(mouseEvent);
        }, false);
        
        canvas.addEventListener('touchmove', function(e) {
            var touch = e.touches[0];
            var mouseEvent = new MouseEvent("mousemove", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }, false);

        function startDrawing(event) {
            drawing = true;
            draw(event);
        }

        function draw(event) {
            if (!drawing) return;
            event.preventDefault();

            const rect = canvas.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
        }

        function stopDrawing() {
            if (drawing) {
                drawing = false;
                ctx.beginPath();
                // Transfer gambar canvas ke hidden input data URL Base64
                document.getElementById('ttd_image').value = canvas.toDataURL();
            }
        }

        // Tombol Hapus Coretan TTD
        document.getElementById('clearCanvas').addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('ttd_image').value = '';
        });
    });
</script>

</div> </div> </body>
</html>