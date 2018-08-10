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
 * Ensures that classes do not use public variables.
 *
 * @author Christopher Evans <cmevans@tutanota.com>
 */
class NoPublicVarsSniff implements Sniff
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_PUBLIC];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $variableFunction = $phpcsFile->findNext(
            T_WHITESPACE,
            $stackPtr + 1,
            null,
            true,
            null,
            true
        );

        if ($variableFunction === false) {
            return;
        }

        $token = $tokens[$variableFunction];
        if ($token['type'] === 'T_VARIABLE') {
            $error   = 'Use of the "public" variable "%s" is forbidden';
            $varName = str_replace('$', '', $token['content']);
            $data    = [$varName];

            $phpcsFile->addError($error, $stackPtr, 'NotAllowed', $data);
        }
    }
}
