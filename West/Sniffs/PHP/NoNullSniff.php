<?php
/**
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

/**
 * Ensures null is not used.
 *
 * Null is allowed as the default value for a function argument; this is for convenience
 * to allow (to some degree) multiple signatures for a method.
 *
 * @author Christopher Evans <cmevans@tutanota.com>
 */
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

        if (isset($tokens[$stackPtr]['nested_parenthesis']) === false) {
            // this isn't a default value
            // for a function
            $error = 'Use of null is forbidden';
            $phpcsFile->addError($error, $stackPtr, 'NullUsed');
            $phpcsFile->recordMetric($stackPtr, 'No null members', 'no');

            return;
        }

        // Check to see if this including statement is within the parenthesis
        // of a function.
        foreach ($tokens[$stackPtr]['nested_parenthesis'] as $left => $right) {
            if (! isset($tokens[$left]['parenthesis_owner']) === true ||
                $tokens[$tokens[$left]['parenthesis_owner']]['type'] !== 'T_FUNCTION') {
                // this isn't a default value
                // for a function
                $error = 'Use of null is forbidden';
                $phpcsFile->addError($error, $stackPtr, 'NullUsed');
                $phpcsFile->recordMetric($stackPtr, 'No null members', 'no');

                return;
            }
        }
    }
}
