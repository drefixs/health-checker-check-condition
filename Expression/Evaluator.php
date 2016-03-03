<?php

namespace TonicHealthCheck\Check\Condition\Expression;

use TonicHealthCheck\Check\CheckInterface;
use TonicHealthCheck\Check\Condition\Expression\Exception\LeftExpressionException;
use TonicHealthCheck\Check\Condition\Expression\Exception\RightExpressionException;
use TonicHealthCheck\Check\Condition\Expression\Interfaces\CheckVariableExpressionInterface;
use TonicHealthCheck\Check\Condition\Expression\Interfaces\LeftExpressionInterface;
use TonicHealthCheck\Check\Condition\Expression\Condition\AndExpression;
use TonicHealthCheck\Check\Condition\Expression\Condition\OrExpression;
use TonicHealthCheck\Check\Condition\Expression\Interfaces\RightExpressionInterface;
use TonicHealthCheck\Check\Condition\Expression\Variable\CheckVariableExpression;
use TonicHealthCheck\Checker\ChecksList;

/**
 * Class Evaluator
 * @package TonicHealthCheck\Check\Condition\Expression
 */
class Evaluator implements ExpressionInterface
{
    const LEXER_OR_REGEX ='#^\s*(?:OR|\|\|)#';
    const LEXER_AND_REGEX ='#^\s*(?:AND|\&\&)#';
    const LEXER_VALUE_REGEX ='#\s*([\w\.\-]+)#';

    private static $lexerRegexList = [
        self::LEXER_OR_REGEX => OrExpression::class,
        self::LEXER_AND_REGEX => AndExpression::class,
        self::LEXER_VALUE_REGEX => CheckVariableExpression::class,

    ];

    /**
     * @var ChecksList;
     */
    private $checksList;

    /**
     * @var ExpressionCollection;
     */
    private $expressionCollection;


    /**
     * Evaluator constructor.
     * @param string                    $expressionStr
     * @param null|ExpressionCollection $expressionCollection
     * @throws LeftExpressionException
     * @throws RightExpressionException
     */
    public function __construct($expressionStr, $expressionCollection = null)
    {
        if (null === $expressionCollection) {
            $expressionCollection = new ExpressionCollection();
        }

        $this->setExpressionCollection($expressionCollection);
        $lastOffset = 0;
        do {
            $isFind = false;
            foreach (self::$lexerRegexList as $regex => $expressionClassName) {
                if (preg_match(
                    $regex,
                    substr($expressionStr, $lastOffset),
                    $matches,
                    PREG_OFFSET_CAPTURE
                )) {
                    $isFind = true;
                    $lastOffset = $lastOffset + $matches[0][1]+strlen($matches[0][0]);

                    if (isset($matches[1][0])) {
                        $valueName  = $matches[1][0];

                        $expressionI = new $expressionClassName($valueName);
                    } else {
                        $expressionI = new $expressionClassName();
                    }
                    $this->getExpressionCollection()->add($expressionI);
                    break;
                }
            }
        } while ($isFind);

        foreach ($this->getExpressionCollection() as $key => $expressionI) {

            if ($expressionI instanceof LeftExpressionInterface) {
                if ($key <= 0) {
                    throw  LeftExpressionException::canNotBeFirst($expressionStr);
                }
            }

            if ($expressionI instanceof RightExpressionInterface) {
                if ($key >= $this->getExpressionCollection()->count()-1) {
                    throw  RightExpressionException::canNotBeLast($expressionStr);
                }
            }
        }
    }


    /**
     * @param mixed $context
     * @return mixed
     */
    public function interpret($context)
    {
        $this->setChecksList($context);
        $expressionCollection = clone $this->getExpressionCollection();

        foreach ($expressionCollection as $key => $expressionI) {
            if (is_a($expressionI, CheckVariableExpressionInterface::class)) {
                /** @var CheckVariableExpressionInterface $expressionI */
                $check = $this->checkObjGet($expressionI->getCheckName());

                $expressionI->setCheck($check);
            }
        }
        $key = $expressionCollection->count()-1;
        do {
            $expressionI=$expressionCollection->at($key);
            if ($expressionI instanceof LeftExpressionInterface) {
                /** @var LeftExpressionInterface $expressionI */
                $expressionI->setLeftExpression($expressionCollection->at($key - 1));
                $expressionCollection->removeAt($key - 1);
                $key--;
            }
            if ($expressionI instanceof LeftExpressionInterface) {
                /** @var RightExpressionInterface $expressionI */
                $expressionI->setRightExpression($expressionCollection->at($key + 1));
                $expressionCollection->removeAt($key + 1);
            }
        } while (--$key >= 0);
        /** @var ExpressionInterface $expression */
        $expression = $expressionCollection->at(0);

        return $expression->interpret($context);
    }

    /**
     * @return ChecksList
     */
    public function getChecksList()
    {
        return $this->checksList;
    }

    /**
     * @return ExpressionCollection
     */
    public function getExpressionCollection()
    {
        return $this->expressionCollection;
    }

    /**
     * @param ChecksList $checksList
     */
    protected function setChecksList(ChecksList $checksList)
    {
        $this->checksList = $checksList;
    }

    /**
     * @param ExpressionCollection $expressionCollection
     */
    protected function setExpressionCollection(ExpressionCollection $expressionCollection)
    {
        $this->expressionCollection = $expressionCollection;
    }

    /**
     * @param $valueName
     * @return CheckInterface
     */
    protected function checkObjGet($valueName)
    {
        /** @var CheckInterface $check */
        $check = $this->getChecksList()->find(
            function (CheckInterface $check) use ($valueName) {
                return $check->getIndent() == $valueName;
            }
        );

        return $check;
    }
}
