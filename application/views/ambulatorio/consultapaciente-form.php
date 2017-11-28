<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <!--<div class="clear"></div>-->
    <form name="form_exametemp" id="form_exametemp" action="<?= base_url() ?>ambulatorio/exametemp/gravarpacienteconsultatemp/<?= $agenda_exames_id ?>" method="post">
        <fieldset>
            <legend>Marcar Consulta</legend>

            <div>
                <label>Nome</label>
                <input type="hidden" id="txtNomeid" class="texto_id" name="txtNomeid" readonly="true" />
                <input type="text" id="txtNome" name="txtNome" class="texto10" required/>
            </div>
            <? if ($this->session->userdata('login_paciente') == false) { ?>

                <div>
                    <label>CPF</label>

                    <input type="text" name="Txtcpf" id="Txtcpf" class="texto02 cpf" required/>
                </div>
            <? } ?>
            <div>
                <label>Dt de nascimento</label>

                <input type="text" name="nascimento" id="nascimento" class="texto02" alt="date" required/>
            </div>
            <div>
                <!--<input type="hidden" name="idade" id="txtIdade" class="texto01" alt="numeromask" required/>-->

            </div>
            <div>
                <label>Telefone</label>


                <input type="text" id="telefone" class="texto02 telefone" name="telefone"  />
            </div>
            <div>
                <label>Celular</label>


                <input type="text" id="txtCelular" class="texto02 celular" name="celular" required/>
            </div>
            <div>
                <label>Convenio *</label>
                <select name="convenio" id="convenio" class="size4">
                    <option  value="0">Selecione</option>
                    <? foreach ($convenio as $value) : ?>
                        <option value="<?= $value->convenio_id; ?>"><?php echo $value->nome; ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <style>
                #grupo_chosen a{
                    width: 180px;
                }

            </style>
            <div>
                <label>Procedimento</label>
<!--                <select  name="procedimento" id="procedimento" class="size1" required >
                    <option value="">Selecione</option>
                </select>-->
                <select name="procedimento" id="procedimento" class="size4 " data-placeholder="Selecione" required="">
                    <option value="">Selecione</option>
                </select>

            </div>
            <div>
                <label>Observacoes</label>


                <input type="text" id="observacoes" class="texto10" name="observacoes" />
            </div>


            <div>
                <label>&nbsp;</label>
                <button type="submit" name="btnEnviar">Enviar</button>
            </div>
    </form>
</fieldset>

<fieldset>
    <?
    ?>
    <table id="table_agente_toxico" border="0">
        <thead>

            <tr>
                <th class="tabela_header">Data</th>
                <th class="tabela_header">Hora</th>
                <th class="tabela_header">Exame</th>
                <th class="tabela_header">Observa&ccedil;&otilde;es</th>
            </tr>
        </thead>
        <?
        $estilo_linha = "tabela_content01";
        foreach ($consultas as $item) {
            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
            ?>
            <tbody>
                <tr>
                    <td class="<?php echo $estilo_linha; ?>"><?= substr($item->data, 8, 2) . '/' . substr($item->data, 5, 2) . '/' . substr($item->data, 0, 4); ?></td>
                    <td class="<?php echo $estilo_linha; ?>"><?= $item->inicio; ?></td>
                    <td class="<?php echo $estilo_linha; ?>"><?= $item->medico; ?></td>
                    <td class="<?php echo $estilo_linha; ?>"><?= $item->observacoes; ?></td>
                </tr>

            </tbody>
            <?
        }
        ?>
        <tfoot>
            <tr>
                <th class="tabela_footer" colspan="4">
                </th>
            </tr>
        </tfoot>
    </table> 

</fieldset>
</div> <!-- Final da DIV content -->
<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.maskedinput.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/chosen.css">
<!--<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/style.css">-->
<link rel="stylesheet" href="<?= base_url() ?>js/chosen/docsupport/prism.css">
<script type="text/javascript" src="<?= base_url() ?>js/chosen/chosen.jquery.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/prism.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>js/chosen/docsupport/init.js"></script>
<!--<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>-->
<script type="text/javascript">
//$('#nascimento').mask('99/99/9999');
    $('.cpf').mask('999.999.999-99');
    $('.celular').mask('(99)99999-9999');
    $('.telefone').mask('(99)9999-9999');
    $(function () {
        $("#data_ficha").datepicker({
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
        $('#exame').change(function () {
            if ($(this).val()) {
                $('#horarios').hide();
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/horariosambulatorio', {exame: $(this).val(), teste: $("#data_ficha").val()}, function (j) {
                    var options = '<option value=""></option>';
                    for (var i = 0; i < j.length; i++) {
                        options += '<option value="' + j[i].agenda_exames_id + '">' + j[i].inicio + '-' + j[i].nome + '-' + j[i].medico_agenda + '</option>';
                    }
                    $('#horarios').html(options).show();
                    $('.carregando').hide();
                });
            } else {
                $('#horarios').html('<option value="">-- Escolha um exame --</option>');
            }
        });
    });

    $(function () {
        $("#txtNome").autocomplete({
            source: "<?= base_url() ?>autocomplete/paciente",
            minLength: 3,
            focus: function (event, ui) {
                $("#txtNome").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#Txtcpf").val(ui.item.cpf);
                $("#txtNome").val(ui.item.nome);
                $("#txtNomeid").val(ui.item.id);
                $("#celular").val(ui.item.celular);
                $("#telefone").val(ui.item.itens);
                $("#nascimento").val(ui.item.valor);
                return false;
            }
        });
    });

    $(function () {
        $("#Txtcpf").autocomplete({
            source: "<?= base_url() ?>autocomplete/pacientecpf",
            minLength: 3,
            focus: function (event, ui) {
                $("#cpf").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#Txtcpf").val(ui.item.value);
                $("#txtNome").val(ui.item.nome);
                $("#txtNomeid").val(ui.item.id);
                $("#celular").val(ui.item.celular);
                $("#telefone").val(ui.item.itens);
                $("#nascimento").val(ui.item.valor);
                return false;
            }
        });
    });


    $(function () {
        $('#convenio').change(function () {
            if ($(this).val()) {
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/procedimentoconvenioconsulta', {convenio1: $(this).val(), ajax: true}, function (j) {
                    options = '<option value=""></option>';
                    for (var c = 0; c < j.length; c++) {
                        options += '<option value="' + j[c].procedimento_convenio_id + '">' + j[c].procedimento + '</option>';
                    }
//                                    $('#procedimento').html(options).show();
                    $('#procedimento option').remove();
                    $('#procedimento').append(options);
                    $("#procedimento").trigger("chosen:updated");
                    $('.carregando').hide();
                });
            } else {
                $('#procedimento option').remove();
                $('#procedimento').append('');
                $("#procedimento").trigger("chosen:updated");
            }
        });
    });



    //$(function(){     
    //    $('#exame').change(function(){
    //        exame = $(this).val();
    //        if ( exame === '')
    //            return false;
    //        $.getJSON( <?= base_url() ?>autocomplete/horariosambulatorio, exame, function (data){
    //            var option = new Array();
    //            $.each(data, function(i, obj){
    //                console.log(obl);
    //                option[i] = document.createElement('option');
    //                $( option[i] ).attr( {value : obj.id} );
    //                $( option[i] ).append( obj.nome );
    //                $("select[name='horarios']").append( option[i] );
    //            });
    //        });
    //    });
    //});


    $(function () {
        $("#txtNome").autocomplete({
            source: "<?= base_url() ?>index.php?c=autocomplete&m=paciente",
            minLength: 5,
            focus: function (event, ui) {
                $("#txtNome").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#txtNome").val(ui.item.value);
                $("#txtNomeid").val(ui.item.id);
                $("#txtTelefone").val(ui.item.itens);
                $("#txtCelular").val(ui.item.celular);
                $("#nascimento").val(ui.item.valor);
                return false;
            }
        });
    });


    $(function () {
        $("#accordion").accordion();
    });


    $(document).ready(function () {
        jQuery('#form_exametemp').validate({
            rules: {
                txtNome: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "!"
                }
            }
        });
    });

</script>