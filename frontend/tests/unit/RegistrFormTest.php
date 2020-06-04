<?php

namespace frontend\tests;

use Codeception\Util\Fixtures;
use frontend\models\forms\SignupForm;
use frontend\tests\fixtures\CategoriesFixtures;
use frontend\tests\fixtures\CitiesFixtures;
use frontend\tests\fixtures\TasksFixtures;
use frontend\tests\fixtures\UsersFixtures;

class SignUpFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
          'users' => ['class' => UsersFixtures::class],
          'categories' => ['class' => CategoriesFixtures::class],
          'cities' => ['class' => CitiesFixtures::class],
          'tasks' => ['class' => TasksFixtures::class]
        ]);
    }

    protected function _after()
    {
    }

    // tests

    /**
     * @dataProvider getRightVariantsNameAndEmail
     */
    public function testUserRegisterRight($name, $email)
    {
        $model = $this->make(SignupForm::class, [
          'user_name' => $name,
          'email' => $email,
          'city_id' => 1,
          'password' => 'dpkjvfqvtyz',
        ]);

        $message = "{$name} {$email}";

        $this->assertTrue($model->validate(), $message);
        $this->assertTrue($model->register(), $message);
    }

    public function getRightVariantsNameAndEmail()
    {
        return [
          ['Билли Миллиган', 'билли.миллиган@почта.рус'],
          ['Bill Lading', 'bill.lading@usa.com'],
          ['Lading Bill', 'ladingBill@usa.com'],
          ['Лэдинг бил', 'лэдингБил@сша.ком'],
          ['Usa Lading', 'usa@lading.biz'],
          ['asdasd sadasdas', '12321dhos@fozd.com']
        ];
    }

    /**
     * @dataProvider getWrongVariantsName
     */
    public function testUserRegisterWrongName($name)
    {
        $model = $this->make(SignupForm::class, [
          'user_name' => $name,
          'city_id' => 1,
          'email' => 'bill.lading@usa.com',
          'password' => 'dpkjvfqvtyz',
        ]);

        $message = "{$name}";

        $this->assertFalse($model->validate(), $message);
        $this->assertFalse($model->register(), $message);
    }

    public function getWrongVariantsName()
    {
        return [
          'Билли Миллиган 21' => ['Билли Миллиган 21'],
          'Bill Of Lading' => ['Bill Of Lading'],
          'Ladin12g Bill' => ['Ladin12g Bill'],
          'Лэдинг бил biz name' => ['Лэдинг бил biz name'],
          'Usa _Lading' => ['Usa _Lading'],
          'asdasd sadasds232' => ['asdasd sadasds232'],
          'asdasd' => ['asdasd'],
          '21312 123123' => ['21312 123123'],
          'User' => ['User'],
          '!!! !!!' => ['!!! !!!'],
          '!!!' => ['!!!'],
        ];
    }

    /**
     * @dataProvider getWrongVariantsEmail
     */
    public function testUserRegisterWrongEmail($email)
    {
        $model = $this->make(SignupForm::class, [
          'user_name' => 'bill lading',
          'city_id' => 1,
          'email' => $email,
          'password' => 'dpkjvfqvtyz',
        ]);

        $message = "{email}";

        $this->assertFalse($model->validate(), $message);
        $this->assertFalse($model->register(), $message);
    }

    public function getWrongVariantsEmail()
    {
        return [
          'eml' => ['em1'],
          'Bill Of Lading@mal.ru' => ['Bill Of Lading@mal.ru'],
          'Ladin12g@Bill' => ['Ladin12g@Bill'],
          'Лэдинг @бил @biz name' => ['Лэдинг @бил @biz name'],
          'Usa@_Lading.com' => ['Usa@_Lading.com'],
          'asdasd@sadasds232.com' => ['asdasd@sadasds232.com'],
          'asdasd' => ['asdasd'],
          '21312 123123' => ['21312 123123'],
          '!!!@!!!.rus' => ['!!!@!!!.rus'],
          'lasing,bill@mail.com' => ['lasing,bill@mail.com'],
        ];
    }
}
