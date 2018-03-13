<div class="content"> <!-- Inicio da DIV content -->
    <div id="accordion">
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

                            <td width="10px"><img  width="50px" height="50px" onclick="javascript:window.open('<?= str_replace("paciente", "clinicas", base_url()) . "upload/consulta/" . $ambulatorio_laudo_id . "/" . $value ?>', '_blank', 'toolbar=no,Location=no,menubar=no,width=1200,height=600');" 
                                src="<?= str_replace("paciente", "clinicas", base_url()) . "upload/consulta/" . $ambulatorio_laudo_id . "/" . $value ?>">
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
