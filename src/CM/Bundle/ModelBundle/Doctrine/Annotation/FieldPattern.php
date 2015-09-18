<?php

namespace CM\Bundle\ModelBundle\Doctrine\Annotation;

use FS\SolrBundle\Doctrine\Annotation\Field;

/**
 * @Annotation
 */
class FieldPattern extends Field
{

    /**
     * @var string
     */
    public $pattern;

    /**
     * @return string
     */
    public function getValue()
    {
        $this->pattern = str_replace("_", "\"", $this->pattern);
        $matches = array();
        preg_match_all("'".$this->pattern."'", $this->value, $matches);
        $string_match = "";
        foreach ($matches[1] as $match) {
            $string_match .= $match." ";
        }
        $string_match = trim($string_match, " ");
        return $string_match;
    }

}
