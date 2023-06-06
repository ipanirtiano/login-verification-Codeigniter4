<?= $this->extend('Template/login_template'); ?>

<?= $this->section('content'); ?>
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-3">
                            <div class="card-header">
                                <h3 class="text-left font-weight-light my-2">Sign Up</h3>
                            </div>
                            <div class="card-body">
                                <!-- set session flash data message -->
                                <?php if (session()->getFlashdata('pesan')) : ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?= session()->getFlashdata('pesan'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('pesan-success')) : ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?= session()->getFlashdata('pesan-success'); ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                <!-- end set flash data message -->
                                <form action="<?= base_url(); ?>/auth/process_register" method="post">
                                    <?= csrf_field(); ?>
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputFirstName">Full Name</label>
                                        <input class="form-control py-8 <?= ($validation->hasError('nama_lengkap') ? 'is-invalid' : ''); ?>" id="inputFirstName" type="text" placeholder="Enter Full name" name="nama_lengkap" value="<?= old('nama_lengkap'); ?>" autocomplete="off" />
                                        <div class="invalid-feedback" style="font-size: small">
                                            <?= $validation->getError('nama_lengkap'); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                                        <input class="form-control py-4 <?= ($validation->hasError('email') ? 'is-invalid' : ''); ?>" id="inputEmailAddress" type="text" aria-describedby="emailHelp" placeholder="Enter email address" name="email" value="<?= old('email'); ?>" autocomplete="off" />
                                        <div class="invalid-feedback" style="font-size: small">
                                            <?= $validation->getError('email'); ?>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4 <?= ($validation->hasError('password1') ? 'is-invalid' : ''); ?>" id="inputPassword" type="password" placeholder="Enter password" name="password1" autocomplete="off" />
                                                <div class="invalid-feedback" style="font-size: small">
                                                    <?= $validation->getError('password1'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                <input class="form-control py-4 <?= ($validation->hasError('password2') ? 'is-invalid' : ''); ?>" id="inputConfirmPassword" type="password" placeholder="Confirm password" name="password2" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>

                                    <input type="submit" value="Sign Up" class="btn btn-block btn-primary mb-2">

                                    <div class="social-login">
                                        <a href="<?= $link; ?>" class="google btn d-flex justify-content-center align-items-center">
                                            <span class="google mr-3"></span></i>Login with Google
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a href="<?= base_url('/'); ?>">Have an account? Go to login</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?= $this->endSection(); ?>