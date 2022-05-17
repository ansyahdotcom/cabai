<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php
$db = \Config\Database::connect();
$nilai = $db->query("SELECT * FROM nilai_awal ORDER BY id_awal DESC LIMIT 1");
foreach ($nilai->getResultArray() as $data) :
    $st_ppm = $db->query("SELECT * FROM ppm WHERE st_ppm = 1");
    foreach ($st_ppm->getResultArray() as $st) :
        $kondisi = $db->query("SELECT id_knd FROM hitung_fuzzy ORDER BY id_ht DESC LIMIT 1");
        foreach ($kondisi->getResultArray() as $knd) :
?>
    <div class="page-inner">
        <div class="row">
            <div class="col-sm-6 col-md-4">
                <div class="card card-stats <?= ($knd['id_knd'] == 1) ? 'card-success' : 'card-danger'; ?> card-round">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-3">
                                <div class="icon-big text-center">
                                    <i class="<?= ($knd['id_knd'] == 1) ? 'flaticon-success' : 'flaticon-error'; ?>"></i>
                                </div>
                            </div>
                            <div class="col-9 col-stats">
                                <div class="numbers">
                                    <p class="card-category"><?= $st['umur']; ?></p>
                                    <h3 class="card-title"><?= ($knd['id_knd'] == 1) ? 'kondisi bagus' : 'kondisi kurang bagus'; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-8">
                <div class="card card-stats card-secondary card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <div class="icon-big text-center">
                                    <i class="flaticon-network"></i>
                                </div>
                            </div>
                            <div class="col-2 col-stats">
                                <div class="numbers">
                                    <p class="card-category">PPM</p>
                                    <h4 class="card-title"><?= $data['ppm']; ?></h4>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="icon-big text-center">
                                    <i class="flaticon-interface-3"></i>
                                </div>
                            </div>
                            <div class="col-2 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Suhu</p>
                                    <h4 class="card-title"><?= $data['suhu']; ?></h4>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="icon-big text-center">
                                    <i class="flaticon-signs"></i>
                                </div>
                            </div>
                            <div class="col-2 col-stats">
                                <div class="numbers">
                                    <p class="card-category">PH</p>
                                    <h4 class="card-title"><?= $data['ph']; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endforeach; ?>
        <?php endforeach; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Grafik Suhu</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="lineChartsuhu"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Grafik PPM</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="lineChartppm"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Grafik PH</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="lineChartph"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->endSection(); ?>