<?= $this->extend('Template/admin_template'); ?>
<?= $this->section('content'); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Data Users</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Data Users</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    DataTable Data Users
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <!-- looping data user -->
                            <?php
                            $i = 1;
                            foreach ($data_users as  $data) :
                            ?>
                                <tbody>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $data['nama_lengkap']; ?></td>
                                        <td><?= $data['email']; ?></td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="" class=" btn btn-sm btn-danger tombol-hapus">Hapus</a>
                                        </td>
                                    </tr>

                                </tbody>
                            <?php
                                $i++;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>
<?= $this->endSection() ?>