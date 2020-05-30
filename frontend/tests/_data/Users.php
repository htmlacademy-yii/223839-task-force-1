<?php

use frontend\models\Users;

return [
  [
    'id' => 1,
    'first_name' => 'Виктория',
    'last_name' => 'Соловьева',
    'address' => 'ул. 1905 года, 89',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 1,
    'password' => 'dpkjvfqvtyz',
    'birthday' => '1971-05-23',
    'role' => Users::ROLE_PERFORMER,
    'is_public' => 1,
    'date_joined' => '2020-01-27 09:11:25',
    'last_activity' => '2020-04-10 00:00:00',
    'phone' => 73868714210,
    'email' => 'victoria.fix@mail.ru',
    'skype' => 'victoria123',
    'telegram' => 'vika_solovyeva',
    'visit_counter' => 1502
  ],
  [
    'id' => 2,
    'first_name' => 'Евгений',
    'last_name' => 'Рудин',
    'address' => 'ул. 1927 года, 89',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 4,
    'password' => 'ytdpkfvsdfqvtyz',
    'birthday' => '1979-05-23',
    'role' => Users::ROLE_CUSTOMER,
    'is_public' => 1,
    'date_joined' => '2019-07-07 19:11:25',
    'last_activity' => '2020-05-10 00:00:00',
    'phone' => 79868714210,
    'email' => 'банк@рф.рус',
    'skype' => 'bankrf.rudin',
    'telegram' => 'rudinevgenij',
    'visit_counter' => 9554
  ],
  [
    'id' => 3,
    'first_name' => 'Bill',
    'last_name' => 'Lading',
    'address' => '1600 Amphitheatre Parkway Mountain View, CA 94043',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 1,
    'password' => 'dpkjvfqvtyz',
    'birthday' => '1979-01-23',
    'role' => Users::ROLE_CUSTOMER,
    'is_public' => 1,
    'date_joined' => '2020-01-27 17:11:25',
    'last_activity' => '2020-05-15 00:00:00',
    'phone' => 13868714210,
    'email' => 'bill.lading@google.com',
    'skype' => 'bill_lading.google',
    'telegram' => 'bill_lading',
    'visit_counter' => 99454
  ],
  [
    'id' => 4,
    'first_name' => 'Delete',
    'last_name' => 'User',
    'address' => 'DeleteUser',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 2,
    'password' => 'dpkjvfqvtyz',
    'birthday' => '1971-05-23',
    'role' => Users::ROLE_PERFORMER,
    'is_public' => 1,
    'date_joined' => '2020-01-27 09:11:25',
    'last_activity' => '2020-04-10 00:00:00',
    'phone' => 73863714211,
    'email' => 'victdelet@delete.delete',
    'skype' => 'deleteDelete',
    'telegram' => 'deletevyeva',
    'visit_counter' => 15
  ],
  [
    'id' => 5,
    'first_name' => 'Степан',
    'last_name' => 'Иванов',
    'address' => 'Kievsky railway station',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 2,
    'password' => 'dpkjvfqvtyz',
    'birthday' => '1971-05-23',
    'role' => Users::ROLE_PERFORMER,
    'is_public' => 1,
    'date_joined' => '2020-01-27 09:11:25',
    'last_activity' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
    'phone' => 73863714212,
    'email' => 'victdelet@delete.delete',
    'skype' => 'deleteDelete',
    'telegram' => 'deletevyeva',
    'visit_counter' => 15
  ],
  [
    'id' => 6,
    'first_name' => 'John',
    'last_name' => 'Ivanov',
    'address' => 'New railway station',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 1,
    'password' => 'dpkjvfqvtyz',
    'birthday' => '1971-05-23',
    'role' => Users::ROLE_PERFORMER,
    'is_public' => 1,
    'date_joined' => '2020-01-27 09:11:25',
    'last_activity' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
    'phone' => 72863714210,
    'email' => 'victdelet@delete.delete',
    'skype' => 'deleteDelete',
    'telegram' => 'deletevyeva',
    'visit_counter' => 15
  ],
  [
    'id' => 7,
    'first_name' => 'Johns',
    'last_name' => 'Ivanov',
    'address' => 'New railway station',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 5,
    'password' => 'dpkjvfqvtyz',
    'birthday' => '1971-05-23',
    'role' => Users::ROLE_PERFORMER,
    'is_public' => 1,
    'date_joined' => '2020-01-27 09:11:25',
    'last_activity' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
    'phone' => 72863714250,
    'email' => 'victdelet@delete.delete',
    'skype' => 'deleteDelete',
    'telegram' => 'deletevyeva',
    'visit_counter' => 15
  ],
  [
    'id' => 8,
    'first_name' => 'Bill',
    'last_name' => 'Ivanov',
    'address' => 'New railway station',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 5,
    'password' => 'dpkjvfqvtyz',
    'birthday' => '1971-05-23',
    'role' => Users::ROLE_PERFORMER,
    'is_public' => 1,
    'date_joined' => '2020-01-27 09:11:25',
    'last_activity' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
    'phone' => 72863714210,
    'email' => 'victdeet@delete.delete',
    'skype' => 'deleteDelete',
    'telegram' => 'deletevyeva',
    'visit_counter' => 15
  ],
  [
    'id' => 9,
    'first_name' => 'Bill',
    'last_name' => 'Clinton',
    'address' => 'New railway station',
    'biography' => 'Non modi aut et ad voluptas sit ut amet. Dicta ut cupiditate a id hic fugit aut. Voluptatum non quam ut blanditiis vitae nesciunt. Sed rerum error voluptatem dolorum. Consequatur numquam natus voluptas deserunt. Vel quasi nisi ut non omnis est animi architecto. Eaque voluptas voluptas molestias quae illo hic. Maxime qui hic sit perferendis architecto. Quaerat quia iusto doloremque quod ipsam officiis. Et pariatur est earum id dolorem. Dolorem nulla perferendis quo animi ut.',
    'city_id' => 2,
    'password' => 'dpkjvfqvtyz',
    'birthday' => '1971-05-23',
    'role' => Users::ROLE_PERFORMER,
    'is_public' => 1,
    'date_joined' => '2020-01-27 09:11:25',
    'last_activity' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s'),
    'phone' => 12863714210,
    'email' => 'victdeet@delete.delete',
    'skype' => 'deleteDelete',
    'telegram' => 'deletevyeva',
    'visit_counter' => 150
  ],
];