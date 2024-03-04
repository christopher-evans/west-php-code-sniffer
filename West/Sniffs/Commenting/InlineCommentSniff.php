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
 * Formatting tests for inline comments.
 *
 * The following rules apply:
 * - Perl style ('#') comments are disallowed
 * - Must start with a single space
 * - Empty comments are disallowed
 * - Must not be followed by a blank line
 *
 * @author Christopher Evans <cvns.github@gmail.com>
 */
class InlineCommentSniff implements Sniff
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        return [
            T_COMMENT,
            T_DOC_COMMENT_OPEN_TAG,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // If this is a function/class/interface doc block comment, skip it.
        // We are only interested in inline doc block comments, which are
        // not allowed.
        if ($tokens[$stackPtr]['code'] === T_DOC_COMMENT_OPEN_TAG) {
            $nextToken = $phpcsFile->findNext(
                Tokens::$emptyTokens,
                ($stackPtr + 1),
                null,
                true
            );

            $ignore = [
                T_CLASS,
                T_INTERFACE,
                T_TRAIT,
                T_FUNCTION,
                T_CLOSURE,
                T_PUBLIC,
                T_PRIVATE,
                T_PROTECTED,
                T_FINAL,
                T_STATIC,
                T_ABSTRACT,
                T_CONST,
                T_PROPERTY,
                T_INCLUDE,
                T_INCLUDE_ONCE,
                T_REQUIRE,
                T_REQUIRE_ONCE,
            ];

            if (in_array($tokens[$nextToken]['code'], $ignore) === true) {
                return;
            }

            $prevToken = $phpcsFile->findPrevious(
                Tokens::$emptyTokens,
                ($stackPtr - 1),
                null,
                true
            );

            if ($tokens[$prevToken]['code'] === T_OPEN_TAG) {
                return;
            }
        }//end if

        if ($tokens[$stackPtr]['content']{0} === '#') {
            $error = 'Perl-style comments are not allowed; use "// Comment" instead';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'WrongStyle');
            if ($fix === true) {
                $comment = ltrim($tokens[$stackPtr]['content'], "# \t");
                $phpcsFile->fixer->replaceToken($stackPtr, "// $comment");
            }
        }

        // We don't want end of block comments. If the last comment is a closing
        // curly brace.
        $previousContent = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if ($tokens[$previousContent]['line'] === $tokens[$stackPtr]['line']) {
            if ($tokens[$previousContent]['code'] === T_CLOSE_CURLY_BRACKET) {
                return;
            }

            // Special case for JS files.
            if ($tokens[$previousContent]['code'] === T_COMMA
                || $tokens[$previousContent]['code'] === T_SEMICOLON
            ) {
                $lastContent = $phpcsFile->findPrevious(T_WHITESPACE, ($previousContent - 1), null, true);
                if ($tokens[$lastContent]['code'] === T_CLOSE_CURLY_BRACKET) {
                    return;
                }
            }
        }

        $comment = rtrim($tokens[$stackPtr]['content']);

        // Only want inline comments.
        if (substr($comment, 0, 2) !== '//') {
            return;
        }

        if (trim(substr($comment, 2)) !== '') {
            $spaceCount = 0;
            $tabFound   = false;

            $commentLength = strlen($comment);
            for ($i = 2; $i < $commentLength; $i++) {
                if ($comment[$i] === "\t") {
                    $tabFound = true;
                    break;
                }

                if ($comment[$i] !== ' ') {
                    break;
                }

                $spaceCount++;
            }

            $fix = false;
            if ($tabFound === true) {
                $error = 'Tab found before comment text; expected "// %s" but found "%s"';
                $data  = [
                    ltrim(substr($comment, 2)),
                    $comment,
                ];
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'TabBefore', $data);
            } else if ($spaceCount === 0) {
                $error = 'No space found before comment text; expected "// %s" but found "%s"';
                $data  = [
                    substr($comment, 2),
                    $comment,
                ];
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpaceBefore', $data);
            } else if ($spaceCount > 1) {
                $error = 'Expected 1 space before comment text but found %s; use block comment if you need indentation';
                $data  = [
                    $spaceCount,
                    substr($comment, (2 + $spaceCount)),
                    $comment,
                ];
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpacingBefore', $data);
            }

            if ($fix === true) {
                $newComment = '// '.ltrim($tokens[$stackPtr]['content'], "/\t ");
                $phpcsFile->fixer->replaceToken($stackPtr, $newComment);
            }
        }

        // The below section determines if a comment block is correctly capitalised,
        // and ends in a full-stop. It will find the last comment in a block, and
        // work its way up.
        $nextComment = $phpcsFile->findNext(T_COMMENT, ($stackPtr + 1), null, false);
        if ($nextComment !== false
            && $tokens[$nextComment]['line'] === ($tokens[$stackPtr]['line'] + 1)
        ) {
            $nextNonWhitespace = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), $nextComment, true);
            if ($nextNonWhitespace === false) {
                return;
            }
        }

        $lastComment = $stackPtr;
        while (($topComment = $phpcsFile->findPrevious([T_COMMENT], ($lastComment - 1), null, false)) !== false) {
            if ($tokens[$topComment]['line'] !== ($tokens[$lastComment]['line'] - 1)) {
                break;
            }

            $nextNonWhitespace = $phpcsFile->findNext(T_WHITESPACE, ($topComment + 1), $lastComment, true);
            if ($nextNonWhitespace !== false) {
                break;
            }

            $lastComment = $topComment;
        }

        $topComment  = $lastComment;
        $commentText = '';

        for ($i = $topComment; $i <= $stackPtr; $i++) {
            if ($tokens[$i]['code'] === T_COMMENT) {
                $commentText .= trim(substr($tokens[$i]['content'], 2));
            }
        }

        if ($commentText === '') {
            $error = 'Blank comments are not allowed';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'Empty');
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($stackPtr, '');
            }

            return;
        }

        // Finally, the line below the last comment cannot be empty if this inline
        // comment is on a line by itself.
        if ($tokens[$previousContent]['line'] < $tokens[$stackPtr]['line']) {
            $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
            if ($next === false) {
                // Ignore if the comment is the last non-whitespace token in a file.
                return;
            }

            if ($tokens[$next]['code'] === T_DOC_COMMENT_OPEN_TAG) {
                // If this inline comment is followed by a docblock,
                // ignore spacing as docblock/function etc spacing rules
                // are likely to conflict with our rules.
                return;
            }

            $errorCode = 'SpacingAfter';

            if (isset($tokens[$stackPtr]['conditions']) === true) {
                $conditions   = $tokens[$stackPtr]['conditions'];
                $type         = end($conditions);
                $conditionPtr = key($conditions);

                if (($type === T_FUNCTION || $type === T_CLOSURE)
                    && $tokens[$conditionPtr]['scope_closer'] === $next
                ) {
                    $errorCode = 'SpacingAfterAtFunctionEnd';
                }
            }

            for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
                if ($tokens[$i]['line'] === ($tokens[$stackPtr]['line'] + 1)) {
                    if ($tokens[$i]['code'] !== T_WHITESPACE) {
                        return;
                    }
                } else if ($tokens[$i]['line'] > ($tokens[$stackPtr]['line'] + 1)) {
                    break;
                }
            }

            $error = 'There must be no blank line following an inline comment';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, $errorCode);
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                for ($i = ($stackPtr + 1); $i < $next; $i++) {
                    if ($tokens[$i]['line'] === $tokens[$next]['line']) {
                        break;
                    }

                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}
