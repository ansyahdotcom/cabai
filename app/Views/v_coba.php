<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="page-inner">
    <h4 class="page-title">Data Olah Fuzzy</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tabel Nilai Awal</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Suhu</th>
                                        <th>Kelembaban</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Suhu</th>
                                        <th>Kelembaban</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    foreach ($awal as $aw) : ?>
                                        <tr>
                                            <td><?= date('d-m-Y, H:i:s', strtotime($aw['created_dht'])); ?></td>
                                            <td><?= $aw['suhu']; ?></td>
                                            <td><?= $aw['kelembaban']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tabel Nilai Awal</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>m_klmbban_kering</th>
                                        <th>m_klmbban_basah</th>
                                        <th>m_suhu_rendah</th>
                                        <th>m_suhu_cukup</th>
                                        <th>m_suhu_tinggi</th>
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
                                        <th>m_klmbban_kering</th>
                                        <th>m_klmbban_basah</th>
                                        <th>m_suhu_rendah</th>
                                        <th>m_suhu_cukup</th>
                                        <th>m_suhu_tinggi</th>
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
                                    foreach ($olah as $ol) : ?>
                                        <tr>
                                            <th><?= $ol['m_klmbban_kering']; ?></th>
                                            <th><?= $ol['m_klmbban_basah']; ?></th>
                                            <th><?= $ol['m_suhu_rendah']; ?></th>
                                            <th><?= $ol['m_suhu_cukup']; ?></th>
                                            <th><?= $ol['m_suhu_tinggi']; ?></th>
                                            <th><?= $ol['a1']; ?></th>
                                            <th><?= $ol['a2']; ?></th>
                                            <th><?= $ol['a3']; ?></th>
                                            <th><?= $ol['a4']; ?></th>
                                            <th><?= $ol['a5']; ?></th>
                                            <th><?= $ol['a6']; ?></th>
                                            <th><?= $ol['z1']; ?></th>
                                            <th><?= $ol['z2']; ?></th>
                                            <th><?= $ol['z3']; ?></th>
                                            <th><?= $ol['z4']; ?></th>
                                            <th><?= $ol['z5']; ?></th>
                                            <th><?= $ol['z6']; ?></th>
                                            <th><?= $ol['total_AiZi']; ?></th>
                                            <th><?= $ol['total_a']; ?></th>
                                            <th><?= $ol['total_Z']; ?></th>
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
<?= $this->endSection(); ?>