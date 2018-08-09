<?php
/*
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cmevans@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Sniffs\PHP;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class NoNullSniff implements Sniff
{
    /**
     * If true, allow private static class members.
     *
     * @var bool
     */
    public $allowPrivate = true;

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [
            T_NULL
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $statementStart = $phpcsFile->findStartOfStatement($stackPtr);
        $token = $tokens[$statementStart];

        if ($token['type'] !== 'T_VARIABLE') {
            // this isn't a default value
            // for a function
            $error = 'Use of null is forbidden';
            $phpcsFile->addError($error, $stackPtr, 'NullUsed');
            $phpcsFile->recordMetric($stackPtr, 'No null members', 'no');

            return;
        }


        // determine if this is a default value for a function (ok)
        // or an assignment (error)
        $statementStart = $phpcsFile->findStartOfStatement($statementStart - 1);
        $token = $tokens[$statementStart];

        if ($token['type'] !== 'T_OPEN_PARENTHESIS') {
            // this isn't a default value
            // for a function
            $error = 'Use of null is forbidden';
            $phpcsFile->addError($error, $stackPtr, 'NullUsed');
            $phpcsFile->recordMetric($stackPtr, 'No null members', 'no');

            return;
        }

        // now either we can search forward to a function
        // or the null is an error
        $statementStart = $phpcsFile->findStartOfStatement($statementStart - 1);
        $function = $phpcsFile->findNext(
            T_FUNCTION,
            $statementStart,
            $stackPtr
        );

        if ($function === false) {
            $error = 'Use of null is forbidden';
            $phpcsFile->addError($error, $stackPtr, 'NullUsed');
            $phpcsFile->recordMetric($stackPtr, 'No null members', 'no');

            return;
        }
    }
}
