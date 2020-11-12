<?php 

//Configuração de proxy SENAI
$proxy = '10.1.21.254:3128';

//Array de configuração
$arrayConfig = array(
    'http' => array(
        'proxy' => $proxy,
        'request_fulluri' => true
    ),
    'https' => array(
        'proxy' => $proxy,
        'request_fulluri' => true
    )
);
$context = stream_context_create($arrayConfig);
//Fim do array de configuração. Aceita tanto o http e o https.

$url = "https://www.gutenberg.org/";
$html = file_get_contents($url, false, $context);

$dom = new DOMDocument();
libxml_use_internal_errors(true);

//Trasnforma o HTML em objeto
$dom->loadHTML($html);
libxml_clear_errors();

//Captura as tags div
$tagsDiv = $dom->getElementsByTagName('div');

//Array de paragráfos
$arrayParagrafos = [];

foreach ($tagsDiv as $div) {
    $classe = $div->getAttribute('class');

    if($classe == 'page_content') {
        $divsInternas = $div->getElementsByTagName('div');

        foreach($divsInternas as $divInterna) {
            $classeInterna = $divInterna->getAttribute('class');
            if ($classeInterna == 'box_announce') {
                echo $divInterna->nodeValue;
                
                //Vai percorrer a página e achar o dois parágrafos, que 
                //estão na div box_announce.
                $tagsP = $divInterna->getElementsByTagName('p');
                foreach ($tagsP as $p) {
                    $arrayParagrafos [] = $p->nodeValue;
                }
            break;
            }
        }
    break;
        }
}
//Exibe o array de parágrafos
print_r($arrayParagrafos);