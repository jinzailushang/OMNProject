<?php

/**
 * 数组转xml
 * @copyright 2015-07-12, jack
 * @package    library
 *
 * */
defined('InOmniWL') or exit('Access Invalid!');

class ArrayToXML {

    public static $cilk = 1;

    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML
     */
    public static function toXml($data, $rootNodeName = 'data', $xml = null, $str = '') {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1) {
            ini_set('zend.ze1_compatibility_mode', 0);
        }

        if ($xml == null) {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
        }

        // loop through the data passed in.
        foreach ($data as $key => $value) {
            // no numeric keys in our xml please!
            if (is_numeric($key)) {
                // make string key...
                if (empty($str)) {
                    $key = 'unknownNode_' . (string) $key;
                } else {
                    $key = $str;
                }
            }

            // replace anything not alpha numeric
            //$key = preg_replace('/[^a-z]/i', '', $key);
            // if there is another array found recrusively call this function
            if (is_array($value)) {

                $node = $xml->addChild($key);

                // recrusive call.
                ArrayToXML::toXml($value, $rootNodeName, $node, $str);
            } else {
                // add single node.
                $value = xmlencode($value);

                $xml->addChild($key, $value);
            }
        }
        // pass back as string. or simple xml object if you want!
        return $xml->asXML();
    }

    /**
     * 数组转XML
     * @copyright 2015-07-12, jack
     * @param string $root
     * @param array $arr
     * @param string $arr
     * @param string $item
     * @return string|boolean
     */
    public static function toXmlTwo($root = 'root', $arr, $dom = 0, $item = 0) {
        if (!$dom) {
            $dom = new DOMDocument("1.0", "utf-8");
        }
        if (!$item) {
            if (is_array($root)) {
                $item = $dom->createElement($root['key']);
                $item->setAttribute($root['val']['key'], $root['val']['val']);
                $dom->appendChild($item);
            } else {
                $item = $dom->createElement($root);
                $dom->appendChild($item);
            }
        }
        foreach ($arr as $key => $val) {
            $keys = '';
            if (is_string($key)) {
                $keys = explode('_', $key);
                $keys = $keys[0];
            } else {
                $keys = 'item';
            }
            $itemx = $dom->createElement($keys);
            $item->appendChild($itemx);
            if (!is_array($val)) {
                $text = $dom->createTextNode($val);
                $itemx->appendChild($text);
            } else {
                ArrayToXML::toXmlTwo($root, $val, $dom, $itemx);
            }
        }
        return $dom->saveXML();
    }

    /**
     * 数组转XML
     * @copyright 2016-01-29, jack
     * @param string $root
     * @param array $arr
     * @param string $arr
     * @param string $item
     * @return string|boolean
     */
    public static function toXmlThree($root = 'root', $arr, $dom = 0, $item = 0) {
        if (!$dom) {
            $dom = new DOMDocument("1.0", "utf-8");
        }
        if (!$item) {
            if (is_array($root)) {
                $item = $dom->createElement($root['key']);
//                $item->setAttribute($root['val']['key'], $root['val']['val']);
                $dom->appendChild($item);
            } else {
                $item = $dom->createElement($root);
                $dom->appendChild($item);
            }
        }
        foreach ($arr as $key => $val) {
            $keys = '';
            if (is_string($key)) {
                $keys = explode('~', $key);
                $keys = $keys[0];
            } else {
                $keys = 'item';
            }
            $itemx = $dom->createElement($keys);
            $item->appendChild($itemx);
            if (!is_array($val)) {
                $text = $dom->createTextNode($val);
                $itemx->appendChild($text);
            } else {
                ArrayToXML::toXmlThree($root, $val, $dom, $itemx);
            }
        }
        return $dom->saveXML();
    }
}

//        $a = 'mo';
//        $a = array(
//            'key' => 'mo',
//            'val' => array(
//                "key" => "version",
//                "val" => "1.0.0"
//            )
//        );
//        $xml_data = ArrayToXML::toXmlTwo($a, $data);