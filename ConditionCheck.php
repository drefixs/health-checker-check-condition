<?php

namespace TonicHealthCheck\Check\Condition;

use TonicHealthCheck\Check\AbstractCheck;
use TonicHealthCheck\Check\CheckResult;
use TonicHealthCheck\Check\Condition\Expression\Evaluator;
use TonicHealthCheck\Checker\ChecksList;

/**
 * Class AbstractActiveMQCheck
 * @package TonicHealthCheck\Check\ActiveMQ
 */
class ConditionCheck extends AbstractCheck
{
    /**
     * @var Evaluator;
     */
    private $evaluator;

    /**
     * @var ChecksList;
     */
    private $checksList;

    /**
     * @var string
     */
    private $checkComponent;

    /**
     * @var string
     */
    private $checkGroup;

    /**
     * @var string
     */
    private $checkIdent;


    /**
     * ConditionCheck constructor.
     * @param string     $checkNode
     * @param Evaluator  $evaluator
     * @param ChecksList $checksList
     */
    public function __construct($checkNode, Evaluator $evaluator, ChecksList $checksList)
    {
        parent::__construct($checkNode);

        $this->setEvaluator($evaluator);
        $this->setChecksList($checksList);
    }

    /**
     * @return bool
     */
    public function check()
    {
        return $this->getEvaluator()->interpret($this->getChecksList());
    }

    /**
     * @return CheckResult
     */
    public function performCheck()
    {
        return $this->check();
    }

    /**
     * @return Evaluator
     */
    public function getEvaluator()
    {
        return $this->evaluator;
    }

    /**
     * @return ChecksList
     */
    public function getChecksList()
    {
        return $this->checksList;
    }

    /**
     * @return string
     */
    public function getCheckComponent()
    {
        return null !== $this->checkComponent?$this->checkComponent:parent::getCheckComponent();
    }

    /**
     * @return string
     */
    public function getCheckGroup()
    {
        return null !== $this->checkGroup?$this->checkGroup:parent::getCheckGroup();
    }

    /**
     * @return string
     */
    public function getCheckIdent()
    {
        return null !== $this->checkIdent?$this->checkIdent:parent::getCheckIdent();
    }

    /**
     * @param string $checkComponent
     */
    public function setCheckComponent($checkComponent)
    {
        $this->checkComponent = $checkComponent;
    }

    /**
     * @param string $checkGroup
     */
    public function setCheckGroup($checkGroup)
    {
        $this->checkGroup = $checkGroup;
    }

    /**
     * @param string $checkIdent
     */
    public function setCheckIdent($checkIdent)
    {
        $this->checkIdent = $checkIdent;
    }

    /**
     * @param Evaluator $evaluator
     */
    protected function setEvaluator(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    /**
     * @param ChecksList $checksList
     */
    protected function setChecksList(ChecksList $checksList)
    {
        $this->checksList = $checksList;
    }
}