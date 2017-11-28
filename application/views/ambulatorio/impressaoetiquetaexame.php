
<div class="content ficha_ceatox">

    <table>
        <tbody>
            <tr>
                <td colspan="2" ><font size = -2><center><b><?= utf8_decode($empresa[0]->nome) ?> - <?= str_replace("-", "/", $emissao); ?></b></center></td>
        </tr>
        <tr>
            <td  ><font size = -2><b><?= $paciente['0']->nome; ?></b></td>
        </tr>
        <tr>
            <td ><font size = -2><b>Nasc. <?= substr($paciente['0']->nascimento, 8, 2) . "/" . substr($paciente['0']->nascimento, 5, 2) . "/" . substr($paciente['0']->nascimento, 0, 4); ?></b></td>
        </tr>
        </tbody>
    </table>
    <?
    $barra = '';
    $i = 0;
    $b = 0;
    foreach ($exames as $item) :
        $i++;
        $b++;
        if ($item->grupo == $exame[0]->grupo) {
            if ($b == 2) {
                $barra = '/';
            }
            ?>
    <font size = -2><b><?= $barra . utf8_decode($item->procedimento) ?></b>
            <?
            if ($i == 2) {
                $i = 0;
                ?><br>
                <?
            }
        }
    endforeach;
    ?>
</div>
