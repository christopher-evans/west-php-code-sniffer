<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cvns.github@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Ensures that classes are not abstract and do not use abstract or protected methods.
 *
 * @author Christopher Evans <cvns.github@gmail.com>
 */
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
