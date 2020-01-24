<?php

namespace src\classes;

/**
 *  Класс определяет списки действий и статусов, а также выполняет базовую работу с ними.
 *
 */
class Task
{
//    внутреннее значение
//      ROLE_GUEST так и остается
//      Ключ
//    guest  = гость ????
// класс имеет методы для возврата «карты» статусов и действий.
// Карта — это ассоциативный массив, где ключ — внутреннее имя,
// а значение — названия статуса/действия на русском.
// на кирилице писать??

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
    const STATUS_ALL = 'все статусы';

    private $currentStatus;
    private $currentActive;

    private $customerID;
    private $performerID;

    private $allActive = [];
    private $allStatus = [];

    private $allowActiveCustomer = [];
    private $allowActivePerformer = [];

    public function __construct($customerID, $performerID, $currentActive)
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;
        $this->currentActive= $currentActive;
        $this->getTaskStatus();
        $this->getStatus();
        // вывод полученных данных
        echo 'Статус: ' . $this->currentStatus . '<br>';
        echo 'Текущее действие: ' . $this->currentActive . '<br>';
        echo 'Доступные действия заказчику: ' . $this->allowActiveCustomer . '<br>';
        echo 'Доступные действия исполнителю: ' . $this->allowActivePerformer . '<br>';
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
            'STATUS_ALL_TASK' => self::STATUS_ALL
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
            $this->currentStatus = self::STATUS_NEW;
        } else if ( $this->currentActive == $this->allActive['ACTIVE_REFUSAL_TASK'] ) {
            $this->currentStatus = self::STATUS_CANCELED;
        } else if ( $this->currentActive == $this->allActive['ACTIVE_PERFORM_TASK'] ) {
            $this->currentStatus = self::STATUS_ACTIVE;
        } else if ( $this->currentActive == $this->allActive['ACTIVE_COMPLETE_TASK'] ) {
            $this->currentStatus = self::STATUS_COMPLETED;
        } else if ( $this->currentActive == $this->allActive['ACTIVE_CANCEL_TASK'] ) {
            $this->currentStatus = self::STATUS_FAILED;
//        } else if ( $this->currentActive == $this->allStatus['ACTIVE_ALL_TASK'] ) {
//            $this->currentStatus = self::STATUS_ALL . '<br>';
//            var_dump($this->allStatus);
        } else {
            null;
        }
    }

    //класс имеет метод для получения доступных действий для указанного статуса

    private function getStatus()
    {
        $this->getTaskStatus();
        switch ( $this->currentStatus ) {
            case self::STATUS_NEW;
                $this->allowActiveCustomer = self::ACTION_CANCEL;
                $this->allowActivePerformer = self::ACTION_START;
                break;
            case self::STATUS_ACTIVE;
                $this->allowActiveCustomer = self::ACTION_COMPLETE;
                $this->allowActivePerformer = self::ACTION_REFUSAL;
                break;
            case self::STATUS_CANCELED || self::STATUS_COMPLETED || self::STATUS_FAILED;
                $this->allowActiveCustomer = 'пусто';
                $this->allowActivePerformer = 'пусто';
                break;

        }
    }

}
