<div >

    <div >
        <h3 class="singular"><a href="<?= base_url() ?>ambulatorio/laudo">Voltar</a></h3>
        <div >
            <fieldset>
                <legend>Dados</legend>
                <table> 
                    <tr><td width="400px;">Paciente:<?= @$obj->_nome ?></td>
                        <td width="400px;">Exame: <?= @$obj->_procedimento ?></td>
                        <td>Solicitante: <?= @$obj->_solicitante ?></td>
                    </tr>
                    <tr><td>Idade:<?= @$obj->_idade ?></td>
                        <td>Nascimento:<?= substr(@$obj->_nascimento,8, 2) . "/" .  substr(@$obj->_nascimento,5, 2) . "/" . substr(@$obj->_nascimento,0, 4);?></td>
                        <td>Sala:<?= @$obj->_sala ?></td>
                    </tr>
                </table>
            </fieldset>
                    <?
                    $i = 0;
                    if ($arquivo_pasta != false):
                        foreach ($arquivo_pasta as $value) {
                        $i++;
                        }
                                            endif
                    ?>
            <fieldset>
                <legend>Imagens : <font size="2"><b> <?= $i ?></b></legend>
                <ul id="sortable">
                    <?
                    if ($arquivo_pasta != false):
                        foreach ($arquivo_pasta as $value) {
                            ?>
                            <li class="ui-state-default"> <input type="hidden"  value="<?= $value ?>" name="teste[]" class="size2" /><img  width="100px" height="100px" src="<?= base_url() . "upload/" . $exame_id . "/" . $value ?>"></li>
                            <?
                        }
                    endif
                    ?>
                </ul>
            </fieldset>
            <table>
                <tr><td width="60px;"><center>
                    <div class="bt_link_new">
                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/guia/galeria/" . $exame_id ?> ','_blank','toolbar=no,Location=no,menubar=no,width=900,height=650');">
                            vizualizar imagem
                        </a>
                    </div>
                    </td>
                    <td width="60px;"><center>
                        <div class="bt_link_new">
                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/exame/anexarimagemmedico/" . $exame_id . "/" . @$obj->_sala; ?> ','_blank','toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                adicionar/excluir
                            </a>
                        </div></center>
                    </td>

                        <td width="60px;"><center>
                    <div class="bt_link_new">
                        <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelolaudo"; ?> ','_blank','toolbar=no,Location=no,menubar=no,width=900,height=650 ');">
                            laudo Modelo
                        </a>
                    </div>
                    </td>
                    <td width="60px;"><center>
                        <div class="bt_link_new">
                            <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/modelolinha"; ?> ','_blank','toolbar=no,Location=no,menubar=no,width=900,height=650');">
                                Linha Modelo
                            </a>
                        </div></center>
                    </td>
            </table>

        </div>
        <div>
            <form name="form_guia" id="form_guia" action="<?= base_url() ?>ambulatorio/laudo/gravarrevisao/<?php echo $ambulatorio_laudo_id; ?>" method="post">
                <fieldset>
                    <legend>Laudo</legend>
                    <div>
                        <label>Textos</label>
                        <select name="exame" id="exame" class="size2" >
                            <option value='' >selecione</option>
                            <?php foreach ($lista as $item) { ?>
                                <option value="<?php echo $item->nome; ?>" ><?php echo $item->nome; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label>Laudos anteriores</label>
                        <select name="anteriores" id="anteriores" class="size2" >
                            <option value='' >selecione</option>
                            <?php foreach ($laudos_anteriores as $itens) { ?>
                                <option value="<?php echo $itens->ambulatorio_laudo_id; ?>" ><?php echo $itens->nome . " " . substr($itens->data_cadastro, 8, 2) . "-" . substr($itens->data_cadastro, 5, 2) . "-" . substr($itens->data_cadastro, 0, 4); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <textarea id="laudo" name="laudo" class="jqte-test" ><?= @$obj->_texto; ?></textarea>
                    </div>
                    <div>
                        <label>situa&ccedil;&atilde;o</label>
                        <select name="situacao" id="situacao" class="size2" >
                            <option value='DIGITANDO' >DIGITANDO</option>
                            <option value='FINALIZADO' >FINALIZADO</option>
                        </select>
                        <label>Revisor</label>
                        <select name="medicorevisor" id="medicorevisor" class="size4">
                            <? foreach ($operadores as $valor) : ?>
                                <option value="<?= $valor->operador_id; ?>"<? if (@$obj->_medico_parecer2 == $valor->operador_id):echo 'selected';
                    endif;
                        ?>><?= $valor->nome; ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <div>


                        <!--<input name="textarea" id="textarea"></input>
                   <!-- <input name="textarea" id="textarea" ></input>-->

                        <hr/>

                        <button type="submit" name="btnEnviar">Enviar</button>
                        <button type="reset" name="btnLimpar">Limpar</button>
                    </div>
                </fieldset>
            </form>

        </div> 
    </div> 
</div> 
</div> <!-- Final da DIV content -->
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 1300px; }
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="<?= base_url() ?>css/estilo.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/form.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>css/jquery-treeview.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-1.9.1.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-ui-1.10.4.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery-te-1.4.0.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">

    //tinymce.init({
    //    selector: "textarea"
    //
    // }); 

    $(document).ready(function(){ 
        $('#sortable').sortable();
    });

    $(function(){
        $('#exame').change(function(){
            if( $(this).val() ) {
                //$('#laudo').hide();
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/modeloslaudo',{exame:$(this).val(), ajax:true}, function(j){
                    options =  "";
                    
                    options += j[0].texto;
                    document.getElementById("laudo").value = $('#laudo').val() + options
                    //$('#laudo').val(options);
                    //$('#laudo').html(options).show();
                    $('.carregando').hide();
                    history.go(0) 
                });
            } else {
                $('#laudo').html('value=""');
            }
        });
    });

    $(function(a){
        $('#anteriores').change(function(){
            if( $(this).val() ) {
                //$('#laudo').hide();
                $('.carregando').show();
                $.getJSON('<?= base_url() ?>autocomplete/laudosanteriores',{anteriores:$(this).val(), ajax:true}, function(i){
                    option =  "";
                    
                    option = i[0].texto;
                    document.getElementById("laudo").value = option
                    //$('#laudo').val(options);
                    //$('#laudo').html(options).show();
                    $('.carregando').hide();
                    history.go(0) 
                });
            } else {
                $('#laudo').html('value="texto"');
            }
        });
    });
    //bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
    $('.jqte-test').jqte();
    
    


    
    
    

</script>