<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="page-inner">
    <!-- <h4 class="page-title">Diagram Suhu Air</h4> -->
    <div class="row text-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Diagram Suhu Air</h4>
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
                    <h4 class="card-title">Diagram PPM Air</h4>
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
                    <h4 class="card-title">Diagram PH Air</h4>
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