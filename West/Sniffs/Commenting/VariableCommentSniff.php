<?php
/**
 * This file is part of the West\\CodingStandard package
 *
 * (c) Chris Evans <cmevans@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace West\CodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Common;

/**
 * Formatting tests for variable comments (including member variable comments).
 *
 * The following rules apply:
 * - Member variables must have a comment
 * - Must begin with /**
 * - Must contain exactly one "@var" tag
 * - "@var" tag must be the first tag in the comment
 * - "@var" tag must have some content
 * - "@see" tags, if present, must have some content
 * - The variable type in the "@var" tag must be an expected form (e.g. "bool" not "boolean", "int" not "integer")
 *
 * If the comment contains a single @inheritdoc tag and no other tags these rules will not be applied.
 *
 * @author Christopher Evans <cmevans@tutanota.com>
 */
class VariableCommentSniff extends AbstractVariableSniff
{
    /**
     * {@inheritdoc}
     */
    public function processMemberVar(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $ignore = [
            T_PUBLIC,
            T_PRIVATE,
            T_PROTECTED,
            T_VAR,
            T_STATIC,
            T_WHITESPACE,
        ];

        $commentEnd = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), null, true);
        if ($commentEnd === false
            || ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
                && $tokens[$commentEnd]['code'] !== T_COMMENT)
        ) {
            $phpcsFile->addError('Missing member variable doc comment', $stackPtr, 'Missing');
            return;
        }

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            $phpcsFile->addError('You must use "/**" style comments for a member variable comment', $stackPtr, 'WrongStyle');
            return;
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];


        // if the comment contains a single tag; if it's @inheritdoc
        // we can skip the rest of the comment validation
        if (count($tokens[$commentStart]['comment_tags']) === 1) {
            $allowedTokens = ['@inheritdoc'];
            $commentToken = $tokens[$tokens[$commentStart]['comment_tags'][0]];
            if (in_array(strtolower($commentToken['content']), $allowedTokens)) {
                return;
            }
        }

        $foundVar = null;
        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] === '@var') {
                if ($foundVar !== null) {
                    $error = 'Only one @var tag is allowed in a member variable comment';
                    $phpcsFile->addError($error, $tag, 'DuplicateVar');
                } else {
                    $foundVar = $tag;
                }
            } else if ($tokens[$tag]['content'] === '@see') {
                // Make sure the tag isn't empty.
                $string = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $tag, $commentEnd);
                if ($string === false || $tokens[$string]['line'] !== $tokens[$tag]['line']) {
                    $error = 'Content missing for @see tag in member variable comment';
                    $phpcsFile->addError($error, $tag, 'EmptySees');
                }
            }
        }

        // The @var tag is the only one we require.
        if ($foundVar === null) {
            $error = 'Missing @var tag in member variable comment';
            $phpcsFile->addError($error, $commentEnd, 'MissingVar');
            return;
        }

        $firstTag = $tokens[$commentStart]['comment_tags'][0];
        if ($foundVar !== null && $tokens[$firstTag]['content'] !== '@var') {
            $error = 'The @var tag must be the first tag in a member variable comment';
            $phpcsFile->addError($error, $foundVar, 'VarOrder');
        }

        // Make sure the tag isn't empty and has the correct padding.
        $string = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $foundVar, $commentEnd);
        if ($string === false || $tokens[$string]['line'] !== $tokens[$foundVar]['line']) {
            $error = 'Content missing for @var tag in member variable comment';
            $phpcsFile->addError($error, $foundVar, 'EmptyVar');
            return;
        }

        $varType       = $tokens[($foundVar + 2)]['content'];
        $suggestedType = Common::suggestType($varType);
        if ($varType !== $suggestedType) {
            $error = 'Expected "%s" but found "%s" for @var tag in member variable comment';
            $data  = [
                $suggestedType,
                $varType,
            ];

            $fix = $phpcsFile->addFixableError($error, ($foundVar + 2), 'IncorrectVarType', $data);
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken(($foundVar + 2), $suggestedType);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function processVariable(File $phpcsFile, $stackPtr)
    {

    }

    /**
     * {@inheritdoc}
     */
    protected function processVariableInString(File $phpcsFile, $stackPtr)
    {

    }
}
