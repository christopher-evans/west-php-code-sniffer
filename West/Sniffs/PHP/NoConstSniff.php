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
 * Ensures that constants are not used.
 *
 * Checks for class constants and global constants.
 * @TODO maybe allow private contants from PHP 7.2
 *
 * @author Christopher Evans <cmevans@tutanota.com>
 */
class NoConstSniff implements Sniff
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_CONST];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $error   = 'Use of constant "%s" is forbidden';
        $nextVar = $tokens[$phpcsFile->findNext([T_STRING], $stackPtr)];
        $varName = $nextVar['content'];
        $data    = [$varName];

        $phpcsFile->addError($error, $stackPtr, 'NotAllowed', $data);
    }
}
