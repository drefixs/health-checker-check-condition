<?php

namespace TonicHealthCheck\Check\Condition\Expression\Condition;

use TonicHealthCheck\Check\CheckResult;
use TonicHealthCheck\Check\Condition\Expression\ExpressionInterface;
use TonicHealthCheck\Check\Condition\Expression\Interfaces\LeftRightExpressionInterface;
use TonicHealthCheck\Check\Condition\Expression\Traits\LeftExpressionTrait;
use TonicHealthCheck\Check\Condition\Expression\Traits\RightExpressionTrait;

/**
 * Class OrExpression
 * @package TonicHealthCheck\Check\Condition\Expression\Condition
 */
class OrExpression implements ExpressionInterface, LeftRightExpressionInterface
{
    use LeftExpressionTrait;
    use RightExpressionTrait;

    /**
     * @param mixed $context
     * @return CheckResult
     */
    public function interpret($context)
    {
        $checkResult = $this->getLeftExpression()->interpret($context);
        if (!$checkResult->isOk()) {
            $checkResult = $this->getRightExpression()->interpret($context);
        }

        return $checkResult;
    }
}
