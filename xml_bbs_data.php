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
	header("Content-type: text/xml;charset=utf-8");
	$xml = '<?xml version="1.0" encoding="utf-8" ?>';
	$xml.= '<bbs>';
	
	foreach($bbs_list as $bbs_key => $bbs_item) {
		$xml .= '<item>';
		$xml .= '<title><![CDATA['. $bbs_item['subject'] .']]></title>';
		$xml .= '<link><![CDATA[//www.hackers.co.kr/?m=bbs&bid='.$bid.'&uid='.$bbs_item['uid'].']]></link>';
		$xml .= '<guid><![CDATA[//www.hackers.co.kr/?m=bbs&bid='.$bid.'&uid='.$bbs_item['uid'].']]></guid>';
		$xml .= '<nic><![CDATA['.$bbs_item['nic'].']]></nic>';
		$xml .= '<hit><![CDATA['.$bbs_item['hit'].']]></hit>';
		$xml .= '<comment><![CDATA['.($bbs_item['comment'] + $bbs_item['oneline']).']]></comment>';
		$xml .= '<d_regis><![CDATA['.$bbs_item['d_regis'].']]></d_regis>';
		$xml .= '</item>';
	}
	
	$xml.= '</bbs>';
	
	echo $xml;
}


?>