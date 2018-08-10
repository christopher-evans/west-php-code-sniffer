<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cmevans@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Tests\PHP;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit tests for West.PHP.NoNull sniff.
 */
class NoNullUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList()
    {
        return [
            2 => 1,
            20 => 1,
            34 => 1
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningList()
    {
        return [];
    }
}
