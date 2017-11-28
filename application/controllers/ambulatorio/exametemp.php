<?php

require_once APPPATH . 'controllers/base/BaseController.php';

/**
 * Esta classe é o controler de Servidor. Responsável por chamar as funções e views, efetuando as chamadas de models
 * @author Equipe de desenvolvimento APH
 * @version 1.0
 * @copyright Prefeitura de Fortaleza
 * @access public
 * @package Model
 * @subpackage GIAH
 */
class Exametemp extends BaseController {

    function Exametemp() {
        parent::Controller();
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->model('ambulatorio/procedimentoplano_model', 'procedimentoplano');
        $this->load->model('cadastro/paciente_model', 'paciente');
        $this->load->library('mensagem');
        $this->load->library('utilitario');
        $this->load->library('pagination');
        $this->load->library('validation');
    }

    function index() {
        $this->pesquisar();
    }

    function pesquisar($args = array()) {

        $this->loadView('ambulatorio/exametemp-lista', $args);

//            $this->carregarView($data);
    }

    function novo() {
        $ambulatorio_pacientetemp_id = $this->exametemp->criar();
        $this->carregarexametemp($ambulatorio_pacientetemp_id);

//            $this->carregarView($data);
    }

    function novopaciente() {
        $data['idade'] = 0;
        $this->loadView('ambulatorio/pacientetemp-form', $data);
    }

    function novopacienteconsulta() {
        $data['idade'] = 0;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempconsulta-form', $data);
    }

    function novopacientefisioterapia() {
        $data['idade'] = 0;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempfisioterapia-form', $data);
    }

    function novopacienteconsultaencaixe() {
        $data['idade'] = 0;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempconsultaencaixe-form', $data);
    }

    function novopacientefisioterapiaencaixe() {
        $data['idade'] = 0;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $this->loadView('ambulatorio/pacientetempfisioterapiaencaixe-form', $data);
    }

    function pacienteconsultaencaixe($paciente_id) {
        $data['idade'] = 0;
        $data['paciente_id'] = $paciente_id;
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $this->loadView('ambulatorio/pacienteconsultaencaixe-form', $data);
    }

    function carregarexametemp($ambulatorio_pacientetemp_id) {
        $obj_exametemp = new exametemp_model($ambulatorio_pacientetemp_id);
        $data['obj'] = $obj_exametemp;
        $data['idade'] = 0;
        $data['contador'] = $this->exametemp->contador($ambulatorio_pacientetemp_id);
        if ($data['contador'] > 0) {
            $data['exames'] = $this->exametemp->listaragendas($ambulatorio_pacientetemp_id);
        }
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/exametemp-form', $data);
    }

    function unificar($paciente_id) {
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $data['paciente_id'] = $paciente_id;
        $this->loadView('ambulatorio/unificar-form', $data);
    }

    function gravarunificar() {
        $pacientetemp_id = $_POST['paciente_id'];
        $verifica = $this->exametemp->gravarunificacao();
        if ($verifica == "-1") {
            $data['mensagem'] = 'Erro ao unificar Paciente. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao unificar Paciente.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp/unificar/$pacientetemp_id");
    }

    function carregarpacientetemp($pacientetemp_id) {

        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['contador'] = $this->exametemp->contadorpaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpaciente($pacientetemp_id);
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/examepacientetemp-form', $data);
    }

    function carregarpacienteconsultatemp($pacientetemp_id) {

        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['contador'] = $this->exametemp->contadorconsultapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacienteconsulta($pacientetemp_id);
        $data['consultasanteriores'] = $this->exametemp->listarconsultaanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/consultapacientetemp-form', $data);
    }

    function carregarpacientefisioterapiatemp($pacientetemp_id) {

        $obj_paciente = new paciente_model($pacientetemp_id);
        $data['obj'] = $obj_paciente;
        $data['medico'] = $this->exametemp->listarmedicoconsulta();
        $data['contador'] = $this->exametemp->contadorfisioterapiapaciente($pacientetemp_id);
        $data['exames'] = $this->exametemp->listaragendatotalpacientefisioterapia($pacientetemp_id);
        $data['consultasanteriores'] = $this->exametemp->listarconsultaanterior($pacientetemp_id);
        $data['consultaanteriorcontador'] = $this->exametemp->listarconsultaanteriorcontador($pacientetemp_id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/fisioterapiapacientetemp-form', $data);
    }

    function carregarexame($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['exames'] = $this->exametemp->listaragendasexamepaciente($agenda_exames_id);
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/examepaciente-form', $data);
    }

    function carregarconsultatemp($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['consultas'] = $this->exametemp->listaragendasconsultapaciente($agenda_exames_id);
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/consultapaciente-form', $data);
    }

    function carregarfisioterapiatemp($agenda_exames_id) {
        $data['agenda_exames_id'] = $agenda_exames_id;
        $data['convenio'] = $this->procedimentoplano->listarconvenio();
        $data['consultas'] = $this->exametemp->listaragendasconsultapaciente($agenda_exames_id);
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/fisioterapiapaciente-form', $data);
    }

    function excluir($agenda_exames_id, $ambulatorio_pacientetemp_id) {
        $this->exametemp->excluir($agenda_exames_id);
        $this->carregarexametemp($ambulatorio_pacientetemp_id);
    }

    function excluirexametemp($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->excluirexametemp($agenda_exames_id);
        $this->carregarpacientetemp($pacientetemp_id);
    }

    function excluirconsultatemp($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->excluirexametemp($agenda_exames_id);
        $this->carregarpacienteconsultatemp($pacientetemp_id);
    }

    function excluirfisioterapiatemp($agenda_exames_id, $pacientetemp_id) {
        $this->exametemp->excluirexametemp($agenda_exames_id);
        $this->carregarpacientefisioterapiatemp($pacientetemp_id);
    }

    function gravar() {
        $exametemp_tuss_id = $this->exametemp->gravar();
        if ($exametemp_tuss_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar o Procedimento. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar o Procedimento.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/exametemp");
    }

    function excluirpaciente($paciente_id) {
        $pacienteexame = $this->exametemp->contadorpacienteexame($paciente_id);
        $pacienteagendaexame = $this->exametemp->contadorpaciente($paciente_id);
        $pacienteguia = $this->exametemp->contadorpacienteguia($paciente_id);
        $pacienteconsulta = $this->exametemp->contadorpacienteconsulta($paciente_id);
        $pacientelaudo = $this->exametemp->contadorpacientelaudo($paciente_id);
        if ($pacienteexame != 0 || $pacienteagendaexame != 0 || $pacienteguia != 0 || $pacienteconsulta != 0 || $pacientelaudo != 0) {
            $data['mensagem'] = 'Erro ao excluir o Paciente. Favor verificar pendencias';
        } else {
            $verifica = $this->exametemp->excluirpaciente($paciente_id);
            if ($verifica == "-1") {
                $data['mensagem'] = 'Erro ao excluir o Paciente. Opera&ccedil;&atilde;o cancelada.';
            } else {
                $data['mensagem'] = 'Sucesso ao excluir o Paciente.';
            }
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes");
    }

    function gravartemp() {
        $ambulatorio_pacientetemp_id = $_POST['txtpaciente_id'];
        $this->exametemp->gravarexames($ambulatorio_pacientetemp_id);
        $this->carregarexametemp($ambulatorio_pacientetemp_id);
    }

    function gravarpacienteexametemp($agenda_exames_id, $paciente_id) {
            $paciente_id = $this->exametemp->gravarpacienteexames($agenda_exames_id, $paciente_id);
            $data['mensagem'] = 'Sucesso ao agendar Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/guia/agendamento/$paciente_id");
    }

    function gravarpacienteconsultatemp($agenda_exames_id) {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } else {
            $data['medico'] = $this->exametemp->listarmedicoconsulta();
            $paciente_id = $this->exametemp->gravarpacienteconsultas($agenda_exames_id);
            redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
        }
    }

    function gravarpacientefisioterapiatemp($agenda_exames_id) {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } else {
            $data['medico'] = $this->exametemp->listarmedicoconsulta();
            $paciente_id = $this->exametemp->gravarpacientefisioterapia($agenda_exames_id);
            $this->carregarpacientefisioterapiatemp($paciente_id);
        }
    }

    function reservarexametemp($agenda_exames_id, $paciente_id, $agenda_exames_nome_id, $data) {
        $paciente_id = $this->exametemp->reservarexametemp($agenda_exames_id, $paciente_id, $agenda_exames_nome_id, $data);
        $this->carregarpacientetemp($paciente_id);
    }

    function reservarconsultatemp($agenda_exames_id, $paciente_id, $medico_consulta_id, $data) {
        $paciente_id = $this->exametemp->reservarconsultatemp($agenda_exames_id, $paciente_id, $medico_consulta_id, $data);
        $this->carregarpacienteconsultatemp($paciente_id);
    }

    function reservarfisioterapiatemp($agenda_exames_id, $paciente_id, $medico_consulta_id, $data) {
        $paciente_id = $this->exametemp->reservarfisioterapiatemp($agenda_exames_id, $paciente_id, $medico_consulta_id, $data);
        $this->carregarpacientefisioterapiatemp($paciente_id);
    }

    function gravarpacientetemp() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
        $this->exametemp->gravarexames($pacientetemp_id);
        $this->carregarpacientetemp($pacientetemp_id);
    }

    function gravarconsultapacientetemp() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
        $this->exametemp->gravarconsultaspacienteexistente($pacientetemp_id);
        $this->carregarpacienteconsultatemp($pacientetemp_id);
    }

    function gravarfisioterapiapacientetemp() {

        $pacientetemp_id = $_POST['txtpaciente_id'];
        $this->exametemp->gravarfisioterapiapacienteexistente($pacientetemp_id);
        $this->carregarpacientefisioterapiatemp($pacientetemp_id);
    }

    function gravarpaciente() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopaciente");
        } else {
            $pacientetemp_id = $this->paciente->gravarpacientetemp();
            $this->exametemp->gravarexames($pacientetemp_id);
            $this->carregarpacientetemp($pacientetemp_id);
        }
    }

    function gravarpacienteconsulta() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } else {
            $pacientetemp_id = $this->paciente->gravarpacientetemp();
            $this->exametemp->gravarconsultas($pacientetemp_id);
            $this->carregarpacienteconsultatemp($pacientetemp_id);
        }
    }

    function gravarpacientefisioterapia() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacientefisioterapia");
        } else {
            $pacientetemp_id = $this->paciente->gravarpacientetemp();
            $this->exametemp->gravarfisioterapia($pacientetemp_id);
            $this->carregarpacientefisioterapiatemp($pacientetemp_id);
        }
    }

    function gravarpacienteconsultaencaixe() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } else {
            $pacientetemp_id = $this->exametemp->gravarconsultasencaixe();
            $this->carregarpacienteconsultatemp($pacientetemp_id);
        }
    }

    function gravarpacientefisioterapiaencaixe() {
        if (trim($_POST['txtNome']) == "") {
            $data['mensagem'] = 'Erro ao marcar consulta é obrigatorio nome do Paciente.';
            $this->session->set_flashdata('message', $data['mensagem']);
            redirect(base_url() . "ambulatorio/exametemp/novopacienteconsulta");
        } else {
            $pacientetemp_id = $this->exametemp->gravarfisioterapiaencaixe();
            $this->carregarpacientefisioterapiatemp($pacientetemp_id);
        }
    }

    function gravapacienteconsultaencaixe() {
            $pacientetemp_id = $_POST['txtpaciente_id'];
            $this->exametemp->gravaconsultasencaixe($pacientetemp_id);
            $this->carregarpacienteconsultatemp($pacientetemp_id);
    }

    private function carregarView($data = null, $view = null) {
        if (!isset($data)) {
            $data['mensagem'] = '';
        }

        if ($this->utilitario->autorizar(2, $this->session->userdata('modulo')) == true) {
            $this->load->view('header', $data);
            if ($view != null) {
                $this->load->view($view, $data);
            } else {
                $this->load->view('giah/servidor-lista', $data);
            }
        } else {
            $data['mensagem'] = $this->mensagem->getMensagem('login005');
            $this->load->view('header', $data);
            $this->load->view('home');
        }
        $this->load->view('footer');
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
