<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cvns.github@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class GlobalFunctionSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_FUNCTION];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (empty($tokens[$stackPtr]['conditions']) === true) {
            $functionName = $phpcsFile->getDeclarationName($stackPtr);
            if ($functionName === null) {
                return;
            }

            // Special exception for __autoload as it needs to be global.
            if ($functionName !== '__autoload') {
                $error = 'Global function "%s" is not allowed';
                $data  = [$functionName];
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        }
    }
}
