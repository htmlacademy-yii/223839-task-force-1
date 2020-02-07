<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root . '/vendor/autoload.php';

use Logic\Task;
use src\Logic\actions\ActionCancel;
use src\Logic\actions\ActionComplete;
use src\Logic\actions\ActionRefusal;
use src\Logic\actions\ActionStart;
?>
////Task::STATUS_NEW 0 <br>
////Task::STATUS_CANCELED 1 <br>
////Task::STATUS_ACTIVE 2 <br>
////Task::STATUS_COMPLETED 3 <br>
////Task::STATUS_FAILED 4 <br>
    <hr><br>
<?php
$testTask = new Task(1,0);
$testActionComplete = new ActionComplete();
$testActionCancel = new ActionCancel();
$testActionRefusal = new ActionRefusal();
$testActionStart = new ActionStart();
var_dump($testTask->getActionForStatus($testTask::STATUS_NEW));



class TestMe
{
    public function __construct($action, $customerID, $performerID, $currentUsedID, $status)
    {
        echo
        '<br>статус: ' .  $status .
        '<hr><br<hr>ID заказчика: ' . $customerID .
        '<br><hr>ID исполнителя: ' . $performerID .
        '<br><hr>ID пользователя: ' . $currentUsedID .
        '<br><hr>действие: ' . $action->getInnerName() . '<br><hr> Результат: ';
        if ($action->checkRights($customerID, $performerID, $currentUsedID, $status)) {
            echo 'SUCCESS ';
            echo '<hr style="height: 5px; background: green;">';
        } else {
            echo 'ERROR ';
            echo '<hr style="height: 5px; background: red;">';
        }

    }
}

?>
<div style="display: flex; margin: 10px;">
    STATUS NEW
    <div style="width: 50%;">
        <?php echo 'Заказчик не может начать заказ';
        new TestMe($testActionStart, $testTask->customerID, $testTask->performerID, 1, Task::STATUS_NEW); ?>
    </div>
    <div style="width: 50%;;">
        <?php echo 'Исполнитель может откликнутся на заказ';
        new TestMe($testActionStart, $testTask->customerID, $testTask->performerID, 0, Task::STATUS_NEW); ?>
    </div>
</div>

<div style="display: flex; margin: 10px;">
    STATUS ACTIVE
    <div style="width: 50%;">
        <?echo 'Заказчик не может отказаться от заказа';
        new TestMe($testActionRefusal, $testTask->customerID, $testTask->performerID, 1, Task::STATUS_ACTIVE);?>
     </div>
    <div style="width: 50%;;">
        <?php echo 'Исполнитель может отказаться от заказа';
        new TestMe($testActionRefusal, $testTask->customerID, $testTask->performerID, 0, Task::STATUS_ACTIVE);?>
    </div>
</div>

<div style="display: flex; margin: 10px;">
    STATUS ACTIVE
    <div style="width: 50%;">
        <?php echo 'Исполнитель не может подтвердить выполнения заказа';
        new TestMe($testActionComplete, $testTask->customerID, $testTask->performerID, 0, Task::STATUS_ACTIVE);?>
    </div>
    <div style="width: 50%;;">
        <?php echo 'Заказчик может подтвердить выполнения заказа';
        new TestMe($testActionComplete, $testTask->customerID, $testTask->performerID, 1, Task::STATUS_ACTIVE);?>
    </div>
</div>





