        <table >
            <tr>
        
    <?

    $i = 0;
    $y = 1;
    if ($arquivo_pasta != false):
        foreach ($arquivo_pasta as $value) :
      


    if ($i < 2){
$i++;

$imagem = $nomeimagem[$y];
            ?>
    
            <td><img  width="240px" height="190px" src="<?=  $caminho_arquivos. "/" . $value; ?>"><br><br><?=$imagem['nome']; ?></td>
            
            <?

    }if ($i == 2){
  $i = 0;
        ?></tr><tr>
            <?

}
$y++;  
        endforeach;
    endif
    ?>
</tr>
</table>
<h5  style="text-align: center;">Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5>



