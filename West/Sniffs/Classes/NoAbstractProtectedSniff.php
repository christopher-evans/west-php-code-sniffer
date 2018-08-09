<?php
/*
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

class NoAbstractProtectedSniff implements Sniff
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_ABSTRACT, T_PROTECTED];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $error   = 'Use of the "%s" keyword is forbidden';
        $data    = [$tokens[$stackPtr]['content']];

        $phpcsFile->addError($error, $stackPtr, 'NotAllowed', $data);
        $phpcsFile->recordMetric($stackPtr, 'Abstract or protected used', 'yes');
    }
}
