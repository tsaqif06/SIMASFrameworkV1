<?php

class API extends Controller
{
    public function index()
    {
        echo "Page not found!";
    }

    // example //

    public function example($id = null)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        ($id == null) ?
            $data = $this->model("Example", 'Example_model')->getAllExistData() :
            $data = $this->model("Example", 'Example_model')->getDataById($id);
        ($data) ?
            $response = true :
            $response = false;
        echo json_encode(["success" => $response, "data" => $data]);
    }
}
