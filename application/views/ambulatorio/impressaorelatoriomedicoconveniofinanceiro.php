<div class="content"> <!-- Inicio da DIV content -->
    <? if (count($empresa) > 0) { ?>
        <h4><?= $empresa[0]->razao_social; ?></h4>
    <? } else { ?>
        <h4>TODAS AS CLINICAS</h4>
    <? } ?>
    <h4>Medico Convenios</h4>
    <h4>PERIODO: <?= $txtdata_inicio; ?> ate <?= $txtdata_fim; ?></h4>
    <h4>Medico: <?= $medico[0]->operador; ?></h4>
    <hr>
    <? if ($contador > 0) {
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th class="tabela_header"><font size="-1">Convenio</th>
                    <th class="tabela_header"><font size="-1">Nome</th>
                    <th class="tabela_header"><font size="-1">Medico</th>
                    <th class="tabela_header"><font size="-1">Data</th>
                    <th class="tabela_header"><font size="-1">Qtde</th>
                    <th class="tabela_header" width="220px;"><font size="-1">Procedimento</th>
                    <th class="tabela_header" ><font size="-1">Valor</th>
                    <th class="tabela_header" width="80px;"><font size="-1">Perc. Medico</th>
                    <th class="tabela_header" width="80px;"><font size="-1">Indice</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                $valor = 0;
                $valortotal = 0;
                $convenio = "";
                $y = 0;
                $qtde = 0;
                $qtdetotal = 0;
                $perc = 0;
                $totalperc = 0;
                $totalgeral = 0;
                $totalconsulta = 0;
                $totalretorno = 0;
                foreach ($relatorio as $item) :
                    $i++;
                    $procedimentopercentual = $item->procedimento_tuss_id;
                    $medicopercentual = $item->medico_parecer1;
                    $percentual = $this->guia->percentualmedico($procedimentopercentual, $medicopercentual);
                    $testearray = count($percentual);
                    if ($item->classificacao == 1) {
                        $totalconsulta++;
                    }
                    if ($item->classificacao == 2) {
                        $totalretorno++;
                    }
                    ?>
                    <tr>
                        <td><font size="-2"><?= $item->convenio; ?></td>
                        <td><font size="-2"><?= $item->paciente; ?></td>
                        <td><font size="-2"><?= $item->medico; ?></td>
                        <td><font size="-2"><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                        <td ><font size="-2"><?= $item->quantidade; ?></td>
                        <td><font size="-2"><?= utf8_decode($item->procedimento); ?></td>
                        <td style='text-align: right;'><font size="-2"><?= number_format($item->valor_total, 2, ",", "."); ?></td>
                        <?
                        if ($testearray > 0) {
                            $valorpercentualmedico = $percentual[0]->valor;
                        } else {
                            $valorpercentualmedico = $item->perc_medico;
                        }
                        $perc = $item->valor_total * ($valorpercentualmedico / 100);
                        $totalperc = $totalperc + $perc;
                        $totalgeral = $totalgeral + $item->valor_total;
                        ?>
                        <td style='text-align: right;'><font size="-2"><?= number_format($perc, 2, ",", "."); ?></td>
                        <td style='text-align: right;'><font size="-2"><?= $valorpercentualmedico; ?> %</td>
                    </tr>


                    <?php
                    $qtdetotal = $qtdetotal + $item->quantidade;
                endforeach;
                $resultadototalgeral = $totalgeral - $totalperc;
                ?>
                <tr>
                    <td ><font size="-1">TOTAL</td>
                    <td style='text-align: right;'><font size="-1">Nr. Procedimentos: <?= $qtdetotal; ?></td>
                    <td colspan="3" style='text-align: right;'><font size="-1">VALOR TOTAL CLINICA: <?= number_format($resultadototalgeral, 2, ",", "."); ?></td>
                    <td colspan="3" style='text-align: right;'><font size="-1">VALOR TOTAL MEDICO: <?= number_format($totalperc, 2, ",", "."); ?></td>
                </tr>
            </tbody>
        </table>
        <?
        if ($totalretorno > 0 || $totalconsulta > 0) {
            ?>
            <hr>
            <table border="1">
                <tr>
                    <th colspan="2" width="200px;">RESUMO</th>
                </tr>
                <tr>
                    <td>TOTAL CONSULTAS</td>
                    <td style='text-align: right;'><?= $totalconsulta; ?></td>
                </tr>

                <tr>
                    <td>TOTAL RETORNO</td>
                    <td style='text-align: right;'><?= $totalretorno; ?></td>
                </tr>

            </table>

            <?
        }
        $irpf = $totalperc * 0.015;
        if ($irpf > 10) {
            ?>
            <hr>
            <table border="1">
                <tr>
                    <th colspan="2" width="200px;">RESUMO FISCAL</th>
                </tr>
                <tr>
                    <td>TOTAL</td>
                    <td style='text-align: right;'><?= number_format($totalperc, 2, ",", "."); ?></td>
                </tr>

                <tr>
                    <td>IRPF</td>
                    <td style='text-align: right;'><?= number_format($irpf, 2, ",", "."); ?></td>
                </tr>
                <?
                $resultado = $totalperc - $irpf;
                if ($totalperc > 5000) {
                    $pis = $totalperc * 0.0065;
                    $csll = $totalperc * 0.01;
                    $cofins = $totalperc * 0.03;
                    $resultado = $resultado - $pis - $csll - $cofins;
                    ?>
                    <tr>
                        <td>PIS</td>
                        <td style='text-align: right;'><?= number_format($pis, 2, ",", "."); ?></td>
                    </tr>
                    <tr>
                        <td>CSLL</td>
                        <td style='text-align: right;'><?= number_format($csll, 2, ",", "."); ?></td>
                    </tr>
                    <tr>
                        <td>COFINS</td>
                        <td style='text-align: right;'><?= number_format($cofins, 2, ",", "."); ?></td>
                    </tr>
                <? } ?>
                <tr>
                    <td>RESULTADO</td>
                    <td style='text-align: right;'><?= number_format($resultado, 2, ",", "."); ?></td>
                </tr>
            </table>
        <? } ?>
    <? } else {
        ?>
        <h4>N&atilde;o h&aacute; resultados para esta consulta.</h4>
        <?
    }
    ?>

</div> <!-- Final da DIV content -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="<?php base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript">



    $(function() {
        $("#accordion").accordion();
    });

</script>
