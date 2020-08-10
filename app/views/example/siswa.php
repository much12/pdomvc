<?php

/***
 *      __  __            _                     _          _   ______              _   __  ____      _______ 
 *     |  \/  |          | |         /\        (_)        | | |  ____|            (_) |  \/  \ \    / / ____|
 *     | \  / | ___   ___| |__      /  \   _ __ _ ______ _| | | |__ __ _ _   _ _____  | \  / |\ \  / / |     
 *     | |\/| |/ _ \ / __| '_ \    / /\ \ | '__| |_  / _` | | |  __/ _` | | | |_  / | | |\/| | \ \/ /| |     
 *     | |  | | (_) | (__| | | |  / ____ \| |  | |/ / (_| | | | | | (_| | |_| |/ /| | | |  | |  \  / | |____ 
 *     |_|  |_|\___/ \___|_| |_| /_/    \_\_|  |_/___\__,_|_| |_|  \__,_|\__,_/___|_| |_|  |_|   \/   \_____|
 *                                                                                                           
 *                                                                                                           
 */

defined('BASEPATH') or die('No direct script access allowed!');
?>
<div class="container-fluid" style="padding-top: 1rem;">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Data Siswa

                    <div class="float-right">
                        <a href="<?= base_url('siswa/tambah') ?>" title="Tambah Data Siswa" class="btn btn-primary btn-sm">Tambah Data Siswa</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($data['siswa']->num_rows() > 0) : ?>
                                <?php foreach ($data['siswa']->result() as $key => $value) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $value->nis ?></td>
                                        <td><?= $value->nama ?></td>
                                        <td><?= $value->kelas ?></td>
                                        <td><?= $value->jenis_kelamin ?></td>
                                        <td><?= DateFormat($value->tanggal_lahir, 'd F Y'); ?></td>
                                        <td>
                                            <a href="<?= base_url('siswa/edit/' . $value->id) ?>" title="Edit" class="btn btn-primary btn-sm">
                                                <span>Edit</span>
                                            </a>

                                            <button type="button" class="btn btn-danger btn-sm" onclick="delete_siswa(<?= $value->id ?>)">
                                                <span>Hapus</span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data yang ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function delete_siswa(id) {
        let confirm_var = confirm('Apakah anda yakin ingin menghapus data?');

        if (confirm_var) {
            window.location.href = '<?= base_url('siswa/delete/') ?>' + id;
        }
    }
</script>