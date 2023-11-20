<?php

class Example extends Controller
{
    public $model_name = "Example";

    // Main Routing //

    public function index()
    {
        $data['judul'] = 'Example';

        // $data['user'] = $this->user;             @jika sudah memakai jwt
        // $akses = ['all', 'mastertu', 'kurikulum']; @untuk menentukan akses user melalui kolom hak_akses

        $data['example'] = $this->model("$this->model_name", 'Example_model')->getAllExistData();

        if (in_array($data['user']['hak_akses'], $akses)) {   //jika akses ada di array $akses maka view akan tampil dibawah ini, biasanya page akan bisa crud
            $this->view('templates/header', $data);
            $this->view('example/index', $data);
            $this->view('templates/footer');
        } else if ($data['user']['hak_akses'] == '') {   //jika hak_akses user == ''
            header("Location: " . BASEURL);
            Flasher::setFlash('GAGAL', 'Anda Tidak Mempunyai Akses Untuk Menuju Halaman Tersebut', 'danger');
        } else {  // selain itu, page akan tampil tapi biasanya hanya bisa read, tidak bisa create update n delete
            $this->view('templates/header', $data);
            $this->view('example/detail', $data);
            $this->view('templates/footer');
        }
    }

    // Tambah Data //

    public function tambahData()
    {

        if ($this->model("$this->model_name", "Example_model")->tambahData($_POST) > 0) {
            Flasher::setFlash('BERHASIL', 'Ditambahkan', 'success');
        } else {
            Flasher::setFlash('GAGAL', 'Ditambahkan', 'danger');
        }
        header("Location: " . BASEURL . "/example");
        exit;
    }

    // Hapus Data //

    public function hapusData($id)
    {
        if ($this->model("$this->model_name", "Example_model")->hapusData($id) > 0) {
            Flasher::setFlash('BERHASIL', 'Dihapus', 'success');
        } else {
            Flasher::setFlash('GAGAL', 'Dihapus', 'danger');
        }
        header("Location: " . BASEURL . "/example");
        exit;
    }

    // Edit Data //

    public function getUbahData()
    {
        echo json_encode($this->model("$this->model_name", "Example_model")->getDataById($_POST["id"]));
    }

    public function ubahData()
    {
        if ($this->model("$this->model_name", "Example_model")->ubahData($_POST) > 0) {
            Flasher::setFlash('BERHASIL', 'Diubah', 'success');
        } else {
            Flasher::setFlash('GAGAL', 'Diubah', 'danger');
        }
        header("Location: " . BASEURL . "/example");
        exit;
    }
}
