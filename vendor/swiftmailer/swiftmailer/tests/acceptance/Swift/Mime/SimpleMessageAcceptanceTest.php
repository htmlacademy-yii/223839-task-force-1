<?php

class Swift_Mime_SimpleMessageAcceptanceTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        Swift_Preferences::getInstance()->setCharset(null); //TODO: Test with the charset defined
    }

    public function testBasicHeaders()
    {
        /* -- RFC 2822, 3.6.
     */

        $message = $this->createMessage();
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString(),
            '%s: Only required headers, and non-empty headers should be displayed'
            );
    }

    public function testSubjectIsDisplayedIfSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testDateCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $id = $message->getId();
        $date = new DateTimeImmutable();
        $message->setDate($date);
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testMessageIdCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setId('foo@bar');
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <foo@bar>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testContentTypeCanBeChanged()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setContentType('text/html');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/html'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testCharsetCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setContentType('text/html');
        $message->setCharset('iso-8859-1');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/html; charset=iso-8859-1'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testFormatCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFormat('flowed');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain; format=flowed'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testEncoderCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setContentType('text/html');
        $message->setEncoder(
            new Swift_Mime_ContentEncoder_PlainContentEncoder('7bit')
            );
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/html'."\r\n".
            'Content-Transfer-Encoding: 7bit'."\r\n",
            $message->toString()
            );
    }

    public function testFromAddressCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom('chris.corbyn@swiftmailer.org');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: chris.corbyn@swiftmailer.org'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testFromAddressCanBeSetWithName()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris Corbyn']);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testMultipleFromAddressesCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn',
            'mark@swiftmailer.org',
            ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>, mark@swiftmailer.org'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testReturnPathAddressCanBeSet()
    {
        $message = $this->createMessage();
        $message->setReturnPath('chris@w3style.co.uk');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Return-Path: <chris@w3style.co.uk>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testEmptyReturnPathHeaderCanBeUsed()
    {
        $message = $this->createMessage();
        $message->setReturnPath('');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Return-Path: <>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testSenderCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setSender('chris.corbyn@swiftmailer.org');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Sender: chris.corbyn@swiftmailer.org'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testSenderCanBeSetWithName()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setSender(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Sender: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testReplyToCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo(['chris@w3style.co.uk' => 'Myself']);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testMultipleReplyAddressCanBeUsed()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testToAddressCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo('mark@swiftmailer.org');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testMultipleToAddressesCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo([
            'mark@swiftmailer.org', 'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org, Chris Corbyn <chris@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testCcAddressCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo([
            'mark@swiftmailer.org', 'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $message->setCc('john@some-site.com');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org, Chris Corbyn <chris@swiftmailer.org>'."\r\n".
            'Cc: john@some-site.com'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testMultipleCcAddressesCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo([
            'mark@swiftmailer.org', 'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $message->setCc([
            'john@some-site.com' => 'John West',
            'fred@another-site.co.uk' => 'Big Fred',
            ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org, Chris Corbyn <chris@swiftmailer.org>'."\r\n".
            'Cc: John West <john@some-site.com>, Big Fred <fred@another-site.co.uk>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testBccAddressCanBeSet()
    {
        //Obviously Transports need to setBcc(array()) and send to each Bcc recipient
        // separately in accordance with RFC 2822/2821
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo([
            'mark@swiftmailer.org', 'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $message->setCc([
            'john@some-site.com' => 'John West',
            'fred@another-site.co.uk' => 'Big Fred',
            ]);
        $message->setBcc('x@alphabet.tld');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org, Chris Corbyn <chris@swiftmailer.org>'."\r\n".
            'Cc: John West <john@some-site.com>, Big Fred <fred@another-site.co.uk>'."\r\n".
            'Bcc: x@alphabet.tld'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testMultipleBccAddressesCanBeSet()
    {
        //Obviously Transports need to setBcc(array()) and send to each Bcc recipient
        // separately in accordance with RFC 2822/2821
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo([
            'mark@swiftmailer.org', 'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $message->setCc([
            'john@some-site.com' => 'John West',
            'fred@another-site.co.uk' => 'Big Fred',
            ]);
        $message->setBcc(['x@alphabet.tld', 'a@alphabet.tld' => 'A']);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org, Chris Corbyn <chris@swiftmailer.org>'."\r\n".
            'Cc: John West <john@some-site.com>, Big Fred <fred@another-site.co.uk>'."\r\n".
            'Bcc: x@alphabet.tld, A <a@alphabet.tld>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testStringBodyIsAppended()
    {
        $message = $this->createMessage();
        $message->setReturnPath('chris@w3style.co.uk');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);
        $message->setBody(
            'just a test body'."\r\n".
            'with a new line'
            );
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Return-Path: <chris@w3style.co.uk>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n".
            "\r\n".
            'just a test body'."\r\n".
            'with a new line',
            $message->toString()
            );
    }

    public function testStringBodyIsEncoded()
    {
        $message = $this->createMessage();
        $message->setReturnPath('chris@w3style.co.uk');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);
        $message->setBody(
            'Just s'.pack('C*', 0xC2, 0x01, 0x01).'me multi-'."\r\n".
            'line message!'
            );
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Return-Path: <chris@w3style.co.uk>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n".
            "\r\n".
            'Just s=C2=01=01me multi-'."\r\n".
            'line message!',
            $message->toString()
            );
    }

    public function testChildrenCanBeAttached()
    {
        $message = $this->createMessage();
        $message->setReturnPath('chris@w3style.co.uk');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);

        $id = $message->getId();
        $date = $message->getDate();
        $boundary = $message->getBoundary();

        $part1 = $this->createMimePart();
        $part1->setContentType('text/plain');
        $pa