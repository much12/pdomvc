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

class Siswa extends Controller
{
    private $siswa, $kelas;

    public function __construct()
    {
        $this->siswa = $this->model('Siswa_model');
        $this->kelas = $this->model('Kelas_model');
    }

    /**
     * index
     * 
     * @return mixed
     */
    public function index()
    {
        $data['title'] = 'Data Siswa';
        $data['content'] = 'example/siswa';
        $data['siswa'] = $this->siswa->getDataSiswa();

        $this->view('example/index', $data);
    }

    /**
     * delete
     * 
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        $data['id'] = $id;

        $getDataSiswa = $this->siswa->getDataSiswa($data);

        if ($getDataSiswa->num_rows() == 0) {
            echo "
            <script>
                alert('Data tidak ditemukan');
                window.location.href = '" . base_url('siswa') . "';
            </script>
            ";
        }

        $delete = $this->siswa->where($data)->delete();

        if ($delete) {
            echo "
            <script>
                alert('Data berhasil dihapus');
                window.location.href = '" . base_url('siswa') . "';
            </script>
            ";
        } else {
            echo "
            <script>
                alert('Data gagal dihapus');
                window.location.href = '" . base_url('siswa') . "';
            </script>
            ";
        }
    }

    /**
     * tambah
     * 
     * @return mixed
     */
    public function tambah()
    {
        $data['title'] = 'Tambah Data Siswa';
        $data['content'] = 'example/tambah';
        $data['kelas'] = $this->kelas->getDataKelas();

        return $this->view('example/index', $data);
    }

    /**
     * add_process
     * 
     * @return mixed
     */
    public function add_process()
    {
        $nis = $this->post('nis');
        $nama = $this->post('nama');
        $jenis_kelamin = $this->post('jenis_kelamin');
        $tanggal_lahir = $this->post('tanggal_lahir');
        $kelas = $this->post('kelas');

        $getDataKelas = $this->kelas->getDataKelas(array(
            'id' => $kelas
        ));

        if ($getDataKelas->num_rows() == 0) {
            echo "
            <script>
                alert('Kelas tidak ditemukan');
                window.location.href = '" . base_url('siswa/tambah') . "';
            </script>
            ";
        }

        $data['nis'] = $nis;
        $data['nama'] = $nama;
        $data['jenis_kelamin'] = $jenis_kelamin;
        $data['tanggal_lahir'] = DateFormat($tanggal_lahir, 'Y-m-d');
        $data['id_kelas'] = $kelas;

        $insert = $this->siswa->insert($data);

        if ($insert) {
            echo "
            <script>
                alert('Data berhasil disimpan');
                window.location.href = '" . base_url('siswa') . "';
            </script>
            ";
        } else {
            echo "
            <script>
                alert('Data gagal disimpan');
                window.location.href = '" . base_url('siswa') . "';
            </script>
            ";
        }
    }

    /**
     * edit
     * 
     * @param int $id
     * @return mixed
     */
    public function edit($id)
    {
        $data['a.id'] = $id;

        $getDataSiswa = $this->siswa->getDataSiswa($data);

        if ($getDataSiswa->num_rows() == 0) {
            echo "
            <script>
                alert('Data tidak ditemukan');
                window.location.href = '" . base_url('siswa') . "';
            </script>
            ";
        }

        $data['title'] = 'Edit Data Siswa';
        $data['content'] = 'example/edit';
        $data['row'] = $getDataSiswa->row();
        $data['kelas'] = $this->kelas->getDataKelas();

        return $this->view('example/index', $data);
    }

    public function process_edit()
    {
        $id = $this->post('id');
        $nis = $this->post('nis');
        $nama = $this->post('nama');
        $jenis_kelamin = $this->post('jenis_kelamin');
        $tanggal_lahir = $this->post('tanggal_lahir');
        $kelas = $this->post('kelas');

        $getDataKelas = $this->kelas->getDataKelas(array(
            'id' => $kelas
        ));

        if ($getDataKelas->num_rows() == 0) {
            echo "
            <script>
                alert('Kelas tidak ditemukan');
                window.location.href = '" . base_url('siswa/edit/' . $id) . "';
            </script>
            ";
        }

        $data['nis'] = $nis;
        $data['nama'] = $nama;
        $data['jenis_kelamin'] = $jenis_kelamin;
        $data['tanggal_lahir'] = DateFormat($tanggal_lahir, 'Y-m-d');
        $data['id_kelas'] = $kelas;

        $update = $this->siswa->update(array(
            'id' => $id
        ), $data);

        if ($update) {
            echo "
            <script>
                alert('Data berhasil diupdate');
                window.location.href = '" . base_url('siswa') . "';
            </script>
            ";
        } else {
            echo "
            <script>
                alert('Data gagal diupdate');
                window.location.href = '" . base_url('siswa') . "';
            </script>
            ";
        }
    }
}
