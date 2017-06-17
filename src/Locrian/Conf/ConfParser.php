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

    namespace Locrian\Conf;

    use Locrian\Collections\ArrayList;
    use Locrian\Collections\Stack;
    use Locrian\Conf\Tokenizer\Tokenizer;
    use Locrian\Conf\Tokenizer\TokenType;
    use Locrian\InvalidArgumentException;
    use Locrian\Util\StringUtils;
    use Symfony\Component\Yaml\Exception\ParseException;

    class ConfParser{

        /**
         * @var Tokenizer
         */
        private $tokenizer;


        /**
         * ConfParser constructor.
         *
         * @param \Locrian\Conf\Tokenizer\Tokenizer $tokenizer
         */
        public function __construct(Tokenizer $tokenizer){
            $this->tokenizer = $tokenizer;
        }


        /**
         * @param $content
         * @return \Locrian\Conf\ConfTree
         * Parses the content and creates ConfTree
         */
        public function parseTree($content){
            $it = $this->getTokens($content)->iterator();
            $root = new ConfTree(ConfTree::NAMESPACE_NODE);
            $namespaceStack = new Stack();
            $tokenStack = new Stack();
            while( $it->hasNext() ){
                $token = $it->next();
                switch($token->getTokenType()){
                    case TokenType::NS:
                        $tokenStack->push($token);
                        break;
                    case TokenType::CURLY_OPEN:
                        $co = $tokenStack->top();
                        if( $co != null && $co->getTokenType() == TokenType::NS ){
                            $ns = $tokenStack->pop();
                            $parent = $namespaceStack->isEmpty() ? $root : $namespaceStack->top();
                            $namespaceStack->push($this->createNamespaceNode($parent, $ns->getToken()));
                        }
                        else{
                            throw new ParseException("Invalid namespace declaration");
                        }
                        break;
                    case TokenType::CURLY_CLOSE:
                        $cNs = $namespaceStack->pop();
                        $pNs = $namespaceStack->top();
                        if( $cNs != null ){
                            if( $cNs->getParent() == null ){
                                if( $pNs == null ){
                                    $root->add($cNs);
                                }
                                else{
                                    $pNs->add($cNs);
                                }
                            }
                        }
                        else{
                            throw new ParseException("Invalid curly brackets");
                        }
                        break;
                    case TokenType::SQUARE_OPEN:
                        $ass = $tokenStack->pop();
                        $key = $tokenStack->top();
                        $tokenStack->push($ass);
                        if( $ass != null && $key != null && $ass->getTokenType() == TokenType::ASSIGN && $key->getTokenType() == TokenType::KEY ){
                            $tokenStack->push($token);
                        }
                        else{
                            throw new ParseException("Invalid array declaration");
                        }
                        break;
                    case TokenType::SQUARE_CLOSE:
                        $tokenStack->push($token);
                        $node = $this->createArrayNode($tokenStack);
                        $parent = $namespaceStack->top();
                        if( $parent != null ){
                            $parent->add($node);
                        }
                        else{
                            throw new ParseException("Missing namespace of array");
                        }
                        break;
                    case TokenType::ASSIGN:
                        $tokenStack->push($token);
                        break;
                    case TokenType::KEY:
                        if( preg_match("/[a-zA-Z0-9]+/", $token->getToken()) ){
                            $tokenStack->push($token);
                        }
                        else{
                            throw new ParseException("Invalid key: " . $token->getToken());
                        }
                        break;
                    case TokenType::VALUE:
                        $prev = $tokenStack->top();
                        if( $prev != null && ($prev->getTokenType() == TokenType::COMMA ||
                                $prev->getTokenType() == TokenType::ASSIGN || $prev->getTokenType() == TokenType::SQUARE_OPEN) ){
                            if( $prev->getTokenType() == TokenType::ASSIGN ){
                                $tokenStack->push($token);
                                $node = $this->createObjectNode($tokenStack);
                                $parent = $namespaceStack->top();
                                if( $parent != null ){
                                    $parent->add($node);
                                }
                                else{
                                    throw new ParseException("Missing namespace");
                                }
                            }
                            else{
                                $tokenStack->push($token);
                            }
                        }
                        break;
                    case TokenType::COMMA:
                        $tokenStack->push($token);
                        break;
                    case TokenType::COMMENT_LINE:
                        // Do nothing
                        break;
                }
            }
            if( !$namespaceStack->isEmpty() || !$tokenStack->isEmpty() ){
                throw new ParseException("Unable to parse .conf");
            }
            return $root;
        }


        /**
         * @param \Locrian\Collections\Stack $stack
         * @return \Locrian\Conf\ConfTree
         */
        private function createArrayNode(Stack $stack){
            if( $stack->top() != null && ($stack->top()->getTokenType() != TokenType::SQUARE_CLOSE || $stack->size() < 2) ){
                throw new ParseException("Invalid array declaration");
            }
            $list = [];
            $stack->pop(); // Remove ']' token
            $lastType = null;
            while( !$stack->isEmpty() && $stack->top()->getTokenType() != TokenType::SQUARE_OPEN ){
                $token = $stack->pop();
                if( $lastType == TokenType::VALUE && $token->getTokenType() == TokenType::COMMA ){
                    // Do nothing
                }
                else{
                    if( ($lastType == null || $lastType == TokenType::COMMA) && $token->getTokenType() == TokenType::VALUE ){
                        $list[] = $this->processValue($token->getToken());
                    }
                    else{
                        throw new ParseException("Invalid array declaration");
                    }
                }
                $lastType = $token->getTokenType();
            }
            $sqOpen = $stack->pop();
            if( $sqOpen == null || $sqOpen->getTokenType() != TokenType::SQUARE_OPEN ){
                throw new ParseException("Invalid array declaration");
            }
            else{
                $ass = $stack->pop();
                $key = $stack->pop();
                if( $ass != null && $key != null && $ass->getTokenType() == TokenType::ASSIGN && $key->getTokenType() == TokenType::KEY ){
                    $arrayNode = new ConfTree(ConfTree::ARRAY_NODE);
                    $arrayNode->setKey($key->getToken());
                    $arrayNode->setValue(new ArrayList(array_reverse($list)));
                    return $arrayNode;
                }
                else{
                    throw new ParseException("Invalid array declaration");
                }
            }
        }


        /**
         * @param \Locrian\Collections\Stack $stack
         * @return \Locrian\Conf\ConfTree
         */
        private function createObjectNode(Stack $stack){
            $v = $stack->pop();
            $a = $stack->pop();
            $k = $stack->pop();
            if( $v != null && $a != null && $k != null &&
                $v->getTokenType() == TokenType::VALUE && $a->getTokenType() == TokenType::ASSIGN && $k->getTokenType() == TokenType::KEY ){
                $node = new ConfTree(ConfTree::OBJECT_NODE);
                $node->setKey($k->getToken());
                $node->setValue($this->processValue($v->getToken()));
                return $node;
            }
            else{
                throw new ParseException("Invalid assignment");
            }
        }


        /**
         * @param $val string
         * @return bool|float|int|null|string
         */
        private function processValue($val){
            $valCp = mb_strtolower($val);
            if( $valCp == "true" ){
                return true;
            }
            if( $valCp == "false" ){
                return false;
            }
            if( $valCp == "null" ){
                return null;
            }
            if( preg_match("/^[0-9\.]+$/", $val) ){
                if( StringUtils::contains(".", $val) ){
                    return doubleval($val);
                }
                else{
                    return intval($val);
                }
            }
            return $val;
        }


        /**
         * @param \Locrian\Conf\ConfTree $parent
         * @param string $namespace
         * @return \Locrian\Conf\ConfTree
         */
        private function createNamespaceNode(ConfTree $parent, $namespace){
            $nsTokens = explode(".", $namespace);
            $len = count($nsTokens);
            if( $len > 1 ){
                if( !StringUtils::isBlank($nsTokens[0]) ){
                    if( $parent->has($nsTokens[0]) ){
                        if( $parent->get($nsTokens[0])->getType() !== ConfTree::NAMESPACE_NODE ){
                            throw new ParseException("Invalid namespace declaration");
                        }
                        $node = $parent->get($nsTokens[0]);
                    }
                    else{
                        $node = new ConfTree(ConfTree::NAMESPACE_NODE);
                        $node->setKey($nsTokens[0]);
                        $parent->add($node);
                    }
                    $tmp = $node;
                    for( $i = 1; $i < $len; $i++ ){
                        if( !StringUtils::isBlank($nsTokens[$i]) ){
                            if( $tmp->has($nsTokens[$i]) ){
                                if( $tmp->get($nsTokens[0])->getType() !== ConfTree::NAMESPACE_NODE ){
                                    throw new ParseException("Invalid namespace declaration");
                                }
                                $n = $tmp->get($nsTokens[$i]);
                            }
                            else{
                                $n = new ConfTree(ConfTree::NAMESPACE_NODE);
                                $n->setKey($nsTokens[$i]);
                                $tmp->add($n);
                            }
                            $tmp = $n;
                        }
                        else{
                            throw new ParseException("Invalid namespace declaration");
                        }
                    }
                    return $tmp;
                }
                else{
                    throw new ParseException("Invalid namespace declaration");
                }
            }
            else{
                $node = new ConfTree(ConfTree::NAMESPACE_NODE);
                $node->setKey($namespace);
                return $node;
            }
        }


        /**
         * @param string $content
         * @return \Locrian\Collections\Queue
         * @throws \Locrian\InvalidArgumentException
         */
        private function getTokens($content){
            if( !is_string($content) ){
                throw new InvalidArgumentException("Invalid content");
            }
            $this->tokenizer->tokenize($content);
            return $this->tokenizer->getTokens();
        }

    }