function fuzzyChain($search,$where,$skipFirst=0) {
     
    $spaces="\s";
    $sWords=preg_split("/[".$spaces."]+/iu",$search);
    $wWords=preg_split("/[".$spaces."]+/iu",$where);
    $OnlyRu = "БбвГгДдЁёЖжЗзИиЙйЛлмнПптУФфЦцЧчШшЩщЪъЫыЬьЭэЮюЯя";
    $OnlyEn = "DdFfGghIiJjLlNQqRrSstUVvWwYZz";
    $Rus = array("А","а","В","Е","е","К","к","М","Н","О","о","Р","р","С","с","Т","у","Х","х","З","О","1","т","и","а","@","п","ь");
    $Eng = array("A","a","B","E","e","K","k","M","H","O","o","P","p","C","c","T","y","X","x","3","0","i","m","u","@","a","n","b");
    $RRus=array();
    $REng=array();
    foreach ($Rus as $k=>$i) $RRus[$k]="/".$i."/u";
    foreach ($Eng as $k=>$i) $REng[$k]="/".$i."/u";
    $wordPos=0;
    $chain=0;

    foreach($wWords as $wpos=>$w) {
        $w=mb_strtolower($w, 'UTF-8');
        $s=mb_strtolower($sWords[$chain], 'UTF-8');
        if(!preg_match("/[".$OnlyEn."]/u",$w)) {
            $w=preg_replace($REng,$Rus,$w);
            $s=preg_replace($REng,$Rus,$s);
        } else {
            $w=preg_replace($RRus,$Eng,$w);
            $s=preg_replace($RRus,$Eng,$s);
        }
        $found=0;
        $d=0;
        $pos=0;
        $len=min(mb_strlen($w),mb_strlen($s));
        for ($i = 0; $i < $len-$skipFirst; $i++) {
            if (mb_substr($s,$skipFirst+$d,1)===mb_substr($w,$skipFirst+$i,1)) {
                if($d==0) $pos=$i;
                $d++;
            } else $d=0;

            if (mb_substr($s,$skipFirst,$len-$pos)===mb_substr($w,$skipFirst+$pos,$len-$pos) || $d>=$len/2) {
                $found=1;break;
            }
        }
        if ($found===1) {
            $chain++;
            if ($chain===1) $wordPos=$wpos;
            if ($chain===count($sWords)) break;
        } else $chain=0;
    }
    if ($chain===count($sWords)) return array($wordPos+1,$wWords[$wordPos]);
    else return false;
}

fuzzyChain($search, $string); // (что искать, где искать) 
