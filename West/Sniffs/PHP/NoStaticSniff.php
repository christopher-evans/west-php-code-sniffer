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
 * Ensures static variables are not used.
 *
 * Any static function, member variable or global variable is disallowed; in the case
 * "allowPrivate" is true (the default) private static class variables and functions
 * are permitted.
 *
 * @author Christopher Evans <cmevans@tutanota.com>
 */
class NoStaticSniff implements Sniff
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
            T_STATIC
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($this->allowPrivate) {
            $modifier = $phpcsFile->findPrevious(
                T_PRIVATE,
                $stackPtr - 1,
                null,
                false,
                null,
                true
            );

            if ($modifier === false) {
                $modifier = $phpcsFile->findNext(
                    T_PRIVATE,
                    $stackPtr + 1,
                    null,
                    false,
                    null,
                    true
                );
            }

            if ($modifier !== false && $tokens[$modifier]['type'] === 'T_PRIVATE') {
                $phpcsFile->recordMetric($stackPtr, 'No static members', 'yes');

                return;
            }
        }

        // Determine the name of the variable. Note that we cannot
        // simply look for the first T_STRING because a class name
        // starting with the number will be multiple tokens.
        $modifiedItem = $phpcsFile->findNext(
            [
                T_VARIABLE,
                T_FUNCTION
            ],
            $stackPtr + 1,
            null,
            false,
            null,
            true
        );

        $name = '___';
        $error = 'Use of %sstatic variable "%s" is forbidden';
        if (isset($tokens[$modifiedItem]['type'])) {
            switch ($tokens[$modifiedItem]['type']) {
                case 'T_FUNCTION':
                    $nameStart = $phpcsFile->findNext(T_STRING, ($modifiedItem + 1), null, false, null, true);

                    $error = 'Use of %sstatic function "%s" is forbidden';
                    $name = $tokens[$nameStart]['content'];
                    break;
                default:
                    $name = $tokens[$modifiedItem]['content'];

                    break;
            }
        }

        $data = [
            $this->allowPrivate ? 'non-private ' : '',
            $name
        ];
        $phpcsFile->addError($error, $stackPtr, 'StaticMember', $data);
        $phpcsFile->recordMetric($stackPtr, 'No static members', 'no');
    }
}
