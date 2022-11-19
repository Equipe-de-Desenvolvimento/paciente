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
class Laudo extends BaseController {

    function Laudo() {
        parent::Controller();
        $this->load->model('ambulatorio/laudo_model', 'laudo');
        $this->load->model('ambulatorio/guia_model', 'guia');
        $this->load->model('ambulatorio/laudooit_model', 'laudooit');
        $this->load->model('seguranca/operador_model', 'operador_m');
        $this->load->model('ambulatorio/procedimento_model', 'procedimento');
        $this->load->model('ambulatorio/exametemp_model', 'exametemp');
        $this->load->model('ambulatorio/exame_model', 'exame');
        $this->load->model('cadastro/convenio_model', 'convenio');
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
        $this->loadView('ambulatorio/laudo-lista', $args);

//            $this->carregarView($data);
    }

    function pesquisarconsulta($args = array()) {
        $this->loadView('ambulatorio/laudoconsulta-lista', $args);

//            $this->carregarView($data);
    }

    function pesquisardigitador($args = array()) {
        $this->loadView('ambulatorio/laudodigitador-lista', $args);
    }

    function pesquisarlaudoantigo($args = array()) {
        $this->loadView('ambulatorio/laudoantigo-lista', $args);

//            $this->carregarView($data);
    }

    function pesquisarrevisor($args = array()) {
        $this->loadView('ambulatorio/revisor-lista', $args);

//            $this->carregarView($data);
    }

    function calculadora($args = array()) {
        $data['valor1'] = '';
        $data['valor2'] = '';
        $data['valor3'] = '';
        $data['resultado'] = '';
        $this->load->View('ambulatorio/calculadora-form', $data);
    }

    function calcularvolume($args = array()) {
        (int)
                $valor1 = str_replace(",", ".", $_POST['valor1']);
        $valor2 = str_replace(",", ".", $_POST['valor2']);
        $valor3 = str_replace(",", ".", $_POST['valor3']);
        $resultado = 0.5233 * $valor1 * $valor2 * $valor3;
        $data['valor1'] = $valor1;
        $data['valor2'] = $valor2;
        $data['valor3'] = $valor3;
        $data['resultado'] = $resultado;
        $this->load->View('ambulatorio/calculadora-form', $data);
    }

    function carregarlaudo($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
//        $arquivo_pasta = directory_map( base_url() . "dicom/");
        $this->load->helper('directory');
        $agenda_exames_id = $obj_laudo->_agenda_exames_id;
        $arquivo_pasta = directory_map("/home/sisprod/projetos/clinica/dicom/$agenda_exames_id/");
        $origem = "/home/sisprod/projetos/clinica/dicom/$agenda_exames_id";

//        foreach ($arquivo_pasta as $nome1 => $item) {
//            foreach ($item as $nome2 => $valor) {
//                foreach ($valor as $value) {
//                    $nova = $value;
//                    if (!is_dir(base_url()."upload/$exame_id")) {
//                        mkdir(base_url()."upload/$exame_id");
//                        $destino = base_url()."upload/$exame_id/$nova";
//                        chmod($destino, 0777);
//                    }
//                    $destino = base_url()."upload/$exame_id/$nova";
//                    $local = "$origem/$nome1/$nome2/$nova";
//                    $deletar = "$origem/$nome1/$nome2";
//                    copy($local, $destino);
//                    delete_files($deletar);
//                }
//            }
//        }

        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);

        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/laudo-form_1', $data);
    }

    function carregarlaudolaboratorial($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);

        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/laudolaboratorial-form', $data);
    }

    function carregarlaudoeco($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);

        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/laudoeco-form', $data);
    }

    function carregarlaudohistorico($paciente_id) {
        $data['paciente_id'] = $paciente_id;
        $data['paciente'] = $this->paciente->listardados($paciente_id);
        $this->load->View('ambulatorio/laudoconsultahistorico-form', $data);
    }

    function carregaranaminese($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['historico'] = $this->laudo->listarconsultahistorico($paciente_id);
        $data['historicoantigo'] = $this->laudo->listarconsultahistoricoantigo($paciente_id);
        $data['historicoexame'] = $this->laudo->listarexamehistorico($paciente_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $data['paciente_id'] = $paciente_id;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/laudoconsulta-form', $data);
    }

    function carregarreceituario($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['contador'] = $this->laudo->contadorlistarreceita($ambulatorio_laudo_id);
        $data['receita'] = $this->laudo->listarreceita($ambulatorio_laudo_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/receituarioconsulta-form', $data);
    }

    function imprimirmodeloaih($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('internacao/impressaoaih', $data);
    }

    function carregarreceituarioespecial($ambulatorio_laudo_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['receita'] = $this->laudo->listarreceitasespeciais($ambulatorio_laudo_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/receituarioespecialconsulta-form', $data);
    }

    function editarcarregarreceituarioespecial($ambulatorio_laudo_id, $ambulatorio_receituario_especial_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['receita'] = $this->laudo->listarreceitaespecial($ambulatorio_receituario_especial_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->load->View('ambulatorio/editarreceituarioespecialconsulta-form', $data);
    }

    function carregarlaudodigitador($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id);
        $data['linha'] = $this->exametemp->listarmodeloslinha($procedimento_tuss_id);
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['padrao'] = $this->laudo->listarlaudopadrao($procedimento_tuss_id);


        $data['operadores'] = $this->operador_m->listarmedicos();
        $data['mensagem'] = $messagem;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        //$this->carregarView($data, 'giah/servidor-form');

        $this->load->View('ambulatorio/laudodigitador-form_1', $data);
    }

    function listararquivos($ambulatorio_laudo_id=NULL,$paciente_id=0) { 

        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id); 
        if($this->session->userdata('paciente_id') != $paciente_id || $this->session->userdata('paciente_id') != $data['laudo'][0]->paciente_id){   
                $mensagem = "Ops, Você não tem acesso a essa pagina";
                    echo "<html>
                        <meta charset='UTF-8'>
            <script type='text/javascript'> 
            alert('$mensagem');
            window.onunload = fechaEstaAtualizaAntiga;
            function fechaEstaAtualizaAntiga() {
                window.opener.location.reload();
                }
            window.close();
                </script>
                </html>"; 
                die(); 
        } 

        
        $this->load->helper('directory'); 
        $empresa_upload = $this->laudo->listarempresaenderecoupload();
//        var_dump($empresa_upload); die;
        // if ($empresa_upload != '') {
        //     $caminho_arquivos = "$empresa_upload/consulta/paciente/$ambulatorio_laudo_id/";
        // } else {
        //     $caminho_arquivos = base_url()."upload/consulta/paciente/$ambulatorio_laudo_id/";
        // } 

        if ($empresa_upload != '') {
            $caminho_arquivos = "$empresa_upload/consulta/$ambulatorio_laudo_id/";
        } else {
            $caminho_arquivos = base_url()."upload/consulta/$ambulatorio_laudo_id/";
        }  
       
        $empresa_upload_pasta = $this->laudo->listarempresaenderecouploadpasta();
//        var_dump($empresa_upload_pasta); die;
        if ($empresa_upload_pasta != '') {
            $pasta_sistema = $empresa_upload_pasta;
        } else {
            $pasta_sistema = "clinica";
        }

        $empresa_upload_pasta_paciente = $this->laudo->listarempresaenderecouploadpastapaciente();
//        var_dump($empresa_upload_pasta); die;
        if ($empresa_upload_pasta_paciente != '') {
            $pasta_sistema_paciente = $empresa_upload_pasta_paciente;
        } else {
            $pasta_sistema_paciente = "paciente";
        } 
        $data['pasta_sistema'] = $pasta_sistema;

        $data['pasta_sistema_paciente'] = $pasta_sistema_paciente;
        
// var_dump($pasta_sistema); die; 

        $data['arquivo_pasta_pdf'] = directory_map($caminho_arquivos);
        if ($data['arquivo_pasta_pdf'] != false) {
            sort($data['arquivo_pasta_pdf']);
        }
 
        $data['observacao'] = $this->laudo->listaobservacaopaciente($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        
        if($ambulatorio_laudo_id == ""){
            $mensagem = "Ainda não foi feito laudo para esse paciente";
             echo "<html>
                    <meta charset='UTF-8'>
        <script type='text/javascript'> 
        alert('$mensagem');
        window.onunload = fechaEstaAtualizaAntiga;
        function fechaEstaAtualizaAntiga() {
            window.opener.location.reload();
            }
        window.close();
            </script>
            </html>";
        }
        
          
         
        $this->loadView('ambulatorio/listararquivos-form', $data);
    }

        function importararquivospaciente() {

        $empresa_upload = $this->laudo->listarempresaenderecoupload();
    //    var_dump($empresa_upload); die;
        if ($empresa_upload != '') {
            $caminho_arquivos = "$empresa_upload";
        } else {
            $caminho_arquivos = "/home/sisprod/projetos/clinica/upload";
        } 

        $ambulatorio_laudo_id = $_POST['paciente_id'];

        for ($i = 0; $i < count(@$_FILES['arquivos']['name']); $i++) {
            $_FILES['userfile']['name'] = $_FILES['arquivos']['name'][$i];
            $_FILES['userfile']['type'] = $_FILES['arquivos']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES['arquivos']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $_FILES['arquivos']['error'][$i];
            $_FILES['userfile']['size'] = $_FILES['arquivos']['size'][$i];

            $_FILES['userfile']['name'] = str_replace(" ","_", $_FILES['userfile']['name']);
            
            //  if (!is_dir("$caminho_arquivos/consulta/paciente/")) {
            //     mkdir("$caminho_arquivos/consulta/paciente/");
            //     $destino = "$caminho_arquivos/consulta/paciente/";
            //     chmod($destino, 0777);
            // }
            // if (!is_dir("$caminho_arquivos/consulta/paciente/$ambulatorio_laudo_id")) {
            //     mkdir("$caminho_arquivos/consulta/paciente/$ambulatorio_laudo_id");
            //     $destino = "$caminho_arquivos/consulta/paciente/$ambulatorio_laudo_id";
            //     chmod($destino, 0777);
            // }
             
             if (!is_dir("$caminho_arquivos/consulta/")) {
                mkdir("$caminho_arquivos/consulta/");
                $destino = "$caminho_arquivos/consulta/";
                chmod($destino, 0777);
            }
            if (!is_dir("$caminho_arquivos/consulta/$ambulatorio_laudo_id")) {
                mkdir("$caminho_arquivos/consulta/$ambulatorio_laudo_id");
                $destino = "$caminho_arquivos/consulta/$ambulatorio_laudo_id";
                chmod($destino, 0777);
            } 

            //        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
            $config['upload_path'] = "$caminho_arquivos/consulta/" . $ambulatorio_laudo_id . "/";
            $config['allowed_types'] = 'gif|jpg|BMP|bmp|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar|xml|txt|';
            $config['max_size'] = '0';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $error = null;
                $data = array('upload_data' => $this->upload->data());
                $nome = $_FILES['userfile']['name'];
                $this->laudo->gravaranexoarquivo($ambulatorio_laudo_id, "upload/consulta/$ambulatorio_laudo_id/$nome", $nome);
            }
        }
//        var_dump($error); die;


        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $observacao = $this->laudo->gravarobservacao($ambulatorio_laudo_id);

        $this->listararquivos($ambulatorio_laudo_id);
    }

    function todoslaudo($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id, $guia_id, $messagem = null) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['obj'] = $obj_laudo;
        $data['lista'] = $this->exametemp->listarmodeloslaudo($procedimento_tuss_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $grupo = @$obj_laudo->_grupo;
        $procedimento = $this->laudo->listarprocedimentos($guia_id, $grupo);
        $data['grupo'] = $grupo;
        $data['mensagem'] = $messagem;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $uniaoprocedimento = "";
        foreach ($procedimento as $value) {
            $procedimentos = $value->procedimento_tuss_id;
            $contador = $this->laudo->contadorlistarlaudopadrao($procedimentos);
            $item = $this->laudo->listarlaudopadrao($procedimentos);
            if ($contador > 0) {
                $uniaoprocedimento = $uniaoprocedimento . '<br><u><b>' . $item['0']->procedimento . '</u></b>';
                $uniaoprocedimento = $uniaoprocedimento . '<br>' . $item['0']->texto;
            } else {
                $uniaoprocedimento = $uniaoprocedimento . '<br><u><b>' . $value->nome . '</u></b><br>';
            }
        }
        $data['padrao'] = $uniaoprocedimento;
        $this->load->View('ambulatorio/laudodigitadortotal-form_1', $data);
    }

    function carregarlaudoanterior($paciente_id, $ambulatorio_laudo_id) {
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['total'] = $this->laudo->listarlaudoscontador($paciente_id, $ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $ambulatorio_laudo_id;

        $this->loadView('ambulatorio/laudoanterior-lista', $data);
    }

    function carregarlaudoantigo($id) {
        $data['id'] = $id;
        $data['laudo'] = $this->laudo->listarlaudoantigoimpressao($id);
        $this->load->View('ambulatorio/laudoantigo-form', $data);
    }

    function impressaolaudo1($ambulatorio_laudo_id, $exame_id) {
 
        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $texto = $data['laudo'][0]->texto;
//        $adendo = $data['laudo'][0]->adendo;
        $data['laudo'][0]->texto = $texto;
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
     
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        //HUMANA IMAGEM
        $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
        pdf($html, $filename, $cabecalho, $rodape);
        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //CDC
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
//        
        //CLINICA MAIS
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<img align = 'left'  width='900px' height='100px' src='img/rodapemais.png'>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //CLINICA DEZ
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        $grupo = 'laboratorial';
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //RONALDO BARREIRA
//        $medicoparecer = $data['laudo']['0']->medico_parecer1;
//        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
//            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
//            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
//            <tr><td></td><td></td></tr>
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
//            </table>";
//        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
//            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
//            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
//            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
//            </table>";
//        }
//        if ($data['laudo']['0']->medico_parecer1 != 929) {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='200px' height='130px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 2483) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        $grupo = $data['laudo']['0']->grupo;
//        $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
//        $this->load->View('ambulatorio/impressaolaudo_2', $data);
    }

    function adicionalcabecalho($cabecalho, $laudo) {
        $ano = 0;
        $mes = 0;
//        $cabecalho = $impressaolaudo[0]->texto;
        $cabecalho = str_replace("_paciente_", $laudo['0']->paciente, $cabecalho);
        $cabecalho = str_replace("_sexo_", $laudo['0']->sexo, $cabecalho);
        $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($laudo['0']->nascimento)), $cabecalho);
        $cabecalho = str_replace("_convenio_", $laudo['0']->convenio, $cabecalho);
        $cabecalho = str_replace("_sala_", $laudo['0']->sala, $cabecalho);
        $cabecalho = str_replace("_CPF_", $laudo['0']->cpf, $cabecalho);
        $cabecalho = str_replace("_RG_", $laudo['0']->rg, $cabecalho);
        $cabecalho = str_replace("_solicitante_", $laudo['0']->solicitante, $cabecalho);
        $cabecalho = str_replace("_data_", substr($laudo['0']->data, 8, 2) . '/' . substr($laudo['0']->data, 5, 2) . '/' . substr($laudo['0']->data, 0, 4), $cabecalho);
        $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($laudo[0]->data_cadastro)), $cabecalho);
        $cabecalho = str_replace("_medico_", $laudo['0']->medico, $cabecalho);
        $cabecalho = str_replace("_revisor_", $laudo['0']->medicorevisor, $cabecalho);
        $cabecalho = str_replace("_procedimento_", $laudo['0']->procedimento, $cabecalho);
        $cabecalho = str_replace("_laudo_", $laudo['0']->texto, $cabecalho);
        $cabecalho = str_replace("_nomedolaudo_", $laudo['0']->cabecalho, $cabecalho);
        $cabecalho = str_replace("_queixa_", $laudo['0']->cabecalho, $cabecalho);
        $cabecalho = str_replace("_peso_", $laudo['0']->peso, $cabecalho);
        $cabecalho = str_replace("_altura_", $laudo['0']->altura, $cabecalho);
        $cabecalho = str_replace("_cid1_", $laudo['0']->cid1, $cabecalho);
        $cabecalho = str_replace("_cid2_", $laudo['0']->cid2, $cabecalho);
        $cabecalho = str_replace("_guia_", $laudo[0]->guia_id, $cabecalho);
        // $operador_id = $this->session->userdata('operador_id');
        // $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
        @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
        @$cabecalho = str_replace("_usuario_salvar_", $laudo['laudo'][0]->usuario_salvar, $cabecalho);
        $cabecalho = str_replace("_telefone1_", $laudo[0]->telefone, $cabecalho);
        $cabecalho = str_replace("_telefone2_", $laudo[0]->celular, $cabecalho);
        $cabecalho = str_replace("_whatsapp_", $laudo[0]->whatsapp, $cabecalho);
        $cabecalho = str_replace("_prontuario_antigo_", $laudo[0]->prontuario_antigo, $cabecalho);
        $cabecalho = str_replace("_prontuario_", $laudo[0]->paciente_id, $cabecalho);
        $cabecalho = str_replace("_nome_mae_", $laudo[0]->nome_mae, $cabecalho);
        $cabecalho = str_replace("_especialidade_", $laudo[0]->grupo, $cabecalho);
        $dataFuturo2 = date("Y-m-d");
        $dataAtual2 = $laudo[0]->nascimento;
        $date_time2 = new DateTime($dataAtual2);
        $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
        $idade = $diff2->format('%Y anos'); 
     
        $ano = $diff2->format('%Y'); 
        $mes = $diff2->format('%m');  
        
        if ($ano > 1) {
           $s = "s"; 
        }else{
           $s = ""; 
        }
        
        if ($ano == 0) { 
             if ($mes > 1) {
                    $sm = "es";
                }else{
                    $sm = "";
                } 
            $ano_formado = $mes." mes".$sm;   
        }else{
            if ($mes > 0) { 
                if ($mes > 1) {
                    $sm = " meses";
                }else{
                    $sm = " mês";
                }
                  $ano_formado = $ano." ano".$s." e ".$mes.$sm; 
            }else{
                  $ano_formado = $ano." ano".$s;  
            }
        } 
        
        $cabecalho = str_replace("_idade_", $ano_formado, $cabecalho);
          
        return $cabecalho;
    }


    function impressaolaudo($ambulatorio_laudo_id, $exame_id,$paciente_id = 0) {
        $this->load->plugin('mpdf');
        $empresa_id = $this->session->userdata('empresa_id');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);

	 if($this->session->userdata('paciente_id') != $paciente_id || $this->session->userdata('paciente_id') != $data['laudo'][0]->paciente_id){   
            $mensagem = "Ops, Você não tem acesso a essa pagina";
            echo "<html>
                   <meta charset='UTF-8'>
       <script type='text/javascript'> 
       alert('$mensagem');
       window.onunload = fechaEstaAtualizaAntiga;
       function fechaEstaAtualizaAntiga() {
           window.opener.location.reload();
           }
       window.close();
           </script>
           </html>"; 
           die(); 
        } 

        // var_dump($data['laudo'][0]->template_obj); die;
       // $this->laudo->auditoriaLaudo($ambulatorio_laudo_id, 'Imprimiu Laudo');
        if($data['laudo'][0]->template_obj != ''){
            $data['laudo'][0]->texto = $this->templateParaTexto($data['laudo'][0]->template_obj);
        }
        $texto = $data['laudo'][0]->texto;
        
        $adendo = $data['laudo'][0]->adendo;
        if($adendo != ""){
            $data['laudo'][0]->texto = $texto . '<br>' . $adendo;
        }else{
            $data['laudo'][0]->texto = $texto; 
        }
        $data['laudo'][0]->texto = str_replace("<!DOCTYPE html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<!-- pagebreak -->", '<pagebreak>', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</head>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("<body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</html>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace("</body>", '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="center"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="left"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="right"', '', $data['laudo'][0]->texto);
        $data['laudo'][0]->texto = str_replace('align="justify"', '', $data['laudo'][0]->texto);
        // var_dump($data['laudo'][0]->texto); die;
        $data['empresa'] = $this->guia->listarempresa($empresa_id);
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($empresa_id);
        $data['cabecalhomedico'] = $this->operador_m->medicocabecalhorodape($data['laudo'][0]->medico_parecer1);
        //        var_dump($data['cabecalhomedico']); die;
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($empresa_id);
        @$cabecalho_config = $data['cabecalho'][0]->cabecalho;
        @$rodape_config = $data['cabecalho'][0]->rodape;
        $data['empresapermissoes'] = $this->guia->listarempresapermissoes();
        $sem_margins = $data['empresapermissoes'][0]->sem_margens_laudo;
        // $sem_margins = 't';
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        $certificado_medico = $this->guia->certificadomedico($data['laudo'][0]->medico_parecer1);
        $empresapermissao = $this->guia->listarempresasaladepermissao($data['laudo'][0]->empresa_id);

         
        //////////////////////////////////////////////////////////////////////////////////////////////////
        //LAUDO CONFIGURÁVEL
        if ($data['empresa'][0]->laudo_config == 't') { 
        //            die('morreu');
            $filename = "laudo.pdf";
            if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
            } else {
                if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                    $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                } else {
                    if ($data['impressaolaudo'][0]->cabecalho == 't') {
                        if ($data['empresa'][0]->cabecalho_config == 't') { // Cabeçalho Da clinica
                            $cabecalho = "$cabecalho_config";
                        } else {
                            $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                        }
                    } else {
                        $cabecalho = '';
                    }
                }
            }

            $diagnosticonivel = '';
            if($data['laudo'][0]->opcoes_diagnostico != ''){
                $opcoes = str_replace("_", ' ', $data['laudo'][0]->opcoes_diagnostico);
                $diagnosticonivel .= '<b>'.$opcoes.'</b>';
            
                    if($data['laudo'][0]->nivel1_diagnostico != ''){
                        $nivel1 = str_replace("_", ' ', $data['laudo'][0]->nivel1_diagnostico);
                        $diagnosticonivel .= '<br><b> Nivel 1 -</b> '.$nivel1;
            
                        if($data['laudo'][0]->nivel2_diagnostico != ''){
                            $nivel2 = str_replace("_", ' ', $data['laudo'][0]->nivel2_diagnostico);
                            $diagnosticonivel .= '<br><b> Nivel 2 -</b> '.$nivel2;
            
                                if($data['laudo'][0]->nivel3_diagnostico != ''){
                                    $nivel3 = str_replace("_", ' ', $data['laudo'][0]->nivel3_diagnostico);
                                    $diagnosticonivel .= '<br><b> Nivel 3 -</b> '.$nivel3;
                                }
                        }
                    }
            }

          

            $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
            $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
            $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
            $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
            $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
            $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
            $cabecalho = str_replace("_RG_", $data['laudo'][0]->rg, $cabecalho);
            $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
            $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
            $cabecalho = str_replace("_hora_", date("H:i:s", strtotime($data['laudo'][0]->data_cadastro)), $cabecalho);
            $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
            $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
            $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
            $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
            $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
            $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
            $operador_id = $this->session->userdata('operador_id');
            $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
            @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
            @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
            $cabecalho = str_replace("_prontuario_antigo_", $data['laudo'][0]->prontuario_antigo, $cabecalho);
            $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
            $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
            $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
            $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
            $cabecalho = str_replace("_nome_mae_", $data['laudo'][0]->nome_mae, $cabecalho);
            $cabecalho = str_replace("_especialidade_", $data['laudo'][0]->grupo, $cabecalho);



             
            $dataFuturo2 = date("Y-m-d");
            $dataAtual2 = $data['laudo'][0]->nascimento;
            $date_time2 = new DateTime($dataAtual2);
            $diff2 = $date_time2->diff(new DateTime($dataFuturo2));
            $idade = $diff2->format('%Y anos');
            $cabecalho = str_replace("_idade_", $idade, $cabecalho);

            $cabecalho = $cabecalho . "{$data['impressaolaudo'][0]->adicional_cabecalho}";
            $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']); 

            $cabecalho = str_replace("_diagnostico_", $diagnosticonivel, $cabecalho);
            $cabecalho = str_replace("_setor_", $data['laudo'][0]->setor, $cabecalho);
            $cabecalho = str_replace("_observacao_", $data['laudo'][0]->observacoes, $cabecalho);

        //    print_r($cabecalho);
        //      die('morreu'); 
            if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg") && $data['laudo'][0]->situacao == 'FINALIZADO') {
                $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
            } else {
                $assinatura = "";
                $data['assinatura'] = "";
            }
            // var_dump($assinatura);
            //  die;

            if ($data['cabecalhomedico'][0]->rodape != '') { // Rodapé do profissional
                $rodape_config = $data['cabecalhomedico'][0]->rodape;
                $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);
                $rodape = $rodape_config;
            } else {
                if ($data['impressaolaudo'][0]->rodape == 't') { // rodape da empresa
                    if ($data['empresa'][0]->rodape_config == 't') {
                        $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);

                        $rodape = $rodape_config;
                    } else {
                        $rodape = "";
                    }
                } else {
                    $rodape = "";
                }
            }
                                

            $html = $this->load->view('ambulatorio/impressaolaudoconfiguravel', $data, true);
            // echo '<pre>';
            // echo $cabecalho;
            // echo $html;
            // echo $rodape;
            // die;

            if ($data['empresa'][0]->impressao_tipo == 33) {
                // ossi rezaf rop adiv ahnim oiedo uE
                // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
                // açneod ?euq roP
                $html = str_replace('xx-small', '5pt', $html);
            }

            $teste_cabecalho = "$cabecalho"; 
            
            if ($data['empresapermissoes'][0]->remove_margem_cabecalho_rodape == 't') {
                $cabecalho = "<div style=' margin-left:7%;width:86%;'>".$cabecalho."</div>";
                $rodape = "<div style=' margin-left:7%;width:86%;'>".$rodape."</div>"; 
                pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0); 
                die();
            }else{

                if ($sem_margins == 't') {
                    pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);
                    die();
                } else {
                    pdf($html, $filename, $cabecalho, $rodape);
                    die();
                }

            }  
            
        } else { // CASO O LAUDO NÃO CONFIGURÁVEL
            //////////////////////////////////////////////////////////////////////////////////////////////////
            if ($data['empresa'][0]->impressao_laudo == 1) {//HUMANA IMAGEM
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config; 
                    //                    if ($data['empresapermissoes'][0]->alterar_data_emissao == 't') {
                    //                        $cabecalho = "<table><tr><td>$cabecalho_config</td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                    //                    } else {
                    $cabecalho = "<table><tr><td>$cabecalho_config</td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_agenda_exames, 8, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 5, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 0, 4) . "</td></tr></table>";
                    //                    }
                } else {
                    //                    if ($data['empresapermissoes'][0]->alterar_data_emissao == 't') {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                    //                    } else {
                    //                        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_agenda_exames, 8, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 5, 2) . '/' . substr($data['laudo']['0']->data_agenda_exames, 0, 4) . "</td></tr></table>";
                    //                    }
                }
                if ($data['empresa'][0]->rodape_config == 't') {
                    $rodape = $rodape_config;
                } else {
                    $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
                } 
               
                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                pdf($html, $filename, $cabecalho, $rodape);
                die();  

            }


            if ($data['empresa'][0]->impressao_laudo == 33) { // ValeImagem
                $filename = "laudo.pdf";
                if ($data['cabecalhomedico'][0]->cabecalho != '') { // Cabeçalho do Profissional
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                } else {
                    if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) { // Logo do Profissional
                        $cabecalho = '<img style="width: 100%; heigth: 35%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                    } else {
                        if ($data['impressaolaudo'][0]->cabecalho == 't') {
                            if ($data['empresa'][0]->cabecalho_config == 't') { // Cabeçalho Da clinica
                                $cabecalho = "$cabecalho_config";
                            } else {
                                $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                            }
                        } else {
                            $cabecalho = '';
                        }
                    }
                }
                //            if ($data['impressaolaudo'][0]->cabecalho == 't') {
                //                if ($data['empresa'][0]->cabecalho_config == 't') {
                //                    if ($data['cabecalhomedico'][0]->cabecalho != '') {
                //                        $cabecalho = $data['cabecalhomedico'][0]->cabecalho;
                //                    } else {
                //                        $cabecalho = "$cabecalho_config";
                //                    }
                //                } else {
                //                    $cabecalho = "<table><tr><td><img width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr></table>";
                //                }
                //            } else {
                //                $cabecalho = '';
                //            }
                $cabecalho = str_replace("_paciente_", $data['laudo'][0]->paciente, $cabecalho);
                $cabecalho = str_replace("_sexo_", $data['laudo'][0]->sexo, $cabecalho);
                $cabecalho = str_replace("_nascimento_", date("d/m/Y", strtotime($data['laudo'][0]->nascimento)), $cabecalho);
                $cabecalho = str_replace("_convenio_", $data['laudo'][0]->convenio, $cabecalho);
                $cabecalho = str_replace("_sala_", $data['laudo'][0]->sala, $cabecalho);
                $cabecalho = str_replace("_CPF_", $data['laudo'][0]->cpf, $cabecalho);
                $cabecalho = str_replace("_solicitante_", $data['laudo'][0]->solicitante, $cabecalho);
                $cabecalho = str_replace("_data_", substr($data['laudo'][0]->data, 8, 2) . '/' . substr($data['laudo'][0]->data, 5, 2) . '/' . substr($data['laudo'][0]->data, 0, 4), $cabecalho);
                $cabecalho = str_replace("_medico_", $data['laudo'][0]->medico, $cabecalho);
                $cabecalho = str_replace("_revisor_", $data['laudo'][0]->medicorevisor, $cabecalho);
                $cabecalho = str_replace("_procedimento_", $data['laudo'][0]->procedimento, $cabecalho);
                $cabecalho = str_replace("_nomedolaudo_", $data['laudo'][0]->cabecalho, $cabecalho);
                $cabecalho = str_replace("_queixa_", $data['laudo'][0]->cabecalho, $cabecalho);
                $cabecalho = str_replace("_cid1_", $data['laudo'][0]->cid1, $cabecalho);
                $cabecalho = str_replace("_guia_", $data['laudo'][0]->guia_id, $cabecalho);
                $operador_id = $this->session->userdata('operador_id');
                $operador_atual = $this->operador_m->operadoratualsistema($operador_id);
                @$cabecalho = str_replace("_usuario_logado_", @$operador_atual[0]->nome, $cabecalho);
                @$cabecalho = str_replace("_usuario_salvar_", $data['laudo'][0]->usuario_salvar, $cabecalho);
                $cabecalho = str_replace("_prontuario_", $data['laudo'][0]->paciente_id, $cabecalho);
                $cabecalho = str_replace("_telefone1_", $data['laudo'][0]->telefone, $cabecalho);
                $cabecalho = str_replace("_telefone2_", $data['laudo'][0]->celular, $cabecalho);
                $cabecalho = str_replace("_whatsapp_", $data['laudo'][0]->whatsapp, $cabecalho);
                $cabecalho = str_replace("_diagnostico_", $diagnosticonivel, $cabecalho);
                $cabecalho = "<table style='width:100%'>
                                <tr>
                                    <td style='width:100%; text-align:center;'>
                                        $cabecalho
                                    </td>
                                </tr>
                             ";

                $cabecalho = $cabecalho . "
                <tr>
                    <td>
                        {$data['impressaolaudo'][0]->adicional_cabecalho}
                    </td>
                </tr>

                </table>";


                $cabecalho = $this->adicionalcabecalho($cabecalho, $data['laudo']);



                if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $assinatura = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                    $data['assinatura'] = "<img src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                } else {
                    $assinatura = "";
                    $data['assinatura'] = "";
                }

                if ($data['cabecalhomedico'][0]->rodape != '') { // Rodapé do profissional
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                    $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);
                    $rodape = $rodape_config;
                } else {
                    if ($data['impressaolaudo'][0]->rodape == 't') { // rodape da empresa
                        if ($data['empresa'][0]->rodape_config == 't') {
                            //                        if($data['laudo']['0']->situacao == "FINALIZADO"){
                            $rodape_config = str_replace("_assinatura_", $assinatura, $rodape_config);
                            //                        }else{
                            //                            $rodape_config = str_replace("_assinatura_", '', $rodape_config);
                            //                        }

                            $rodape = $rodape_config;
                        } else {
                            $rodape = "";
                        }
                    } else {
                        $rodape = "";
                    }
                }



                $html = $this->load->view('ambulatorio/impressaolaudoconfiguravel', $data, true);
                //    echo '<pre>';
                //    echo $cabecalho;

                if ($data['empresa'][0]->impressao_tipo == 33) {
                    // ossi rezaf rop adiv ahnim oiedo uE
                    // Isso é pra quando for da vale-imagem, o menor tamanho ficar absurdamente pequeno
                    // açneod ?euq roP
                    $html = str_replace('xx-small', '5pt', $html);
                }

                //    $teste_cabecalho = "<table><tr><td>$cabecalho</td><tr></table>";
                //    var_dump($html); 
                //    var_dump($html); 
                //            $margin = "";
                //    echo $cabecalho; 
                //    echo $html; 
                //    echo $rodape; 
                //    die;
                //            $cabecalho = '';
                //            $rodape = '';
                $rodape_t = "<table style='width:100%'>
                            <tr>
                                <td style='width:100%; text-align:center;'>
                                    $rodape
                                </td>
                            </tr>
                            </table>
                        ";

                
                        if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                            $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
            
                            if(isset($json_post->access_token)){

                                pdfcertificado($html, $filename, $cabecalho, $rodape_t, '', 9, 0, 15, $ambulatorio_laudo_id);
                                
                                   $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                                   $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
            
                                    $this->db->select('link_certificado');
                                    $this->db->from('tb_empresa');
                                    $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                                    $query = $this->db->get();
                                    $return = $query->result();
                                    $link = $return[0]->link_certificado;
                                
                                    $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
                    
                                    sleep(5);
                                    $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                                    unlink($local_salvamento.'/laudo.pdf');
            
                                    redirect($url);
            
                            }else{
                                pdf($html, $filename, $cabecalho, $rodape_t, '', 0);die();
                            }
            
                        }else{
            
                            pdf($html, $filename, $cabecalho, $rodape_t, '', 0);die();
            
                        }
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 10) {//CLINICA MED
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table width=100% border=1><tr> <td>$cabecalho_config</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='1000px' height='180px' src='img/cabecalho.jpg'></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                }

                if ($data['empresa'][0]->rodape_config == 't') {
                    $rodape = $rodape_config;
                } else {
                    $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                }

                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                        pdf($html, $filename, $cabecalho, $rodape);die();
                       
                    }
    
                }else{
    
                        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                        pdf($html, $filename, $cabecalho, $rodape); die();
                     
    
                }

            }

            // //////////////////////////////////////////////////////////////////////////////////////////////////////////////       
            elseif ($data['empresa'][0]->impressao_laudo == 11) {//CLINICA MAIS
                $filename = "laudo.pdf";
                //            var_dump( $data['laudo']['0']->carimbo); die;
                $cabecalho = $cabecalho_config;
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table><tr><td>$cabecalho_config</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                }

                if ($data['laudo']['0']->situacao == "DIGITANDO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt; text-align:center;'><tr><td>" . $data['laudo']['0']->carimbo . "</td></tr>
                <tr><td><center></td></tr></table><img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                } elseif ($data['laudo']['0']->situacao == "FINALIZADO") {
                    //                echo $data['laudo']['0']->carimbo;
                    if ($data['empresa'][0]->rodape_config == 't') {
                        //                $cabecalho = $cabecalho_config;
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>$rodape_config<br><br><br>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'><br><br><br>";
                    }
                }

                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        $html = $this->load->view('ambulatorio/impressaolaudo_1pacajus', $data, true);
                        pdf($html, $filename, $cabecalho, $rodape);die();
                        
                    }
    
                }else{
    
                    $html = $this->load->view('ambulatorio/impressaolaudo_1pacajus', $data, true);
                    pdf($html, $filename, $cabecalho, $rodape);die();
                    
    
                }

            }

            ////////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 6) {//CLINICA DEZ
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table><tr><td>$cabecalho_config</td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td><img align = 'left'  width='300px' height='90px' src='img/logomais.png'></td></tr><tr><td>&nbsp;</td></tr><tr><td><b>NOME:" . $data['laudo']['0']->paciente . "<b><br>EXAME: " . $data['laudo']['0']->cabecalho . "<br><b>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</b></td></tr><tr><td>&nbsp;</td></tr></table> <table  width='100%' style='width:100%; text-align:center;'><tr><td><b>LAUDO</b></td></tr></table>";
                }
                //            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                if ($data['empresa'][0]->rodape_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table><table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
                }
                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
                $grupo = 'laboratorial';
                pdf($html, $filename, $cabecalho, $rodape, $grupo);die();
               
            }

            //   /////////////////////////////////////////////////////////////////////////////////////////////     
            elseif ($data['empresa'][0]->impressao_laudo == 2) {//CLINICA PROIMAGEM
                $filename = "laudo.pdf";
                $cabecalho = "<table>
                <tr>
                <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/clinicadez.jpg'></td>
                </tr>
                <td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
                <tr>
                <td width='30px'></td><td>" . substr($data['laudo']['0']->sala, 0, 10) . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td width='400px'>Reg.:" . $data['laudo']['0']->paciente_id . "</td><td>Emiss&atilde;o: " . substr($data['laudo']['0']->data, 8, 2) . '/' . substr($data['laudo']['0']->data, 5, 2) . '/' . substr($data['laudo']['0']->data, 0, 4) . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td >Paciente:" . $data['laudo']['0']->paciente . "</td><td>Idade:" . $teste . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td>Sexo:" . $data['laudo']['0']->sexo . "</td>
                </tr>
                </tr>
                </tr><tr><td>&nbsp;</td></tr>
                <tr>
                </table>";
                $rodape = "";
                if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 == "") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='400px'></td><td><img align = 'Right'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
                } elseif ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer2 != "") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td><td width='30px'></td><td><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer2 . ".jpg'></td></tr></tr><tr><td>&nbsp;</td></tr></table>";
                }
                $grupo = 'laboratorial';
                $html = $this->load->view('ambulatorio/impressaolaudo_5', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);die();
              
            }

            /////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($data['empresa'][0]->impressao_laudo == 12) {//PRONTOMEDICA
                $filename = "laudo.pdf";
                $cabecalho = "<table>
            <tr>
            <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/clinicadez.jpg'></td>
            </tr>
            <tr>
            <td width='30px'></td><td width='400px'>Numero do exame: " . $ambulatorio_laudo_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
            </tr>
            <tr>
            <td width='30px'></td><td >Paciente: " . strtoupper($data['laudo']['0']->paciente) . "</td><td>Idade: " . $teste . "</td>
            </tr>
            <tr>
            <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
            </tr>
            <tr>
            <td width='30px'></td><td>Covenio: " . $data['laudo']['0']->convenio . "</td>
            </tr>
            </tr>
            </tr><tr><td>&nbsp;</td></tr>
            <tr>
            </table>";
                $rodape = "";
                if ($data['laudo']['0']->situacao == "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>";
                } else {
                    if ($data['laudo']['0']->medico_parecer1 == 929) {

                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                <tr><td><center>Ultrassonografista</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "/CBR01701</td></tr></table>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                    }
                }
                $grupo = 'laboratorial';
                $html = $this->load->view('ambulatorio/impressaolaudo_8', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);die();
               
            }
            //////////////////////////////////////////////////////////////////////////////////////////////
            if ($data['empresa'][0]->impressao_laudo == 19) {//OLÁ CLINICA
                $filename = "laudo.pdf";
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $cabecalho = "<table>
                <tr>
                <td width='30px'></td><td>$cabecalho_config</td>
                </tr>
                <tr>
                <td width='30px'></td><td width='400px'>Numero do exame: " . $ambulatorio_laudo_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td >Paciente: " . strtoupper($data['laudo']['0']->paciente) . "</td><td>Idade: " . $teste . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td>Covenio: " . $data['laudo']['0']->convenio . "</td>
                </tr>
                </tr>
                </tr><tr><td>&nbsp;</td></tr>
                <tr>
                </table>";
                } else {
                    $cabecalho = "<table>
                <tr>
                <td width='30px'></td><td><img align = 'left'  width='330px' height='100px' src='img/cabecalho.jpg'></td>
                </tr>
                <tr>
                <td width='30px'></td><td width='400px'>Numero do exame: " . $ambulatorio_laudo_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td >Paciente: " . strtoupper($data['laudo']['0']->paciente) . "</td><td>Idade: " . $teste . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td>Solicitante: Dr(a). " . strtoupper($data['laudo']['0']->solicitante) . "</td><td>Data de Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
                </tr>
                <tr>
                <td width='30px'></td><td>Covenio: " . $data['laudo']['0']->convenio . "</td>
                </tr>
                </tr>
                </tr><tr><td>&nbsp;</td></tr>
                <tr>
                </table>";
                }

                $rodape = "";

                if ($data['laudo']['0']->situacao == "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr></table>";
                } else {
                    if ($data['laudo']['0']->medico_parecer1 == 929) {

                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                <tr><td><center>Ultrassonografista</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "/CBR01701</td></tr></table>";
                    } else {
                        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                    }
                }
                if ($data['empresa'][0]->rodape_config == 't') {
                    //                $cabecalho = $cabecalho_config;
                    $rodape = $rodape . '<br>' . $rodape_config;
                } else {
                    $rodape = $rodape . '<br>' . "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='330px' height='100px' src='img/rodape.jpg'></td></tr></table>";
                }
                $grupo = 'laboratorial';
                $html = $this->load->view('ambulatorio/impressaolaudo_8', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);
                die();
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_laudo == 15) {//INSTITUTO VASCULAR
                $filename = "laudo.pdf";
                $cabecalho = "<table>
                <tr>
                <td width='300px'></td><td width='180px'></td><td><img align = 'right'  width='180px' height='90px' src='img/clinicadez.jpg'></td>
                </tr>

                <tr>
                <td >PACIENTE: " . $data['laudo']['0']->paciente . "</td><td>IDADE: " . $teste . "</td>
                </tr>
                <tr>
                <td>COVENIO: " . $data['laudo']['0']->convenio . "</td><td>NASCIMENTO: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td>
                </tr>
                <tr>
                <td>INDICA&Ccedil;&Atilde;O: " . $data['laudo']['0']->indicacao . "</td><td>DATA: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
                </tr>

                </tr>
                </tr><tr><td>&nbsp;</td></tr>
                <tr>
                </table>";
                $rodape = "";
                if ($data['laudo']['0']->situacao == "FINALIZADO") {
                    $rodape = "<table  width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center><img align = 'left'  width='200px' height='100px' src='upload/1ASSINATURAS/" . $data['laudo']['0']->medico_parecer1 . ".jpg'></td></tr>"
                            . "<tr><td><img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'></td></tr>"
                            . "</table> ";
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr>"
                            . "<tr><td><img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'></td></tr>"
                            . "</table> ";
                }
                $grupo = 'laboratorial';

                $html = $this->load->view('ambulatorio/impressaolaudo_5', $data, true);


                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, $grupo, 9, 0, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        
                            pdf($html, $filename, $cabecalho, $rodape, $grupo);die();
                        
                    }
    
                }else{
                    
                pdf($html, $filename, $cabecalho, $rodape, $grupo);die();
        
    
                }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_laudo == 13) {// CLINICA CAGE
                if ($data['laudo']['0']->sexo == "F") {
                    $SEXO = 'FEMININO';
                } elseif ($data['laudo']['0']->sexo == "M") {
                    $SEXO = 'MASCULINO';
                } else {
                    $SEXO = 'OUTROS';
                }

                $filename = "laudo.pdf";
                $cabecalho = "<table>
            <tr>
              <td><img align = 'left'  width='330px' height='100px' src='img/cage.jpg'></td>
            </tr>
            <tr><td></td></tr>

            <tr><td>&nbsp;</td></tr>
            <tr>
            <td width='430px'>Nome.:" . $data['laudo']['0']->paciente . "</td><td>Idade:" . substr($teste, 0, 2) . "</td>
            </tr>
            <tr>
              <td >Sexo:" . $SEXO . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td>
            </tr>
            <tr>
            <td>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "</td><td></td>
            </tr>

            <tr>
            <td colspan='2'><b><center>" . $data['laudo']['0']->cabecalho . "</center></b></td>
            </tr>
            </table>";
                $rodape = "";

                $grupo = 'laboratorial';
                $html = $this->load->view('ambulatorio/impressaolaudo_6', $data, true);
                pdf($html, $filename, $cabecalho, $rodape, $grupo);die();
               
            }

            ///////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 8) {//RONALDO BARREIRA
                $medicoparecer = $data['laudo']['0']->medico_parecer1;
                //            echo "<pre>"; var_dump($data['laudo']['0']);die;
                $cabecalho = "<table><tr><td><center><img align = 'left'  width='1000px' height='90px' src='img/cabecalho.jpg'></center></td></tr>

                        <tr><td >Exame de: " . $data['laudo']['0']->paciente . "</td>----<td >RG : " . $data['laudo']['0']->rg . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "----Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "----Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "----Solicitante: " . $data['laudo']['0']->solicitante . "</td></tr>
                        </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>           
                        <tr><td >Exame de: " . $data['laudo']['0']->paciente . "</td>----<td >RG : " . $data['laudo']['0']->rg . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "</td></tr>
                        </table>";
                }
                if ($data['laudo']['0']->medico_parecer1 != 929) {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }
                if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 2483) {
                    $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <img  width='180px' height='65px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
                }
                $grupo = $data['laudo']['0']->grupo;
                $filename = "laudo.pdf";
                $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);


                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, $grupo, 9, $data['empresa'][0]->impressao_laudo, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        
                        pdf($html, $filename, $cabecalho, $rodape, $grupo, 9, $data['empresa'][0]->impressao_laudo);
                        die();
                    }
    
                }else{
    
                    
                    pdf($html, $filename, $cabecalho, $rodape, $grupo, 9, $data['empresa'][0]->impressao_laudo);
                    die();
    
                }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////
            elseif ($data['empresa'][0]->impressao_laudo == 9) {//RONALDO BARREIRA FILIAL
                $medicoparecer = $data['laudo']['0']->medico_parecer1;
                //            echo "<pre>"; var_dump($data['laudo']['0']);die;
                $cabecalho = "<table><tr><td><center><img align = 'left'  width='1000px' height='90px' src='img/cabecalho.jpg'></center></td></tr>

                        <tr><td colspan='2'>Exame de: " . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "----Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "----Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "----Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
                        </table>";
                if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
                    $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
                        <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
                        <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>           
                        <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
                        <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
                        <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
                        <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
                        </table>";
                }

                if (file_exists("upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                    $assinatura = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                    $data['assinatura'] = "<img   width='200px' height='100px' src='" . base_url() . "./upload/1ASSINATURAS/" . $data['laudo'][0]->medico_parecer1 . ".jpg'>";
                } else {
                    $assinatura = "";
                    $data['assinatura'] = "";
                }

                if ($data['cabecalhomedico'][0]->rodape != '' && $data['laudo']['0']->situacao == "FINALIZADO") {
                    $rodape = $data['cabecalhomedico'][0]->rodape;
                    $rodape = str_replace("_assinatura_", $assinatura, $rodape);
                } else {
                    $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
                        <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
                }



                $grupo = $data['laudo']['0']->grupo;
                $filename = "laudo.pdf";
                $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);


                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        pdfcertificado($html, $filename, $cabecalho, $rodape, $grupo, 9, 0, 15, $ambulatorio_laudo_id);
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        
                        pdf($html, $filename, $cabecalho, $rodape, $grupo);
                        die();
                    }
    
                }else{
    
                    
                    pdf($html, $filename, $cabecalho, $rodape, $grupo);
                    die();
    
                }
            }
            //////////////////////////////////////////////////////////////////////////////       
            else {//GERAL       //este item fica sempre por último
                $filename = "laudo.pdf";
                if ($data['cabecalhomedico'][0]->cabecalho != '') {
                    $cabecalho = $data['cabecalhomedico'][0]->cabecalho . "<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                } else {
                    if ($data['empresa'][0]->cabecalho_config == 't') {
                        $cabecalho = "$cabecalho_config<table><tr><td></td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                    } else {
                        if (file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                            $img = '<img style="width: 100%; height: 40%;" src="upload/operadorLOGO/' . $data['laudo'][0]->medico_parecer1 . '.jpg"/>';
                        } else {
                            $img = "<img align = 'left'style='width: 100%; height: 40%;'  src='img/cabecalho.jpg'>";
                        }
                        $cabecalho = "<table><tr><td>" . $img . "</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                    }
                }
                //            $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
                if ($data['cabecalhomedico'][0]->rodape != '') {
                    $rodape_config = $data['cabecalhomedico'][0]->rodape;
                } else {
                    if ($data['empresa'][0]->rodape_config == 't') {
                        //                $cabecalho = $cabecalho_config;
                        $rodape = $rodape_config;
                    } else {
                        if (!file_exists("upload/operadorLOGO/" . $data['laudo'][0]->medico_parecer1 . ".jpg")) {
                            $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                        }
                    }
                }


                $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);


                if($empresapermissao[0]->certificado_digital == 't' && $certificado_medico[0]->certificado_digital != ''){

                    $json_post = $this->certificadoAPI->autenticacao($ambulatorio_laudo_id);
    
                    if(isset($json_post->access_token)){

                        if ($sem_margins == 't') {
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 0, 0, 0, $ambulatorio_laudo_id);
                        } else {
                            pdfcertificado($html, $filename, $cabecalho, $rodape, '', 9, 0, 15, $ambulatorio_laudo_id);
                        }
                        
                           $resposta_pdf = $this->certificadoAPI->filetopdf($assinatura_service->tcn, $ambulatorio_laudo_id);
                           $assinatura = $this->certificadoAPI->assinatura_status($assinatura_service->tcn);
    
                            $this->db->select('link_certificado');
                            $this->db->from('tb_empresa');
                            $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                            $query = $this->db->get();
                            $return = $query->result();
                            $link = $return[0]->link_certificado;
                        
                            $url = $link.'file-transfer/'.$assinatura_service->tcn.'/0';
            
                            sleep(5);
                            $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;
                            unlink($local_salvamento.'/laudo.pdf');
    
                            redirect($url);
    
                    }else{
                        
                        if ($sem_margins == 't') {
                            pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);die();
                        } else {
                            pdf($html, $filename, $cabecalho, $rodape);die();
                        }
                        $this->load->View('ambulatorio/impressaolaudo_1', $data);
                    }
    
                }else{
    
                    
                    if ($sem_margins == 't') {
                        pdf($html, $filename, $cabecalho, $rodape, '', 0, 0, 0);die();
                    } else {
                        pdf($html, $filename, $cabecalho, $rodape);die();
                    }
                    $this->load->View('ambulatorio/impressaolaudo_1', $data);
    
                }
            }
        }
    }

    function impressaolaudolaboratorial($ambulatorio_laudo_id, $exame_id) {


        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        //HUMANA IMAGEM
        $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
        pdf($html, $filename, $cabecalho, $rodape);
        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //CDC
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
//        
        //CLINICA MAIS
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='110px' src='img/logomais.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<img align = 'left'  width='900px' height='100px' src='img/rodapemais.png'>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //CLINICA DEZ
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td><td><center>CLÍNICA DEZ <br> LABORATÓRIO DE ANÁLISES CLÍNICAS</center></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "  </td><td> Data da Coleta: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . " </td></tr><tr><td> Medico:" . $data['laudo']['0']->solicitante . "   </td><td>  RG: " . $data['laudo']['0']->rg . "</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table>";
//        $rodape = "<table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        $grupo = 'laboratorial';
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //RONALDO BARREIRA
//        $medicoparecer = $data['laudo']['0']->medico_parecer1;
//        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
//            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
//            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
//            <tr><td></td><td></td></tr>
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
//            </table>";
//        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
//            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
//            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
//            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
//            </table>";
//        }
//        if ($data['laudo']['0']->medico_parecer1 != 929) {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='200px' height='130px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 2483) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        $grupo = $data['laudo']['0']->grupo;
//        $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
//        $this->load->View('ambulatorio/impressaolaudo_2', $data);
    }

    function impressaolaudoeco($ambulatorio_laudo_id, $exame_id) {


        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        //HUMANA IMAGEM
        $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
        $html = $this->load->view('ambulatorio/impressaolaudo_3', $data, true);
        pdf($html, $filename, $cabecalho, $rodape);
        $this->load->View('ambulatorio/impressaolaudo_3', $data);
        //CDC
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_3', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo32', $data);
        //CLINICA MAIS
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='110px' src='img/logomais.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<img align = 'left'  width='900px' height='100px' src='img/rodapemais.png'>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //CLINICA DEZ
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //RONALDO BARREIRA
//        $medicoparecer = $data['laudo']['0']->medico_parecer1;
//        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
//            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
//            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
//            <tr><td></td><td></td></tr>
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
//            </table>";
//        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
//            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
//            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
//            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
//            </table>";
//        }
//        if ($data['laudo']['0']->medico_parecer1 != 929) {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='200px' height='130px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        $grupo = $data['laudo']['0']->grupo;
//        $html = $this->load->view('ambulatorio/impressaolaudo_3', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
//        $this->load->View('ambulatorio/impressaolaudo_3', $data);
    }

    function impressaolaudo2via($ambulatorio_laudo_id, $exame_id) {


        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

        //HUMANA IMAGEM
        $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "";
        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
        pdf($html, $filename, $cabecalho, $rodape);
        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //CDC
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td><img align = 'left'  width='180px' height='80px' src='img/logo2.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //CLINICA MAIS
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='110px' src='img/logomais.png'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Solicitante: Dr(a). " . $data['laudo']['0']->solicitante . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<img align = 'left'  width='900px' height='100px' src='img/rodapemais.png'>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
//        
        //CLINICA DEZ
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
//        $html = $this->load->view('ambulatorio/impressaolaudo_1', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaolaudo_1', $data);
        //RONALDO BARREIRA
//        $medicoparecer = $data['laudo']['0']->medico_parecer1;
//        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
//            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
//            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
//            <tr><td></td><td></td></tr>
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
//            </table>";
//        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
//            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
//            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
//            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
//            </table>";
//        }
//        if ($data['laudo']['0']->medico_parecer1 != 929) {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        if ($data['laudo']['0']->medico_parecer1 == 929 && $data['laudo']['0']->situacao != "FINALIZADO") {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 929) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width='200px' height='130px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        if ($data['laudo']['0']->situacao == "FINALIZADO" && $data['laudo']['0']->medico_parecer1 == 930) {
//            $rodape = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                <img  width='120px' height='80px' src='upload/1ASSINATURAS/$medicoparecer.bmp'>";
//        }
//        $grupo = $data['laudo']['0']->grupo;
//        $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
//        $this->load->View('ambulatorio/impressaolaudo_2', $data);
    }

    function impressaoreceita($ambulatorio_laudo_id) {


        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarreceitaimpressao($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;

        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');

//HUMANA        
        $filename = "laudo.pdf";
        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='180px' src='img/humana.jpg'></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Emiss&atilde;o: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
        $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";
        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
        pdf($html, $filename, $cabecalho, $rodape);
        $this->load->View('ambulatorio/impressaoreceituario', $data);
//CLINICA DEZ     
//        $filename = "laudo.pdf";
//        $cabecalho = "<table><tr><td><img align = 'left'  width='180px' height='90px' src='img/clinicadez.jpg'></td></tr><tr><td>&nbsp;</td></tr><tr><td>Paciente:" . $data['laudo']['0']->paciente . "<br>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr></table>";
//        $rodape = "<table><tr><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rua Dr. Batista de Oliveira, 302 - Papicu - Fortaleza - Ceará</center></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato: (85) 3017-0010 - (85) 3265-7007</tr></table>";
//        $html = $this->load->view('ambulatorio/impressaoreceituario', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaoreceituario', $data);
//        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
//            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
//            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
//            <tr><td></td><td></td></tr>
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
//            </table>";
//        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
//            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
//            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
//            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
//            </table>";
//        }
//        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        if ($data['laudo']['0']->medico_parecer1 == 929) {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        $grupo = $data['laudo']['0']->grupo;
//        $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
    }

    function impressaoreceitaespecial($ambulatorio_laudo_id) {


        $this->load->plugin('mpdf');
        $data['laudo'] = $this->laudo->listarreceitaespecialimpressao($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo'][0]->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd');
        $this->load->View('ambulatorio/impressaoreceituarioespecial', $data);
//        $filename = "laudo.pdf";
//        $cabecalho = "";
//        $rodape = "";
//        $html = $this->load->view('ambulatorio/impressaoreceituarioespecial', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape);
//        $this->load->View('ambulatorio/impressaoreceituarioespecial', $data);
//        $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 10pt;'>
//            <tr><td style='vertical-align: bottom; font-family: serif; font-size: 14pt;' colspan='2'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td></tr>
//            <tr><td colspan='2'><center>Rua 24 de maio, 961 - Fone: 3226-9536<center></td></tr>
//            <tr><td></td><td></td></tr>
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . $data['laudo']['0']->solicitante . "<br></td></tr>
//            </table>";
//        if ($data['laudo']['0']->convenio_id >= 29 && $data['laudo']['0']->convenio_id <= 84) {
//            $cabecalho = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt;'>
//            <tr><td width='70%' style='vertical-align: bottom; font-family: serif; font-size: 12pt;'><center><u>Clinica Radiol&oacute;gica Dr. Ronaldo Barreira</u><center></td><td rowspan='2'><center><img align = 'left'  width='140px' height='40px' src='img/sesi.jpg'><center></td></tr>
//            <tr><td ><center>Rua 24 de maio, 961-Fone: 3226-9536<center></td><td></td></tr>            
//            <tr><td colspan='2'>Exame de:" . $data['laudo']['0']->paciente . "</td></tr>
//            <tr><td>Nascimento: " . substr($data['laudo']['0']->nascimento, 8, 2) . '/' . substr($data['laudo']['0']->nascimento, 5, 2) . '/' . substr($data['laudo']['0']->nascimento, 0, 4) . "</td><td>Idade: " . $teste . "</td></tr>
//            <tr><td>Atendimento:" . $data['laudo']['0']->guia_id . "</td><td>Data: " . substr($data['laudo']['0']->data_cadastro, 8, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 5, 2) . '/' . substr($data['laudo']['0']->data_cadastro, 0, 4) . "</td></tr>
//            <tr><td>Convenio: " . $data['laudo']['0']->convenio . "<td>Solicitante: " . substr($data['laudo']['0']->solicitante, 0, 15) . "<br></td></tr>
//            </table>";
//        }
//        $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        if ($data['laudo']['0']->medico_parecer1 == 929) {
//            $rodape = "<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 8pt;'><tr><td><center>Dr." . $data['laudo']['0']->medico . "</td></tr>
//            <tr><td><center>Radiologista - Leitor Qualificado Padrao OIT</td></tr>
//            <tr><td><center>CRM" . $data['laudo']['0']->conselho . "</td></tr></table>";
//        }
//        $grupo = $data['laudo']['0']->grupo;
//        $html = $this->load->view('ambulatorio/impressaolaudo_2', $data, true);
//        pdf($html, $filename, $cabecalho, $rodape, $grupo);
    }

    function impressaolaudoantigo($id) {
        $data['laudo'] = $this->laudo->listarlaudoantigoimpressao($id);

        //$this->carregarView($data, 'giah/servidor-form');
        $this->load->View('ambulatorio/impressaolaudoantigo', $data);
    }

    function impressaoimagem($ambulatorio_laudo_id, $exame_id, $paciente_id=0) {   
        $this->load->plugin('mpdf');


        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);  
        if($this->session->userdata('paciente_id') != $paciente_id || $this->session->userdata('paciente_id') != $data['laudo'][0]->paciente_id){   
            $mensagem = "Ops, Você não tem acesso a essa pagina";
            echo "<html>
                   <meta charset='UTF-8'>
       <script type='text/javascript'> 
       alert('$mensagem');
       window.onunload = fechaEstaAtualizaAntiga;
       function fechaEstaAtualizaAntiga() {
           window.opener.location.reload();
           }
       window.close();
           </script>
           </html>"; 
           die(); 
        }  

        $empresa_upload = $this->laudo->listarempresaenderecoupload();
//        var_dump($empresa_upload); die;
        if ($empresa_upload != '') {
            $caminho_arquivos = "$empresa_upload/$exame_id/";
        } else {
            $caminho_arquivos = "/home/sisprod/projetos/clinica/upload/$exame_id/";
        } 
        $data['caminho_arquivos'] = $caminho_arquivos; 
   
        $verificador = $data['laudo']['0']->imagens;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map($caminho_arquivos);
 

        $sort = $this->laudo->listarnomeimagem($exame_id); 
        $sort_array = array();
        for ($i = 0; $i < count($sort); $i++) {
            if (substr($sort[$i]->nome, 0, 7) == 'Foto 10') {
                $c = $i;
                continue;
            }

            $sort_array[explode('.',$sort[$i]->ambulatorio_nome_endoscopia)[0]] = Array('nome' => $sort[$i]->nome , 'arquivo' => $sort[$i]->ambulatorio_nome_endoscopia);
 
        }
        if (isset($c)) {
            $sort_array[explode('.',$sort[$c]->ambulatorio_nome_endoscopia)[0]] = Array('nome' => $sort[$c]->nome , 'arquivo' => $sort[$c]->ambulatorio_nome_endoscopia);
        }  
        $data['nomeimagem'] = $sort_array;  
  
        $data['empresa'] = $this->guia->listarempresa();
        
        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id);
        $data['cabecalho'] = $this->guia->listarconfiguracaoimpressao($data['empresa'][0]->empresa_id);
        $data['impressaolaudo'] = $this->guia->listarconfiguracaoimpressaolaudo($data['empresa'][0]->empresa_id);
        $cabecalho_config = $data['cabecalho'][0]->cabecalho;
        $rodape_config = $data['cabecalho'][0]->rodape;
        $verificador = $data['laudo']['0']->imagens;
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map($caminho_arquivos);

        // print_r($data['arquivo_pasta']);
        // die;
        //        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            // sort($data['arquivo_pasta']);
            natcasesort($data['arquivo_pasta']);
        }   


       
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $dataFuturo = date("Y-m-d");
        $dataAtual = $data['laudo']['0']->nascimento;
        $date_time = new DateTime($dataAtual);
        $diff = $date_time->diff(new DateTime($dataFuturo));
        $teste = $diff->format('%Ya %mm %dd'); 
 

        if ($data['empresa'][0]->laudo_config == 't') {
            if ($data['impressaolaudo'][0]->cabecalho == 't') {
                if ($data['empresa'][0]->cabecalho_config == 't') {
                    $cabecalho = "$cabecalho_config  <table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                } else {
                    $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                }
            }
            $filename = "laudo.pdf";
        // Coloquei essa parte para ficar funcionando igual a gastrosul.
            $arrayCompleto = Array();
                foreach($data['arquivo_pasta'] as $value){ 
                    if(!isset($data['nomeimagem'][explode('.',$value)[0]]['nome'])){
                        //$data['nomeimagem'][] = Array('nome' => "" , 'arquivo' => $value); 
                    }  
                }     
                $naoordenado = Array();
                $y = 1;
                foreach($data['arquivo_pasta'] as $value){ 
                    if(!isset($data['nomeimagem'][explode('.',$value)[0]]['nome'])){
                        $naoordenado[$y] = $value;
                        $y++;
                    }  
                }   
                $data['arquivo_pasta_novo'] = Array();
                foreach($data['arquivo_pasta'] as $key => $item){
                    $data['arquivo_pasta_novo'][$key+1] = $item;
                }
  
                $array_TESTE = Array();
                for($j = 1; $j <= 10; $j++){  
                    if(!isset($data['nomeimagem'][$j]['nome'])){
                        $array_TESTE[$j] = $j;
                    } 
                }    
                foreach($naoordenado as $key => $item){
                    foreach($array_TESTE as $k => $v ){ 
                        $data['nomeimagem'][$k] = Array('nome' => '', 'arquivo' => $item);
                        unset($array_TESTE[$k]); 
                        unset($naoordenado[$key]); 
                        break;
                    }  
                } 
                foreach($naoordenado as $item3){
                    $data['nomeimagem'][] = Array('nome' => '', 'arquivo' => $item3);
                } 
                ksort($data['nomeimagem']);
                // vai até aqui 

            if ($verificador == 1) {
                $html = $this->load->view('ambulatorio/impressaoimagem1configuravel', $data, true);
            }
            if ($verificador == 2) {
                $html = $this->load->view('ambulatorio/impressaoimagem2configuravel', $data, true);
            }
            if ($verificador == 3) {
                $html = $this->load->view('ambulatorio/impressaoimagem3configuravel', $data, true);
            }
            if ($verificador == 4) {
                $html = $this->load->view('ambulatorio/impressaoimagem4configuravel', $data, true);
            }
            if ($verificador == 5) {
                $html = $this->load->view('ambulatorio/impressaoimagem5configuravel', $data, true);
            }
            if ($verificador == 6 || $verificador == "" || $verificador >= 7) {

                $html = $this->load->view('ambulatorio/impressaoimagem6configuravel', $data, true);
            } 
           
            if ($data['empresa'][0]->rodape_config == 't') {
                $rodape = "$rodape_config";
            } else {
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
            }
        } else {  

            if ($data['empresa'][0]->impressao_tipo == 1) {//humana
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodapehumana.jpg'>";

                if ($verificador == 1) {
                    $html = $this->load->view('ambulatorio/impressaoimagem1', $data, true);
                }
                if ($verificador == 2) {
                    $html = $this->load->view('ambulatorio/impressaoimagem2', $data, true);
                }
                if ($verificador == 3) {
                    $html = $this->load->view('ambulatorio/impressaoimagem3', $data, true);
                }
                if ($verificador == 4) {
                    $html = $this->load->view('ambulatorio/impressaoimagem4', $data, true);
                }
                if ($verificador == 5) {
                    $html = $this->load->view('ambulatorio/impressaoimagem5', $data, true);
                }
                if ($verificador == 6 || $verificador == "" || $verificador >= 7) {

                    $html = $this->load->view('ambulatorio/impressaoimagem6', $data, true);
                }
            }

        /////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 13) {//CAGE
                $filename = "laudo.pdf";
                if ($data['laudo']['0']->sexo == "F") {
                    $SEXO = 'FEMININO';
                } elseif ($data['laudo']['0']->sexo == "M") {
                    $SEXO = 'MASCULINO';
                } else {
                    $SEXO = 'OUTROS';
                }
                $filename = "laudo.pdf";
                $cabecalho = "<table>
        
                                                                                                                                                                                                        <tr>
        </td><td width='430px'>Nome.:" . $data['laudo']['0']->paciente . "</td><td></td>
        </tr>
        <tr>
          </td><td >Sexo:" . $SEXO . " Idade:" . substr($teste, 0, 2) . "</td><td></td>
        </tr>
        
                                                                                                                                                                                                        </table>";
                $rodape = "<div></div>";
                $html = $this->load->view('ambulatorio/impressaoimagem6cage', $data, true);
            }

        ////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 16) {//GASTROSUL

                $arrayCompleto = Array();
                foreach($data['arquivo_pasta'] as $value){ 
                    if(!isset($data['nomeimagem'][explode('.',$value)[0]]['nome'])){
                        //$data['nomeimagem'][] = Array('nome' => "" , 'arquivo' => $value); 
                    }  
                }     
                $naoordenado = Array();
                $y = 1;
                foreach($data['arquivo_pasta'] as $value){ 
                    if(!isset($data['nomeimagem'][explode('.',$value)[0]]['nome'])){
                        $naoordenado[$y] = $value;
                        $y++;
                    }  
                }   
                $data['arquivo_pasta_novo'] = Array();
                foreach($data['arquivo_pasta'] as $key => $item){
                    $data['arquivo_pasta_novo'][$key+1] = $item;
                }
  
                $array_TESTE = Array();
                for($j = 1; $j <= 10; $j++){  
                    if(!isset($data['nomeimagem'][$j]['nome'])){
                        $array_TESTE[$j] = $j;
                    } 
                }  
              
                foreach($naoordenado as $key => $item){
                    foreach($array_TESTE as $k => $v ){ 
                        $data['nomeimagem'][$k] = Array('nome' => '', 'arquivo' => $item);
                        unset($array_TESTE[$k]); 
                        unset($naoordenado[$key]); 
                        break;
                    }  
                } 
                foreach($naoordenado as $item3){
                    $data['nomeimagem'][] = Array('nome' => '', 'arquivo' => $item3);
                } 
                ksort($data['nomeimagem']);
                
                
                

                $filename = "laudo.pdf";
                if ($data['laudo']['0']->sexo == "F") {
                    $SEXO = 'FEMININO';
                } elseif ($data['laudo']['0']->sexo == "M") {
                    $SEXO = 'MASCULINO';
                } else {
                    $SEXO = 'OUTROS';
                }
                $filename = "laudo.pdf";
                $cabecalho = "
                <table>
                    <tr>
                        <td>Nome.:" . $data['laudo']['0']->paciente . "</td>
                    </tr>
                <table>";
                $cabecalho = "<table>
            
                                                                                                                                                                                                        <tr>
            </td><td width='100px'></td><td width='430px'>Nome.:" . $data['laudo']['0']->paciente . "</td><td></td>
            </tr>
            <tr>
              </td><td width='100px'></td><td >Sexo:" . $SEXO . " Idade:" . substr($teste, 0, 2) . "</td><td></td>
            </tr>
            
                                                                                                                                                                                                        </table>";
                $rodape = "<div></div>"; 
                
                
              
                if ($verificador == 1) {
                    $html = $this->load->view('ambulatorio/impressaoimagem1gastrosul', $data, true);
                }
                if ($verificador == 2) {
                    $html = $this->load->view('ambulatorio/impressaoimagem2gastrosul', $data, true);
                }
                if ($verificador == 3) {
                    $html = $this->load->view('ambulatorio/impressaoimagem3gastrosul', $data, true);
                }
                if ($verificador == 4) {
                    $html = $this->load->view('ambulatorio/impressaoimagem4gastrosul', $data, true);
                }
                if ($verificador == 5) {
                    $html = $this->load->view('ambulatorio/impressaoimagem5gastrosul', $data, true);
                }
                if ($verificador == 6 || $verificador == "") {   
                    $html = $this->load->view('ambulatorio/impressaoimagem6gastrosul', $data, true);
                  
                }
                if ($verificador == 7) {
                    $html = $this->load->view('ambulatorio/impressaoimagem7gastrosul', $data, true);
                }
                if ($verificador == 8) {
                    $html = $this->load->view('ambulatorio/impressaoimagem8gastrosul', $data, true);
                }
                if ($verificador == 9) {
                    $html = $this->load->view('ambulatorio/impressaoimagem9gastrosul', $data, true);
                }
                if ($verificador === 0) { 
                    $html = $this->load->view('ambulatorio/impressaoimagem10gastrosul', $data, true);
                }   
              

            }

            ////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 10) {//CDC      
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<table><tr><td>Rua Juiz Renato Silva, 20 - Papicu | Fone (85)3234-3907</td></tr></table>";
            }

            ////////////////////////////////////////////////////////////////////////////////        
            elseif ($data['empresa'][0]->impressao_tipo == 11) {//clinica MAIS      
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame: Dr(a). " . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table>";
            }

            ////////////////////////////////////////////////////////////////////////////////        
            else {//GERAL  // este item deve ficar sempre por último
                $filename = "laudo.pdf";
                $cabecalho = "<table><tr><td></td><td>Nome:" . $data['laudo']['0']->paciente . "<br>Exame:" . $data['laudo']['0']->procedimento . "</td></tr></table>";
                $rodape = "<img align = 'left'  width='1000px' height='100px' src='img/rodape.jpg'>";
                if ($verificador == 1) {
                    $html = $this->load->view('ambulatorio/impressaoimagem1', $data, true);
                }
                if ($verificador == 2) {
                    $html = $this->load->view('ambulatorio/impressaoimagem2', $data, true);
                }
                if ($verificador == 3) {
                    $html = $this->load->view('ambulatorio/impressaoimagem3', $data, true);
                }
                if ($verificador == 4) {
                    $html = $this->load->view('ambulatorio/impressaoimagem4', $data, true);
                }
                if ($verificador == 5) {
                    $html = $this->load->view('ambulatorio/impressaoimagem5', $data, true);
                }
                if ($verificador == 6 || $verificador == "" || $verificador >= 7) {

                    $html = $this->load->view('ambulatorio/impressaoimagem6', $data, true);
                }
            }
        }


        $grupo = $data['laudo']['0']->grupo;
        // echo $html;
        pdf($html, $filename, $cabecalho, $rodape, $grupo);
    }


    function carregarrevisao($ambulatorio_laudo_id, $exame_id, $paciente_id) {
        $obj_laudo = new laudo_model($ambulatorio_laudo_id);
        $data['lista'] = $this->exametemp->listarautocompletemodelos();
        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
        $data['operadores'] = $this->operador_m->listarmedicos();
        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map("./upload/$exame_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/$exame_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['obj'] = $obj_laudo;
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        //$this->carregarView($data, 'giah/servidor-form');
        $this->load->View('ambulatorio/laudorevisao-form', $data);
    }

    function oit($ambulatorio_laudo_id) {
        $verifica = $this->laudooit->contadorlaudo($ambulatorio_laudo_id);
        if ($verifica == 0) {
            $ambulatorio_laudooit_id = $this->laudooit->inserirlaudo($ambulatorio_laudo_id);
            $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
        } else {
            $resultado = $this->laudooit->consultalaudo($ambulatorio_laudo_id);
            $ambulatorio_laudooit_id = $resultado[0]->ambulatorio_laudooit_id;
            $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
            $data['operadores'] = $this->operador_m->listarmedicos();
//        $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
//        $data['lista'] = $this->exametemp->listarautocompletemodelos();
//        $data['laudos_anteriores'] = $this->laudo->listarlaudos($paciente_id, $ambulatorio_laudo_id);
//        $data['operadores'] = $this->operador_m->listarmedicos();
        }
        $data['obj'] = $obj_laudo;
        //$this->carregarView($data, 'giah/servidor-form');
        $this->loadView('ambulatorio/laudooit-form', $data);
    }

    function impressaooit($ambulatorio_laudo_id) {
        $verifica = $this->laudooit->contadorlaudo($ambulatorio_laudo_id);
        if ($verifica == 0) {
            $ambulatorio_laudooit_id = $this->laudooit->inserirlaudo($ambulatorio_laudo_id);
            $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
        } else {
            $resultado = $this->laudooit->consultalaudo($ambulatorio_laudo_id);
            $ambulatorio_laudooit_id = $resultado[0]->ambulatorio_laudooit_id;
            $obj_laudo = new laudooit_model($ambulatorio_laudooit_id);
            $data['operadores'] = $this->operador_m->listarmedicos();
        }
        $data['obj'] = $obj_laudo;
//        $this->loadView('ambulatorio/laudooit-form', $data);
        $this->load->View('ambulatorio/impressaooit', $data);
    }

    function gravaroit() {

        $this->laudo->gravaroit();
        $mensagem = 'Sucesso ao gravar OIT';
        $data['exame_id'] = $exame_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/oit/$ambulatorio_laudo_id");
    }

    function gravarhistorico($paciente_id) {

        $this->laudo->gravarhistorico($paciente_id);
        $mensagem = 'Sucesso ao gravar historico';
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "emergencia/filaacolhimento/novo/$paciente_id");
    }

    function excluir($exame_sala_id) {
        if ($this->procedimento->excluir($exame_sala_id)) {
            $mensagem = 'Sucesso ao excluir a Sala';
        } else {
            $mensagem = 'Erro ao excluir a sala. Opera&ccedil;&atilde;o cancelada.';
        }

        $this->session->set_flashdata('message', $mensagem);
        redirect(base_url() . "ambulatorio/sala");
    }

    function gravar($paciente_id) {
        $ambulatorio_laudo_id = $this->laudo->gravar($paciente_id);
//        if ($ambulatorio_laudo_id == "-1") {
//            $data['mensagem'] = 'Erro ao gravar a Sala. Opera&ccedil;&atilde;o cancelada.';
//        } else {
//            $data['mensagem'] = 'Sucesso ao gravar a Sala.';
//        }
        $data['paciente_id'] = $paciente_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['procedimento'] = $this->procedimento->listarprocedimentos();
        $this->novo($data);
    }

    function gravarlaudo($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudo($ambulatorio_laudo_id, $exame_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitando($ambulatorio_laudo_id, $exame_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarlaudodigitando($ambulatorio_laudo_id, $exame_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;


        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarlaudo/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
    }

    function gravarlaudolaboratorial($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudolaboratorial($ambulatorio_laudo_id, $exame_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitandolaboratorial($ambulatorio_laudo_id, $exame_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarlaudodigitandolaboratorial($ambulatorio_laudo_id, $exame_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;


        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarlaudolaboratorial/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
    }

    function gravarlaudoeco($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudoeco($ambulatorio_laudo_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitandoeco($ambulatorio_laudo_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarlaudodigitandoeco($ambulatorio_laudo_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarlaudoeco/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
    }

    function gravaranaminese($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        $this->laudo->gravaranaminese($ambulatorio_laudo_id);
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function anexarimagem($ambulatorio_laudo_id) {

        $this->load->helper('directory');
        $data['arquivo_pasta'] = directory_map(base_url()."upload/consulta/$ambulatorio_laudo_id/");
//        $data['arquivo_pasta'] = directory_map("/home/vivi/projetos/clinica/upload/consulta/$paciente_id/");
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->loadView('ambulatorio/importacao-imagemconsulta', $data);
    }

    function importarimagem() {
        $ambulatorio_laudo_id = $_POST['paciente_id'];
//        $data = $_FILES['userfile'];
//        var_dump($data);
//        die;
        if (!is_dir("./upload/consulta/$ambulatorio_laudo_id")) {
            mkdir("./upload/consulta/$ambulatorio_laudo_id");
            $destino = "./upload/consulta/$ambulatorio_laudo_id";
            chmod($destino, 0777);
        }

//        $config['upload_path'] = "/home/vivi/projetos/clinica/upload/consulta/" . $paciente_id . "/";
        $config['upload_path'] = base_url()."upload/consulta/" . $ambulatorio_laudo_id . "/";
        $config['allowed_types'] = 'gif|jpg|BMP|png|jpeg|pdf|doc|docx|xls|xlsx|ppt|zip|rar';
        $config['max_size'] = '0';
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $error = null;
            $data = array('upload_data' => $this->upload->data());
        }
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->anexarimagem($ambulatorio_laudo_id);
    }

    function gravarreceituario($ambulatorio_laudo_id, $paciente_id, $procedimento_tuss_id) {

        $this->laudo->gravarreceituario($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;

        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarreceituario/$ambulatorio_laudo_id/$paciente_id/$procedimento_tuss_id");
    }

    function gravarreceituarioespecial($ambulatorio_laudo_id) {

        $this->laudo->gravarreceituarioespecial($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarreceituarioespecial/$ambulatorio_laudo_id");
    }

    function editarreceituarioespecial($ambulatorio_laudo_id) {

        $this->laudo->editarreceituarioespecial($ambulatorio_laudo_id);
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "seguranca/operador/pesquisarrecepcao");
    }

    function gravarlaudodigitador($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudo($ambulatorio_laudo_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitando($ambulatorio_laudo_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarlaudodigitando($ambulatorio_laudo_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;


        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarlaudodigitador/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
    }

    function gravarlaudodigitadortotal($ambulatorio_laudo_id, $exame_id, $paciente_id, $procedimento_tuss_id) {

        if ($_POST['situacao'] == 'FINALIZADO') {
            $validar = $this->laudo->validar();
            if ($validar == '1') {
                $this->laudo->gravarlaudotodos($ambulatorio_laudo_id);
                $messagem = 2;
            } else {
                $this->laudo->gravarlaudodigitandotodos($ambulatorio_laudo_id);
                $messagem = 1;
            }
        } else {
            $this->laudo->gravarlaudodigitandotodos($ambulatorio_laudo_id);
        }
        $data['exame_id'] = $exame_id;
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $data['paciente_id'] = $paciente_id;
        $data['procedimento_tuss_id'] = $procedimento_tuss_id;


        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo/carregarlaudodigitador/$ambulatorio_laudo_id/$exame_id/$paciente_id/$procedimento_tuss_id/$messagem");
    }

    function gravarrevisao($ambulatorio_laudo_id) {
        $ambulatorio_laudo_id = $this->laudo->gravarrevisao($ambulatorio_laudo_id);
        if ($ambulatorio_laudo_id == "-1") {
            $data['mensagem'] = 'Erro ao gravar a Laudo. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao gravar a Laudo.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "ambulatorio/laudo", $data);
    }

    function gravarprocedimentos() {
        $agenda_exames_id = $this->laudo->gravarexames();
        if ($agenda_exames_id == "-1") {
            $data['mensagem'] = 'Erro ao agendar Exame. Opera&ccedil;&atilde;o cancelada.';
        } else {
            $data['mensagem'] = 'Sucesso ao agendar Exame.';
        }
        $this->session->set_flashdata('message', $data['mensagem']);
        redirect(base_url() . "cadastros/pacientes", $data);
    }

    function novo($data) {
        $data['paciente'] = $this->paciente->listardados($data['paciente_id']);
        $data['convenio'] = $this->convenio->listardados();
        $this->loadView('ambulatorio/laudo-form', $data);
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

    
    function imagens($ambulatorio_laudo_id, $paciente_id= 0){ 

        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id); 
        if($this->session->userdata('paciente_id') != $paciente_id || $this->session->userdata('paciente_id') != $data['laudo'][0]->paciente_id){   
            $mensagem = "Ops, Você não tem acesso a essa pagina";
            echo "<html>
                   <meta charset='UTF-8'>
       <script type='text/javascript'> 
       alert('$mensagem');
       window.onunload = fechaEstaAtualizaAntiga;
       function fechaEstaAtualizaAntiga() {
           window.opener.location.reload();
           }
       window.close();
           </script>
           </html>"; 
           die(); 
        } 


        $this->load->helper('directory');
        
        $endereco_externo = $this->exame->listarenderecoexterno();
        $data['endereco_externo'] = $endereco_externo;
        $data['arquivo_pasta'] = directory_map("$endereco_externo/consulta/$ambulatorio_laudo_id/");
        
        
        if ($data['arquivo_pasta'] != false) {
            sort($data['arquivo_pasta']);
        }   
        
        $data['ambulatorio_laudo_id'] = $ambulatorio_laudo_id;
        $this->loadView('ambulatorio/imagemconsulta', $data);
        
       
    }
    
    function downloadarquivos($ambulatorio_laudo_id){
        $zip = new ZipArchive;
        $this->load->helper('directory'); 

        $data['laudo'] = $this->laudo->listarlaudo($ambulatorio_laudo_id); 
        if($this->session->userdata('paciente_id') != $data['laudo'][0]->paciente_id){   
                $mensagem = "Ops, Você não tem acesso a essa pagina";
                    echo "<html>
                           <meta charset='UTF-8'>
               <script type='text/javascript'> 
               alert('$mensagem');
               window.onunload = fechaEstaAtualizaAntiga;
               function fechaEstaAtualizaAntiga() {
                   window.opener.location.reload();
                   }
               window.close();
                   </script>
                   </html>"; 
                   die(); 
        }  
        
        $empresa_upload = $this->laudo->listarempresaenderecoupload();
        if ($empresa_upload != '') {
            $caminho_arquivos = "$empresa_upload/consulta/$ambulatorio_laudo_id/";
        } else {
            $caminho_arquivos = base_url()."upload/consulta/$ambulatorio_laudo_id/";
        } 
        
         $arquivo_pasta_pdf = directory_map($caminho_arquivos);
        if ($arquivo_pasta_pdf != false) {
            sort($arquivo_pasta_pdf);
        }
        if ($arquivo_pasta_pdf != true){
              $mensagem = "Nenhum arquivo encontrado.";
             echo "<html>
                    <meta charset='UTF-8'>
            <script type='text/javascript'> 
            alert('$mensagem');
            window.onunload = fechaEstaAtualizaAntiga;
            function fechaEstaAtualizaAntiga() {
                window.opener.location.reload();
                }
            window.close();
                </script>
                </html>";
             die();
        }
                
        $arquivo_pasta = directory_map("./upload/consulta/$ambulatorio_laudo_id");
        if ($arquivo_pasta != false) { 
            $deletar[] = "./upload/consulta/$ambulatorio_laudo_id/Arquivos.zip"; 
            foreach ($deletar as $arquivonome) {
                unlink($arquivonome);
            } 
            $zip->open("./upload/consulta/$ambulatorio_laudo_id/Arquivos.zip", ZipArchive::CREATE); //Criando arquivo Arquivos.zip
            foreach ($arquivo_pasta as $value) { 
                $zip->addFile("./upload/consulta/$ambulatorio_laudo_id/$value", $value); // fazendo com que todos os arquivos da pasta vire .zip
            }
            $zip->close();
        } 
        $empresa_upload_pasta = $this->laudo->listarempresaenderecouploadpasta(); 
        if ($empresa_upload_pasta != '') {
            $pasta_sistema = $empresa_upload_pasta;
        } else {
            $pasta_sistema = "clinica";
        } 
        $empresa_upload_pasta_paciente = $this->laudo->listarempresaenderecouploadpastapaciente(); 
        if ($empresa_upload_pasta_paciente != '') {
            $pasta_sistema_paciente = $empresa_upload_pasta_paciente;
        } else {
            $pasta_sistema_paciente = "paciente";
        } 
        
        redirect(str_replace($pasta_sistema_paciente, $pasta_sistema, base_url())."upload/consulta/$ambulatorio_laudo_id/Arquivos.zip");
        
        die(); 

    }

    function templateParaTexto($json_obj) {
        $array_obj = json_decode($json_obj);
        $string = '';
        // echo '<pre>';
        // var_dump($array_obj); die;
        foreach ($array_obj as $key => $value) {
            if ($value->tipo == 'checkbox') {
                if ($value->value != '') {
                    $string_value = 'Verdadeiro';
                } else {
                    $string_value = 'Falso';
                }
            } elseif ($value->tipo == 'multiplo') {
                $string_array = '';
                foreach ($value->value as $key => $item) {
                    $string_array .= " $item,";
                }
                $string_value = $string_array;
            } else {
                $string_value = $value->value;
            }
            $string .= "<p style='font-weight: bold'>{$value->nome}</p>
            <p>{$string_value}</p>";
        }
        // echo '<pre>';
        // var_dump($string); die;
        return $string;
    }


    
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
