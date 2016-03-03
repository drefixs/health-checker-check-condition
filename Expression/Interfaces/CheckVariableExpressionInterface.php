<?php
namespace TonicHealthCheck\Check\Condition\Expression\Interfaces;

use TonicHealthCheck\Check\CheckInterface;


/**
 * Class CheckVariableExpression
 * @package TonicHealthCheck\Check\Condition\Expression\Variable
 */
interface CheckVariableExpressionInterface
{
    /**
     * @return CheckInterface
     */
    public function getCheck();

    /**
     * @param CheckInterface $check
     */
    public function setCheck(CheckInterface $check);

    /**
     * @return string
     */
    public function getCheckName();
}