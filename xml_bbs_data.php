<?php
/****************************************************************************************************
 * RSS 피드 (최근 2일 이내 올라온 게시글)
 ****************************************************************************************************/
require $_SERVER['DOCUMENT_ROOT'].'/_core/engine/single.engine.php';

## 게시판 정보
$bbs_table = (!empty($bid)) ? "hc_bbs_data_" . $bid : "hc_bbs_data_B_TOEIC_QA";
$max = ($max) ? $max : 20;
$sort = ($sort) ? $sort : 'd_regis';
$orderby = ($orderby) ? $orderby : 'DESC';

## 데이터 가져오기
$bbs_list = getDbArrayRow2($bbs_table, "display IN (1, 2) AND hidden = 0 AND notice <> 1", "uid, nic, subject, content, hit, comment, oneline, d_regis", $sort, $orderby, $max, "1");

if (!empty($bbs_list)) {
	header("Content-type: text/xml; charset=utf-8");
	$xml = "<?xml version='1.0' encoding='UTF-8'?>";
	$xml .= "<rss version='2.0'>";
	$xml .= "<channel>";
	$xml .= "<title>토익자유게시판</title>";
	$xml .= "<description>토익을 준비하는 해티즌들이 자유롭게 이야기를 나누는 게시판입니다.</description>";
	$xml .= "<link>https://".str_replace('&','&#38;',$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])."</link>";
	
	foreach($bbs_list as $bbs_key => $bbs_item) {
		$url_request = '/?m=bbs&bid='.$bid."&uid=".$bbs_item['uid'];
		$url = str_replace('=', '&#61;', str_replace('&','&#38;', $url_request));
		$bbs_url = 'https://www.hackers.co.kr' . $url;
		
		$bbs_title = str_replace('ζ', '', str_replace('amp;', '', str_replace('nbsp;', '', htmlspecialchars(strip_tags($bbs_item['subject'])))));
		$bbs_content = str_replace('ζ', '', str_replace('amp;', '', str_replace('nbsp;', '', htmlspecialchars(strip_tags($bbs_item['content'])))));
		
		$xml .= '<item>';
		$xml .= '<title><![CDATA['. $bbs_title .']]></title>';
		$xml .= '<link><![CDATA['. $bbs_url .']]></link>';
		$xml .= '<guid><![CDATA['. $bbs_url .']]></guid>';
		$xml .= '<description><![CDATA['. $bbs_content .']]></description>';
		// $xml .= '<nic><![CDATA['.$bbs_item['nic'].']]></nic>';
		// $xml .= '<hit><![CDATA['.$bbs_item['hit'].']]></hit>';
		// $xml .= '<comment><![CDATA['.($bbs_item['comment'] + $bbs_item['oneline']).']]></comment>';
		$xml .= "<pubDate>".date("D, d M Y H:i:s O", strtotime($bbs_item['d_regis']))."</pubDate>";
		$xml .= '</item>';
	}
	
	$xml.= "</channel>";
	$xml .= "</rss>";
	
	echo $xml;
}


?>