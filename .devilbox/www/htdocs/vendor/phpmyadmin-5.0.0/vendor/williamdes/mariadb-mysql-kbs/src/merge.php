<?php
declare(strict_types = 1);
$dataDir = __DIR__."/../data/";
$files   = glob("$dataDir*.json");

/**
 * Compare 2 variables
 *
 * @param mixed $var1 First
 * @param mixed $var2 Second
 * @return bool
 */
function JSONcompare($var1, $var2): bool
{
    return json_encode($var1) === json_encode($var2);
}

/**
 * Fix key=range
 *
 * @param stdClass $current Current value
 * @param stdClass $cache   Cache value
 * @return void
 */
function fixRange(stdClass &$current, stdClass &$cache): void
{
        global $newData, $key, $nbrConflictsSolved;
    $currentHasFrom = isset($current->from);
    $cacheHasFrom   = isset($cache->from);
    $currentHasTo   = isset($current->to);
    $cacheHasTo     = isset($cache->to);
    if ($currentHasFrom === false && $cacheHasFrom === true) {
        $current->from = $cache->from;
        if (JSONcompare($cache, $current)) {
            $newData->$key = $current;
            if (JSONcompare($current, $cache)) {
                $nbrConflictsSolved++;
            } else {
                fixRange($current, $cache);
            }
        }
    } elseif ($currentHasFrom === true && $cacheHasFrom === false) {
        $cache->from = $current->from;
        if (JSONcompare($cache, $current)) {
            $newData->$key = $cache;
            if (JSONcompare($current, $cache)) {
                $nbrConflictsSolved++;
            } else {
                fixRange($current, $cache);
            }
        }
    } elseif ($currentHasTo === false && $cacheHasTo === true) {
        $current->to = $cache->to;
        if (JSONcompare($cache, $current)) {
            $newData->$key = $current;
            if (JSONcompare($current, $cache)) {
                $nbrConflictsSolved++;
            } else {
                fixRange($current, $cache);
            }
        }
    } elseif ($currentHasTo === true && $cacheHasTo === false) {
        $cache->to = $current->to;
        if (JSONcompare($cache, $current)) {
            $newData->$key = $cache;
            if (JSONcompare($current, $cache)) {
                $nbrConflictsSolved++;
            } else {
                fixRange($current, $cache);
            }
        }
    } elseif (( $currentHasFrom === true && $currentHasFrom === true )
        && ( $cache->from === $current->from )
    ) {
        $onlyFrom       = new stdClass();
        $onlyFrom->from = $current->from;
        $newData->$key  = $onlyFrom;
        $nbrConflictsSolved++;
        echo '[WARN] conflict range to - '.json_encode($cache).' - '.json_encode($current).PHP_EOL;
    } else {
        echo '[ERROR] conflict range - '.json_encode($cache).' - '.json_encode($current).PHP_EOL;
    }
}

$variables = array();

$nbr                = 0;
$nbrConflicts       = 0;
$nbrConflictsSolved = 0;
foreach ($files as $file) {
    $fileData = json_decode(file_get_contents($file));
    if (isset($fileData->data) === false) {
        continue;
    } else {
        $data = $fileData->data;
    }

    foreach ($data as $doc) {
        $identifier = $doc->name;
        if (isset($identifier)) {
            if (isset($variables[$identifier]) === false) {
                if (isset($doc->ids) === false) {
                    $doc->ids = array();
                }
                $variables[$identifier] = $doc;
                $kbEntry                = new stdClass();
                $kbEntry->anchor        = $doc->id;
                $kbEntry->url           = $fileData->url;
                $doc->ids[]             = $kbEntry;
                unset($doc->id);
            } else {
                if (isset($doc->ids) === false) {
                    $doc->ids = array();
                }
                $kbEntry         = new stdClass();
                $kbEntry->anchor = $doc->id;
                $kbEntry->url    = $fileData->url;
                $doc->ids[]      = $kbEntry;
                unset($doc->id);
                //echo $identifier." duplicate ! in ".str_replace($dataDir, "", $file).PHP_EOL;
                $newData = new stdClass();
                foreach ((array) $doc as $key => $val) {
                    if (isset($variables[$identifier]->$key)) {
                        $cacheValue = $variables[$identifier]->$key;
                        $docValue   = $doc->$key;
                        if ((                            strtoupper(json_encode($cacheValue)) === strtoupper(json_encode($docValue)))
                            && (                            json_encode($cacheValue) !== json_encode($docValue))
                        ) {
                            $nbrConflicts++;
                            $nbrConflictsSolved++;
                            //echo 'upper conflict '.$key.' - '.json_encode($cacheValue).' - '.json_encode($docValue).PHP_EOL;
                            $docValue = strtoupper(json_encode($docValue));
                        } elseif (json_encode($cacheValue) !== json_encode($docValue)) {
                            $nbrConflicts++;
                            if ($key === "type") {
                                $realTypes = array(
                                    "string",
                                    "boolean",
                                    "integer",
                                    "numeric",
                                    "enumeration",
                                    "set",
                                    "directory name",
                                    "file name",
                                    "byte"
                                );
                                if (in_array($cacheValue, $realTypes)// original
                                    && in_array($docValue, $realTypes) === false// dupe
                                ) {//original type valid
                                    echo 'original type valid : '.$cacheValue.PHP_EOL;
                                } elseif (in_array($cacheValue, $realTypes) === false// original
                                    && in_array($docValue, $realTypes)// dupe
                                ) {// dupe type valid
                                    $newData->$key = $docValue;
                                    //echo 'dupe type valid : '.$docValue.PHP_EOL;
                                    $nbrConflictsSolved++;
                                } else {
                                    if ((                                        json_encode($cacheValue) === '"numeric"'
                                        && json_encode($docValue) === '"integer"')
                                        || (                                        json_encode($cacheValue) === '"integer"'
                                        && json_encode($docValue) === '"numeric"')
                                    ) {// numeric vs integer
                                        //echo "integer wins !".PHP_EOL;
                                        $newData->$key = "integer";
                                        $nbrConflictsSolved++;
                                    } else {
                                        echo 'type conflict : '.json_encode($cacheValue).' - '.json_encode($docValue).PHP_EOL;
                                    }
                                }
                            } elseif ($key === "ids") {
                                /*if (isset($newData->ids) === false) {
                                    $newData->ids = array();
                                }*/
                                $newData->ids = array_merge($cacheValue, $docValue);
                                /*$source = array("_", "option-mysqld-", "sysvar-", );
                                $destination = array("-", "","");
                                if (// Replace prefix to see if same id
                                    str_replace($source, $destination, $docValue)
                                    ===
                                    str_replace($source, $destination, $cacheValue)
                                ) {
                                    $newData->$key = str_replace($source, $destination, $docValue);
                                    $nbrConflictsSolved++;// TODO: check if good idea
                                } else {
                                    echo '[ERROR] conflict id : '
                                    .json_encode($cacheValue)
                                    .' - '
                                    .json_encode($docValue)
                                    .' - '
                                    .str_replace($source, $destination, $docValue)
                                    .' - '
                                    .str_replace($source, $destination, $cacheValue)
                                    .PHP_EOL;
                                }*/
                            } elseif ($key === "default") {
                                $originalValues    = array("on", "off", "ON", "OFF", "true", "false", "TRUE", "FALSE");
                                $destinationValues = array("1", "0", "1", "0", "1", "0", "1", "0");
                                $docValue          = str_replace($originalValues, $destinationValues, $docValue);
                                $cacheValue        = str_replace($originalValues, $destinationValues, $cacheValue);
                                if ($docValue === $cacheValue) {
                                    $newData->$key = $docValue;
                                    $nbrConflictsSolved++;
                                } else {
                                    if (is_array($cacheValue) === false
                                        && is_array($docValue) === false
                                    ) {
                                        if (floatval($cacheValue) === floatval($docValue)) {
                                            $newData->$key = $docValue;
                                            $nbrConflictsSolved++;
                                        } else {
                                            echo '[ERROR] conflict default, not array : '.json_encode($cacheValue).' - '.json_encode($docValue).PHP_EOL;
                                        }
                                    } else {
                                        echo '[ERROR] conflict default : '.json_encode($cacheValue).' - '.json_encode($docValue).PHP_EOL;
                                    }
                                }
                            } elseif ($key === "validValues") {
                                if (is_array($cacheValue) === false) {
                                    $cacheValue = array($cacheValue);
                                }
                                if (is_array($docValue) === false) {
                                    $docValue = array($docValue);
                                }
                                $intersecValidValues = array_intersect($docValue, $cacheValue);
                                if (count($intersecValidValues) === count($docValue)
                                    && count($intersecValidValues) === count($cacheValue)
                                ) {// No variables were lost in process
                                    $newData->$key = $intersecValidValues;
                                    $nbrConflictsSolved++;
                                } elseif (array_values(array_diff($docValue, $cacheValue)) === array("32768","65536")
                                ) {// Missing translation (in bytes) for 32k and 64k
                                    $intersecValidValues[] = "32768";
                                    $intersecValidValues[] = "65536";
                                    $newData->$key         = array_values($intersecValidValues);
                                    $nbrConflictsSolved++;
                                } elseif (strtoupper(json_encode(ksort($docValue))) === strtoupper(json_encode(ksort($cacheValue)))
                                ) {// uppercase / lowercase
                                    ksort($cacheValue);
                                    $newData->$key = json_decode(json_encode($cacheValue));
                                    $nbrConflictsSolved++;
                                } else {
                                    echo '[ERROR] conflict validValues : '
                                    .json_encode($cacheValue)
                                    .' - '
                                    .json_encode($docValue)
                                    .' - '
                                    .json_encode($intersecValidValues)
                                    .' - '
                                    .json_encode(array_values(array_diff($docValue, $cacheValue))).PHP_EOL;
                                }
                            } elseif ($key === "cli") {
                                $replaceSource      = array("file", "dir_name", "-- ", "_");
                                $replaceDest        = array("path", "path", "--", "-");
                                $replacedDocValue   = str_replace($replaceSource, $replaceDest, $docValue);
                                $replacedCacheValue = str_replace($replaceSource, $replaceDest, $cacheValue);
                                if (str_replace($replaceSource, $replaceDest, $docValue) === str_replace($replaceSource, $replaceDest, $cacheValue)
                                ) {//Try replacements
                                    $newData->$key = str_replace($replaceSource, $replaceDest, $docValue);
                                    $nbrConflictsSolved++;
                                } elseif (str_replace("--", "", $docValue) === str_replace("--", "", $cacheValue)
                                ) {// Doc not well formated, missing -- before cli command
                                    $newData->$key = "--".str_replace("--", "", $docValue);
                                    $nbrConflictsSolved++;
                                } elseif (strlen(str_replace(str_replace("#", "", $docValue), "", $cacheValue)) !== strlen($cacheValue)
                                ) {// More precise doc, value hint, eg: --blablabla={0|1}
                                    $newData->$key = $cacheValue;
                                    $nbrConflictsSolved++;
                                } elseif (strlen(
                                    str_replace(
                                        str_replace(
                                            array("#"),
                                            array(""),
                                            $replacedDocValue
                                        ), "", $replacedCacheValue
                                    )
                                ) !== strlen($replacedCacheValue)
                                ) {// More precise doc, value hint, eg: --blablabla={0|1} using replaced values
                                    $newData->$key = $replacedCacheValue;
                                    $nbrConflictsSolved++;
                                } elseif (strlen(
                                    str_replace(
                                        str_replace(
                                            array("#"),
                                            array(""),
                                            $replacedCacheValue
                                        ), "", $replacedDocValue
                                    )
                                ) !== strlen($replacedDocValue)
                                ) {// More precise doc, value hint, eg: --blablabla={0|1} using replaced values, reversed: cache/doc
                                    $newData->$key = $replacedDocValue;
                                    $nbrConflictsSolved++;
                                } elseif (strlen(str_replace(str_replace(array("#"), array(""), $docValue), "", $cacheValue)) !== strlen($cacheValue)
                                ) {// More precise doc, value hint, eg: --blablabla={0|1}
                                    $newData->$key = $cacheValue;
                                    $nbrConflictsSolved++;
                                } elseif (strlen(str_replace($cacheValue, "", $docValue)) !== strlen($docValue)
                                ) {// contained in cache
                                    $newData->$key = $docValue;
                                    $nbrConflictsSolved++;
                                } elseif (strlen(str_replace($docValue, "", $cacheValue)) !== strlen($cacheValue)
                                ) {// contained in conflict
                                    $newData->$key = $cacheValue;
                                    $nbrConflictsSolved++;
                                } else {
                                    echo '[ERROR] conflict cli : cacheValue: '
                                    .json_encode($cacheValue)
                                    .' - docValue: '
                                    .json_encode($docValue)
                                    .' - docValue: '
                                    .str_replace($replaceSource, $replaceDest, $docValue)
                                    .' - cacheValue: '
                                    .str_replace($replaceSource, $replaceDest, $cacheValue)
                                    .PHP_EOL;
                                }
                            } elseif ($key === "range") {
                                $current = $docValue;
                                $cache   = $cacheValue;
                                fixRange($current, $cache);
                            } else {
                                echo '[ERROR] conflict '.$key.' + '.$identifier.' - '.json_encode($cacheValue).' - '.json_encode($docValue).PHP_EOL;
                            }
                        } else {
                            $newData->$key = $val;
                        }
                    } else {
                        $newData->$key = $val;
                    }
                }
                //print_r($newData);
                $variables[$identifier] = $newData;
            }
        }
    }
    $nbr += count($data);
}
echo "NBR: ".$nbr.PHP_EOL;
echo "NBR_UNIQUE: ".count($variables).PHP_EOL;
echo "NBR_CONFLICTS: ".$nbrConflicts.PHP_EOL;
echo "NBR_CONFLICTS_SOLVED: ".$nbrConflictsSolved.PHP_EOL;
echo "NBR_CONFLICTS_REMAINING: ".($nbrConflicts - $nbrConflictsSolved).PHP_EOL;

$fileOut          = new stdClass();
$fileOut->vars    = json_decode(json_encode($variables));
$fileOut->version = 1.0;

$md = "# Variables and options".PHP_EOL;
foreach ($fileOut->vars as $id => $doc) {
    //$md .= "## ".$doc->url.PHP_EOL;
    $md .= "## ".$doc->name.PHP_EOL;
    $md .= "|name|value|".PHP_EOL;
    $md .= "|----|-----|".PHP_EOL;
    if (isset($doc->name)) {
        $md .= "|Name|`$doc->name`|".PHP_EOL;
    }
    if (isset($doc->cli)) {
        $md .= "|Command line|`$doc->cli`|".PHP_EOL;
    }
    if (isset($doc->type)) {
        $md .= "|Type of variable|`$doc->type`|".PHP_EOL;
    }
    if (isset($doc->scope)) {
        $md .= "|Scope|`".implode("`, `", $doc->scope)."`|".PHP_EOL;
    }
    if (isset($doc->default)) {
        $md .= "|Default value|`$doc->default`|".PHP_EOL;
    }
    if (isset($doc->dynamic)) {
        $md .= "|Dynamic|`".( ($doc->dynamic) ? 'true' : 'false')."`|".PHP_EOL;
    }
    if (empty($doc->validValues) === false) {
        $md .= "|Valid value(s)|`".implode("`, `", $doc->validValues)."`|".PHP_EOL;
    }
    if (isset($doc->range)) {
        $r = '';
        if (isset($doc->range->from)) {
            $r .= "from: `".$doc->range->from."`";
        }

        if (isset($doc->range->to)) {
            if (isset($doc->range->from)) {
                $r .= " ";
            }
            $r .= "to: `".$doc->range->to."`";
        }
        $md .= "|Range|$r|".PHP_EOL;
    }
    $md .= PHP_EOL;
    $md .= "### Documentation(s)".PHP_EOL;
    $md .= "|source|anchor name|".PHP_EOL;
    $md .= "|------|----|".PHP_EOL;
    foreach ($doc->ids as &$kbEntry) {
        $matchs = array();
        preg_match("/:\/\/([a-z.]+)/i", $kbEntry->url, $matchs);
        $md .= "|$matchs[1]|[$kbEntry->anchor]($kbEntry->url#$kbEntry->anchor)|".PHP_EOL;
    }
    $md .= PHP_EOL;
}

file_put_contents(__DIR__."/../dist/merged-raw.md", $md.PHP_EOL);

file_put_contents(__DIR__."/../dist/merged-raw.json", json_encode($fileOut, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).PHP_EOL);

$fileOut->urls = array();

foreach ($fileOut->vars as $id => $doc) {
    foreach ($doc->ids as &$kbEntry) {
        $urlId = array_search($kbEntry->url, $fileOut->urls, true);
        if ($urlId === false) {
            $urlId = array_push($fileOut->urls, $kbEntry->url);
        }
        $kbEntry->url = $urlId;
        $kbEntry      = "$urlId#$kbEntry->anchor";
    }
}
$fileOut->version = 1.0;
file_put_contents(__DIR__."/../dist/merged-slim.json", json_encode($fileOut, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).PHP_EOL);

$fileOut->vars     = json_decode(json_encode($variables));
$fileOut->types    = array( "MYSQL" => 1, "MARIADB" => 2 );
$fileOut->varTypes = array(
    "string" => 1,
    "boolean" => 2,
    "integer" => 3,
    "numeric" => 4,
    "enumeration" => 5,
    "set" => 6,
    "directory name" => 7,
    "file name" => 8,
    "byte" => 9
);
foreach ($fileOut->vars as $id => &$doc) {
    $data = new stdClass();
    if (isset($doc->dynamic)) {
        $data->d = $doc->dynamic;
    }
    if (isset($doc->type)) {
        $data->t = $fileOut->varTypes[$doc->type];
    }
    $data->a = array();
    foreach ($doc->ids as &$kbEntry) {
        $urlId = array_search($kbEntry->url, $fileOut->urls, true);
        if ($urlId === false) {
            $urlId = array_push($fileOut->urls, $kbEntry->url);
        }
        $kbEntryMin    = new stdClass();
        $kbEntryMin->a = $kbEntry->anchor;

        $kbEntryMin->u = $urlId;
        if (preg_match("/mysql\.com/", $kbEntry->url)) {
            $kbEntryMin->t = $fileOut->types["MYSQL"];
        } elseif (preg_match("/mariadb\.com/", $kbEntry->url)) {
            $kbEntryMin->t = $fileOut->types["MARIADB"];
        }
        $data->a[] = $kbEntryMin;
    }
    $doc = $data;
}
$fileOut->types    = array_flip($fileOut->types);
$fileOut->varTypes = array_flip($fileOut->varTypes);
$fileOut->version  = 1.0;
file_put_contents(__DIR__."/../dist/merged-ultraslim.json", json_encode($fileOut, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).PHP_EOL);

$content = '<?php'.PHP_EOL.'$data = '.json_encode($fileOut, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).';'.PHP_EOL;

$content = str_replace(
    array("{", "}", ":"),
    array("[", "]", "=>"),
    $content
);

file_put_contents(__DIR__."/../dist/merged-ultraslim.php", $content);
echo "Files merged !".PHP_EOL;
