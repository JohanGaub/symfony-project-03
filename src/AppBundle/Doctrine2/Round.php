<?php

namespace AppBundle\Doctrine2;

use \Doctrine\ORM\Query\AST\Functions\FunctionNode;
use \Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use \Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

/**
 * MysqlRound
 *
 * @uses FunctionNode
 * @author Julien DENIAU <julien.deniau@mapado.com>
 */
class Round extends FunctionNode
{
    /**
     * simpleArithmeticExpression
     *
     * @var mixed
     * @access public
     */
    public $simpleArithmeticExpression;

    /**
     * roundPrecision
     *
     * @var mixed
     * @access public
     */
    public $roundPrecision;

    /**
     * getSql
     *
     * @param SqlWalker $sqlWalker
     * @access public
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            'ROUND(%s, %s)',
            $sqlWalker->walkSimpleArithmeticExpression($this->simpleArithmeticExpression),
            (is_null($this->roundPrecision) ? 0 : $sqlWalker->walkStringPrimary($this->roundPrecision))
        );
    }

    /**
     * parse
     *
     * @param Parser $parser
     * @access public
     * @return void
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->simpleArithmeticExpression = $parser->SimpleArithmeticExpression();

        try {
            $parser->match(Lexer::T_COMMA);
            $this->roundPrecision = $parser->ArithmeticExpression();
        } catch(QueryException $e) {
            // ROUND() is being used without round precision
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}