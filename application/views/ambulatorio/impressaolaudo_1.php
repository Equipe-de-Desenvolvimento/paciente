<!-- <link href="<?= base_url() ?>css/style_p.css" rel="stylesheet" type="text/css" /> -->

<BODY> 
    <p><b><?= $laudo['0']->cabecalho; ?></b></p>
    <p><?= $laudo['0']->texto; ?></p>
        <?
            if ($laudo['0']->situacao == "FINALIZADO" && $laudo['0']->medico_parecer2 == "" || $laudo['0']->medico_parecer1 == 38 ) {
        ?>
    <br>
    <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img width="130px" height="80px" src="<?= "upload/1ASSINATURAS/" . $laudo['0']->medico_parecer1 . ".jpg" ?>"></center>
                <?
            }
            if ($laudo['0']->situacao == "FINALIZADO" && $laudo['0']->medico_parecer2 != "") {
                ?>
    <br>
    <br>
                <img  width="130px" height="80px" src="<?=  "upload/1ASSINATURAS/" . $laudo['0']->medico_parecer1 . ".jpg" ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img  width="130px" height="80px" src="<?=  "upload/1ASSINATURAS/" . $laudo['0']->medico_parecer2 . ".jpg" ?>">
            <? }
            ?>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <?
        if ($laudo['0']->rodape == "t") {
            ?>
            <FONT size="-1"> REALIZAMOS EXAMES DE RESSON&Acirc;NCIA MAGN&Eacute;TICA DE ALTO CAMPO (1,5T)
            <?
        } 
        ?>
</BODY>
</HTML>