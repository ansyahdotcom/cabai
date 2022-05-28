<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="page-inner">
    <h4 class="page-title">Data Perhitungan Fuzzy</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Card</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Datatable</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger ml-auto" style="float: right;" data-toggle="modal" data-target="#resetModal">
                                Reset data
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>N awal</th>
                                            <th>dingin</th>
                                            <th>normal</th>
                                            <th>panas</th>
                                            <th>rendah</th>
                                            <th>cukup</th>
                                            <th>tinggi</th>
                                            <th>asam</th>
                                            <th>netral</th>
                                            <th>basa</th>
                                            <?php for ($i = 1; $i <= 27; $i++) : ?>
                                                <th>a <?= $i; ?></th>
                                            <?php endfor; ?>
                                            <?php for ($j = 1; $j <= 27; $j++) : ?>
                                                <th>z <?= $j; ?></th>
                                            <?php endfor; ?>
                                            <th>total_AiZi</th>
                                            <th>total_a</th>
                                            <th>total_Z</th>
                                            <th>kondisi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>N awal</th>
                                            <th>dingin</th>
                                            <th>normal</th>
                                            <th>panas</th>
                                            <th>rendah</th>
                                            <th>cukup</th>
                                            <th>tinggi</th>
                                            <th>asam</th>
                                            <th>netral</th>
                                            <th>basa</th>
                                            <?php for ($i = 1; $i <= 27; $i++) : ?>
                                                <th>a <?= $i; ?></th>
                                            <?php endfor; ?>
                                            <?php for ($j = 1; $j <= 27; $j++) : ?>
                                                <th>z <?= $j; ?></th>
                                            <?php endfor; ?>
                                            <th>total_AiZi</th>
                                            <th>total_a</th>
                                            <th>total_Z</th>
                                            <th>kondisi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $db = \Config\Database::connect();
                                        $nilai = $db->query("SELECT * FROM nilai_awal, hitung_fuzzy WHERE nilai_awal.id_awal = hitung_fuzzy.id_awal
                                    ORDER BY nilai_awal.id_awal DESC");
                                        foreach ($nilai->getResultArray() as $data) :
                                        ?>
                                            <tr>
                                                <td><?= date('d-m, H:i', strtotime($data['created_awal'])); ?></td>
                                                <td>
                                                    Suhu : <?= $data['suhu']; ?>
                                                    <br>
                                                    PPM : <?= $data['ppm']; ?>
                                                    <br>
                                                    PH : <?= $data['ph']; ?>
                                                </td>
                                                <td><?= $data['m_shu_dngin'] ?></td>
                                                <td><?= $data['m_shu_nrmal'] ?></td>
                                                <td><?= $data['m_shu_pnas'] ?></td>
                                                <td><?= $data['m_ppm_rndah'] ?></td>
                                                <td><?= $data['m_ppm_ckup'] ?></td>
                                                <td><?= $data['m_ppm_tnggi'] ?></td>
                                                <td><?= $data['m_ph_asam'] ?></td>
                                                <td><?= $data['m_ph_ntral'] ?></td>
                                                <td><?= $data['m_ph_basa'] ?></td>
                                                <?php for ($x = 1; $x <= 27; $x++) : ?>
                                                    <td><?= $data['a' . $x]; ?></td>
                                                <?php endfor; ?>
                                                <?php for ($y = 1; $y <= 27; $y++) : ?>
                                                    <td><?= $data['z' . $y]; ?></td>
                                                <?php endfor; ?>
                                                <td><?= $data['total_AiZi']; ?></td>
                                                <td><?= $data['total_a']; ?></td>
                                                <td><?= $data['total_Z']; ?></td>
                                                <td><?= $data['id_knd']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                        $db = \Config\Database::connect();
                        $data = $db->query("SELECT * FROM hitung_fuzzy ORDER BY id_ht DESC LIMIT 1");
                        $nilai = $db->query("SELECT * FROM nilai_awal ORDER BY id_awal DESC LIMIT 1");
                        foreach ($data->getResultArray() as $dt) :
                            foreach ($nilai->getResultArray() as $n) :
                        ?>
                                <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Kondisi</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= ($dt['id_knd'] == 1) ? 'kondisi bagus' : 'kondisi kurang bagus' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Suhu air</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $n['suhu'] ?> &#8451;">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">PPM</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $n['ppm'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">PH</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $n['ph'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu dingin</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_shu_dngin'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu normal</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_shu_nrmal'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu panas</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_shu_pnas'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu rendah</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_ppm_rndah'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu cukup</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_ppm_ckup'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu tinggi</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_ppm_tnggi'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu asam</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_ph_asam'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu netral</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_ph_ntral'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Miu basa</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['m_ph_basa'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Total Ai*Zi</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['total_AiZi'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Total alfa</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['total_a'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group form-inline">
                                                <label class="col-md-3 col-form-label">Total Z</label>
                                                <div class="col-md-9 p-0">
                                                    <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['total_Z'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <?php for ($k = 1; $k <= 27; $k++) : ?>
                                                <div class="form-group form-inline">
                                                    <label class="col-md-3 col-form-label">a<?= $k; ?></label>
                                                    <div class="col-md-9 p-0">
                                                        <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['a' . $k] ?>">
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php for ($l = 1; $l <= 27; $l++) : ?>
                                                <div class="form-group form-inline">
                                                    <label class="col-md-3 col-form-label">z<?= $l ?></label>
                                                    <div class="col-md-9 p-0">
                                                        <input type="text" class="form-control input-full text-right" readonly value="<?= $dt['z' . $l] ?>">
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal -->
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reset Data Perhitungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="hitung/reset_data" method="post">
                <div class="modal-body">
                    <?= csrf_field(); ?>
                    <p>Apakah Anda yakin akan menghapus semua data perhitungan?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>