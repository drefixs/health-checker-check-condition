<?php

namespace TonicHealthCheck\Check\Condition\Expression\Traits;

use TonicHealthCheck\Check\Condition\Expression\ExpressionInterface;

trait LeftExpressionTrait
{
    /**
     * @var ExpressionInterface;
     */
    public $leftExpression;

    /**
     * @param ExpressionInterface $leftExpression
     */
    public function setLeftExpression(ExpressionInterface $leftExpression)
    {
        $this->leftExpression = $leftExpression;
    }

    /**
     * @return ExpressionInterface
     */
    public function getLeftExpression()
    {
        return $this->leftExpression;
    }
}
