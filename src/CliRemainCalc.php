<?php

namespace AKikhaev\CliRemainCalc;

use AKikhaev\Terminal\Terminal;

class CliRemainCalc
{
    private $started;
    private $count = 0;
    private $skip = 0;
    private $nextPlot = 0;
    private $str;
    private $value = 0;

    /**
     * remainCalc constructor.
     * @param int $count
     * Количество
     * @param string $str
     * Заголовок
     * @param int $skip
     * Расчет, при пропуске указанного количества
     */
    public function __construct($count = null, $str = '', $skip = 0)
    {
        if ($count !== null) $this->init($count, $str, $skip);
    }

    /** Инициализация калькулятора
     * @param $count
     * Количетво
     * @param string $str
     * Заголовок
     * @param int $skip
     * Расчёт, при пропуске указанного количества
     */
    public function init($count, $str = '', $skip = 0)
    {
        $count_value = 0;
        if (is_array($count) || $count instanceof \Countable) $count_value = count($count);
        if (is_numeric($count)) $count_value = $count;

        if ($count_value === 0)
            echo 'count=0! Remain approximating impossible! ' . $str . "\n";
        $this->count = $count_value;
        $this->skip = $skip;
        $this->value = $skip;
        $this->str = ' ' . $str . ' ';
        @ob_flush();
        $this->started = microtime(true);
    }

    /*** Преобразует число в текстовый интервал
     * @param $t
     * @return string
     */
    function intToTime($t)
    {
        $s = $t % 60;
        $h = floor($t / 3600);
        $m = floor(($t - $h * 3600) / 60);
        return $h . ':' . ($m < 10 ? '0' . $m : $m) . ':' . ($s < 10 ? '0' . $s : $s);
    }

    /** Подсчет и вывод времени не чаще чем раз в несколько секунд
     * @param int $num
     * Текущее значение. Если -1, подсчитает как следующее
     * @param bool $printLog
     * Вывод лога на экран. Если 1 - вывод принудительно, игнорируя частоту
     * @param string $msg
     * Сообщение на экран
     * @param string $msgTitle
     * Сообщение в заголовок
     */
    public function plot($num = -1, $printLog = true, $msg = '', $msgTitle = '')
    {
        if ($num === -1) {
            $num = ++$this->value;
        }
        $elapsed = microtime(true) - $this->started;
        if (($elapsed < $this->nextPlot) && ($num != $this->count) && $printLog !== 1) {
            return;
        }
        if ($elapsed === 0) $elapsed = 1;
        $speed = ($num - $this->skip) / $elapsed;
        if ($speed !== 0) {
            $finish = ($this->count - $this->skip) / $speed;
            $remain = $finish - $elapsed;
        } else $remain = 0;
        if ($remain < 0) {
            $remain = 0;
        }
        $elapsed_str = $this->intToTime($elapsed);
        $remain_str = $this->intToTime($remain);
        if ($printLog) {
            echo "\r\e[2K" .
                Terminal::color(Terminal::CYAN) . $elapsed_str .
                Terminal::color(Terminal::GRAY) . $this->str .
                Terminal::color(Terminal::VIOLET) . $num . '/' . $this->count .
                Terminal::color(Terminal::GREEN) . ' ' . number_format($speed, 2, '.', '') . '/s' .
                Terminal::color(Terminal::BROWN) . ' remain: ' . $remain_str . '  ' . Terminal::es(Terminal::BOLD) . Terminal::color(Terminal::BLUE) . $msg . Terminal::color();
        }
        if ($num === $this->count || $printLog === 1) {
            echo "\n";
        }

        $percent = str_replace('.', '%.', number_format($num / $this->count * 100, 2, '.', ''));
        echo "\e]0;".$percent." $this->str $num/$this->count $remain_str $elapsed_str $msgTitle"."\007";

        @ob_flush();
        $this->nextPlot = $elapsed + 5;
    }

}