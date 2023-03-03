<?php

namespace Aqamarine\AlphaCruds\Components;

use Illuminate\Support\Facades\Session;

class SessionAlerts
{
    public const ERROR = 'error';
    public const SUCCESS = 'success';
    public const WARNING = 'warning';

    public function displayError(): string
    {
        return $this->display($this->getError());
    }

    public function displayWarning(): string
    {
        return $this->display($this->getWarning());
    }

    public function displaySuccess(): string
    {
        return $this->display($this->getSuccess());
    }

    public function error($messages): void
    {
        $this->set(self::ERROR, $messages);
    }

    public function success($messages): void
    {
        $this->set(self::SUCCESS, $messages);
    }

    public function warning($messages): void
    {
        $this->set(self::WARNING, $messages);
    }

    public function getError()
    {
        return $this->get(self::ERROR);
    }

    public function getSuccess()
    {
        return $this->get(self::SUCCESS);
    }

    public function getWarning()
    {
        return $this->get(self::WARNING);
    }

    public function hasError(): bool
    {
        return $this->has(self::ERROR);
    }

    public function hasSuccess(): bool
    {
        return $this->has(self::SUCCESS);
    }

    public function hasWarning(): bool
    {
        return $this->has(self::WARNING);
    }

    private function has($type): bool
    {
        return Session::has($type);
    }

    private function set($type, $messages): void
    {
        Session::push($type, $messages);
    }

    private function get($type)
    {
        $message = Session::get($type);
        Session::forget($type);

        return $message;
    }

    private function display($messages): string
    {
        $response = '';
        foreach ($messages as $message) {
            $response .= '<p>'.$message.'</p>';
        }

        return $response;
    }
}
