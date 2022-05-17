<?= $this->extend('layout/header_login'); ?>
<?= $this->section('content'); ?>
<div class="col-md-4 ml-auto mr-auto pt-5">
    <div class="card">
        <form action="<?= base_url('auth/login') ?>" method="post">
            <?= csrf_field(); ?>
            <div class="card-body">
                <a href="<?= base_url('/') ?>">
                    <h1 class="card-title font-weight-bold text-center">IOT HIDROPONIK CABAI</h1>
                </a>
                <hr>
                <div class="card-title font-weight-bold text-center">Login Admin</div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control <?= (session()->getFlashdata('username')) ? 'is-invalid' : '' ?>" id="username" name="username" required placeholder="Masukkan username" autofocus autocomplete="off">
                    <?= session()->getFlashdata('username'); ?>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control <?= (session()->getFlashdata('password')) ? 'is-invalid' : '' ?>" id="password" name="password" required placeholder="Masukkan password">
                </div>
                <div class="form-group" align="right">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>