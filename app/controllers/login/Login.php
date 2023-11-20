<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Login extends Controller
{
    public static string $SECRET_KEY = "YOUR_SECRET_KEY"; // secret key bebas
    private $model_name = "Login";

    public function index()
    {
        if (isset($_COOKIE['YOURPROJECT-SESSION'])) {
            header("Location: " . BASEURL);
        }

        $data['judul'] = 'Login';

        $this->view('login/head', $data);
        $this->view('login/index');
        $this->view('login/foot');
    }

    public function logProccess()
    {
        // Validasi username dan password
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $data['username'] = $_POST['username'];
            $data['password'] = $_POST['password'];
            $user = $this->model("$this->model_name", "Login_model")->login($data);
            if ($user) {
                if ($this->model("$this->model_name", "Login_model")->log($user['id']) > 0) {
                    // Jika validasi berhasil, buat token JWT
                    $payload = [
                        'sub' => $user['id'],
                        'name' => $user['username'],
                        'role' => $user['role'],
                        'akses' => $user['hak_akses'],
                        'iat' => time(),
                        'exp' =>  time() + (7 * 24 * 60 * 60) // Token berlaku selama 1 hari
                    ];
                    Cookie::create_jwt($payload, $payload['exp']);
                    // Kirim token JWT sebagai respons
                    Flasher::setFlash('BERHASIL', 'Login', 'success');
                    header("Location: " . BASEURL);
                }
            } else {
                Flasher::setFlash('GAGAL', 'Login', 'danger');
                header("Location: " . BASEURL . "/login");
            }
        }
    }
}