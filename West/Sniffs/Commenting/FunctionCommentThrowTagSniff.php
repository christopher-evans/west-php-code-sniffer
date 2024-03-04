<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cvns.github@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace West\CodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FunctionCommentThrowTagSniff as SquizFunctionCommentThrowTagSniff;

/**
 * Ensures all exceptions thrown by a function are documented.
 *
 * If the comment block consists of either
 * - a single "@see" tag only (an implementation of an interface)
 * - a single "@inheritdoc" tag only (extends a parent's method)
 * then the requirement that all exceptions are documented is droppped; the
 * expectation is that these will be documented by the referenced function.
 *
 * @author Christopher Evans <cvns.github@gmail.com>
 */
class FunctionCommentThrowTagSniff extends SquizFunctionCommentThrowTagSniff
{
    /**
     * {@inheritdoc}
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

        $find   = Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;

        // if the comment contains a single tag; if it's @see or @inheritdoc
        // we can skip the rest of the comment validation
        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);
        if(isset($tokens[$commentEnd]['comment_opener']) === true) {
            $commentStart = $tokens[$commentEnd]['comment_opener'];
            if (count($tokens[$commentStart]['comment_tags']) === 1) {
                $allowedTokens = ['@see', '@inheritdoc'];
                $commentToken = $tokens[$tokens[$commentStart]['comment_tags'][0]];
                if (in_array(strtolower($commentToken['content']), $allowedTokens)) {
                    return;
                }
            }
        }

        parent::process($phpcsFile, $stackPtr);
    }
}
