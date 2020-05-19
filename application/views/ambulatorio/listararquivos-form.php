<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">

        <h3><a href="#">Carregar imagem individual </a></h3>
        <div >
            <?= form_open_multipart(base_url() . 'ambulatorio/laudo/importararquivospaciente'); ?>
            <label>Informe o arquivo para importa&ccedil;&atilde;o</label><br>
            <input type="file" multiple="" name="arquivos[]"/>
            <br><br><label>Observação</label><br>
            <textarea name="observacao_arquivo"><?=$observacao[0]->observacao_paciente?></textarea><br><br>
            <button type="submit" name="btnEnviar">Enviar</button>
            <input type="hidden" name="paciente_id" value="<?= $ambulatorio_laudo_id; ?>" />
            <?= form_close(); ?>

        </div>

        <h3><a href="#">Arquivos</a></h3>
        <div >
            <table>
                <tr>
                    <?
                    $i = 0;
                    if ($arquivo_pasta_pdf != false):
                        foreach ($arquivo_pasta_pdf as $value) :
                            $i++;
                            ?>

                            <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= str_replace("paciente", $pasta_sistema, base_url()) . "upload/consulta/" . $ambulatorio_laudo_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" 
                                src="<?= str_replace("paciente", $pasta_sistema, base_url()) . "upload/consulta/" . $ambulatorio_laudo_id . "/" . $value ?>">
                                <br><? echo substr($value, 0, 10) ?><br>
                            </td>
                            <?
                            if ($i == 8) {
                                ?>
                            </tr><tr>
                                <?
                            }
                        endforeach;
                    endif
                    ?>
            </table>
        </div>
        <!-- Final da DIV content -->
    </div> <!-- Final da DIV content -->
</div> <!-- Final da DIV content -->
<script type="text/javascript">

    $(function () {
        $("#accordion").accordion();
    });



</script>
