<?php

require_once APPPATH . 'controllers/base/BaseController.php';

class Operador extends BaseController {

    function Operador() {
        parent::Controller();
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {

        if ($this->utilitario->autorizar(1, $this->session->userdata('modulo')) == true) {
            $this->pesquisar();
        } else {
            $data['mensagem'] = 'Usuario sem permissao';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "cadastros/pacientes/pesquisarbe", $data);
        }
    }

    function novo() {
        $data['listarPerfil'] = $this->operador_m->listarPerfil();
        $this->loadView('seguranca/operador-form', $data);
    }

    function novorecepcao() {
        $this->loadView('seguranca/operador-formrecepcao');
    }

    function alterarrecepcao($operador_id) {
        $obj_operador_id = new operador_model($operador_id);
        $data['obj'] = $obj_operador_id;
        $this->loadView('seguranca/operador-formrecepcao', $data);
    }

    function alterar($operador_id) {
        $obj_operador_id = new operador_model($operador_id);
        $data['obj'] = $obj_operador_id;
        $data['listarPerfil'] = $this->operador_m->listarPerfil();
        $this->loadView('seguranca/operador-form', $data);
    }

    function alteraSenha($operador_id) {
        $data['lista'] = $this->operador_m->listarCada($operador_id);

        $this->loadView('seguranca/operador-novasenha', $data);
    }

    function gravarNovaSenha() {
        $novasenha = $_POST['txtNovaSenha'];
        $confirmacao = $_POST['txtConfirmacao'];

        if ($novasenha == $confirmacao) {
            if ($this->operador_m->gravarNovaSenha()) {
                $data['mensagem'] = 'Nova senha cadastrada com sucesso.';
            } else {
                $data['mensagem'] = 'Erro ao cadastrar nova senha . Opera&ccedil;&atilde;o cancelada.';
            }
        } else {
            $data['mensagem'] = 'Confirma&ccedil;&atilde;o de nova senha diferente da nova senha . Opera&ccedil;&atilde;o cancelada.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador", $data);
    }

    function pesquisar($filtro = -1, $inicio = 0) {
        $this->loadView('seguranca/operador-lista');
    }

    function pesquisarrecepcao($filtro = -1, $inicio = 0) {
        echo '<html>
        <script type="text/javascript">
 //       alert("Operação Efetuada Com Sucesso");
        window.onunload = fechaEstaAtualizaAntiga;
        function fechaEstaAtualizaAntiga() {
            window.opener.location.reload();
            }
        window.close();
            </script>
            </html>';
        $this->loadView('seguranca/operador-listarecepcao');
    }

    function operadorsetor($filtro = -1, $inicio = 0) {
        $this->loadView('estoque/operador-lista');
    }

    function gravar() {
        if ($this->operador_m->gravar()) {
            $data['mensagem'] = 'Operador cadastrado com sucesso.';
        } else {
            $data['mensagem'] = 'Erro ao cadastrar novo operador . Opera&ccedil;&atilde;o cancelada.';
        }
        $data['lista'] = $this->operador_m->listar($filtro = null, $maximo = null, $inicio = null);

//            redirect(base_url()."seguranca/operador/index/$data","refresh");
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador", $data);
    }

    function gravarrecepcao() {
        if ($this->operador_m->gravarrecepcao()) {
            $data['mensagem'] = 'Operador cadastrado com sucesso.';
        } else {
            $data['mensagem'] = 'Erro ao cadastrar novo operador . Opera&ccedil;&atilde;o cancelada.';
        }
        $data['lista'] = $this->operador_m->listar($filtro = null, $maximo = null, $inicio = null);

//            redirect(base_url()."seguranca/operador/index/$data","refresh");
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao", $data);
    }

    function excluirOperador($operador_id) {
        $this->operador_m->excluirOperador($operador_id);
        $data['mensagem'] = 'Operador excluido com sucesso.';

        $data['lista'] = $this->operador_m->listar($filtro = null, $maximo = null, $inicio = null);

//            redirect(base_url()."seguranca/operador/index/$data","refresh");
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador", $data);
    }

    private function carregarView($data = null, $view = null) {
        if (!isset($data)) {
            $data['mensagem'] = '';
        }

        if ($this->utilitario->autorizar(19, $this->session->userdata('modulo')) == true) {
            $this->load->view('header', $data);
            if ($view != null) {
                $this->load->view($view, $data);
            } else {
                $this->load->view('seguranca/operador-lista', $data);
            }
        } else {
            $data['mensagem'] = $this->mensagem->getMensagem('login005');
            $this->load->view('header', $data);
            $this->load->view('home');
        }
        $this->load->view('footer');
    }

}

?>
