<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="page-inner">
    <h4 class="page-title">Data Olah PPM</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary text-right" style="float: right;" data-toggle="modal" data-target="#tambahData">
                        Tambah data
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Rentang Umur</th>
                                        <th>Rendah</th>
                                        <th>Cukup minimal</th>
                                        <th>Cukup maksimal</th>
                                        <th>Tinggi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($data_ppm as $ppm) :
                                        $id_ppm = $ppm['id_ppm'];
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $ppm['umur']; ?></td>
                                            <td><?= $ppm['ppm_rendah']; ?></td>
                                            <td><?= $ppm['ppm_cukup_min']; ?></td>
                                            <td><?= $ppm['ppm_cukup_max']; ?></td>
                                            <td><?= $ppm['ppm_tinggi']; ?></td>
                                            <td>
                                                <button type="button" class="btn <?= ($ppm['st_ppm'] == 1) ? 'btn-warning' : 'btn-secondary' ?> btn-sm" data-toggle="modal" data-target="#stppm<?= $id_ppm ?>">
                                                    <?= ($ppm['st_ppm'] == 1) ? 'aktif' : 'nonaktif' ?>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ubahppm<?= $id_ppm ?>">
                                                    Ubah
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapusppm<?= $id_ppm ?>">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah data PPM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="ppm/tambah" method="post">
                <div class="modal-body">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label for="umur">Rentang Umur</label>
                        <input type="text" id="umur" name="umur" class="form-control" placeholder="Contoh : 14 - 28 HST" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="ppm_cukup_min">PPM Minimal</label>
                        <input type="number" id="ppm_cukup_min" name="ppm_cukup_min" class="form-control" placeholder="Isikan hanya angka" autocomplete="off" required>
                    </div>
                    <div class="form-group">`
                        <label for="ppm_cukup_max">PPM Maksimal</label>
                        <input type="number" id="ppm_cukup_max" name="ppm_cukup_max" class="form-control" placeholder="Isikan hanya angka" autocomplete="off" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
foreach ($data_ppm as $ppm) :
    $id_ppm = $ppm['id_ppm'];
    $umur = $ppm['umur'];
    $ppm_cukup_min = $ppm['ppm_cukup_min'];
    $ppm_cukup_max = $ppm['ppm_cukup_max'];
    $st_ppm = $ppm['st_ppm'];
?>
    <!-- Modal aktifkan PPM -->
    <div class="modal fade" id="stppm<?= $id_ppm; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Status PPM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="ppm/ubah_stppm" method="post">
                    <div class="modal-body">
                        <?= csrf_field(); ?>
                        <?php if ($st_ppm == 0) : ?>
                            <p>Apakah Anda akan mengaktifkan rentang umur <?= $umur; ?> ini?</p>
                        <?php else : ?>
                            <p>Pilihan rentang umur tidak boleh semuanya nonaktif.</p>
                        <?php endif; ?>
                        <input type="hidden" name="id_ppm" value="<?= $id_ppm; ?>">
                        <input type="hidden" name="st_ppm" value="<?= ($st_ppm == 0) ? '1' : '0'; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" <?= ($st_ppm == 0) ? '' : 'disabled'; ?>>Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Ubah -->
    <div class="modal fade" id="ubahppm<?= $id_ppm; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Data PPM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="ppm/ubah" method="post">
                    <div class="modal-body">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label for="umur">Rentang Umur</label>
                            <input type="text" id="umur" name="umur" class="form-control" placeholder="Contoh : 14 - 28 HST" autocomplete="off" required value="<?= $umur; ?>">
                            <input type="hidden" id="id_ppm" name="id_ppm" value="<?= $id_ppm; ?>">
                        </div>
                        <div class="form-group">
                            <label for="ppm_cukup_min">PPM Minimal</label>
                            <input type="number" id="ppm_cukup_min" name="ppm_cukup_min" class="form-control" placeholder="Isikan hanya angka" autocomplete="off" required value="<?= $ppm_cukup_min; ?>">
                        </div>
                        <div class="form-group">
                            <label for="ppm_cukup_max">PPM Maksimal</label>
                            <input type="number" id="ppm_cukup_max" name="ppm_cukup_max" class="form-control" placeholder="Isikan hanya angka" autocomplete="off" required value="<?= $ppm_cukup_max; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal hapus -->
    <div class="modal fade" id="hapusppm<?= $id_ppm; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data PPM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="ppm/hapus" method="post">
                    <div class="modal-body">
                        <?= csrf_field(); ?>
                        <p>Apakah Anda yakin akan menghapus data <b><?= $umur; ?></b>?</p>
                        <input type="hidden" name="id_ppm" value="<?= $id_ppm; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?= $this->endSection(); ?>