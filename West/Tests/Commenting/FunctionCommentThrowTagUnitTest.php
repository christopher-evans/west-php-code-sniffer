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
 * Unit tests for West.Commenting.FunctionCommentThrowTag sniff.
 */
class FunctionCommentThrowTagUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList()
    {
        return [
            9   => 1,
            21  => 1,
            35  => 1,
            47  => 1,
            61  => 2,
            106 => 1,
            123 => 1,
            200 => 1,
            219 => 1,
            287 => 1,
            397 => 1,
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
