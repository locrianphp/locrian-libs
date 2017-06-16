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

    namespace Locrian\Conf\Tokenizer;

    use Locrian\Collections\ArrayList;
    use Locrian\Util\StringUtils;

    class Tokenizer{

        /**
         * @var string content
         */
        private $content;


        /**
         * @var \Locrian\Collections\ArrayList tokens
         */
        private $tokens;


        /**
         * Tokenizer constructor.
         *
         * @param string $content
         */
        public function __construct($content){
            $this->content = $content;
            $this->tokens = new ArrayList();
        }


        /**
         * Tokenize all the conf file
         */
        public function tokenize(){
            $lines = explode("\n", $this->content);
            foreach( $lines as $line ){
                $len = strlen($line);
                $buffer = "";
                for( $pos = 0; $pos < $len; $pos++ ){
                    $char = $line[$pos];
                    switch($char){
                        case '{':
                            if( $pos > 0 && $line[$pos - 1] == '$' ){
                                $buffer .= $char;
                            }
                            else{
                                $this->tokens->add(new Token(trim($buffer), TokenType::NAMESPACE));
                                $buffer = "";
                                $this->tokens->add(new Token($char, TokenType::CURLY_OPEN));
                            }
                            break;
                        case '}';
                            if( $pos > 0 && StringUtils::contains("\${", $buffer) ){
                                $buffer .= $char;
                            }
                            else{
                                if( strlen(trim($buffer)) > 0 ){
                                    $this->tokens->add(new Token(trim($buffer), TokenType::VALUE));
                                    $buffer = "";
                                }
                                $this->tokens->add(new Token($char, TokenType::CURLY_CLOSE));
                            }
                            break;
                        case '[':
                            $this->tokens->add(new Token($char, TokenType::SQUARE_OPEN));
                            break;
                        case ']':
                            if( strlen(trim($buffer)) > 0 ){
                                $this->tokens->add(new Token(trim($buffer), TokenType::VALUE));
                                $buffer = "";
                            }
                            $this->tokens->add(new Token($char, TokenType::SQUARE_CLOSE));
                            break;
                        case ',':
                            if( strlen(trim($buffer)) > 0 ){
                                $this->tokens->add(new Token(trim($buffer), TokenType::VALUE));
                                $buffer = "";
                            }
                            $this->tokens->add(new Token($char, TokenType::COMMA));
                            break;
                        case ':':
                            if( $pos < ($len - 1) && $line[$pos + 1] == '=' ){
                                $pos++;
                                if( strlen($buffer) > 0 ){
                                    $this->tokens->add(new Token(trim($buffer), TokenType::KEY));
                                    $buffer = "";
                                }
                                $this->tokens->add(new Token(":=", TokenType::ASSIGN));
                            }
                            break;
                        case '#':
                            while( $char != '\n' && $pos < $len ){
                                $char = $line[$pos];
                                $buffer .= $char;
                                $pos++;
                            }
                            $buffer = trim(str_replace("#", "", $buffer));
                            $this->tokens->add(new Token($buffer, TokenType::COMMENT_LINE));
                            $buffer = "";
                            break;
                        default: // add buffer
                            $buffer .= $char;
                    }
                }
                $buffer = trim($buffer);
                if( strlen($buffer) > 0 && $this->tokens->size() > 0 ){
                    $lastToken = $this->tokens->last();
                    if( $lastToken->getTokenType() == TokenType::ASSIGN ||
                        $lastToken->getTokenType() == TokenType::COMMA || $lastToken->getTokenType() == TokenType::SQUARE_OPEN ){
                        $this->tokens->add(new Token($buffer, TokenType::VALUE));
                    }
                }
            }
        }


        /**
         * @return \Locrian\Collections\ArrayList
         */
        public function getTokens(){
            return $this->tokens;
        }

    }