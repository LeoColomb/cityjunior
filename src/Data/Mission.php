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
}
