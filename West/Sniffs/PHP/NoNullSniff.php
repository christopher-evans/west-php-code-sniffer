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
 * Ensures null is not used.
 *
 * Null is allowed as the default value for a function argument; this is for convenience
 * to allow (to some degree) multiple signatures for a method.
 *
 * @author Christopher Evans <cvns.github@gmail.com>
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
            $allowedPlacement = ['T_FUNCTION', 'T_IF', 'T_ELSEIF'];
            if (! isset($tokens[$left]['parenthesis_owner']) === true ||
                ! in_array($tokens[$tokens[$left]['parenthesis_owner']]['type'], $allowedPlacement)) {
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
