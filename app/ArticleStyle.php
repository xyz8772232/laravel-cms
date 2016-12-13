<?php

namespace App;

use Sunra\PhpSimple\HtmlDomParser;


/**
 * Class ArticleStyle
 *
 * @package \App
 */
class ArticleStyle
{
    //处理之后的html
    protected static $rs = "";
    //html自封闭标签
    protected static $noEndLabel = array("embed", "wbr", "hr", "col", "area", "input", "param", "basefont");

    /**
     * @param $content
     * @param $video_finished 视频转码是否完成
     * @return string
     * @author tishun
     */

    public static function articleContent($content, $video_finished = true){
        self::$rs = "";
        //如果文章内容里面没有标签，则原样返回
        if(strpos($content, "<") == false &&  strpos($content, ">") == false){
            return $content;
        }

        $content = str_replace(array("\n", "\r", "\t"), "", $content);
        //多个空格"&nbsp;"换成一个
        $content = preg_replace("/(\s*\&nbsp\;\s*)+/", "&nbsp;", $content);
        //把多个<br/>换成一个<br/>
        $content = preg_replace("/(\s*<(\/)?\s*br\s*(\/)?>\s*)+/", "<br/>", $content);
        //如果自闭合标签不带 / ,则simple_html_dom不认为它是自闭合标签，会出问题，所以直接替换掉(除了wbr,hr标签，注意下还有没有其它标签问题)
        foreach(self::$noEndLabel as $value){
            $content = preg_replace("/(<(\/)?\s*$value\s*(\/)?>)+/", "", $content);
        }

        //simple_html_dom对于img的闭合标签，并不识别，所以要去除
        $content = preg_replace("/(\s*<(\/)?\s*img\s*(\/)?>\s*)+/", "", $content);

        $content = str_replace(' alt="\"', "", $content);

        $html = HtmlDomParser::str_get_html( $content );

        self::filterContent($html, $video_finished);

        //把多个<br/>换成两个<br/>
        self::$rs = preg_replace("/(\s*<(\/)?\s*br\s*(\/)?>\s*)+/", "<br/><br/>", self::$rs);
        return self::$rs;
    }

    public static function filterContent($htmlObject, $video_finished = true){
        //到达叶子节点，输出内容
        if($htmlObject->firstChild() == null){
            return preg_replace("/\s+/", " ", $htmlObject->innertext());
        }
        $elementObject = $htmlObject->firstChild();

        $parentInnerText = $elementObject->parent()->innertext();

        do{
            self::$rs .= preg_replace("/\s+/", " ", strstr($parentInnerText, "<", true));
            $innertext = $elementObject->innertext();
            $elementOutertext = $elementObject->outertext();
            //$elementOutertext = preg_quote($elementOutertext, "/");
            //如果该标签内的内容有效，则处理这段 内容
            if(self::validate($innertext) || $elementObject->tag == "img" || $elementObject->tag == "iframe" || $elementObject->tag == "video"){
                //链接白名单，内部链接和合作方链接不会被过滤，sinaimg.cn是一个下载文档的链接
                //$whiteUrl = array("sina.cn","sina.com.cn","m.yizhibo.com","sinaimg.cn","http://form.mikecrm.com/24f0Cx");
                if($elementObject->tag =="img"){
                    if (strpos($elementObject->src, '.x-empty') === false) {
                        self::$rs .= '<'.$elementObject->tag.' src="'.$elementObject->src.'">';
                    }
                //}elseif($elementObject->tag =="a" && (self::strposa($elementObject->href, $whiteUrl) ===false)) {
                }elseif($elementObject->tag =="a") {
                    //去掉外站的a标签  去掉pc端的关键词标签
                    $endTag = '</' . $elementObject->tag . '>';
                    self::$rs .= self::filterContent($elementObject);
                }elseif($elementObject->tag == "br"){
                    //如果<br/>标签是父标签的第一一个标签，且前无文本内容，则过滤掉该<br/>标签
                    $filter = false;
                    if(strstr($parentInnerText, "<", true) == "" && $parentInnerText == $elementObject->parent()->innertext()){
                        $filter = true;
                    }
                    //如果<br/>标签是父标签的最后一个标签，且后无文本内容，则过滤掉该<br/>标签
                    if(str_replace(" ", "", strstr($parentInnerText, "<")) == "<br/>"){
                        $filter = true;
                    }

                    //如果br标签是在img之后的，且中间无其它文本内容，则该br标签去除
                    if($elementObject->prev_sibling() != null && in_array($elementObject->prev_sibling()->tag, array("p", "img", "div"))){
                        $prevText = str_replace(array("　"," ", "　　"), "", strstr($parentInnerText, "<", true));
                        if($prevText == ""){
                            $filter = true;
                        }
                    }

                    //如果br标签是在img或p之前的，且中间无其它文本内容，则该br标签去除
                    if($elementObject->next_sibling() != null && in_array($elementObject->next_sibling()->tag, array("p", "img", "div"))){
                        $prevText = str_replace(array("　"," ", "　　"), "", substr(strstr(strstr($parentInnerText, ">"), "<", true), 1));
                        if($prevText == ""){
                            $filter = true;
                        }
                    }

                    if(!$filter){
                        self::$rs .= '<br/>';
                    }
                }else{
                    //如果a标签的父标签还是a，则要去掉当前a标签
                    if($elementObject->tag == "a" && $elementObject->parent()->tag != "a"){
                        if(strpos($elementObject->href, "/person_menu/view/") != false){//是标签链接就加参数
                            $source = strpos($elementObject->href, "source=article") == false ? "?source=article" : "";
                            self::$rs .= '<'.$elementObject->tag.' href="'.$elementObject->href.$source.'">';
                        }elseif(preg_match("|sina(\.com)*\.cn|", $elementObject->href) && strpos($elementObject->href, "cref=cj") == false){
                            $cref = strpos($elementObject->href, '?') === false ? "?cref=cj" : "&cref=cj";
                            self::$rs .= '<'.$elementObject->tag.' href="'.$elementObject->href.$cref.'">';
                        }else{
                            self::$rs .= '<'.$elementObject->tag.' href="'.$elementObject->href.'">';
                        }
                    }elseif($elementObject->tag == "table"){
                        self::$rs .= '<'.$elementObject->tag.' border="1">'; //如果是table标签，则加上border=1
                    }elseif($elementObject->tag == "iframe"){
                        $src = $elementObject->src ? $elementObject->src : $elementObject->getAttribute('data-src');
                        if(strpos($src, "v.qq.com") != false){
                            $src = preg_replace("/width=[^&]+/", "", $src);
                            $src = preg_replace("/height=[^&]+/", "", $src);
                            $src = str_replace('preview.html', 'player.html', $src);
                        }

                        $height = $elementObject->height;
                        $width = $elementObject->width;
                        //$ratio = self::ratio($elementObject->height, $elementObject->width);
                        $ratio = 0.98;
                        if(strpos($_SERVER['HTTP_HOST'], 'sina.com.cn') === false){//pc端和wap端iframe的style不一样
                            self::$rs .= '<iframe src="'.$src.'"  ratio="'.$ratio.'" frameborder=0 allowfullscreen>';
                        }else{
                            self::$rs .= '<iframe src="'.$src.'" height="'.$height.'" width="'.$width.'" style="margin:0 auto;max-width:100%;display: block; width:660px;height:400px;" ratio="'.$ratio.'" frameborder=0 allowfullscreen>';
                        }

                    }elseif($elementObject->tag == "video"){
                        if (!$video_finished && (strpos($elementObject->src, '?unhandled_vid=') != false)) {
                            self::$rs .= '<'.$elementObject->tag.' src="'.$elementObject->src.'"  controls="controls" poster="http://n.sinaimg.cn/finance/caitou/houtai/img/video_making.png">';
                        }else {
                            if ($elementObject->poster) {
                                self::$rs .= '<'.$elementObject->tag.' src="'.$elementObject->src.'"  controls="controls" poster="'.$elementObject->poster.'">';
                            } else {
                                self::$rs .= '<'.$elementObject->tag.' src="'.$elementObject->src.'"  controls="controls">';

                            }                        }
                    }elseif($elementObject->tag == 'section'){
                        self::$rs .= '<div>';
                    }elseif($elementObject->tag != "a"){
                        //如果标签里面包含样式text-align:center 则保留该样式
                        preg_match("/text-align\s*:\s*center/", $elementObject->style, $match);
                        if($match){
                            self::$rs .= '<'.$elementObject->tag.' style="text-align: center;">';
                        }else{
                            self::$rs .= '<'.$elementObject->tag.'>'; //这里把标签的属性去掉了
                        }
                    }
                    self::$rs .= self::filterContent($elementObject, $video_finished);

                    if($elementObject->tag == 'section'){
                        self::$rs .= '</div>';
                    }elseif(!in_array($elementObject->tag, self::$noEndLabel) && !($elementObject->tag == "a" && $elementObject->parent()->tag == "a")){
                        self::$rs .= '</'.$elementObject->tag.'>';
                    }
                }
            }

            $parentInnerText = self::changeNstr(strstr($parentInnerText, "<"), $elementOutertext);

            $elementObject = $elementObject->next_sibling();
        }while($elementObject != null);

        self::$rs .= preg_replace("/\s+/", " ", $parentInnerText);
    }

    //计算iframe的高宽比
    public static function ratio($height, $width){
        $rs = "";
        if($height != "" && $width != ""){
            $rs = round($height/$width, 2);
        }
        return $rs;
    }

    //验证内容是否有效
    public static function validate($content){
        $filterContent = str_replace(array(" ", "&nbsp;", "　　","\xc2\xa0", "<br/>"), "",strip_tags($content));
        if($filterContent != "" || stristr($content, "<img") || stristr($content, "<iframe") || stristr($content, "<video")){
            return true;
        }else{
            return false;
        }
    }

    //把text字符串中的第一个word给去掉
    public static function changeNstr($text,$word){
        $arr = explode($word, $text);
        $rs = "";
        foreach ($arr as $key => $value){
            if($key == 0 || $key == count($arr) - 1){
                $rs .=  $value;
            }else{
                $rs .= $value;
                $rs .= $word;
            }
        }
        return $rs;
    }

    //获取文章摘要(先把富文本转化成纯文本，然后再截取规定字符)
    public static function getSummary($content, $length = 80){
        self::$rs = "";
        //如果文章内容里面没有标签，则原样返回
        if(strpos($content, "<") == false &&  strpos($content, ">") == false){
            return mb_substr($content, 0, $length, "UTF-8");
        }

        $content = str_replace(array("\n", "\r", "\t"), "", $content);
        $content = preg_replace("/(\s*\&nbsp\;\s*)+/", "&nbsp;", $content);

        $html = HtmlDomParser::str_get_html( $content );

        self::summary($html);
        self::$rs = str_replace(array("&nbsp;", " "), "", self::$rs);

        return mb_substr(self::$rs, 0, $length, "UTF-8");
    }

    public static function summary($htmlObject){
        //到达叶子节点，输出内容
        if($htmlObject->firstChild() == null){
            return preg_replace("/\s+/", " ", $htmlObject->innertext());
        }
        $elementObject = $htmlObject->firstChild();

        $parentInnerText = $elementObject->parent()->innertext();

        do{
            self::$rs .= preg_replace("/\s+/", " ", strstr($parentInnerText, "<", true));
            $elementOutertext = $elementObject->outertext();

            self::$rs .= self::summary($elementObject);

            $parentInnerText = self::changeNstr(strstr($parentInnerText, "<"), $elementOutertext);

            $elementObject = $elementObject->next_sibling();
        }while($elementObject != null);

        self::$rs .= preg_replace("/\s+/", " ", $parentInnerText);
    }

    /**
     * 判断字符串中是否存在$needle(array)中的字符串
     * @param     $haystack
     * @param     $needle
     * @param int $offset
     *
     * @return bool
     */
    public static function strposa($haystack, $needle, $offset=0) {
        if(!is_array($needle)) $needle = array($needle);
        foreach($needle as $query) {
            if(strpos($haystack, $query, $offset) !== false) return true;
        }
        return false;
    }
}

