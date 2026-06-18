<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'config/koneksi.php';
?>

<div class="col-md-10 p-4">
    <h2 class="mb-4" style="font-weight: 500;">Manajemen Data Unit PS</h2>
    
    <div class="card p-4 shadow-sm border-0 bg-white mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-secondary m-0" style="font-weight: 500;">Daftar Inventaris Unit</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPS">
                <i class="fa fa-plus me-1"></i> Tambah Unit PS
            </button>
        </div>

        <div class="table-responsive">
            <table id="tabelPS" class="table table-striped table-hover align-middle" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Seri</th>
                        <th>Jenis PS</th>
                        <th>Harga / Jam</th>
                        <th>Status</th>
                        <th>Foto Kondisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_ps = mysqli_query($koneksi, "SELECT * FROM unit_ps ORDER BY id DESC");
                    
                    while ($row = mysqli_fetch_assoc($query_ps)) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $row['nomor_seri']; ?></strong></td>
                            <td><span class="badge bg-primary"><?php echo $row['jenis_ps']; ?></span></td>
                            <td>Rp <?php echo number_format($row['harga_per_jam'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($row['status'] == 'ready') { ?>
                                    <span class="badge bg-success">Ready</span>
                                <?php } elseif ($row['status'] == 'disewa') { ?>
                                    <span class="badge bg-warning text-dark">Disewa</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">Maintenance</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php 
                                if (!empty($row['foto_kondisi'])) {
                                    $array_foto = explode(',', $row['foto_kondisi']);
                                    $jumlah_foto = count($array_foto);
                                    echo "<button class='btn btn-outline-secondary btn-sm' data-bs-toggle='modal' data-bs-target='#modalLihatFoto".$row['id']."'>
                                            <i class='fa fa-images me-1'></i> Lihat ($jumlah_foto)
                                          </button>";
                                } else {
                                    echo "<span class='text-muted small'>Tidak ada foto</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm text-dark fw-bold me-1" data-bs-toggle="modal" data-bs-target="#modalEditPS<?php echo $row['id']; ?>">Edit
                               </button>
                                </button>
                                <a href="modul/proses_ps.php?aksi=hapus&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus unit ini, Bang?')">
                                    hapus
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditPS<?php echo $row['id']; ?>" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Form Ubah Data Unit</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="modul/proses_ps.php?aksi=edit" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nomor Seri Unit</label>
                                                <input type="text" class="form-control" name="nomor_seri" value="<?php echo $row['nomor_seri']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Console</label>
                                                <select class="form-select" name="jenis_ps" required>
                                                    <option value="PS3" <?php echo ($row['jenis_ps'] == 'PS3') ? 'selected' : ''; ?>>PlayStation 3</option>
                                                    <option value="PS4" <?php echo ($row['jenis_ps'] == 'PS4') ? 'selected' : ''; ?>>PlayStation 4</option>
                                                    <option value="PS5" <?php echo ($row['jenis_ps'] == 'PS5') ? 'selected' : ''; ?>>PlayStation 5</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Harga Sewa Per Jam (Rp)</label>
                                                <input type="number" class="form-control" name="harga_per_jam" value="<?php echo $row['harga_per_jam']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status Unit</label>
                                                <select class="form-select" name="status" required>
                                                    <option value="ready" <?php echo ($row['status'] == 'ready') ? 'selected' : ''; ?>>Ready</option>
                                                    <option value="disewa" <?php echo ($row['status'] == 'disewa') ? 'selected' : ''; ?>>Disewa</option>
                                                    <option value="maintenance" <?php echo ($row['status'] == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Update Foto Kondisi </label>
                                                <input type="file" class="form-control" name="foto_kondisi[]" accept="image/*" multiple>
                                                <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto kondisi lama.</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning w-100 py-2 text-dark" style="font-weight: 500;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($row['foto_kondisi'])) { ?>
                        <div class="modal fade" id="modalLihatFoto<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Foto Kondisi Unit <?php echo $row['nomor_seri']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <?php 
                                            foreach ($array_foto as $foto) {
                                                echo "<div class='col-md-4'>
                                                        <div class='card shadow-sm'>
                                                            <img src='uploads/foto_ps/".trim($foto)."' class='card-img-top' style='height: 180px; object-fit: cover;'>
                                                        </div>
                                                      </div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white">
        <h4 class="mb-3 text-secondary" style="font-weight: 500;"></h4>
        
        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm" style="max-height: 400px;">
            <iframe src="https://www.youtube.com/embed/c51ND9Hdbw0" 
                    title="Video Tutorial Aplikasi" 
                    allowfullscreen></iframe>
        </div>
    </div>
    </div>

<div class="modal fade" id="modalTambahPS" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 500;">Form Tambah Unit PS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="modul/proses_ps.php?aksi=tambah" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomor Seri Unit</label>
                        <input type="text" class="form-control" name="nomor_seri" placeholder="Contoh: PS5-004" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Console</label>
                        <select class="form-select" name="jenis_ps" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="PS3">PlayStation 3</option>
                            <option value="PS4">PlayStation 4</option>
                            <option value="PS5">PlayStation 5</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Sewa Per Jam (Rp)</label>
                        <input type="number" class="form-control" name="harga_per_jam" placeholder="Contoh: 10000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Foto Kondisi Unit</label>
                        <input type="file" class="form-control" name="foto_kondisi[]" accept="image/*" multiple required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 py-2">Simpan Data Unit</button>
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
        $('#tabelPS').DataTable({
            "language": {
                "search": "Cari Unit PS:"
            }
        });
    });
</script>

</div>
</div>
</body>
</html>