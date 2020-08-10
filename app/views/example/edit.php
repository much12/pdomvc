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
                <form action="<?= base_url('siswa/process_edit') ?>" method="POST" autocomplete="off">
                    <input type="hidden" name="id" value="<?= $data['row']->id ?>">
                    <div class="card-header">
                        Edit Data Siswa
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>NIS</label>
                            <input type="text" name="nis" class="form-control" placeholder="NIS" value="<?= $data['row']->nis ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama" value="<?= $data['row']->nama ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="Laki Laki" <?= $data['row']->jenis_kelamin == 'Laki Laki' ? 'selected' : null ?>>Laki Laki</option>
                                <option value="Perempuan" <?= $data['row']->jenis_kelamin == 'Perempuan' ? 'selected' : null ?>>Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" placeholder="Tanggal Lahir" value="<?= DateFormat($data['row']->tanggal_lahir, 'Y-m-d') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="kelas" class="form-control" required>
                                <?php foreach ($data['kelas']->result() as $key => $value) : ?>
                                    <option value="<?= $value->id ?>" <?= $data['row']->id_kelas == $value->id ? 'selected' : null ?>><?= $value->kelas ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-right">Edit</button>
                        <a href="<?= base_url('siswa') ?>" title="Back" class="btn btn-danger btn-sm float-right mr-1">Kembali</a>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>