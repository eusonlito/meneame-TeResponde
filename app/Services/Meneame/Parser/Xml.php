<?php
namespace App\Services\Meneame\Parser;

use DOMDocument;
use Exception;
use stdClass;

class Xml
{
    public static function getDOMDocument()
    {
        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $xml->recover = true;
        $xml->preserveWhiteSpace = true;
        $xml->substituteEntities = false;

        return $xml;
    }

    public static function fromFile($file)
    {
        return self::fromString(file_get_contents($file));
    }

    public static function fromString($string)
    {
        $xml = self::getDOMDocument();

        $xml->loadXML($string);

        return array(
            $xml->documentElement->tagName => self::nodeToArray($xml->documentElement)
        );
    }

    public static function toString(array $array)
    {
        $xml = self::getDOMDocument();

        $xml->appendChild(self::arrayToNode($xml, 'resources', $array['resources']));

        return $xml->saveXML();
    }

    public static function stringToObject($xml)
    {
        $xml = self::fromString('<root>'.trim($xml).'</root>');

        $item = new stdClass;

        foreach ($xml['root'] as $key => $value) {
            $value = $value[0]['@value'];

            if (!is_array($value)) {
                $item->$key = $value;
            } elseif (isset($value['@cdata'])) {
                $item->$key = $value['@cdata'];
            }
        }

        $item->checksum = md5(serialize($item));

        return $item;
    }

    private static function nodeToArray($node)
    {
        if ($node->nodeType === XML_CDATA_SECTION_NODE) {
            return array('@cdata' => trim($node->textContent));
        }

        if ($node->nodeType === XML_TEXT_NODE) {
            return trim($node->textContent);
        }

        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return array();
        }

        $output = array();

        if (in_array((string)$node->tagName, array('string', 'item'), true)) {
            $output['@value'] = '';

            for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                $output['@value'] .= self::decodeString($node->ownerDocument->saveXml($node->childNodes->item($i)));
            }
        } else {
            for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                $child = $node->childNodes->item($i);
                $v = self::nodeToArray($child);

                if (isset($child->tagName)) {
                    $t = $child->tagName;

                    if (!isset($output[$t])) {
                        $output[$t] = array();
                    }

                    $output[$t][] = $v;
                } elseif ($v !== '') {
                    if (!isset($output['@value'])) {
                        $output['@value'] = '';
                    }

                    if (is_string($v)) {
                        $output['@value'] .= $v;
                    } else {
                        $output['@value'] = $v;
                    }
                }
            }
        }

        if (empty($output)) {
            $output = '';
        }

        if (!is_array($output)) {
            $output = array('@value' => $output);
        }

        if ($node->attributes->length) {
            $output['@attributes'] = array();

            foreach ($node->attributes as $name => $node) {
                $output['@attributes'][$name] = (string)$node->value;
            }
        }

        return $output;
    }

    private static function arrayToNode($xml, $name, $array)
    {
        $node = $xml->createElement($name);

        if (!is_array($array)) {
            return self::nodeValue($node, $array);
        }

        if (isset($array['@attributes'])) {
            foreach ($array['@attributes'] as $key => $value) {
                if (!self::isValidTagName($key)) {
                    throw new Exception('[Array2XML] Illegal character in attribute name. attribute: '.$key.' in node: '.$value);
                }

                $node->setAttribute($key, self::bool2str($value));
            }
        }

        if (isset($array['@cdata'])) {
            $node->appendChild($xml->createCDATASection(self::bool2str($array['@cdata'])));
        }

        if (isset($array['@xml'])) {
            $node->appendChild($xml->createDocumentFragment()->appendXML($array['@xml']));
        }

        if (isset($array['@value'])) {
            $node = self::nodeValue($node, $array['@value']);
        }

        foreach ($array as $key => $value) {
            if (strstr($key, '@')) {
                continue;
            }

            if (!self::isValidTagName($key)) {
                var_dump('[Array2XML] Illegal character in tag name. tag: '.$key.' in node: '.$value);
                var_dump($name, $array);
                continue;
            }

            if (is_array($value) && is_numeric(key($value))) {
                foreach ($value as $v) {
                    $node->appendChild(self::arrayToNode($xml, $key, $v));
                }
            } else {
                $node->appendChild(self::arrayToNode($xml, $key, $value));
            }
        }

        return $node;
    }

    private static function nodeValue($node, $value)
    {
        $node->appendChild($node->ownerDocument->createTextNode(self::decodeString(self::bool2str($value))));

        return $node;
    }

    private static function decodeString($string)
    {
        $decode = htmlspecialchars_decode($string, ENT_HTML401);

        while ($string !== $decode) {
            $decode = htmlspecialchars_decode($string = $decode, ENT_HTML401);
        }

        return $decode;
    }

    private static function bool2str($v)
    {
        return ($v === true) ? 'true' : (($v === false) ? 'false' : $v);
    }

    private static function isValidTagName($tag)
    {
        return preg_match('/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i', $tag, $matches) && ($matches[0] === $tag);
    }
}
