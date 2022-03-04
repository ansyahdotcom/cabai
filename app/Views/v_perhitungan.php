<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="page-inner">
    <h4 class="page-title">Data Perhitungan Fuzzy</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- <div class="card-header">
                    <h4 class="card-title">Tabel Perhitungan Awal</h4>
                </div> -->
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>N_awal</th>
                                        <th>kering</th>
                                        <th>basah</th>
                                        <th>rendah</th>
                                        <th>cukup</th>
                                        <th>tinggi</th>
                                        <th>a1</th>
                                        <th>a2</th>
                                        <th>a3</th>
                                        <th>a4</th>
                                        <th>a5</th>
                                        <th>a6</th>
                                        <th>z1</th>
                                        <th>z2</th>
                                        <th>z3</th>
                                        <th>z4</th>
                                        <th>z5</th>
                                        <th>z6</th>
                                        <th>total_AiZi</th>
                                        <th>total_a</th>
                                        <th>total_Z</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>N_awal</th>
                                        <th>kering</th>
                                        <th>basah</th>
                                        <th>rendah</th>
                                        <th>cukup</th>
                                        <th>tinggi</th>
                                        <th>a1</th>
                                        <th>a2</th>
                                        <th>a3</th>
                                        <th>a4</th>
                                        <th>a5</th>
                                        <th>a6</th>
                                        <th>z1</th>
                                        <th>z2</th>
                                        <th>z3</th>
                                        <th>z4</th>
                                        <th>z5</th>
                                        <th>z6</th>
                                        <th>total_AiZi</th>
                                        <th>total_a</th>
                                        <th>total_Z</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    foreach ($awal as $aw) :
                                        foreach ($hitung as $ht) : ?>
                                            <tr>
                                                <td><?= date('d-m-Y, H:i:s', strtotime($aw['created_awal'])); ?></td>
                                                <td>
                                                    Suhu : <?= $aw['suhu']; ?>
                                                    <br>
                                                    PPM : <?= $aw['ppm']; ?>
                                                    <br>
                                                    PH : <?= $aw['ph']; ?>
                                                </td>
                                                <td><?= $ht['m_klmbban_kering']; ?></td>
                                                <td><?= $ht['m_klmbban_basah']; ?></td>
                                                <td><?= $ht['m_suhu_rendah']; ?></td>
                                                <td><?= $ht['m_suhu_cukup']; ?></td>
                                                <td><?= $ht['m_suhu_tinggi']; ?></td>
                                                <td><?= $ht['a1']; ?></td>
                                                <td><?= $ht['a2']; ?></td>
                                                <td><?= $ht['a3']; ?></td>
                                                <td><?= $ht['a4']; ?></td>
                                                <td><?= $ht['a5']; ?></td>
                                                <td><?= $ht['a6']; ?></td>
                                                <td><?= $ht['z1']; ?></td>
                                                <td><?= $ht['z2']; ?></td>
                                                <td><?= $ht['z3']; ?></td>
                                                <td><?= $ht['z4']; ?></td>
                                                <td><?= $ht['z5']; ?></td>
                                                <td><?= $ht['z6']; ?></td>
                                                <td><?= $ht['total_AiZi']; ?></td>
                                                <td><?= $ht['total_a']; ?></td>
                                                <td><?= $ht['total_Z']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
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
<?= $this->endSection(); ?>