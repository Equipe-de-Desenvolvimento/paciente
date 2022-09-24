<table>
    <?
    $c=0;
    
    foreach ($arquivo_pasta as $value) :
           $c++; 
    endforeach;
    $i = 0;
    $y = 0;
    if ($arquivo_pasta != false):
        foreach ($nomeimagem as $value) :
            $i++;
            $imagem = $value['nome'];
            $y++;
                ?>
                <tr>
                    <td><a><img width="500px" height="500px" src="<?= $caminho_arquivos. "/" . $value['arquivo'] ?>"><br><?=$imagem; ?></a></td>
                </tr>  
            
                <?

        endforeach;
    endif
    ?>
</table>
<!-- <h5>Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5> -->



