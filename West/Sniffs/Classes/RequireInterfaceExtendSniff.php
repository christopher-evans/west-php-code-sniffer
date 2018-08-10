<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cmevans@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Ensures that each class either implements an interface or extends another class.
 *
 * A third party class could legitimately be extended e.g. when adding a new exception type.
 *
 * @author Christopher Evans <cmevans@tutanota.com>
 */
class RequireInterfaceExtendSniff implements Sniff
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_CLASS];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $endOfStatement = $phpcsFile->findEndOfStatement($stackPtr);
        $implements = $phpcsFile->findNext(
            T_IMPLEMENTS,
            $stackPtr,
            $endOfStatement,
            false,
            null,
            true
        );

        if ($implements !== false) {
            // interface found
            $phpcsFile->recordMetric($stackPtr, 'Class ImplementExtend', 'yes');
            return;
        }

        $extends = $phpcsFile->findNext(
            T_EXTENDS,
            $stackPtr,
            $endOfStatement,
            false,
            null,
            true
        );

        if ($extends !== false) {
            // parent class found
            $phpcsFile->recordMetric($stackPtr, 'Class ImplementExtend', 'yes');
            return;
        }

        $error   = 'Class "%s" must implement an interface or extend another class';
        $nextVar = $tokens[$phpcsFile->findNext([T_STRING], $stackPtr)];
        $data    = [$nextVar['content']];

        $phpcsFile->addError($error, $stackPtr, 'NotAllowed', $data);
        $phpcsFile->recordMetric($stackPtr, 'Class implements', 'no');
    }
}
