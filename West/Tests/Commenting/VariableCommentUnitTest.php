<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cmevans@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Tests\Commenting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit tests for West.Commenting.VariableComment sniff.
 */
class VariableCommentUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList()
    {
        return [
            21  => 1,
            24  => 1,
            56  => 1,
            64  => 1,
            73  => 1,
            84  => 1,
            130 => 1,
            136 => 1,
            144 => 1,
            152 => 1,
            160 => 1,
            168 => 1,
            176 => 1,
            184 => 1,
            192 => 1,
            200 => 1,
            208 => 1,
            216 => 1,
            224 => 1,
            232 => 1,
            240 => 1,
            248 => 1,
            256 => 1,
            264 => 1,
            272 => 1,
            280 => 1,
            290 => 1,
            305 => 1,
            330 => 1,
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
