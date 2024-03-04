<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cvns.github@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Tests\PHP;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit tests for West.PHP.NoSwitch sniff.
 */
class NoSwitchUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList()
    {
        return [
            4 => 1,
            12 => 1
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
