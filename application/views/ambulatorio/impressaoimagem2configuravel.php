<table>
    <?
            $c=0;
    
    foreach ($arquivo_pasta as $value) :
           $c++; 
    endforeach;
    $i = 0;
    $y = 0;
    $z = 0;
    $k = 0;
    if ($arquivo_pasta != false):
        foreach ($nomeimagem as $value) :
            $imagem = $value['nome'];
            $i++;
            $y++;
            $k++;
            $z++;
            
                ?>
                <tr>
                    <td width="50px"></td><td><a><img  width="310px" height="310px" src="<?= $caminho_arquivos . "/" . $value['arquivo'] ?>"><br><?=$imagem; ?></a></td>
                </tr>  
                <?

                
     if ($y ==2 && $z != $c){
        $y=0;
        ?>

            <?
    }
                
                
        endforeach;
    endif
    ?>
</table>
<!-- <h5>Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5> -->



