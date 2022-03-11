
<div class="content"> <!-- Inicio da DIV content -->

    <div id="accordion">
        <h3 class="singular"><a href="#">Agendamento</a></h3>
        <div>
            <?
            $paciente_id = $this->session->userdata('operador_id');
            $especialidades = $this->operador_m->listarespecialidade();
            ?>
            <table>
                <thead>
                <form method="get" action="<?= base_url() ?>ambulatorio/guia/listarmultifuncao">

                    <tr>
                        <th class="tabela_title">Especialidade</th> 
                        <th class="tabela_title">Medico</th>                                               
                        <th class="tabela_title">Data</th>
                        <th class="tabela_title">CPF</th>
                    </tr>
                    <tr>
                        <th class="tabela_title">
                            <select name="especialidade" id="especialidade" class="size1">
                                <option value=""></option>
                                <? foreach ($especialidades as $value) : ?>
                                    <option value="<?= $value->cbo_ocupacao_id; ?>"
                                    <?
                                    if (@$_GET['especialidade'] == $value->cbo_ocupacao_id):echo 'selected';
                                    endif;
                                    ?>
                                            ><?php echo $value->descricao; ?></option> 
                                            <?
                                            if (@$_GET['especialidade'] == $value->cbo_ocupacao_id):
                                                echo '<script>carregarmedicoespecialidade();</script>';
                                            endif;
                                            ?>
                                        <? endforeach; ?>
                            </select>
                        </th>
                        <th class="tabela_title">
                            <select name="medico" id="medico" class="size1">                                                                                                                                            
                            </select>
                        </th>


                        <th class="tabela_title">
                            <input type="text"  id="data" alt="date" name="data" class="size1" value="<?php echo @$_GET['data']; ?>"/>
                        </th>
                        <th class="tabela_title">
                            <input type="text"  id="data" alt="cpf" name="cpf" class="size2" value="<?php echo @$_GET['cpf']; ?>"/>
                        </th>
                        <th colspan="3" class="tabela_title">
                            <button type="submit" id="enviar">Pesquisar</button>
                        </th>

                    </tr>
                    <tr>
                        <th colspan="4" class="tabela_title">
                            Obs: Você pode pesquisar pelos seus agendamentos utilizando o CPF
                        </th>
                    </tr>
                </form>
                </thead>
            </table>
            <table>
                <thead>
                    <tr>
                        <th class="tabela_header" >Status</th>
                        <th class="tabela_header" width="250px;">Nome</th>
                        <th class="tabela_header" width="70px;">Data</th>
                        <th class="tabela_header" width="50px;">Dia</th>
                        <th class="tabela_header" width="70px;">Agenda</th>
                        <th class="tabela_header" width="150px;">Sala</th>
                        <th class="tabela_header" width="150px;">Convenio</th>
                        <th class="tabela_header">Telefone</th>
                        <th class="tabela_header" width="250px;">Observa&ccedil;&otilde;es</th>
                        <th class="tabela_header" colspan="3"><center>A&ccedil;&otilde;es</center></th>
                    </tr>
                </thead>
                <?php
                $url = $this->utilitario->build_query_params(current_url(), $_GET);
                $consulta = $this->exame->listarexamemultifuncao($_GET);
                $total = $consulta->count_all_results();
                $limit = 20;
                isset($_GET['per_page']) ? $pagina = $_GET['per_page'] : $pagina = 0;
//                var_dump($total); die;
                $l = $this->exame->listarestatisticapaciente($_GET);
                $p = $this->exame->listarestatisticasempaciente($_GET);

                if ($total > 0) {
                    ?>
                    <tbody>
                        <?php
//                        var_dump($item->situacaoexame);
//                        die;
                        $lista = $this->exame->listarexamemultifuncao2($_GET)->limit($limit, $pagina)->get()->result();
                        $estilo_linha = "tabela_content01";
                        foreach ($lista as $item) {
                            $dataFuturo = date("Y-m-d H:i:s");
                            $dataAtual = $item->data_atualizacao;

                            if ($item->celular != "") {
                                $telefone = $item->celular;
                            } elseif ($item->telefone != "") {
                                $telefone = $item->telefone;
                            } else {
                                $telefone = "";
                            }

                            $date_time = new DateTime($dataAtual);
                            $diff = $date_time->diff(new DateTime($dataFuturo));
                            $teste = $diff->format('%H:%I:%S');
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";

                            if ($item->paciente == "" && $item->bloqueado == 't') {
                                $situacao = "Bloqueado";
                                $paciente = "Bloqueado";
                                $verifica = 5;
                            } else {
                                $paciente = "";

                                if ($item->realizada == 't' && $item->situacaoexame == 'EXECUTANDO') {
                                    $situacao = "Atendendo";
                                    $verifica = 2;
                                } elseif ($item->realizada == 't' && $item->situacaoexame == 'FINALIZADO') {
                                    $situacao = "Finalizado";
                                    $verifica = 4;
                                } elseif ($item->confirmado == 'f' && $item->operador_atualizacao == null) {
                                    $situacao = "agenda";
                                    $verifica = 1;
                                } elseif ($item->confirmado == 'f' && $item->operador_atualizacao != null) {
                                    $situacao = "agendado";
                                    $verifica = 6;
                                } else {
                                    $situacao = "espera";
                                    $verifica = 3;
                                }
                            }
                            if ($item->paciente == "" && $item->bloqueado == 'f') {
                                $paciente = "vago";
                            }
                            $data = $item->data;
                            $dia = strftime("%A", strtotime($data));

                            switch ($dia) {
                                case"Sunday": $dia = "Domingo";
                                    break;
                                case"Monday": $dia = "Segunda";
                                    break;
                                case"Tuesday": $dia = "Terça";
                                    break;
                                case"Wednesday": $dia = "Quarta";
                                    break;
                                case"Thursday": $dia = "Quinta";
                                    break;
                                case"Friday": $dia = "Sexta";
                                    break;
                                case"Saturday": $dia = "Sabado";
                                    break;
                            }
                            ?>
                            <tr>
                                <? if ($verifica == 1) { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><b><?= $situacao; ?></b></td>
                                    <td class="<?php echo $estilo_linha; ?>"><b><?= $item->paciente; ?></b></td>
                                <? }if ($verifica == 2) { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="green"><b><?= $situacao; ?></b></td>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="green"><b><?= $item->paciente; ?></b></td>
                                <? }if ($verifica == 3) { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="red"><b><?= $situacao; ?></b></td>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="red"><b><?= $item->paciente; ?></b></td>
                                <? }if ($verifica == 4) { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="blue"><b><?= $situacao; ?></b></td>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="blue"><b><?= $item->paciente; ?></b></td>
                                <? } if ($verifica == 5) { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="gray"><b><?= $situacao; ?></b></td>
                                    <td class="<?php echo $estilo_linha; ?>"><font color="gray"><b><?= $item->paciente; ?></b></td>
                                <? } if ($verifica == 6) { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><b><a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/exame/agendadoauditoria/<?= $item->agenda_exames_id; ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=500,height=400');"><?= $situacao; ?></b></td>
                                    <td class="<?php echo $estilo_linha; ?>"><b><?= $item->paciente; ?></b></td>
                                <? } ?>
                                <td class="<?php echo $estilo_linha; ?>"><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= substr($dia, 0, 3); ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->inicio; ?></td>
                                <td class="<?php echo $estilo_linha; ?>" width="150px;"><?= $item->sala . " - " . substr($item->medicoagenda, 0, 15); ?></td>
                                <? if ($item->convenio != "") { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><?= $item->convenio . " - " . $item->procedimento . " - " . $item->codigo; ?></td>
                                <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>"><?=
                                        $item->convenio_paciente . " - " . $item->procedimento . " - " . $item->codigo;
                                        ;
                                        ?></td>
                                <? } ?>
                                <td class="<?php echo $estilo_linha; ?>"><?= $telefone; ?></td>
                                <td class="<?php echo $estilo_linha; ?>"><?= $item->observacoes; ?></td>
                             <?php  
                                if ($item->ambulatorio_laudo_id != "") {  
                                        ?>
                                <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                            <a target="_blank" href="<?= base_url() ?>ambulatorio/laudo/imagens/<?= $item->ambulatorio_laudo_id ?>/<?= $this->session->userdata('paciente_id'); ?>">Arquivos
                                            </a></div>
                                </td> 
                              <?php  }else{
                                  ?>
                                  <td class="<?php echo $estilo_linha; ?>" width="60px;"> </td>
                                <?
                                }
                                 ?>
                                <?
                                if ($item->paciente_id == "") {
                                    ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;"><div class="bt_link">
                                            <a target="_blank" href="<?= base_url() ?>ambulatorio/exametemp/carregarconsultatemp/<?= $item->agenda_exames_id ?>">Agendar
                                            </a></div>
                                    </td>
                                <? } else { ?>
                                    <td class="<?php echo $estilo_linha; ?>" width="60px;"> </td>
                                <? }
                                ?>
                               
                            </tr>

                        </tbody>
                        <?php
                    }
                }
                ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="13">
                            <?php $this->utilitario->paginacao($url, $total, $pagina, $limit); ?>
                            Total de registros: <?php echo $total . " - Vago: " . $l . " - Marcado: " . $p; ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div> <!-- Final da DIV content -->
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript">

                                    $(function () {
                                        $("#data").datepicker({
                                            autosize: true,
                                            changeYear: true,
                                            changeMonth: true,
                                            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                                            buttonImage: '<?= base_url() ?>img/form/date.png',
                                            dateFormat: 'dd/mm/yy'
                                        });
                                    });

                                    $(function () {
                                        $("#accordion").accordion();
                                    });

                                    if ($('#especialidade').val() != '') {

                                        $.getJSON('<?= base_url() ?>autocomplete/medicoporespecialidade', {especialidade: $('#especialidade').val(), ajax: true}, function (j) {
                                            options = '<option value=""></option>';
                                            for (var c = 0; c < j.length; c++) {
                                                options += '<option value="' + j[c].operador_id + '">' + j[c].medico + '</option>';
                                            }
                                            $('#medico').html(options).show();
//                                            $('.carregando').hide();
                                        });
                                    }

                                    $(function () {
                                        $('#especialidade').change(function () {
                                            if ($(this).val()) {
                                                $('.carregando').show();
                                                $.getJSON('<?= base_url() ?>autocomplete/medicoporespecialidade', {especialidade: $(this).val(), ajax: true}, function (j) {
                                                    options = '<option value=""></option>';
                                                    for (var c = 0; c < j.length; c++) {
                                                        options += '<option value="' + j[c].operador_id + '">' + j[c].medico + '</option>';
                                                    }
                                                    $('#medico').html(options).show();
                                                    $('.carregando').hide();
                                                });
                                            } else {
                                                $('#medico').html('<option value="">Selecione</option>');
                                            }
                                        });
                                    });

</script>
