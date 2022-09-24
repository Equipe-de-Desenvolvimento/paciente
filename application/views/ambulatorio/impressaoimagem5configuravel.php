<table>
     <tr>
    <?
        $c=0;
    
    foreach ($arquivo_pasta as $value) :
           $c++; 
    endforeach;
    
    $i = 0;
    $b = 0;
    $y = 0;
    $z = 0;
    if ($arquivo_pasta != false):
        foreach ($nomeimagem as $value) :
        $i++;
        $b++;
    if ($i <= 2){
        $imagem = $value['nome'];
            ?>
    
            <td width="50px"></td><td><img  width="260px" height="210px" src="<?= $caminho_arquivos . "/" . $value['arquivo']?>"><br><?=$imagem; ?></td>
            
            <?
    }
    if ($i == 3){
        $imagem = $value['nome'];
        $i=0;
        ?>
            </tr>
            <tr>
        
        <td></td><td colspan="3"><center><img  width="260px" height="210px" src="<?= base_url() . "upload/" . $exame_id . "/" . $value['arquivo'] ?>"><br><?=$imagem; ?></center></td>
            </tr>
            <tr>
            <?
    }
    if ($b==5 && $z != $c){
        $c == 6 ? $i = $i : $i = 0;
        $b=0;
        ?>
            </tr>
            <tr>
            <?
    }

    $y++;
$z++;
        endforeach;
    endif
    ?>
</table>
<!-- <h5>Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5> -->



