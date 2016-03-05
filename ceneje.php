<?php 
require_once 'simple_html_dom.php';

/* require('../../includes/config.php'); */

/*
echo "file-locked";
exit; 
*/



//izpis errrorjev
ini_set('display_errors', 'On');
set_time_limit(12500);
error_reporting(E_ALL);
//overrides the default PHP memory limit
ini_set('memory_limit', '-1');

function odstraniEur($ime){
		return str_replace(" €","",$ime);
}


function breaklines($str){
	
	return trim(preg_replace("/\s+/", " ", preg_replace("/[\r\n]+/", "", str_replace("&nbsp;", " ", $str)))); 
}


 

	function dobiCurl($kat, $sub) {
	
	include "kategorije.php";
	
	$url = 'https://www.ceneje.si/';	
	$vrnjen_arej = array();
	
	
	//indexi za zanko
	$index_strani=1;
	$st_index=0;
	$st_skupno=0;
	
	
	$all_titles = array();
	
	//na vsako stran se vpiše 20 izdelkov, zato zanka
	while(($st_index<$st_skupno) || ($st_skupno==0)){
		
	
		
	
		
	$url_dod = $subkategorije[$kat][$sub];	
	
	
			   
	//za vsako stran nov url
	$url1 = $url ."L2/". $url_dod . "?sort=4&page=" . $index_strani;	
	
	
    $ch = curl_init();
    

    
    curl_setopt($ch, CURLOPT_HTTPHEADER,array('Origin: https://www.ceneje.si', 'Host: www.ceneje.si', 'Content-Type: text/html; charset=utf-8', 'Accept-Language: en-US,en;q=0.5',
																			'Cookie: ".Ceneje.si_AnonymousCookieName=Ois-qGy00wEkAAAAMzFlOTBiNGItZjk2Yi00NTA0LWIwNzAtNDlmZWI0YmYxYjZkJuoefSyXO9jBape006WMM3uJzzAVLPZGf5MDIm-xtSY1; ASP.NET_SessionId=t4br31nzweb3fzunyc5yzbgw; experiment_PPs3l3513=1; _ga=GA1.2.2080643847.1457173544; exS=|https://www.ceneje.si/; __gads=ID=da6e32540eb80e05:T=1457173543:S=ALNI_MZqdhXdxk0U5Luounj-b-dqgyVP4A; exShow=1; cc_cookie_decline=null; cc_cookie_accept=cc_cookie_accept; _gat_GaGlobal_1=1; _gat_GaL1_2=1"'));
   
    curl_setopt($ch, CURLOPT_URL, $url1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
  
    curl_setOpt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_ENCODING ,"UTF-8");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:44.0) Gecko/20100101 Firefox/44.0");

    
   
    $curl_scraped_page = curl_exec($ch);
	
    $podatki_koncni = str_get_html($curl_scraped_page);
	
	
	$zadetki = $podatki_koncni->find('.rightWhiteBox',0);
	
	if(!is_object($zadetki))break;
	
	
	
	//št. zadetkov kot kaže na vrhu
	$st_podatki = $zadetki->find(".topSortBar",0)->find('b',0)->plaintext;
	$st_skupno = intval($st_podatki);

	

		
	
		
		foreach ($zadetki->find(".productBox") as $results) {
			
			
					
					$ime = $results->find(".content",0)->find("h3",0)->plaintext;
					$cena = odstraniEur($results->find(".rBox",0)->find("p",0)->find("b",0)->plaintext);
					$trgovina = $results->find(".rBox",0)->find("p",1)->plaintext;
					$link = $results->find(".content",0)->find("h3",0)->find("a",0)->href;
						
					$trgovina = explode(" ",trim($trgovina))[1];
							
						$st_podatki_arr = explode("  ", $st_podatki);	
								
								
								$all_titles[] = array(
									"ime" => trim($ime),
									"link" => $link,
									"cena" => trim($cena),
									"trgovina" => trim($trgovina)
								);		
		}
		
	
	//povecanje indexa, da gre zanka naprej	
	$st_index=$st_index+20;
	$index_strani++;
	}
	return $all_titles;
	

}




//za izpis uporabi 2 števili: zaporedno številko kategorije (0 do 11) in podtakegorije znotraj kategorije (kategorije.php)
var_dump(dobiCurl(10,2)); echo "<br/>";



?>