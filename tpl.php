<?PHP
$strFileName    = $argv[1];
$arrLines       = file($strFileName);
if (empty($arrLines)) {
    print 'empty file';
    return;
}


$arrData    = array();
$intIndex   = $intIndex2 = -1;

$intLength  = count($arrLines);
$strOldKey  = '';

$arrKeys    = array (
    'title',
    'choice',
    'ex'
);

for($i=0; $i<$intLength; $i++) {

    $strLine = trim($arrLines[$i]);

    if (strlen($strLine) == 0) {
        continue;
    }

    if (isHead1($strLine)) {
        $intIndex++;
        $arrData[$intIndex]['header'] = $strLine;

        $strOldKey = '';

        continue;
    }


    if (isHead2($strLine)) {

        if ($strOldKey == ''  || $strOldKey == 'ex') {
            $intIndex2++;
        }
        foreach ($arrKeys as $kk=>$strKey) {
            if (empty($arrData[$intIndex]['body'][$intIndex2][$strKey])) {
                $arrData[$intIndex]['body'][$intIndex2][$strKey] = $strLine;
                $strOldKey = $strKey;
                break;
            }
        }

        if (!empty($strOldKey)) {
            continue;
        }


        print_r($arrData);
        die(sprintf('%d:%s, logic error', $i, $strLine));

    }

    // print $strOldKey.PHP_EOL;
    if ($strOldKey == 'title') {
        $arrData[$intIndex]['body'][$intIndex2]['answers'][] = $strLine;
    } else {
        $arrData[$intIndex]['body'][$intIndex2][$strOldKey] .= (PHP_EOL.$strLine);
    }

}

$i = 1;
foreach ($arrData as $k=>$row) {
    print '[data='.$i.']'.trim($row['header'], '=');
    print PHP_EOL.'[img='.$i.'.png]'.PHP_EOL;
    print '[/data]'.PHP_EOL;
    print PHP_EOL;

    $i++;
}

$i = 1;
$j = 1;
foreach ($arrData as $k=>$row) {
    foreach ($row['body'] as $kk=>$vv) {
        print $j.'.[data='.$i.']'. trimTitle($vv['title']);
        foreach ($vv['answers'] as $kkk=>$vvv) {
            print PHP_EOL.trim($vvv);
        }
        print PHP_EOL;
        print '[ex]'.PHP_EOL;
        print trimEx($vv['ex']);
        print PHP_EOL;
        print '[choice]'.PHP_EOL;
        print trimChoice($vv['choice']);
        print PHP_EOL;
        print PHP_EOL;
        $j ++;

    }
    $i ++;

}

function trimChoice($str) {
    $str = preg_replace('#[^abcdefg]#i', '', trim($str, '='));
    return $str;
}

function trimEx($str) {
    $str = trim($str);
    $str = preg_replace('#故选[abcdefg]#i', '', trim($str, '='));
    $str = preg_replace('#选[abcdefg]项。$#', '', $str);
    $str = str_replace('，。', '。', $str);
    $str = str_replace('。。', '。', $str);
    $str = preg_replace('#，$#i', '。', $str);
    $str = preg_replace('#^解析[：]*#', '', $str);
    return $str;
}

function trimTitle($str) {
    $str = preg_replace('#^\d+[ .．、]*#', '', trim($str, '='));
    $str = preg_replace('#\( +\)$#', '', $str);
    $str = preg_replace('#（ +）$#', '', $str);
    $str = trim($str);
    return $str;
}

function isHead1($str) {
    if (preg_match('#^=[^=]#' ,$str, $arrMatch)) {
        return true;
    }

    return false;
}

function isHead2($str) {
    if (preg_match('#^==#' ,$str, $arrMatch)) {
        return true;
    }

    return false;
}
