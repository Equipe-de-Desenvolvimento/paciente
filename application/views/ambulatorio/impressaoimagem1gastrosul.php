<table>
    <?
    $i = 0;
    $y = 0;
    if ($arquivo_pasta != false):
        foreach ($nomeimagem as $value) :
            $i++;
            $imagem = $value['nome'];
            $y++;
                ?>
                <tr>
                    <td><a><img  src="<?= $caminho_arquivos . "/" . $value['arquivo'] ?>"><br><?=$imagem; ?></a></td>
                </tr>  
            
                <?

        endforeach;
    endif
    ?>
</table>
<h5 style="text-align: center;">Fotaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5>