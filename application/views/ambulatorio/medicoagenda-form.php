<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
        <h3 class="singular"><a href="#">Cadastro Medico agenda</a></h3>
        <div>
            <form name="form_medicoagenda" id="form_medicoagenda" action="<?= base_url() ?>ambulatorio/agenda/gravarmedico" method="post">

                <dl class="dl_desconto_lista">
                    <dt>
                    <label>Medico</label>
                    </dt>
                    <dd>
                        <select name="medico" id="medico" class="size2">
                            <option value=""></option>
                            <? foreach ($medicos as $value) : ?>
                                <option value="<?= $value->operador_id; ?>"><?php echo $value->nome; ?></option>
                            <? endforeach; ?>
                        </select>
                    </dd>
                    <dt>
                    <label>Salas</label>
                    </dt>
                    <dd>
                            <select name="sala" id="sala" class="size2">
                                <option value=""></option>
                                <? foreach ($salas as $value) : ?>
                                                                        <option value="<?= $value->exame_sala_id; ?>"><?php echo $value->nome; ?></option>
                                <? endforeach; ?>
                            </select>
                    </dd>
                    <dt>
                    <label>Data inicio</label>
                    </dt>
                    <dd>
                        <input type="text"  id="datainicio" name="datainicio" class="size1"/>
                    </dd>
                    <dt>
                    <label>Data fim</label>
                    </dt>
                    <dd>
                        <input type="text"  id="datafim" name="datafim" class="size1"/>
                    </dd>
                    <dt>
                    <label>Hora inicio</label>
                    </dt>
                    <dd>
                        <input type="text" alt="time" id="horainicio" name="horainicio" class="size1"/>
                    </dd>
                    <dt>
                    <label>Hora fim</label>
                    </dt>
                    <dd>
                        <input type="text" alt="time" id="horafim" name="horafim" class="size1"/>
                    </dd>


                </dl>    

                <hr/>
                <button type="submit" name="btnEnviar">Enviar</button>
                <button type="reset" name="btnLimpar">Limpar</button>
                <button type="button" id="btnVoltar" name="btnVoltar">Voltar</button>
            </form>
        </div>
    </div>
</div> <!-- Final da DIV content -->

<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">

    $(function() {
        $( "#datainicio" ).datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });
    $(function() {
        $( "#datafim" ).datepicker({
            autosize: true,
            changeYear: true,
            changeMonth: true,
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            buttonImage: '<?= base_url() ?>img/form/date.png',
            dateFormat: 'dd/mm/yy'
        });
    });

    $(function() {
        $( "#accordion" ).accordion();
    });

    $(document).ready(function(){
        jQuery('#form_medicoagenda').validate( {
            rules: {
                medico: {
                    required: true
                },
                sala: {
                    required: true
                },
                datainicio: {
                    required: true
                },
                datafim: {
                    required: true
                },
                horainicio: {
                    required: true
                },
                horafim: {
                    required: true
                }
            },
            messages: {
                medico: {
                    required: "*"
                },
                sala: {
                    required: "*"
                },
                datainicio: {
                    required: "*"
                },
                datafim: {
                    required: "*"
                },
                horainicio: {
                    required: "*"
                },
                horafim: {
                    required: "*"
                }
            }
        });
    });

</script>