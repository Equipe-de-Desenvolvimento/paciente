<?
$b = 0;
$i = 0;
$y = 0;
$cont = 0;

if ($arquivo_pasta != false){
    foreach ($nomeimagem as $value) {
        $cont++;

        $b++;
if($b == 1){?>
     <table>
        <tr><td width="100px"></td>
<?}


    if($i < 2){
        $i++;
        $imagem = $value['nome'];

        ?><td><img  width="240px" height="190px" src="<?= $caminho_arquivos. "/" . $value['arquivo']; ?>"><br><br><?=$imagem; ?></td><?
    }

    if($i == 2){
        $i = 0;
        ?></tr>
        <tr><td width="100px"></td><?
    }   
    if($b == 6){
        $b = 0;
        ?><td colspan="2"><tr></table> 

        <? if($cont == count($nomeimagem)){ ?> 
        <?}else{?>
             <h1 class="break"></h1>   
        <?} ?>
        
   <? }
    $y++; ?>
<!-- 
    <? if($cont == count($nomeimagem)){ ?>
        <tr><td width="100px"></td><td colspan="4" style="text-align: center;"> <h5 style="text-align: center;">Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5></td></tr> 
    <?}?> -->

<?
    
  }
}
?>
</tr>
</table>
<!-- <h5>Fortaleza: <?= substr($laudo['0']->data_cadastro, 8, 2); ?>/<?= substr($laudo['0']->data_cadastro, 5, 2); ?>/<?= substr($laudo['0']->data_cadastro, 0, 4); ?></h5> -->



