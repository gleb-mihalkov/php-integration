<?php
namespace Integration
{
    use Process\Fetch as FetchProcess;

    /**
     * Базовый класс процесс перечисления элементов из источника данных.
     */
    abstract class Source extends FetchProcess
    {
        use Revertable;
    }
}