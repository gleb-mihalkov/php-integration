<?php
namespace Integration
{
    use Process\Write as WriteProcess;

    /**
     * Базовый класс процесса записи элементов в приемник данных.
     * 
     * @link https://gleb-mihalkov.github.io/php-process-api/class-Process.Write.html \Process\Write.
     */
    abstract class Dest extends WriteProcess
    {
        use Revertable;
    }
}