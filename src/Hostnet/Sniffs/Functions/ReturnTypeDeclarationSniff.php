<?php
/**
 * @copyright 2017 Hostnet B.V.
 */

/**
 * ReturnTypeDeclarationSniff.
 *
 * Checks for return type declarations if the spacing between a function's
 * closing parenthesis, colon, and return type is correct
 */
class Hostnet_Sniffs_Functions_ReturnTypeDeclarationSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Spacing between the function's closing parenthesis and colon.
     *
     * @var string
     */
    public $closing_parenthesis_colon_spacing = '';

    /**
     * Spacing between the colon and the return type.
     *
     * @var string
     */
    public $colon_return_type_spacing = ' ';


    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_FUNCTION,
            T_CLOSURE,
        ];
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcs_file The file being scanned.
     * @param int                  $stack_ptr The position of the current token
     * in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();

        $closing_parenthesis_position = $this->getClosingParenthesis($phpcs_file, $tokens, $stack_ptr);
        $end_position                 = $this->getCharacterAfterReturnTypeDeclaration($phpcs_file, $tokens, $stack_ptr);

        $find = [
            T_COLON,
            T_RETURN_TYPE,
            T_WHITESPACE,
        ];

        $closing_parenthesis_colon_spacing = $this->closing_parenthesis_colon_spacing;
        $colon_return_type_spacing         = $this->colon_return_type_spacing;
        $acc                               = '';
        $next_separator                    = $closing_parenthesis_position;
        while (($next_separator = $phpcs_file->findNext($find, $next_separator + 1, $end_position)) !== false) {
            if ($tokens[$next_separator]['code'] === T_COLON) {
                $closing_parenthesis_colon_spacing = $acc;
                $acc                               = '';
                $colon_position                    = $next_separator;
            } elseif ($tokens[$next_separator]['code'] === T_RETURN_TYPE) {
                $colon_return_type_spacing = $acc;
                $acc                       = '';
                $return_type_position      = $next_separator;
            } else {
                $acc .= $tokens[$next_separator]['content'];
            }
        }

        if ($closing_parenthesis_colon_spacing !== $this->closing_parenthesis_colon_spacing
            || $colon_return_type_spacing !== $this->colon_return_type_spacing
        ) {
            $expected = sprintf(
                "Expected \")%s:%sreturntype\"",
                $this->closing_parenthesis_colon_spacing,
                $this->colon_return_type_spacing
            );
            $found    = sprintf(
                "found \")%s:%sreturntype\"",
                $closing_parenthesis_colon_spacing,
                $colon_return_type_spacing
            );

            $error = $expected . ';' . $found;

            $error = str_replace(["\r\n", "\n", "\r", "\t"], ['\r\n', '\n', '\r', '\t'], $error);
            $fix   = $phpcs_file->addFixableError($error, $stack_ptr, 'ReturnTypeDeclarationSpacing');
            if ($fix === true) {
                if ($closing_parenthesis_colon_spacing !== $this->closing_parenthesis_colon_spacing) {
                    $this->fixSpacing(
                        $phpcs_file,
                        $tokens,
                        $this->closing_parenthesis_colon_spacing,
                        $closing_parenthesis_position,
                        $colon_position
                    );
                }

                if ($colon_return_type_spacing !== $this->colon_return_type_spacing) {
                    $this->fixSpacing(
                        $phpcs_file,
                        $tokens,
                        $this->colon_return_type_spacing,
                        $colon_position,
                        $return_type_position
                    );
                }
            }
        }
    }


    /**
     * Get the position of a function's closing parenthesis within the
     * token stack.
     *
     * @param PHP_CodeSniffer_File $phpcs_file The file being scanned.
     * @param array                $tokens Token stack for this file
     * @param int                  $stack_ptr The position of the current token
     * in the stack passed in $tokens.
     *
     * @return int position within the token stack
     */
    private function getClosingParenthesis(PHP_CodeSniffer_File $phpcs_file, array $tokens, $stack_ptr)
    {
        $closing_parenthesis = $tokens[$stack_ptr]['parenthesis_closer'];

        // In case the function is a closure, the closing parenthesis
        // may be positioned after a use language construct.
        if ($tokens[$stack_ptr]['code'] === T_CLOSURE) {
            $use = $phpcs_file->findNext(T_USE, ($closing_parenthesis + 1), $tokens[$stack_ptr]['scope_opener']);
            if ($use !== false) {
                $open_bracket        = $phpcs_file->findNext(T_OPEN_PARENTHESIS, ($use + 1));
                $closing_parenthesis = $tokens[$open_bracket]['parenthesis_closer'];
            }
        }

        return $closing_parenthesis;
    }


    /**
     * Get the position of first character after the return type declaration
     * within the token stack.
     * This can be an opening brace, or, in case of an interface,
     * a semicolon.
     *
     * @param PHP_CodeSniffer_File $phpcs_file The file being scanned.
     * @param array                $tokens Token stack for this file
     * @param int                  $stack_ptr The position of the current token
     * in the stack passed in $tokens.
     *
     * @return int position within the token stack
     */
    private function getCharacterAfterReturnTypeDeclaration(PHP_CodeSniffer_File $phpcs_file, array $tokens, $stack_ptr)
    {
        if (isset($tokens[$stack_ptr]['scope_opener']) === false) {
            $end_position = $phpcs_file->findNext(T_SEMICOLON, $stack_ptr);
        } else {
            $end_position = $tokens[$stack_ptr]['scope_opener'];
        }

        return $end_position;
    }

    /**
     * Fix the spacing between start and end
     *
     * @param PHP_CodeSniffer_File $phpcs_file The file being scanned.
     * @param array $tokens Token stack for this file
     * @param string $required_spacing Required spacing between start and end
     * @param int $start Position of the start in the token stack
     * @param int $end Position of the end in the token stack
     *
     * @return void
     */
    private function fixSpacing(PHP_CodeSniffer_File $phpcs_file, $tokens, $required_spacing, $start, $end)
    {
        //insert whitespace if there is no whitespace, and whitespace is required
        if (($start + 1) === $end && empty($required_spacing) === false) {
            $phpcs_file->fixer->addContent(
                $start,
                $required_spacing
            );
        //otherwise, if there is whitespace, change the whitespace to the required spacing
        } else {
            for ($i = ($start + 1); $i < $end; $i++) {
                if ($tokens[$i]['code'] === T_WHITESPACE) {
                    if (($i + 1) === $end) {
                        $phpcs_file->fixer->replaceToken($i, $required_spacing);
                    } else {
                        $phpcs_file->fixer->replaceToken($i, '');
                    }
                }
            }
        }
    }
}
