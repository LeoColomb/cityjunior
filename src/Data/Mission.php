<?php

namespace Data;

use Data\Base\Mission as BaseMission;

/**
 * Skeleton subclass for representing a row from the 'cj__missions' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Mission extends BaseMission
{
    private $isAstreinte = null;

    public function getStartFormatted()
    {
        return $this->getStart()->format('G\hi');
    }

    public function getEndFormatted()
    {
        return $this->getEnd()->format('G\hi');
    }

    public function getDateFormatted()
    {
        return $this->getDate()->format('d/m/Y');
    }

    public function getLink()
    {
        return 'http://www.sncf.com/sncf/train?numeroTrain='.$this->getTrain().'&date='.urlencode($this->getDateFormatted());
    }

    public function isAstreinte()
    {
        if ($this->isAstreinte === null)
            $this->isAstreinte = $this->getType() === 'Astreinte';

        return $this->isAstreinte;
    }

    static public function ucname($string) {
        $string = ucwords(strtolower($string));

        foreach (['-', '\''] as $delimiter) {
            if (strpos($string, $delimiter) !== false) {
                $string = implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
            }
        }

        return $string;
    }
}
