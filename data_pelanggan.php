<?php
// 1. SCRIPT SAKTI: Paksa tampilkan eror jika ada yang salah ketik
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load komponen atas dan koneksi database
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'config/koneksi.php';
?>

<div class="col-md-10 p-4">
    <h2 class="mb-4" style="font-weight: 500;">Manajemen Data Pelanggan</h2>
    
    <div class="card p-4 shadow-sm border-0 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
                <i class="fa fa-plus me-1"></i> Tambah Pelanggan
            </button>
        </div>

        <div class="table-responsive">
            <table id="tabelPelanggan" class="table table-striped table-hover align-middle" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Pelanggan</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Tarik data pelanggan dari database rentalPS
                    $query_pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan_hildan_2430511059 ORDER BY id DESC");
                    
                    while ($row = mysqli_fetch_assoc($query_pelanggan)) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><span class="id-pelanggan-bold"><?php echo $row['nik_ktp']; ?></span></td>
                            <td><?php echo $row['nama_pelanggan']; ?></td>
                            <td><?php echo $row['no_hp']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm text-dark fw-bold me-1" data-bs-toggle="modal" data-bs-target="#modalEditPelanggan<?php echo $row['id']; ?>">Edit</button>
                                <a href="modul/proses_pelanggan.php?aksi=hapus&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data pelanggan ini, Bang?')">
                                    hapus
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditPelanggan<?php echo $row['id']; ?>" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Form Ubah Data Pelanggan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="modul/proses_pelanggan.php?aksi=edit" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">ID</label>
                                                <input type="text" class="form-control" name="nik_ktp" value="<?php echo $row['nik_ktp']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" name="nama_pelanggan" value="<?php echo $row['nama_pelanggan']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">No. Handphone</label>
                                                <input type="text" class="form-control" name="no_hp" value="<?php echo $row['no_hp']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning w-100 py-2 text-dark" style="font-weight: 500;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahPelanggan" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 500;">Form Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="modul/proses_pelanggan.php?aksi=tambah" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID</label>
                        <input type="text" class="form-control" name="nik_ktp" placeholder="Masukkan ID" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" name="nama_pelanggan" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" class="form-control" name="no_hp" placeholder="Contoh: 085xxxxxx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 py-2">Simpan Pelanggan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabelPelanggan').DataTable({
            "ordering": false,
            "language": {
                "search": "Cari Pelanggan:"
            }
        });
    });
</script>
</div>
</div>
</body>
</html>