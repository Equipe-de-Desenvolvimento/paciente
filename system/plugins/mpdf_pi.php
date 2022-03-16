<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function pdf($html, $filename = null, $cabecalho = null, $rodape=null, $grupo = '' ) {
    require_once("mpdf_lib/mpdf.php");
    
//    $mpdf = new mPDF('UTF-8', array (210,148));
//    if ($grupo == "US"){
//    $mpdf = new mPDF('UTF-8', array (210, 297));
//    }
    $mpdf = new mPDF('UTF-8', array (210, 297));
//    if ($grupo == "DENSITOMETRIA"){
//    $mpdf = new mPDF('UTF-8', array (210, 297));
//    }
//    $mpdf = new mPDF();
//    $mpdf = new mPDF('UTF-8', array (210, 297));
    //$mpdf->allow_charset_conversion=true;
    //$mpdf->charset_in='iso-8859-1';
    //Exibir a pagina inteira no browser
    //$mpdf->SetDisplayMode('fullpage');
    //Cabeçalho: Seta a data/hora completa de quando o PDF foi gerado + um texto no lado direito
//    $mpdf->SetHTMLHeader('Introduction','O'); 
    $mpdf->SetHTMLHeader($cabecalho);
    $mpdf->SetHTMLFooter($rodape);
//    $mpdf->DefHTMLHeaderByName("teste", $cabecalho);
//    $mpdf->SetHeader($cabecalho, $side='L');
    //Rodapé: Seta a data/hora completa de quando o PDF foi gerado + um texto no lado direito
    //$mpdf->SetFooter('{DATE j/m/Y H:i}|{PAGENO}/{nb}|Texto no rodapé');

    $mpdf->WriteHTML($html);

    // define um nome para o arquivo PDF
    if ($filename == null) {
        $filename = date("Y-m-d_his") . '_impressao.pdf';
    }

    $mpdf->Output($filename, 'I');
}

function salvapdf($texto, $filename , $cabecalho = null, $rodape=null) {
//    var_dump($texto , $filename , $cabecalho , $rodape);
//    die;
    require_once("mpdf_lib/mpdf.php");
    
//    $mpdf = new mPDF('UTF-8', array (210,148));
//    if ($grupo == "US"){
//    $mpdf = new mPDF('UTF-8', array (210, 297));
//    }
    $mpdf = new mPDF('UTF-8', array (210, 297));
//    if ($grupo == "DENSITOMETRIA"){
//    $mpdf = new mPDF('UTF-8', array (210, 297));
//    }
//    $mpdf = new mPDF();
//    $mpdf = new mPDF('UTF-8', array (210, 297));
    //$mpdf->allow_charset_conversion=true;
    //$mpdf->charset_in='iso-8859-1';
    //Exibir a pagina inteira no browser
    //$mpdf->SetDisplayMode('fullpage');
    //Cabeçalho: Seta a data/hora completa de quando o PDF foi gerado + um texto no lado direito
//    $mpdf->SetHTMLHeader('Introduction','O'); 
    $mpdf->SetHTMLHeader($cabecalho);
    $mpdf->SetHTMLFooter($rodape);
//    $mpdf->DefHTMLHeaderByName("teste", $cabecalho);
//    $mpdf->SetHeader($cabecalho, $side='L');
    //Rodapé: Seta a data/hora completa de quando o PDF foi gerado + um texto no lado direito
    //$mpdf->SetFooter('{DATE j/m/Y H:i}|{PAGENO}/{nb}|Texto no rodapé');

    $mpdf->WriteHTML($texto);

    // define um nome para o arquivo PDF
//    if ($filename == null) {
//        $filename = date("Y-m-d_his") . '_impressao.pdf';
//    }

    $mpdf->Output($filename, 'F');
}


function pdfcertificado($html, $filename = null, $cabecalho = null, $rodape = null, $grupo = '', $margin_bottomH = 9, $impressao_tipo = 0, $marginDR = 15, $ambulatorio_laudo_id) {
    require_once("mpdf_lib/mpdf.php");

    $array_tam = array (210, 297);

    if($impressao_tipo == '8'){

        $array_tam = array(210,148);

        if ($grupo == "MAMOGRAFIA"){
            $array_tam = array(148,210);
        }
        if ($grupo == "DENSITOMETRIA" || $grupo == "US"){
            $array_tam = array (210, 297);
        }

    }

    $mpdf = new mPDF('UTF-8', $array_tam, 0, '', $marginDR, $marginDR, 16 , $margin_bottomH, $margin_bottomH, $margin_bottom);

    $mpdf->SetHTMLHeader($cabecalho);
    $mpdf->SetHTMLFooter($rodape);


    $mpdf->WriteHTML($html);
    

    // define um nome para o arquivo PDF
    if ($filename == null) {
        $filename = date("Y-m-d_his") . '_impressao.pdf';
    }

    if (!is_dir("./upload/PDFcertificado")) {
        mkdir("./upload/PDFcertificado");
        $destino = "./upload/PDFcertificado";
        chmod($destino, 0777);
    }

    // echo $destino;
    // die;

    if (!is_dir("./upload/PDFcertificado/$ambulatorio_laudo_id")) {
        mkdir("./upload/PDFcertificado/$ambulatorio_laudo_id");
        $destino = "./upload/PDFcertificado/$ambulatorio_laudo_id";
        chmod($destino, 0777);
    }
    // echo $destino;
    // die;

    $local_salvamento = './upload/PDFcertificado/'.$ambulatorio_laudo_id;

    $mpdf->Output($local_salvamento.'/'.$filename, 'F');
}





/* End of file mpdf_pdf_pi.php */
/* Location: ./system/plugins/mpdf_pi.php */