<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cvns.github@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Sniffs\PHP;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Ensures switch statements are not used.
 *
 * @author Christopher Evans <cvns.github@gmail.com>
 */
class NoSwitchSniff implements Sniff
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_SWITCH];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $error = 'Use of switch statements is forbidden. Consider using objects instead.';

        $phpcsFile->recordMetric($stackPtr, 'No switch', 'no');
        $phpcsFile->addError($error, $stackPtr, 'NotAllowed');
    }
}
