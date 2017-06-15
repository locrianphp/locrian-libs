<?php

    /**
     * * * * * * * * * * * * * * * * * * * *
     *        Locrian Framework            *
     * * * * * * * * * * * * * * * * * * * *
     *                                     *
     * Author  : Özgür Senekci             *
     *                                     *
     * Skype   :  socialinf                *
     *                                     *
     * License : The MIT License (MIT)     *
     *                                     *
     * * * * * * * * * * * * * * * * * * * *
     */

    namespace Locrian\Validation;

    use Locrian\Validation\Rule\AlnumRule;
    use Locrian\Validation\Rule\AlnumsRule;
    use Locrian\Validation\Rule\AlphaRule;
    use Locrian\Validation\Rule\AlphasRule;
    use Locrian\Validation\Rule\BetweenRule;
    use Locrian\Validation\Rule\EmailRule;
    use Locrian\Validation\Rule\MaxRule;
    use Locrian\Validation\Rule\MinRule;
    use Locrian\Validation\Rule\NumberRule;
    use Locrian\Validation\Rule\RequiredRule;
    use Locrian\Validation\Rule\UrlRule;

    class DefaultRuleProvider implements RuleProvider{

        public function registerRules(Validator $validator){
            $validator->addRule(AlnumRule::class, "Only alphanumeric characters are allowed");
            $validator->addRule(AlnumsRule::class, "Only alphanumeric characters are allowed");
            $validator->addRule(AlphaRule::class, "Only alphabetic characters are allowed");
            $validator->addRule(AlphasRule::class, "Only alphabetic characters are allowed");
            $validator->addRule(BetweenRule::class, "Only sizes between $0 and $1 are allowed");
            $validator->addRule(EmailRule::class, "Invalid email address");
            $validator->addRule(MaxRule::class, "Maximum $0 characters are allowed");
            $validator->addRule(MinRule::class, "Minimum $0 characters are allowed");
            $validator->addRule(NumberRule::class, "Only numeric characters are allowed");
            $validator->addRule(RequiredRule::class, "This field cannot be empty");
            $validator->addRule(UrlRule::class, "Invalid url");
        }

    }