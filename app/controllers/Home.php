<?php

class Home extends Controller
{
    public function index()
    {
        $data['judul'] = 'Welcome';

        // $data['user'] = $this->user; @ if already using jwt

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }
}
