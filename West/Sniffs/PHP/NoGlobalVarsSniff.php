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
 * Ensures global variables are not used.
 *
 * Checks for the global keyword and for use of the $GLOBALS variable.
 *
 * @author Christopher Evans <cmevans@tutanota.com>
 */
class NoGlobalVarsSniff implements Sniff
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [T_GLOBAL, T_VARIABLE];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

        $error = '';
        $data = [];
        switch ($token['type']) {
            case 'T_GLOBAL':
                $error   = 'Use of the "global" keyword is forbidden';
                $nextVar = $tokens[$phpcsFile->findNext([T_VARIABLE], $stackPtr)];
                $varName = str_replace('$', '', $nextVar['content']);
                $data    = [$varName];

                $phpcsFile->recordMetric($stackPtr, 'Global variable', 'yes');
                break;
            case 'T_VARIABLE':
                if ($token['content'] !== '$GLOBALS') {
                    return;
                }

                $error = 'Use of the "$GLOBALS" array is forbidden';
                $phpcsFile->recordMetric($stackPtr, 'Global variable', 'yes');
                break;
        }

        $phpcsFile->addError($error, $stackPtr, 'NotAllowed', $data);
    }
}
