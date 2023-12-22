<?php

namespace USPS;

/*
 * Array2XML: A class to convert array in PHP to XML
 * It also takes into account attributes names unlike SimpleXML in PHP
 * It returns the XML in form of DOMDocument class for further manipulation.
 * It throws exception if the tag name or attribute name has illegal chars.
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes
 * License: Apache License 2.0
 *          http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.1 (10 July 2011)
 * Version: 0.2 (16 August 2011)
 *          - replaced htmlentities() with htmlspecialchars() (Thanks to Liel Dulev)
 *          - fixed an edge case where root node has a false/null/0 value. (Thanks to Liel Dulev)
 * Version: 0.3 (22 August 2011)
 *          - fixed tag sanitize regex which didn't allow tagnames with single character.
 * Version: 0.4 (18 September 2011)
 *          - Added support for CDATA section using @cdata instead of @value.
 * Version: 0.5 (07 December 2011)
 *          - Changed logic to check numeric array indices not starting from 0.
 * Version: 0.6 (04 March 2012)
 *          - Code now doesn't @cdata to be placed in an empty array
 * Version: 0.7 (24 March 2012)
 *          - Reverted to version 0.5
 *
 * Usage:
 *       $xml = Array2XML::createXML('root_node_name', $php_array);
 *       echo $xml->saveXML();
 */

use DOMDocument;
use DOMNode;
use Exception;

class XMLParser
{
    private static ?DOMDocument $xml = null;
    private static string $encoding = 'UTF-8';

    /**
     * Initialize the root XML node [optional].
     */
    public static function init($version = '1.0', string $encoding = 'UTF-8', bool $format_output = true): void
    {
        self::$xml = new DOMDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
        self::$encoding = $encoding;
    }

    /**
     * Convert an Array to XML.
     * @throws Exception
     */
    public static function &createXML(string $node_name, mixed $arr = []): ?DOMDocument
    {
        $xml = self::getXMLRoot();
        $xml->appendChild(self::convert($node_name, $arr));

        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $xml;
    }

    /**
     * Convert an Array to XML.
     * @throws Exception
     */
    private static function &convert(string $node_name, mixed $arr = []): DOMNode
    {

        //print_arr($node_name);
        $xml = self::getXMLRoot();
        $node = $xml->createElement($node_name);
        // get the attributes first.;
        if (isset($arr['@attributes'])) {
            foreach ($arr['@attributes'] as $key => $value) {
                if (!self::isValidTagName($key)) {
                    throw new Exception('[XMLParser] Illegal character in attribute name. attribute: '.$key.' in node: '.$node_name);
                }
                $node->setAttribute($key, htmlspecialchars((string) self::bool2str($value), ENT_QUOTES, self::$encoding));
            }
            unset($arr['@attributes']); //remove the key from the array once done.
        }
        if (isset($arr['@value'])) {
            $node->appendChild($xml->createTextNode(htmlspecialchars((string) self::bool2str($arr['@value']), ENT_QUOTES, self::$encoding)));
            unset($arr['@value']);    //remove the key from the array once done.
            //return from recursion, as a note with value cannot have child nodes.
            return $node;
        } elseif (isset($arr['@cdata'])) {
            $node->appendChild($xml->createCDATASection(self::bool2str($arr['@cdata'])));
            unset($arr['@cdata']);    //remove the key from the array once done.
            //return from recursion, as a note with cdata cannot have child nodes.
            return $node;
        }

        //create subnodes using recursion
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                if (!self::isValidTagName($key)) {
                    throw new Exception('[XMLParser] Illegal character in tag name. tag: ' . $key . ' in node: ' . $node_name);
                }
                if (is_array($value) && is_numeric(key($value))) {
                    // MORE THAN ONE NODE OF ITS KIND;
                    // if the new array is numeric index, means it is array of nodes of the same kind
                    // it should follow the parent key name
                    foreach ($value as $v) {
                        $node->appendChild(self::convert($key, $v));
                    }
                } else {
                    // ONLY ONE NODE OF ITS KIND
                    $node->appendChild(self::convert($key, $value));
                }
                unset($arr[$key]); //remove the key from the array once done.
            }
        }

        // after we are done with all the keys in the array (if it is one)
        // we check if it has any text value, if yes, append it.
        if (!is_array($arr)) {
            $node->appendChild($xml->createTextNode(htmlspecialchars((string) self::bool2str($arr), ENT_QUOTES, self::$encoding)));
        }

        return $node;
    }

    /**
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(): ?DOMDocument
    {
        if (!self::$xml instanceof DOMDocument) {
            self::init();
        }

        return self::$xml;
    }

    /**
     * Get string representation of boolean value.
     */
    private static function bool2str($v)
    {
        //convert boolean to text value.
        $v = $v === true ? 'true' : $v;

        return $v === false ? 'false' : $v;
    }

    /**
     * Check if the tag name or attribute name contains illegal characters
     * Ref: http://www.w3.org/TR/xml/#sec-common-syn.
     */
    private static function isValidTagName($tag): bool
    {
        $pattern = '/^[a-z_]+[a-z0-9:\-._]*[^:]*$/i';

        return preg_match($pattern, (string) $tag, $matches) && $matches[0] == $tag;
    }
}
