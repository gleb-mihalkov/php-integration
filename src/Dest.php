<?php
namespace Integration
{
    use Process\Write as WriteProcess;

    /**
     * Базовый класс процесса записи элементов в приемник данных.
     */
    abstract class Dest extends WriteProcess
    {
        use Revertable;
    }
}