<?php

namespace CM\Bundle\ModelBundle\Doctrine\Annotation;

use FS\SolrBundle\Doctrine\Annotation\Field;

/**
 * @Annotation
 */
class FieldObjectArrayCollection extends Field
{


    /**
     * @return string
     */
    public function getValue()
    {
        $array_values = array();
        foreach ($this->value as $key => $value) {
            $array_values[] = $value->getId();
        }

        return implode(' ', $array_values);
    }

}
