<?php

function generateMenu() {
    $menu = array(
        'home'  => array('text'=>'Accueil',  'url'=>'home.php'),
        'upload'  => array('text'=>'Importer',  'url'=>'upload.php'),
        'gallery'  => array('text'=>'Galerie',  'url'=>'gallery.php')
    );
    $html = "<nav>\n";
    foreach($menu as $item) {
        $html .= "<a href='{$item['url']}'>{$item['text']}</a>\n";
    }
    $html .= "</nav>\n";
    return $html;
}

function generateFilter($id) {
    $menu = array(
        '+Contrast'  => array('text'=>'+ contraste',   'url'=>'?filter=1'),
        '-Contrast'  => array('text'=>'- contraste',   'url'=>'?filter=2'),
        '+Brightness'=> array('text'=>'+ luminosité',  'url'=>'?filter=3'),
        '-Brightness'=> array('text'=>'- luminosité',  'url'=>'?filter=4'),
        'Sepia'      => array('text'=>'Sepia',         'url'=>'?filter=5'),
        'Grayscale'  => array('text'=>'Gris',          'url'=>'?filter=6'),
        'Gauss'      => array('text'=>'Gauss',         'url'=>'?filter=7'),
        'Edge'       => array('text'=>'Contours',      'url'=>'?filter=8')
    );
    $html = "<nav style=\"text-align:center\">\n";
    foreach($menu as $item) {
        $html .= "<a href='{$item['url']}'>{$item['text']}</a>\n";
    }
    $html .= "</nav>\n";
    return $html;
}