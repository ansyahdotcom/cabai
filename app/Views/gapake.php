<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tabel Olah Fuzzy</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>PPM</th>
                                <th>Suhu Air</th>
                                <th>PH Air</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>PPM</th>
                                <th>Suhu Air</th>
                                <th>PH Air</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            foreach ($awal as $aw) : ?>
                                <tr>
                                    <td><?= $aw['ppm']; ?></td>
                                    <td><?= $aw['suhu']; ?></td>
                                    <td><?= $aw['ph']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>