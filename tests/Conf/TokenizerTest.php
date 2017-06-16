<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 16.06.2017
     * Time: 00:36
     */

    namespace Locrian\Tests\Conf;

    use Locrian\Conf\Tokenizer\Token;
    use Locrian\Conf\Tokenizer\DefaultConfTokenizer;
    use Locrian\Conf\Tokenizer\TokenType;
    use Locrian\IO\File;
    use Locrian\Util\FileUtils;
    use PHPUnit_Framework_TestCase;

    class TokenizerTest extends PHPUnit_Framework_TestCase{

        public function testTokens1(){
            $tokenizer = new DefaultConfTokenizer();
            $tokenizer->tokenize(FileUtils::readText(new File("tests/Conf/tk.conf")));
            $arr = [ new Token("Application", TokenType::NAMESPACE), new Token("{", TokenType::CURLY_OPEN),
                new Token("key", TokenType::KEY), new Token(":=", TokenType::ASSIGN), new Token("value", TokenType::VALUE),
                new Token("}", TokenType::CURLY_CLOSE)];
            $tokenizer->getTokens()->each(function($i, Token $token) use($arr){
               self::assertEquals($arr[$i]->getToken(), $token->getToken());
                self::assertEquals($arr[$i]->getTokenType(), $token->getTokenType());
            });
        }

        public function testTokens2(){
            $tokenizer = new DefaultConfTokenizer();
            $tokenizer->tokenize(FileUtils::readText(new File("tests/Conf/tk2.conf")));
            $arr = [
                new Token("Application", TokenType::NAMESPACE), new Token("{", TokenType::CURLY_OPEN),
                new Token("Application type, production or development", TokenType::COMMENT_LINE),
                new Token("type", TokenType::KEY), new Token(":=", TokenType::ASSIGN),
                new Token("development", TokenType::VALUE), new Token("Default language", TokenType::COMMENT_LINE),
                new Token("lang", TokenType::KEY), new Token(":=", TokenType::ASSIGN), new Token("en-us", TokenType::VALUE),
                new Token("varUsage", TokenType::KEY), new Token(":=", TokenType::ASSIGN),
                new Token("\${Application.type} or \${type} the rest", TokenType::VALUE),
                new Token("}", TokenType::CURLY_CLOSE), new Token("Application.Http", TokenType::NAMESPACE),
                new Token("{", TokenType::CURLY_OPEN), new Token("Cookie configurations", TokenType::COMMENT_LINE),
                new Token("Cookie", TokenType::NAMESPACE), new Token("{", TokenType::CURLY_OPEN),
                new Token("Crypt cookie keys", TokenType::COMMENT_LINE), new Token("crypt", TokenType::KEY),
                new Token(":=", TokenType::ASSIGN), new Token("true", TokenType::VALUE),
                new Token("}", TokenType::CURLY_CLOSE), new Token("}", TokenType::CURLY_CLOSE),
                new Token("Application.Array", TokenType::NAMESPACE), new Token("{", TokenType::CURLY_OPEN),
                new Token("Arr", TokenType::KEY), new Token(":=", TokenType::ASSIGN),
                new Token("[", TokenType::SQUARE_OPEN), new Token("value1", TokenType::VALUE),
                new Token(",", TokenType::COMMA), new Token("value2", TokenType::VALUE),
                new Token("]", TokenType::SQUARE_CLOSE), new Token("arr2", TokenType::KEY),
                new Token(":=", TokenType::ASSIGN), new Token("[", TokenType::SQUARE_OPEN),
                new Token("val1", TokenType::VALUE), new Token("]", TokenType::SQUARE_CLOSE),
                new Token("}", TokenType::CURLY_CLOSE),
            ];
            $tokenizer->getTokens()->each(function($i, Token $token) use($arr){
                self::assertEquals($arr[$i]->getToken(), $token->getToken());
                self::assertEquals($arr[$i]->getTokenType(), $token->getTokenType());
            });
        }

    }