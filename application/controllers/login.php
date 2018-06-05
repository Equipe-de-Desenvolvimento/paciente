<?php

class Login extends Controller {

    function Login() {
        parent::Controller();
        $this->load->model('login_model', 'login');
        $this->load->library('mensagem');
    }

    function index() {
        $this->carregarView();
    }

    function autenticar() {
        $usuario = $_POST['txtLogin'];
        $senha = $_POST['txtSenha'];
        if (($this->login->autenticar($usuario, $senha)) &&
                ($this->session->userdata('autenticado') == true)) {
            redirect(base_url() . "home", "refresh");
        } else {
            $data['mensagem'] = $this->mensagem->getMensagem('login002');
            $this->carregarView($data);
        }
    }

    function sair() {
        $this->session->sess_destroy();
        $data['mensagem'] = $this->mensagem->getMensagem('login003');
        $this->carregarView($data);
    }

    private function carregarView($data = null, $view = null) {
        if (!isset($data)) {
            $data['mensagem'] = '';
        }
        $data['empresa'] = $this->login->listar();
//            var_dump($data['empresa']); die;
        if ($data['empresa'][0]->login_paciente == 't') {
            $this->load->view('login', $data);
        } else {
            $p = array(
                'autenticado' => true,
                'login_paciente' => false
            );
            $this->session->set_userdata($p);
//            return true;
            redirect(base_url() . "home", "refresh");
            echo 'pagina de agendamento apenas';
        }
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */