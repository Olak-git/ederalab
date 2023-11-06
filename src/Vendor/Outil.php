<?php
namespace src\Vendor;

class Outil
{
    /**
     * @var bool
     */
    private static $show_notification;

    /**
     * @var string
     */
    private static $notification_color;

    /**
     * @var array
     */
    private static $notifications;

    /**
     * @var array
     */
    private static $error;

    /**
     * @var array
     */
    private static $_messages;


    public function __construct()
    {
        // (new GenerateRoutes)->routes();
        // $this->routes = new Routes;
        self::$show_notification = false;
        self::$notification_color = 'btn-white';
        self::$notifications = [];
        self::$error = [];
        self::$_messages = [];
    }

    public function setShowNotification(bool $v): self
    {
        self::$show_notification = $v;

        return $this;
    }

    public function getShowNotification(): bool
    {
        return self::$show_notification;
    }

    public function setNotificationColor(string $color): self
    {
        self::$notification_color = $color;

        return $this;
    }

    public function getNotificationColor(): string
    {
        return self::$notification_color;
    }

    /**
     * key peut prendre les valeur: success, error, warning, notice
     */
    public function addNotification($text)
    {
        self::$notifications[] = $text;

        return $this;
    }
    // public function addNotification($key, $text)
    // {
    //     self::$notifications[$key] = $text;

    //     return $this;
    // }

    public function getNotifications(): array
    {
        return self::$notifications;
    }

    public function addError(?string $key, string $error)
    {
        if(null === $key) {
            self::$error[] = $error;
        } else {
            self::$error[$key] = $error;
        }
        return $this;
    }

    public function getError(): array
    {
        return self::$error;
    }

    public function hasError(): bool
    {
        return !empty($this->getError());
    }

    public function containsError($key)
    {
        return array_key_exists($key, $this->getError());
    }

    public function set_messages($message): self
    {
        if(is_array($message)) {
            self::$_messages = $message;
        } else {
            self::$_messages[] = $message;
        }

        return $this;
    }

    public function get_messages(): array
    {
        return self::$_messages;
    }
}