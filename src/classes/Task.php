<?php

namespace src\classes;

/**
 *  Класс определяет списки действий и статусов, а также выполняет базовую работу с ними.
 */
class Task
{

    const ROLE_GUEST = 'гость';
    const ROLE_CUSTOMER = 'клиент';
    const ROLE_PERFORMER = 'исполнитель';

    const ACTION_START = 'начать';
    const ACTION_REFUSAL = 'отказаться';
    const ACTION_PERFORM = 'в работе';
    const ACTION_COMPLETE = 'завершить';
    const ACTION_CANCEL = 'отменить';

    const STATUS_NEW = 'новый';
    const STATUS_CANCELED = 'отменен';
    const STATUS_ACTIVE = 'в исполнении';
    const STATUS_COMPLETED = 'завершен';
    const STATUS_FAILED = 'провален';

    // users
    private $customerID;
    private $performerID;
    // task status
    private $currentTaskStatus = self::STATUS_ACTIVE;
    private $nextStatus;
    // status after action
    private $nextStatusCustomer;
    private $nextStatusPerformer;

    // action
    private $currentActive;
    private $allActive = [];
    private $allStatus = [];
    // allow action
    private $allowActiveCustomer;
    private $allowActivePerformer;

    public function __construct($customerID, $performerID, $currentActive)
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;
        $this->currentActive= $currentActive;
        $this->getTaskStatus();
        $this->getStatus();
        var_dump($this->allowActivePerformer);
        var_dump($this->allowActiveCustomer);
        echo '<br>';


        // вывод полученных данных
        echo 'Статус задачи: <span style="color: red;">' . $this->currentTaskStatus . '</span><br>
        Текущее действие: <span style="color: red;">' . $this->currentActive . '</span><br>
        Статус после действия заказчика: <span style="color: red">' . $this->nextStatusCustomer . '</span><br>
        Статус после действия исполнителя: <span style="color: red">' . $this->nextStatusPerformer . '</span><br>
        Доступные действия заказчику: <span style="color: red;">' . $this->allowActiveCustomer . '</span><br>
        Доступные действия исполнителю: <span style="color: red;">' . $this->allowActivePerformer . '</span><br>';
    }

    /**
     *  метод получения всех статусов
     */
    private static function getAllStatus()
    {
        return [
            'STATUS_NEW_TASK' => self::STATUS_NEW,
            'STATUS_CANCELED_TASK' => self::STATUS_CANCELED,
            'STATUS_ACTIVE_TASK' => self::STATUS_ACTIVE,
            'STATUS_COMPLETED_TASK' => self::STATUS_COMPLETED,
            'STATUS_FAILED_TASK' => self::STATUS_FAILED,
        ];
    }

    /*
     *  метод получения всех действий
     */
   private static function getAllActive()
    {
        return [
            'ACTIVE_START_TASK' => self::ACTION_START,
            'ACTIVE_REFUSAL_TASK' => self::ACTION_REFUSAL,
            'ACTIVE_PERFORM_TASK' => self::ACTION_PERFORM,
            'ACTIVE_COMPLETE_TASK' => self::ACTION_COMPLETE,
            'ACTIVE_CANCEL_TASK' => self::ACTION_CANCEL
        ];
    }

    /**
     * метод для получения статуса задачи
     */
    private function getTaskStatus()
    {
        // получаю массив всех действий и статусов
        $this->allActive = self::getAllActive();
        $this->allStatus = self::getAllStatus();

        // класс имеет метод для получения статуса, в которой он перейдёт после выполнения указанного действия

        if ( $this->currentActive == $this->allActive['ACTIVE_START_TASK'] && $this->currentTaskStatus == self::STATUS_NEW ) {
            $this->nextStatus = self::STATUS_ACTIVE; // начать новую задачи
        } else if ( $this->currentActive == $this->allActive['ACTIVE_REFUSAL_TASK'] && $this->currentTaskStatus == self::STATUS_ACTIVE ) {
            $this->nextStatus = self::STATUS_FAILED; // задача в провалена
        } else if ( $this->currentActive == $this->allActive['ACTIVE_COMPLETE_TASK'] && $this->currentTaskStatus == self::STATUS_ACTIVE ) {
            $this->nextStatus = self::STATUS_COMPLETED; // задача выполнена
        } else {
            null;
        }
    }

    //класс имеет метод для получения доступных действий для указанного статуса

    private function getStatus()
    {
        $this->getTaskStatus();
        if ( $this->currentTaskStatus == self::STATUS_NEW && $this->currentActive == $this->allActive['ACTIVE_START_TASK'] ) {
            $this->allowActiveCustomer = self::ACTION_CANCEL; // доступные действия для заказчика
            $this->nextStatusCustomer = self::STATUS_CANCELED; // статус после действия заказчика

            $this->allowActivePerformer = self::ACTION_START; // доступные действия для исполнителя
            $this->nextStatusPerformer = self::STATUS_ACTIVE; // статус после действия исполнителя
        } else if ( $this->currentTaskStatus == self::STATUS_ACTIVE && $this->currentActive == $this->allActive['ACTIVE_PERFORM_TASK'] ) {
            $this->allowActiveCustomer = self::ACTION_COMPLETE;
            $this->nextStatusCustomer = self::STATUS_COMPLETED;

            $this->allowActivePerformer = self::ACTION_REFUSAL;
            $this->nextStatusPerformer = self::STATUS_FAILED;
        } else {
            null;
        }
    }

}
