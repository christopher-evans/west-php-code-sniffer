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
 * Ensures that all classes are final.
 *
 * @author Christopher Evans <cvns.github@gmail.com>
 */
class FinalClassSniff implements Sniff
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
        $token = $tokens[$stackPtr];

        $final = $phpcsFile->findPrevious(
            T_FINAL,
            $stackPtr,
            $phpcsFile->findStartOfStatement($stackPtr),
            false,
            null,
            true
        );

        if ($final !== false) {
            // final found
            $phpcsFile->recordMetric($stackPtr, 'Final class', 'yes');
            return;
        }

        if (isset($token['scope_opener']) === false) {
            $error = 'Possible parse error: %s missing opening or closing brace';
            $data  = [$token['content']];
            $phpcsFile->addWarning($error, $stackPtr, 'MissingBrace', $data);
            return;
        }

        // Determine the name of the class or interface. Note that we cannot
        // simply look for the first T_STRING because a class name
        // starting with the number will be multiple tokens.
        $opener = $token['scope_opener'];
        $nameStart = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), $opener, true);
        $nameEnd = $phpcsFile->findNext(T_WHITESPACE, $nameStart, $opener);
        if ($nameEnd === false) {
            $name = $tokens[$nameStart]['content'];
        } else {
            $name = trim($phpcsFile->getTokensAsString($nameStart, ($nameEnd - $nameStart)));
        }

        $error = 'Class "%s" is not final';
        $data = [
            $name,
        ];

        $phpcsFile->addError($error, $stackPtr, 'NotFinal', $data);
        $phpcsFile->recordMetric($stackPtr, 'Final class', 'no');
    }
}
