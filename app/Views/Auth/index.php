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
                                <h3 class="text-left font-weight-light my-2">Sign In</h3>
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
                                <form action="<?= base_url(); ?>/auth/process_login" method="post">
                                    <?= csrf_field(); ?>
                                    <div class="form-group">
                                        <label class="small mb-1">Email</label>
                                        <input class="form-control py-4 <?= ($validation->hasError('email') ? 'is-invalid' : ''); ?>" id="inputEmailAddress" type="emailS" placeholder="Enter email address" name="email" autocomplete="off" value="<?= old('email'); ?>" />
                                        <div class="invalid-feedback" style="font-size: small">
                                            <?= $validation->getError('email'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputPassword">Password</label>
                                        <input class="form-control py-4 <?= ($validation->hasError('password') ? 'is-invalid' : ''); ?>" id="inputPassword" type="password" placeholder="Enter password" name="password" autocomplete="off" />
                                        <div class="invalid-feedback" style="font-size: small">
                                            <?= $validation->getError('password'); ?>
                                        </div>
                                    </div>

                                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <a class="small mb-3" href="password.html">Forgot Password?</a>
                                    </div>

                                    <input type="submit" value="Log In" class="btn btn-block btn-primary mb-2">


                                    <div class="social-login">
                                        <a href="<?= $link; ?>" class="google btn d-flex justify-content-center align-items-center">
                                            <span class="google mr-3"></span></i>Login with Google
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small"><a href="<?= base_url(); ?>/register">Need an account? Sign up!</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?= $this->endSection(); ?>