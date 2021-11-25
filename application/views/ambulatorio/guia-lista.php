
<div class="content ficha_ceatox">

    <?
    $operador_id = $this->session->userdata('operador_id');
    ?>
    <div>
        <form name="form_guia" id="form_guia" action="<?= base_url() ?>ambulatorio/guia/gravarprocedimentos" method="post">
            <fieldset>
                <legend>Dados do Paciente</legend>
                <div>
                    <label>Nome</label>                      
                    <input type="text" id="txtNome" name="nome"  class="texto09" value="<?= $paciente['0']->nome; ?>" readonly/>
                </div>
                <div>
                    <label>Sexo</label>
                    <select name="sexo" id="txtSexo" class="size2">
                        <option value="M" <?
                        if ($paciente['0']->sexo == "M"):echo 'selected';
                        endif;
                        ?>>Masculino</option>
                        <option value="F" <?
                        if ($paciente['0']->sexo == "F"):echo 'selected';
                        endif;
                        ?>>Feminino</option>
                    </select>
                </div>

                <div>
                    <label>Nascimento</label>


                    <input type="text" name="nascimento" id="txtNascimento" class="texto02" alt="date" value="<?php echo substr($paciente['0']->nascimento, 8, 2) . '/' . substr($paciente['0']->nascimento, 5, 2) . '/' . substr($paciente['0']->nascimento, 0, 4); ?>" onblur="retornaIdade()" readonly/>
                </div>

                <div>

                    <label>Idade</label>
                    <input type="text" name="idade" id="txtIdade" class="texto01" alt="numeromask" value="<?= $paciente['0']->idade; ?>" readonly />

                </div>

                <div>
                    <label>Nome da M&atilde;e</label>


                    <input type="text" name="nome_mae" id="txtNomeMae" class="texto08" value="<?= $paciente['0']->nome_mae; ?>" readonly/>
                </div>
            </fieldset>
        </form>

        <fieldset>
            <?
            foreach ($guia as $test) :
                $guia_id = $test->ambulatorio_guia_id;
                ?>
                <table >
                    <thead>
                        <tr>
                            <th class="tabela_header">Guia: <?= $test->ambulatorio_guia_id ?></th>

                            <th class="tabela_header" colspan="8"></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        $estilo_linha = "tabela_content01";
                        $urlClinica = $empresa[0]->endereco_externo_base;
                        $urlCaminho = $empresa[0]->endereco_upload;
                            // echo '<pre>';
                            // print_r($urlCaminho);
                            // die;
                        // var_dump($urlClinica); die;
                        foreach ($exames as $item) :
                            ($estilo_linha == "tabela_content01") ? $estilo_linha = "tabela_content02" : $estilo_linha = "tabela_content01";
                            if ($test->ambulatorio_guia_id == $item->guia_id) {
                                ?>
                                <tr>
                                    <td class="<?php echo $estilo_linha; ?>" width="100px;"><?= $item->procedimento ?></td>
                                    <td class="<?php echo $estilo_linha; ?>" width="50px;"><?= substr($item->data, 8, 2) . "/" . substr($item->data, 5, 2) . "/" . substr($item->data, 0, 4); ?></td>
                                    <td class="<?php echo $estilo_linha; ?>" width="50px;"><?= $item->inicio ?></td>

                                    <? if ($item->situacao == "FINALIZADO") { ?>
                                        <?
                                            $caminhoCompleto = $urlCaminho.'/laudopdf/'.$item->ambulatorio_laudo_id;
                                            $arquivos = directory_map($caminhoCompleto);  
                                            if($arquivos != false){
                                                foreach($arquivos as $value){ 
                                                    if(substr($value, 0, 8) == 'imagens_'){
                                                        $nome_image = $value;
                                                    }else{
                                                        $nome_laudo = $value;
                                                    }
                                                }
                                            }  
                                        ?>

                                        <? if (@$empresa[0]->botao_laudo_paciente == 't') { ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaolaudo/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                                        Laudo</a></div>
                                            </td>
                                        <? } ?>

                                        <? if (@$empresa[0]->botao_imagem_paciente == 't') { ?>
                                            <td class="<?php echo $estilo_linha; ?>" width="70px;"><div class="bt_link">
                                                    <a onclick="javascript:window.open('<?= base_url() ?>ambulatorio/laudo/impressaoimagem/<?= $item->ambulatorio_laudo_id ?>/<?= $item->exame_id ?>');">
                                                        Imagem</a></div>
                                            </td>   
                                        <? } ?>
    



                                    <? } ?>
                                            
                                        <? if (@$empresa[0]->botao_arquivos_paciente == 't') { ?>
                                           <!-- <td class="<?php echo $estilo_linha; ?>" width="50px;">
                                                <div class="bt_link">
                                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/downloadarquivos/". $item->ambulatorio_laudo_id ?>');">
                                                        Download</a></div>
                                            </td> -->
                                            <td class="<?php echo $estilo_linha; ?>" width="70px;">
                                                <div class="bt_link">
                                                    <a onclick="javascript:window.open('<?= base_url() . "ambulatorio/laudo/listararquivos/" . $item->ambulatorio_laudo_id; ?> ', '_blank', 'width=800,height=600');">
                                                        Arquivos
                                                    </a> 
                                                </div>
                                            </td>  
                                        <? } ?>

                                        <? if ($empresa[0]->endereco_integracao_lab != '') { ?>
                                        <td  class="<?php echo $estilo_linha; ?>"> <!-- colpan = "6" -->
                                        <div class="bt_link">
                                            <a target="_blank" href='<?= base_url() . "ambulatorio/guia/resultadoExamesLabLuz/" . $guia_id . '/' . $paciente['0']->paciente_id; ?>'>
                                                Imprimir Resultado Lab
                                            </a>
                                        </div>
                                        </td>
                                        <?}?>
                                </tr>

                            </tbody>
                            <?
                        }
                    endforeach;
                    ?>
                    <br>
                <? endforeach; ?>
                <tfoot>
                    <tr>
                        <th class="tabela_footer" colspan="11">
                        </th>
                    </tr>
                </tfoot>
            </table>
        </fieldset>
    </div>


    <script type="text/javascript">



        $(function () {
            $(".competencia").accordion({autoHeight: false});
            $(".accordion").accordion({autoHeight: false, active: false});
            $(".lotacao").accordion({
                active: true,
                autoheight: false,
                clearStyle: true

            });


        });
    </script>
