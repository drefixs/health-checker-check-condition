<?php

$config = $container['config'];

$container['checker.condition'] = $config['condition'];

$container['checker.condition_check'] = function (Container $c) {
    $containerCheckCollection = new ConditionCheckCollection();

    foreach ($c['checker.condition'] as $checkNode => $nodeConfig) {
        $expression = $nodeConfig['expression'];



        $evaluator = new Evaluator($expression);

        $checksList = $c['checker.checks_list_link'];

        $conditionCheck = new ConditionCheck(
            $checkNode,
            $evaluator,
            $checksList
        );
        if (isset($nodeConfig['check_component'])) {
            $conditionCheck->setCheckComponent($nodeConfig['check_component']);
        }
        if (isset($nodeConfig['check_group'])) {
            $conditionCheck->setCheckGroup($nodeConfig['check_group']);
        }
        if (isset($nodeConfig['check_ident'])) {
            $conditionCheck->setCheckIdent($nodeConfig['check_ident']);
        }
        $containerCheckCollection->add($conditionCheck);
    }

    return $containerCheckCollection;
};
