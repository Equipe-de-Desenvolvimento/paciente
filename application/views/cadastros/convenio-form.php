
<div class="content ficha_ceatox"> <!-- Inicio da DIV content -->
    <div>
        <form name="form_convenio" id="form_convenio" action="<?= base_url() ?>cadastros/convenio/gravar" method="post">
            <fieldset>
                <legend>Dados do Convenio</legend>
                <div>
                    <label>Nome</label>
                    <input type="hidden" name="txtconvenio_id" class="texto10 bestupper" value="<?= @$obj->_convenio_id; ?>" />
                    <input type="text" name="txtNome" class="texto10 bestupper" value="<?= @$obj->_nome; ?>" />
                </div>
                <div>
                    <label>Raz&atilde;o social</label>
                    <input type="text" name="txtrazaosocial" class="texto10" value="<?= @$obj->_razao_social; ?>" />
                </div>
                <div>
                    <label>CNPJ</label>
                    <input type="text" name="txtCNPJ" maxlength="14" alt="cnpj" class="texto03" value="<?= @$obj->_cnpj; ?>" />
                </div>

            </fieldset>
            <fieldset>
                <legend>Endereco</legend>

                <div>
                    <label>T. logradouro</label>


                    <select name="tipo_logradouro" id="txtTipoLogradouro" class="size2" >
                        <option value='' >selecione</option>
                        <?php
                        $listaLogradouro = $this->paciente->listaTipoLogradouro($_GET);
                        foreach ($listaLogradouro as $item) {
                            ?>

                            <option   value =<?php echo $item->tipo_logradouro_id; ?> <?
                            if (@$obj->_tipoLogradouro == $item->tipo_logradouro_id):echo 'selected';
                            endif;
                            ?>><?php echo $item->descricao; ?></option>
                                      <?php
                                  }
                                  ?> 
                    </select>
                </div>
                <div>
                    <label>Endere&ccedil;o</label>
                    <input type="text" id="txtendereco" class="texto10" name="endereco" value="<?= @$obj->_logradouro; ?>" />
                </div>
                <div>
                    <label>N&uacute;mero</label>
                    <input type="text" id="txtNumero" class="texto02" name="numero" value="<?= @$obj->_numero; ?>" />
                </div>
                <div>
                    <label>Bairro</label>
                    <input type="text" id="txtBairro" class="texto03" name="bairro" value="<?= @$obj->_bairro; ?>" />
                </div>
                <div>
                    <label>Complemento</label>
                    <input type="text" id="txtComplemento" class="texto06" name="complemento" value="<?= @$obj->_complemento; ?>" />
                </div>

                <div>
                    <label>Município</label>
                    <input type="hidden" id="txtCidadeID" class="texto_id" name="municipio_id" value="<?= @$obj->_cidade; ?>" readonly="true" />
                    <input type="text" id="txtCidade" class="texto04" name="txtCidade" value="<?= @$obj->_cidade_nome; ?>" />
                </div>
                <div>
                    <label>CEP</label>
                    <input type="text" id="txtCep" class="texto02" name="cep" alt="cep" value="<?= @$obj->_cep; ?>" />
                </div>
                <div>
                    <label>Telefone</label>
                    <input type="text" id="txtTelefone" class="texto02" name="telefone" alt="phone" value="<?= @$obj->_telefone; ?>" />
                </div>
                <div>
                    <label>Celular</label>
                    <input type="text" id="txtCelular" class="texto02" name="celular" alt="phone" value="<?= @$obj->_celular; ?>" />
                </div>
            </fieldset>
            <fieldset>
                <legend>Condi&ccedil;&atilde;o para Recebimento</legend>
                <div>
                    <label>Tabela</label>
                    <select  name="tipo" id="tipo" class="size1" >
                        <option value="SIGTAP" <?
                        if (@$obj->_tabela == "SIGTAP"):echo 'selected';
                        endif;
                        ?>>SIGTAP</option>
                        <option value="AMB92" <?
                                if (@$obj->_tabela == "AMB92"):echo 'selected';
                                endif;
                                ?>>AMB92</option>
                        <option value="TUSS" <?
                        if (@$obj->_tabela == "TUSS"):echo 'selected';
                        endif;
                                ?>>TUSS</option>
                    </select>
                </div>
                <div>
                    <label>Primeiro procedimento</label>
                    <input type="text" id="procedimento1" class="texto01" name="procedimento1" alt="integer" value="<?= @$obj->_procedimento1; ?>" />%
                </div>
                <div>
                    <label>Outros procedimento</label>
                    <input type="text" id="procedimento2" class="texto01" name="procedimento2" alt="integer" value="<?= @$obj->_procedimento2; ?>" />%
                </div>
                <div>
                    <label>Orçamento Enteral</label>
                    <input type="text" id="enteral" class="texto02" name="enteral" alt="decimal" value="<?= @$obj->_enteral; ?>" />
                </div>
                <div>
                    <label>Orçamento Parenteral</label>
                    <input type="text" id="parenteral" class="texto02" name="parenteral" alt="decimal" value="<?= @$obj->_parenteral; ?>" />
                </div>
            </fieldset>
            <fieldset>
                <legend>Condi&ccedil;&atilde;o de recebimento</legend>
                <div>
                    <?php
                    if (@$obj->_dinheiro == "t") {
                        ?>
                        <input type="checkbox" name="txtdinheiro" checked ="true" />Dinheiro
                        <?php
                    } else {
                        ?>
                        <input type="checkbox" name="txtdinheiro"  />Dinheiro
    <?php
}
?>
                </div>
                <div>
                    <label>Credor / Devedor</label>


                    <select name="credor_devedor" id="credor_devedor" class="size2" >
                        <option value='' >selecione</option>
                        <?php
                        $credor_devedor = $this->convenio->listarcredordevedor();
                        foreach ($credor_devedor as $item) {
                            ?>

                            <option   value =<?php echo $item->financeiro_credor_devedor_id; ?> <?
                                      if (@$obj->_credor_devedor_id == $item->financeiro_credor_devedor_id):echo 'selected';
                                      endif;
                                      ?>><?php echo $item->razao_social; ?></option>
    <?php
}
?> 
                    </select>
                </div>
                <div>
                    <label>Conta</label>


                    <select name="conta" id="conta" class="size2" >
                        <option value='' >selecione</option>
                        <?php
                        $forma = $this->convenio->listarforma();
                        var_dump($forma);
                        foreach ($forma as $item) {
                            ?>

                            <option   value =<?php echo $item->forma_entradas_saida_id; ?> <?
                                      if (@$obj->_conta_id == $item->forma_entradas_saida_id):echo 'selected';
                                      endif;
                                      ?>><?php echo $item->descricao; ?></option>
    <?php
}
?> 
                    </select>
                </div>
            </fieldset>
            <fieldset>
                <legend>Observa&ccedil;&atilde;o</legend>
                <div>
                    <textarea cols="" rows="" name="txtObservacao" class="texto_area"><?= @$obj->_observacao; ?></textarea>
                </div>
            </fieldset>
            <hr/>
            <fieldset>
                <div>
                    <button type="submit" name="btnEnviar">Enviar</button>
                    <button type="reset" name="btnLimpar">Limpar</button>
                </div>
            </fieldset>
            <br>
        </form>
    </div>
</div> <!-- Final da DIV content -->

<link rel="stylesheet" href="<?= base_url() ?>css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="<?= base_url() ?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        jQuery('#form_convenio').validate({
            rules: {
                txtNome: {
                    required: true,
                    minlength: 2
                },
                txtrazaosocial: {
                    required: true
                },
                txtCNPJ: {
                    required: true
                }

            },
            messages: {
                txtNome: {
                    required: "*",
                    minlength: "*"
                },
                txtrazaosocial: {
                    required: "*"
                },
                txtCNPJ: {
                    required: "*"
                }
            }
        });
    });

    $(function() {
        $("#txtCidade").autocomplete({
            source: "<?= base_url() ?>index?c=autocomplete&m=cidade",
            minLength: 3,
            focus: function(event, ui) {
                $("#txtCidade").val(ui.item.label);
                return false;
            },
            select: function(event, ui) {
                $("#txtCidade").val(ui.item.value);
                $("#txtCidadeID").val(ui.item.id);
                return false;
            }
        });
    });
</script>