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
 * Unit tests for West.Commenting.FunctionComment sniff.
 */
class FunctionCommentUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList()
    {
        $errors = [
            5   => 1,
            10  => 4,
            12  => 2,
            13  => 2,
            14  => 1,
            15  => 1,
            28  => 1,
            43  => 1,
            76  => 1,
            87  => 1,
            103 => 1,
            109 => 1,
            122 => 1,
            123 => 2,
            124 => 3,
            125 => 1,
            126 => 1,
            137 => 5,
            138 => 4,
            139 => 4,
            152 => 1,
            155 => 2,
            159 => 1,
            166 => 1,
            173 => 1,
            190 => 2,
            193 => 3,
            196 => 1,
            199 => 2,
            210 => 1,
            211 => 1,
            222 => 1,
            223 => 1,
            224 => 1,
            225 => 1,
            226 => 1,
            227 => 1,
            230 => 2,
            276 => 1,
            277 => 1,
            279 => 1,
            280 => 1,
            281 => 1,
            319 => 1,
            358 => 1,
            359 => 2,
            372 => 1,
            373 => 1,
            387 => 1,
            407 => 1,
            441 => 1,
            573 => 1,
            669 => 1,
            676 => 1,
            688 => 1,
            723 => 1,
            725 => 1,
            744 => 1,
            748 => 1,
            758 => 1,
            789 => 1,
            792 => 1,
            794 => 1,
            797 => 1,
            840 => 1,
            852 => 1,
            864 => 1,
            886 => 1,
            888 => 1,
            890 => 1,
            952 => 1,
            977 => 1,
            978 => 1,
        ];

        // Object type hints only work from PHP 7.2 onwards.
        if (PHP_VERSION_ID < 70200) {
            $errors[992] = 2;
        }

        return $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningList()
    {
        return [];

    }
}
