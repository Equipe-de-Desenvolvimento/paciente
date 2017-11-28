<?php

class Autocomplete extends Controller {

    function Autocomplete() {

        parent::Controller();
        $this->load->model('ponto/funcao_model', 'funcao');
        $this->load->model('ponto/funcionario_model', 'funcionario');
        $this->load->model('ponto/ocorrenciatipo_model', 'ocorrenciatipo');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('estoque/fornecedor_model', 'fornecedor_m');
        $this->load->model('estoque/produto_model', 'produto_m');
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ponto/cargo_model', 'cargo');
        $this->load->model('ponto/setor_model', 'setor');
        $this->load->model('cadastro/paciente_model', 'paciente_m');
        $this->load->model('cadastro/contaspagar_model', 'contaspagar');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('emergencia/solicita_acolhimento_model', 'solicita_acolhimento_m');
        $this->load->model('internacao/internacao_model', 'internacao_m');
        $this->load->model('internacao/unidade_model', 'unidade_m');
        $this->load->model('internacao/enfermaria_model', 'enfermaria_m');
        $this->load->model('internacao/leito_model', 'leito_m');
        $this->load->model('ponto/horariostipo_model', 'horariostipo');
        ;
    }

    function index() {
        
    }

    function horariosambulatorio() {

        if (isset($_GET['exame'])) {
            $result = $this->exametemp->listarautocompletehorarios($_GET['exame'], $_GET['teste']);
        } else {
            $result = $this->exametemp->listarautocompletehorarios();
        }
        echo json_encode($result);
    }

    function unidadeleito() {

        if (isset($_GET['unidade'])) {
            $result = $this->internacao_m->listaleitointarnacao($_GET['unidade']);
        } else {
            $result = $this->internacao_m->listaleitointarnacao();
        }
        echo json_encode($result);
    }

    function horariosambulatorioconsulta() {

        if (isset($_GET['exame'])) {
            $result = $this->exametemp->listarhorariosconsulta($_GET['exame'], $_GET['teste']);
        } else {
            $result = $this->exametemp->listarhorariosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentoconveniotodos() {

        if (isset($_GET['convenio1'])) {
            $result = $this->exametemp->listarautocompleteprocedimentostodos($_GET['convenio1']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentostodos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio() {

        if (isset($_GET['convenio1'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio1']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function medicoporespecialidade() {

        if (isset($_GET['especialidade'])) {
            $result = $this->operador_m->listarautocompletemedicos($_GET['especialidade']);
        } else {
            $result = $this->operador_m->listarautocompletemedicos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta() {

        if (isset($_GET['convenio1'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio1']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentoconveniofisioterapia() {

        if (isset($_GET['convenio1'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosfisioterapia($_GET['convenio1']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosfisioterapia();
        }
        echo json_encode($result);
    }

    function procedimentovalor() {

        if (isset($_GET['procedimento1'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento1']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentovalorfisioterapia() {

        if (isset($_GET['procedimento1'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento1']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio2() {

        if (isset($_GET['convenio2'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio2']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta2() {

        if (isset($_GET['convenio2'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio2']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor2() {

        if (isset($_GET['procedimento2'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento2']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio3() {

        if (isset($_GET['convenio3'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio3']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta3() {

        if (isset($_GET['convenio3'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio3']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor3() {

        if (isset($_GET['procedimento3'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento3']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio4() {

        if (isset($_GET['convenio4'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio4']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta4() {

        if (isset($_GET['convenio4'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio4']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor4() {

        if (isset($_GET['procedimento4'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento4']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio5() {

        if (isset($_GET['convenio5'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio5']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta5() {

        if (isset($_GET['convenio5'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio5']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor5() {

        if (isset($_GET['procedimento5'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento5']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio6() {

        if (isset($_GET['convenio6'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio6']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta6() {

        if (isset($_GET['convenio6'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio6']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor6() {

        if (isset($_GET['procedimento6'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento6']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio7() {

        if (isset($_GET['convenio7'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio7']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta7() {

        if (isset($_GET['convenio7'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio7']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor7() {

        if (isset($_GET['procedimento7'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento7']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio8() {

        if (isset($_GET['convenio8'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio8']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta8() {

        if (isset($_GET['convenio8'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio8']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor8() {

        if (isset($_GET['procedimento8'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento8']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio9() {

        if (isset($_GET['convenio9'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio9']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta9() {

        if (isset($_GET['convenio9'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio9']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor9() {

        if (isset($_GET['procedimento9'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento9']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function procedimentoconvenio10() {

        if (isset($_GET['convenio10'])) {
            $result = $this->exametemp->listarautocompleteprocedimentos($_GET['convenio10']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentos();
        }
        echo json_encode($result);
    }

    function procedimentoconvenioconsulta10() {

        if (isset($_GET['convenio10'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta($_GET['convenio10']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosconsulta();
        }
        echo json_encode($result);
    }

    function procedimentovalor10() {

        if (isset($_GET['procedimento10'])) {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor($_GET['procedimento10']);
        } else {
            $result = $this->exametemp->listarautocompleteprocedimentosvalor();
        }
        echo json_encode($result);
    }

    function credordevedor() {

        if (isset($_GET['term'])) {
            $result = $this->contaspagar->listarautocompletecredro($_GET['term']);
        } else {
            $result = $this->contaspagar->listarautocompletecredro();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->razao_social;
            $retorno['id'] = $item->financeiro_credor_devedor_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function modeloslaudo() {

        if (isset($_GET['exame'])) {
            //$result = 'oi';
            $result = $this->exametemp->listarautocompletemodelos($_GET['exame']);
        } else {
            $result = $this->exametemp->listarautocompletemodelos();
            //$result = 'oi nao';
        }
        echo json_encode($result);
    }

    function modeloslinhas() {

        if (isset($_GET['linha'])) {
            //$result = 'oi';
            $result = $this->exametemp->listarautocompletelinha($_GET['linha']);
        } else {
            $result = $this->exametemp->listarautocompletelinha();
            //$result = 'oi nao';
        }
        echo json_encode($result);
    }

    function linhas() {

        if (isset($_GET['term'])) {
            $result = $this->exametemp->listarautocompletelinha($_GET['term']);
        } else {
            $result = $this->exametemp->listarautocompletelinha();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome . '-' . $item->texto;
            $retorno['id'] = $item->texto;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function modeloslaudos() {

        if (isset($_GET['exame'])) {
            //$result = 'oi';
            $result = $this->exametemp->listarautocompletemodelos($_GET['exame']);
        } else {
            $result = $this->exametemp->listarautocompletemodelos();
            //$result = 'oi nao';
        }
        echo json_encode($result);
    }

    function laudosanteriores() {

        if (isset($_GET['anteriores'])) {

            $result = $this->laudo->listarautocompletelaudos($_GET['anteriores']);
        } else {
            $result = $this->laudo->listarautocompletelaudos();
        }
        echo json_encode($result);
    }

    function cidade() {

        if (isset($_GET['term'])) {
            $result = $this->paciente_m->listarCidades($_GET['term']);
        } else {
            $result = $this->paciente_m->listarCidades();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome . ' - ' . $item->estado;
            $retorno['id'] = $item->municipio_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function produto() {

        if (isset($_GET['term'])) {
            $result = $this->produto_m->autocompleteproduto($_GET['term']);
        } else {
            $result = $this->produto_m->autocompleteproduto();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->descricao;
            $retorno['id'] = $item->estoque_produto_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function fornecedor() {

        if (isset($_GET['term'])) {
            $result = $this->fornecedor_m->autocompletefornecedor($_GET['term']);
        } else {
            $result = $this->fornecedor_m->autocompletefornecedor();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->fantasia;
            $retorno['id'] = $item->estoque_fornecedor_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function procedimentotuss() {

        if (isset($_GET['term'])) {
            $result = $this->procedimento->listarautocompletetuss($_GET['term']);
        } else {
            $result = $this->procedimento->listarautocompletetuss();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->codigo . ' - ' . $item->descricao . ' - ' . $item->ans;
            $retorno['id'] = $item->tuss_id;
            $retorno['codigo'] = $item->codigo;
            $retorno['descricao'] = $item->descricao . ' - ' . $item->ans;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function cbo() {

        if (isset($_GET['term'])) {
            $result = $this->operador_m->listarcbo($_GET['term']);
        } else {
            $result = $this->operador_m->listarcbo();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->descricao;
            $retorno['id'] = $item->cbo_grupo_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function cargo() {
        if (isset($_GET['term'])) {
            $result = $this->cargo->listarautocomplete($_GET['term']);
        } else {
            $result = $this->cargo->listarautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['id'] = $item->cargo_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function motivo_atendimento() {
        if (isset($_GET['term'])) {
            $result = $this->solicita_acolhimento_m->listamotivoautocomplete($_GET['term']);
        } else {
            $result = $this->solicita_acolhimento_m->listamotivoautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['id'] = $item->emergencia_motivoatendimento_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function medicosaida() {
        if (isset($_GET['term'])) {
            $result = $this->solicita_acolhimento_m->listarmedicosaida($_GET['term']);
        } else {
            $result = $this->solicita_acolhimento_m->listarmedicosaida();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->conselho . '-' . $item->nome;
            $retorno['id'] = $item->operador_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function medicos() {
        if (isset($_GET['term'])) {
            $result = $this->guia->listarmedicos($_GET['term']);
        } else {
            $result = $this->guia->listarmedicos();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->conselho . '-' . $item->nome;
            $retorno['id'] = $item->operador_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function funcao() {
        if (isset($_GET['term'])) {
            $result = $this->funcao->listarautocomplete($_GET['term']);
        } else {
            $result = $this->funcao->listarautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['id'] = $item->funcao_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function ocorrenciatipo() {
        if (isset($_GET['term'])) {
            $result = $this->ocorrenciatipo->listarautocomplete($_GET['term']);
        } else {
            $result = $this->ocorrenciatipo->listarautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['id'] = $item->ocorrenciatipo_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function setor() {
        if (isset($_GET['term'])) {
            $result = $this->setor->listarautocomplete($_GET['term']);
        } else {
            $result = $this->setor->listarautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['id'] = $item->setor_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function horariostipo() {
        if (isset($_GET['term'])) {
            $result = $this->horariostipo->listarautocomplete($_GET['term']);
        } else {
            $result = $this->horariostipo->listarautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['id'] = $item->horariostipo_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function funcionario() {
        if (isset($_GET['term'])) {
            $result = $this->funcionario->listarautocomplete($_GET['term']);
        } else {
            $result = $this->funcionario->listarautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['id'] = $item->funcionario_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function unidade() {
        if (isset($_GET['term'])) {
            $result = $this->unidade_m->listaunidadeautocomplete($_GET['term']);
        } else {
            $result = $this->unidade_m->listaunidadeautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['id'] = $item->internacao_unidade_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function operador() {
        if (isset($_GET['term'])) {
            $result = $this->operador_m->listaoperadorautocomplete($_GET['term']);
        } else {
            $result = $this->operador_m->listaoperadorautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->conselho . '-' . $item->nome;
            $retorno['id'] = $item->operador_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function cboprofissionais() {
        if (isset($_GET['term'])) {
            $result = $this->operador_m->listacboprofissionaisautocomplete($_GET['term']);
        } else {
            $result = $this->operador_m->listacboprofissionaisautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->cbo_ocupacao_id . '-' . $item->descricao;
            $retorno['id'] = $item->cbo_ocupacao_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function paciente() {
        if (isset($_GET['term'])) {
            $result = $this->exame->listarautocompletepaciente($_GET['term']);
        } else {
            $result = $this->exame->listarautocompletepaciente();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome;
            $retorno['cpf'] = $item->cpf;
            $retorno['itens'] = $item->telefone;
            $retorno['celular'] = $item->celular;
            $retorno['valor'] = substr($item->nascimento, 8, 2) . "/" . substr($item->nascimento, 5, 2) . "/" . substr($item->nascimento, 0, 4);
            $retorno['id'] = $item->paciente_id;
            $retorno['endereco'] = $item->logradouro . " - " . $item->numero;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function pacientecpf() {
//        header('Access-Control-Allow-Origin: *');
        $_GET['term'] = str_replace("-", "", str_replace(".", "", $_GET['term']));
        if (isset($_GET['term'])) {
            $result = $this->exame->listarautocompletepacientecpf($_GET['term']);
        } else {
            $result = $this->exame->listarautocompletepacientecpf();
        }
        
        foreach ($result as $item) {
            $retorno['value'] = $item->cpf;
            $retorno['nome'] = $item->nome;
            $retorno['itens'] = $item->telefone;
            $retorno['celular'] = $item->celular;
            $retorno['valor'] = substr($item->nascimento, 8, 2) . "/" . substr($item->nascimento, 5, 2) . "/" . substr($item->nascimento, 0, 4);
            $retorno['id'] = $item->paciente_id;
            $retorno['endereco'] = $item->logradouro . " - " . $item->numero;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function cid1() {
        if (isset($_GET['term'])) {
            $result = $this->internacao_m->listacidautocomplete($_GET['term']);
        } else {
            $result = $this->internacao_m->listacidautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->co_cid . '-' . $item->no_cid;
            $retorno['id'] = $item->co_cid;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function cid2() {
        if (isset($_GET['term'])) {
            $result = $this->internacao_m->listacidautocomplete($_GET['term']);
        } else {
            $result = $this->internacao_m->listacidautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->co_cid . '-' . $item->no_cid;
            $retorno['id'] = $item->co_cid;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function procedimento() {
        if (isset($_GET['term'])) {
            $result = $this->internacao_m->listaprocedimentoautocomplete($_GET['term']);
        } else {
            $result = $this->internacao_m->listaprocedimentoautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->procedimento . '-' . $item->descricao;
            $retorno['id'] = $item->procedimento;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function enfermaria() {

        if (isset($_GET['term'])) {
            $result = $this->enfermaria_m->listaenfermariaautocomplete($_GET['term']);
        } else {
            $result = $this->enfermaria_m->listaenfermariaautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome . ' - ' . $item->unidade;
            $retorno['id'] = $item->internacao_enfermaria_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

    function leito() {

        if (isset($_GET['term'])) {
            $result = $this->leito_m->listaleitoautocomplete($_GET['term']);
        } else {
            $result = $this->leito_m->listaleitoautocomplete();
        }
        foreach ($result as $item) {
            $retorno['value'] = $item->nome . ' - ' . $item->enfermaria . ' - ' . $item->unidade;
            $retorno['id'] = $item->internacao_leito_id;
            $var[] = $retorno;
        }
        echo json_encode($var);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */