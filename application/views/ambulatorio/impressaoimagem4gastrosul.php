
        <table>
            <tr>
        
    <?
        $c=0;
    
    foreach ($arquivo_pasta as $value) :
           $c++; 
    endforeach;
    $i = 0;
    $b = 0;
$y=0;
$z=0;
    if ($arquivo_pasta != false):
        foreach ($nomeimagem as $value) :
        $i++;
        $b++;
        $z++;

        $imagem = $value['nome'];
    if ($i <=2){
            ?>
    
            <td width="30px"></td><td><a><img width="410px" height="410px" src="<?= $caminho_arquivos . "/" . $value['arquivo'] ?>"><br><?=$imagem; ?></a></td>
            
            <?
    }
    if ($i == 2){
        $i=0;
        ?></tr><tr>
            <?
    }

    $y++;

    
        endforeach;
    endif
    ?>
</table>
<h5 style="text-align: center;">Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5>


