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

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Formatting tests for class comments.
 *
 * The following rules apply:
 * - Each class must have exactly one comment
 * - Comment must use /**
 * - No blank lines after the class comment
 *
 * @author Christopher Evans <cvns.github@gmail.com>
 */
class ClassCommentSniff implements Sniff
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
        $find   = Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);
        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
            && $tokens[$commentEnd]['code'] !== T_COMMENT
        ) {
            $class = $phpcsFile->getDeclarationName($stackPtr);
            $phpcsFile->addError('Missing doc comment for class %s', $stackPtr, 'Missing', [$class]);
            $phpcsFile->recordMetric($stackPtr, 'Class has doc comment', 'no');
            return;
        }

        $phpcsFile->recordMetric($stackPtr, 'Class has doc comment', 'yes');

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            $phpcsFile->addError('You must use "/**" style comments for a class comment', $stackPtr, 'WrongStyle');
            return;
        }

        if ($tokens[$commentEnd]['line'] !== ($tokens[$stackPtr]['line'] - 1)) {
            $error = 'There must be no blank lines after the class comment';
            $phpcsFile->addError($error, $commentEnd, 'SpacingAfter');
        }
    }
}
