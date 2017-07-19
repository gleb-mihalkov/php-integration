<?php
namespace Integration
{
    use Process\Fetch as FetchProcess;

    /**
     * Базовый класс процесс перечисления элементов из источника данных.
     * 
     * @link https://gleb-mihalkov.github.io/php-process-api/class-Process.Fetch.html \Process\Fetch
     */
    abstract class Source extends FetchProcess
    {
        use Revertable;
    }
}