
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
    if ($i <=2){
        $imagem = $value['nome'];
            ?>
    
            <td width="30px"></td><td><img  width="410px" height="360px" src="<?= $caminho_arquivos . "/" . $value['arquivo']?>"><br><?=$imagem; ?></td>
            
            <?
    }
    if ($i ==3){
        $imagem = $value['nome'];
        $i=0;
        ?>
            </tr>
            <tr>
        
        <td width="30px"></td><td colspan="3"><center><img  width="410px" height="360px" src="<?= $caminho_arquivos . "/" . $value['arquivo'] ?>"><br><?=$imagem; ?></center></td>
            </tr>
            <tr>
            <?
    }
    if ($b==5 && $z != $c){
        $i=0;
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
<h5 style="text-align: center;">Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5>