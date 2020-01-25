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

    private $currentTaskStatus = self::STATUS_ACTIVE;

    private $nextStatus;
    private $currentActive;

    private $customerID;
    private $performerID;

    private $allActive = [];
    private $allStatus = [];

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
        Статус после действия: <span style="color: red">' . $this->nextStatus . '</span><br>
        Текущее действие: <span style="color: red;">' . $this->currentActive . '</span><br>
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

        if ( $this->currentActive == $this->allActive['ACTIVE_START_TASK'] ) {
            $this->nextStatus = self::STATUS_NEW;
        } else if ( $this->currentActive == $this->allActive['ACTIVE_REFUSAL_TASK'] ) {
            $this->nextStatus = self::STATUS_CANCELED;
        } else if ( $this->currentActive == $this->allActive['ACTIVE_PERFORM_TASK'] ) {
            $this->nextStatus = self::STATUS_ACTIVE;
        } else if ( $this->currentActive == $this->allActive['ACTIVE_COMPLETE_TASK'] ) {
            $this->nextStatus = self::STATUS_COMPLETED;
        } else if ( $this->currentActive == $this->allActive['ACTIVE_CANCEL_TASK'] ) {
            $this->nextStatus = self::STATUS_FAILED;
        } else {
            null;
        }
    }

    //класс имеет метод для получения доступных действий для указанного статуса

    private function getStatus()
    {
        $this->getTaskStatus();
        if ( $this->nextStatus == $this->currentTaskStatus && $this->currentTaskStatus == self::STATUS_NEW ) {
            $this->allowActiveCustomer = self::ACTION_CANCEL;
            $this->allowActivePerformer = self::ACTION_START;
        } else if ( $this->nextStatus == $this->currentTaskStatus && $this->currentTaskStatus == self::STATUS_COMPLETED ) {
            $this->allowActiveCustomer = 'Задание выполено';
            $this->allowActivePerformer = 'Задание выполено';
        } else if ( $this->nextStatus == $this->currentTaskStatus && $this->currentTaskStatus == self::STATUS_ACTIVE ) {
            $this->allowActiveCustomer = self::ACTION_COMPLETE;
            $this->allowActivePerformer = self::ACTION_REFUSAL;
        } else if ( $this->nextStatus == $this->currentTaskStatus && $this->currentTaskStatus == self::STATUS_CANCELED) {
            $this->allowActiveCustomer = 'Задание отменено';
            $this->allowActivePerformer = 'Задание отменено';
        } else if ( $this->nextStatus == $this->currentTaskStatus && $this->currentTaskStatus == self::STATUS_FAILED ) {
            $this->allowActiveCustomer = 'Задание провалено';
            $this->allowActivePerformer = 'Задание провалено';
        } else {
            null;
        }


//        switch ( $this->nextStatus == $this->currentTaskStatus ) {
//            case self::STATUS_NEW;
//                $this->allowActiveCustomer = self::ACTION_CANCEL;
//                $this->allowActivePerformer = self::ACTION_START;
//                break;
//            case self::STATUS_ACTIVE;
//                $this->allowActiveCustomer = self::ACTION_COMPLETE;
//                $this->allowActivePerformer = self::ACTION_REFUSAL;
//                break;
//            case self::STATUS_CANCELED || self::STATUS_COMPLETED || self::STATUS_FAILED;
//                $this->allowActiveCustomer = 'пусто';
//                $this->allowActivePerformer = 'пусто';
//                break;
//        }
    }

}
