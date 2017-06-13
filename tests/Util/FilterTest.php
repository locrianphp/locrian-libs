<?php

    /**
     * Created by PhpStorm.
     * User: social13
     * Date: 13.06.2017
     * Time: 15:36
     */

    namespace Locrian\Tests\Util;

    use PHPUnit_Framework_TestCase;
    use Locrian\Util\Filter;

    class FilterTest extends PHPUnit_Framework_TestCase{

        /**
         * @var Filter
         */
        private $filter;

        protected function setUp(){
            $this->filter = new Filter();
        }

        public function testQuoteFilter(){
            $sentence = "Don't let \"me\" go";
            $encoded = "Don&#39;t let &#34;me&#34; go";
            $cleared = "Dont let me go";
            self::assertEquals($encoded, $this->filter->encodeQuotes($sentence));
            self::assertEquals($sentence, $this->filter->decodeQuotes($encoded));
            self::assertEquals($cleared, $this->filter->clearQuotes($sentence));
        }

        public function testPhpTagFilter(){
            $sentence = "<?php echo 'Hello!'; ?>";
            $encoded = "&#60;&#63;php echo 'Hello!'; &#63;&#62;";
            $cleared = " echo 'Hello!'; ";
            self::assertEquals($encoded, $this->filter->encodePhpTags($sentence));
            self::assertEquals($sentence, $this->filter->decodePhpTags($encoded));
            self::assertEquals($cleared, $this->filter->clearPhpTags($sentence));
        }

        public function testBadCharsFilter(){
            $sentence = "select * (user) where id = \${id}";
            $encoded = "select &#42; &#40;user&#41; where id &#61; &#36;&#123;id&#125;";
            $cleared = "select  user where id  id";
            self::assertEquals($encoded, $this->filter->encodeBadChars($sentence));
            self::assertEquals($sentence, $this->filter->decodeBadChars($encoded));
            self::assertEquals($cleared, $this->filter->clearBadChars($sentence));
        }

        public function testStripTagsFilter(){
            $sentence = "<a><span>Locrian</span> Framework</a>";
            $cleared = "Locrian Framework";
            self::assertEquals($cleared, $this->filter->stripTags($sentence));
        }

        public function testStripTagsWhiteList(){
            $sentence = "<a><span>Locrian</span> Framework</a>";
            $cleared = "<a>Locrian Framework</a>";
            self::assertEquals($cleared, $this->filter->stripTags($sentence, "<a>"));
        }

        public function testStripTagsDecode(){
            $sentence = "&#60;a&#62;Locrian/Framework&#60;&#47;a&#62;";
            $cleared = "Locrian/Framework";
            self::assertEquals($cleared, $this->filter->stripTags($sentence));
        }

        public function testClearUrlFilter(){
            $sentence = "https://www.google.com.tr/#q=��locrian%20framework";
            $cleared = "https://www.google.com.tr/#q=locrian+framework";
            self::assertEquals($cleared, $this->filter->clearUrl($sentence));
        }

    }