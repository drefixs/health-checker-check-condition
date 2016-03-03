<?php

namespace TonicHealthCheck\Check\Condition\Expression\Exception;

use TonicHealthCheck\Check\Condition\Expression\EvaluatorException;

/**
 * Class LeftExpressionException
 * @package TonicHealthCheck\Check\Condition\Expression\Exception
 */
class LeftExpressionException extends EvaluatorException
{
    const TEXT_CAN_NOT_BE_FIRST = "LeftExpressionInterface can't be first token in the expression:\n%s";

    const CODE_CAN_NOT_BE_FIRST = 501;

    /**
     * @param string $expression
     * @return LeftExpressionException
     */
    public static function canNotBeFirst($expression)
    {
        return new static(sprintf(self::TEXT_CAN_NOT_BE_FIRST, $expression), self::CODE_CAN_NOT_BE_FIRST);
    }
}
