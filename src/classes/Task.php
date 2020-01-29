<?php
namespace Logic;

/**
 * Класс определяет списки действий и статусов, а также выполняет базовую работу с ними.
 * Class Task
 * @package Logic
 */
class Task
{
    const ACTION_START = 0;
    const ACTION_REFUSAL = 1;
    const ACTION_PERFORM = 2;
    const ACTION_COMPLETE = 3;
    const ACTION_CANCEL = 4;

    const STATUS_NEW = 0;
    const STATUS_CANCELED = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_FAILED = 4;

    // action
    public $action;

    // task status
    public $currentStatus;

    // users
    private $customerID;
    private $performerID;

    private $nextStatus;

    public function __construct($customerID, $performerID)
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;
        self::getActionForStatus();
        $this->getNextStatus();
    }

    /** метод возвращает массив всех статусов и всех действий
     *  @return array
     */
    public static function getAllStatusesActions()
    {
        return [
            'Statuses' => [
                self::STATUS_NEW => 'Новый',
                self::STATUS_CANCELED => 'Отмененный',
                self::STATUS_ACTIVE => 'Действующий',
                self::STATUS_COMPLETED => 'Завершенный',
                self::STATUS_FAILED => 'Проваленный'
            ],
            'Actions' => [
                self::ACTION_START => 'Начать',
                self::ACTION_CANCEL => 'Отменить',
                self::ACTION_PERFORM => 'Выполняется',
                self::ACTION_COMPLETE => 'Завершить',
                self::ACTION_REFUSAL => 'Отказаться'
            ]
        ];
    }

    /** Метод возвращает массив доступных действий для указанного статуса, где ключ - статус, а значение - массив действий.
     *  @return array
     */
    public function getActionForStatus()
    {
        return [
            self::STATUS_NEW => [
                self::ACTION_CANCEL, self::ACTION_START
            ],
            self::STATUS_ACTIVE => [
                self::ACTION_COMPLETE, self::ACTION_REFUSAL
            ]
        ];
    }

    /**
     * метод для получения статуса, в которой он перейдёт после выполнения указанного действия
     */
    public function getNextStatus()
    {
        if ( $this->action === self::ACTION_START && $this->currentStatus === self::STATUS_NEW ) {
            $this->nextStatus = self::STATUS_ACTIVE;
        } elseif ($this->action === self::ACTION_CANCEL && $this->currentStatus === self::STATUS_NEW ) {
            $this->nextStatus = self::STATUS_CANCELED;
        } elseif ( $this->action === self::ACTION_CANCEL && $this->currentStatus === self::STATUS_ACTIVE ) {
            $this->nextStatus = self::STATUS_FAILED;
        } elseif ( $this->action === self::ACTION_COMPLETE && $this->currentStatus === self::STATUS_ACTIVE ) {
            $this->nextStatus = self::STATUS_COMPLETED;
        } else {
            $this->nextStatus = null;
        }
        return $this->nextStatus;
    }
}
