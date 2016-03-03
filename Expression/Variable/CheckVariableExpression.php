<?php

namespace TonicHealthCheck\Check\Condition\Expression\Variable;

use TonicHealthCheck\Check\CheckInterface;
use TonicHealthCheck\Check\Condition\Expression\ExpressionInterface;
use TonicHealthCheck\Check\Condition\Expression\Interfaces\CheckVariableExpressionInterface;

/**
 * Class CheckVariableExpression
 * @package TonicHealthCheck\Check\Condition\Expression\Variable
 */
class CheckVariableExpression implements ExpressionInterface, CheckVariableExpressionInterface
{
    /**
     * @var CheckInterface;
     */
    private $check;

    /**
     * @var string;
     */
    private $checkName;


    /**
     * CheckVariableExpression constructor.
     * @param string $checkName
     */
    public function __construct($checkName)
    {
        $this->setCheckName($checkName);
    }

    /**
     * @return CheckInterface
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * @param CheckInterface $check
     */
    public function setCheck(CheckInterface $check)
    {
        $this->check = $check;
    }

    /**
     * @param mixed $context
     * @return mixed
     */
    public function interpret($context)
    {
        return $this->getCheck()->performCheck();
    }

    /**
     * @return string
     */
    public function getCheckName()
    {
        return $this->checkName;
    }

    /**
     * @param string $checkName
     */
    protected function setCheckName($checkName)
    {
        $this->checkName = $checkName;
    }
}