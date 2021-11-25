<?
//Da erro no home

if ($this->session->userdata('autenticado') != true) {
    redirect(base_url() . "login/index/login004", "refresh");
}
   
$this->db->select('ep.*, e.*');
$this->db->from('tb_empresa e'); 
$this->db->join('tb_empresa_permissoes ep', 'ep.empresa_id = e.empresa_id', 'left');
$this->db->orderby('e.empresa_id');
$return = $this->db->get();
$data['empresa'] = $return->result();
        
function alerta($valor) {
    echo "<script>alert('$valor');</script>";
}

function debug($object) {
    echo "<pre>";
    var_dump($object);
    echo "</pre>";
}
?>
<!DOCTYPE html PUBLIC "-//carreW3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="pt-BR" >
    <head>
        <title>STG - SISTEMA DE GESTAO DE CLINICAS v1.0</title>
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <!-- Reset de CSS para garantir o funcionamento do layout em todos os brownsers -->
        <link href="<?= base_url() ?>css/reset.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/reset.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/batepapo.css" rel="stylesheet" type="text/css" />

        <link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
        <!--<link href="<?= base_url() ?>js/fullcalendar/lib/cupertino/jquery-ui.min.css" rel="stylesheet" />-->
        <link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
        <!--<script type="text/javascript" src="<?= base_url() ?>js/fullcalendar/lib/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>js/fullcalendar/lib/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.8.5.custom.min.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-cookie.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-treeview.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery-meiomask.js" ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/jquery.bestupper.min.js"  ></script>
        <script type="text/javascript" src="<?= base_url() ?>js/scripts_alerta.js" ></script>
        <script type="text/javascript">
            (function ($) {
                $(function () {
                    $('input:text').setMask();
                });
            })(jQuery);

        </script>
        <style>
            #imgLogoClinica{
                float:right;
            }
        </style>
    </head>
    <script type="text/javascript" src="<?= base_url() ?>js/funcoes.js"></script>

    <?php
    $this->load->library('utilitario');
    Utilitario::pmf_mensagem($this->session->flashdata('message'));
    ?>


    <div class="container">
        <div class="header">
            <div id="imglogo">
                <img src="<?= base_url(); ?>img/stg - logo.jpg" alt="Logo"
                     title="Logo" height="70" id="Insert_logo"
                     style="display:block;" />
            </div>
            <div id="login">

                <div id="login_controles">
                    <!--
                    <a href="#" alt="Alterar senha" id="login_pass">Alterar Senha</a>
                    -->
                    <a id="login_sair" title="Sair do Sistema" onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                       href="<?= base_url() ?>login/sair">Sair</a>
                </div>
                <!--<div id="user_foto">Imagem</div>-->

            </div>
            <?php if($data['empresa'][0]->mostrar_logo_clinica == "t"){?>
             <div id="imgLogoClinica" style="">
                    <img src="<?= base_url(); ?>upload/logomarca/<?= $data['empresa'][0]->empresa_id; ?>/logomarca.jpg" alt="Logo Clinica"
                         title="Logo Clinica" height="70" id="Insert_logo" />
             </div>
            <?php }?> 
            
        </div>
        <div class="decoration_header">&nbsp;</div>
        <!-- Fim do Cabeçalho -->
        <div class="barraMenus" style="float: left;">
            <? if ($this->session->userdata('login_paciente') == false) { ?>
                <ul id="menu" class="filetree">

                    <? $paciente_id = $this->session->userdata('operador_id'); ?>

                     <?php if($data['empresa'][0]->desativar_agendamento_paciente != "t"){?>
                     <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/agendamento">Agendamento</a></span></li>
                    <?php }?>
                    <!--<li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/pesquisar/<?= $paciente_id ?>">Exames</a></span></li>-->
                    <li><span class="file"><a onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                                              href="<?= base_url() ?>login/sair">Sair</a></span>
                    </li>
                </ul>   
            <? } else { ?>
                <ul id="menu" class="filetree">

                    <? $paciente_id = $this->session->userdata('operador_id'); ?>
                    <?php if($data['empresa'][0]->desativar_agendamento_paciente != "t"){?>
                        <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/agendamento/<?= $paciente_id ?>">Agendamento</a></span></li>
                    <?php }?>
                     <?php if($data['empresa'][0]->desativar_exame_paciente != "t"){?>
                    <li><span class="file"><a href="<?= base_url() ?>ambulatorio/guia/pesquisar/<?= $paciente_id ?>">Exames</a></span></li>
                     <?php }?>
                    <li><span class="file"><a onclick="javascript: return confirm('Deseja realmente sair da aplicação?');"
                                              href="<?= base_url() ?>login/sair">Sair</a></span>
                    </li>
                </ul>   
            <? }
            ?>

            <!-- Fim da Barra Lateral -->
        </div>
        <div class="mensagem"><?
            if (isset($mensagem)): echo $mensagem;
            endif;
            ?></div>
        <script type="text/javascript">
            $("#menu").treeview({
                animated: "normal",
                persist: "cookie",
                collapsed: true,
                unique: true
            });
        </script>