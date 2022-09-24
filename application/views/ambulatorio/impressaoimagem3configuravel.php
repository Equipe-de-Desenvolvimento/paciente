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
        
                <td width="30px"></td><td><a><img  width="410px" height="410px" src="<?= $caminho_arquivos . "/" . $value['arquivo'] ?>"><br><?=$imagem; ?><br></a></td>
                
                <?if($c == 10 && $y == 9) { ?>
                <td colspan="2"></td>
                <?}?>

                
                <?
        }
        if ($i == 3){
            $i=0;
            ?>
            </tr>
            <tr>
            
            <td width="30px"></td><td colspan="3"><a><center><br><img  width="410px" height="410px" src="<?= $caminho_arquivos . "/" . $value['arquivo'] ?>"></center><br><?=$imagem; ?></a><br><br></td>
                </tr>
                <tr>
                <?
        }
            if ($b==3){
            $i=0;
            $b=0;
            ?>
             </tr>
            <tr>
            <?if($c == 4) { ?>
                <td colspan="2"></td>
            <?}?>

            

    
                <?
        }
        $y++;
            endforeach;
        endif
        ?>
        </tr>
    </table>
<!-- <h5>Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5> -->



