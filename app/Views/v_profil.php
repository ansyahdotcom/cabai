<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="page-inner">
    <h4 class="page-title">Profil Admin</h4>
    <div class="row">
        <div class="col-md-12">
            <?php foreach ($admin as $adm) : ?>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <h4 class="mb-4 fw-bold">Username </h4>
                            </div>
                            <div class="col-md-10">
                                <h4 class="mb-4 fw-bold"> : <?= $adm['username']; ?></h4>
                            </div>
                        </div>
                        <div class="form">
                            <div class="card">
                                <div class="card-header">Ubah Password</div>
                                <div class="card-body">
                                    <form action="profile/ubahpassword" method="post">
                                        <div class="form-group">
                                            <label for="password">Password Lama</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password Lama">
                                        </div>
                                        <div class="form-group">
                                            <label for="password1">Password Baru</label>
                                            <input type="password" class="form-control" id="password1" name="password_baru" placeholder="Masukkan Password Baru">
                                        </div>
                                        <div class="form-group">
                                            <label for="password2">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" id="password2" name="password_baru_ulang" placeholder="Konfirmasi Password Baru">
                                            <input type="text" hidden value="<?= $adm['username'] ?>" name="username">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>