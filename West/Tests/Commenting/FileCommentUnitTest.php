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
 * Unit tests for West.Commenting.FileComment sniff.
 */
class FileCommentUnitTest extends AbstractSniffUnitTest
{
    /**
     * {@inheritdoc}
     */
    public function getErrorList($testFile='FileCommentUnitTest.inc')
    {
        switch ($testFile) {
            case 'FileCommentUnitTest.1.inc':
                return [
                    1  => 1,
                    32 => 1
                ];
            case 'FileCommentUnitTest.2.inc':
                return [
                    2  => 1
                ];
        default:
            return [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWarningList()
    {
        return [];
    }
}
