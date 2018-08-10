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
 * Unit tests for West.Commenting.InlineComment sniff.
 */
class InlineCommentUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList()
    {
        return [
            17  => 1,
            27  => 1,
            28  => 1,
            32  => 1,
            44  => 1,
            58  => 1,
            61  => 1,
            64  => 1,
            95  => 1,
            96  => 1,
            97  => 2,
            118 => 1,
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
