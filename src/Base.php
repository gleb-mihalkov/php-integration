<?php
namespace Integration
{
    use Process\Exec as ExecProcess;

    /**
     * Базовый класс процесса интеграции.
     * @link https://gleb-mihalkov.github.io/php-process-api/class-Process.Exec.html Документация по \Process\Exec
     */
    abstract class Base extends ExecProcess
    {
        /**
         * Получает сущность процесса перечисления элементов из источника данных.
         * @return Source Процесс перечисления элементов из источника.
         */
        abstract protected function getSource();

        /**
         * Получает сущность процесса записи элементов в приемник данных.
         * @return Dest Процесс записи элементов в приемник данных.
         */
        abstract protected function getDest();

        /**
         * Для каждого элемента из источника данных возвращает перечисление элементов,
         * которые должны быть записаны в приемник данных.
         * @param  mixed           $item Очередной элемент из источника данных.
         * @return Iterator<mixed>       Перечисление элементов для приемника данных.
         */
        abstract protected function map($item);



        /**
         * Сущность процесса перечисления элементов из источника данных.
         * @var Source
         */
        protected $source = null;

        /**
         * Сущность процесса записи элементов в приемник данных.
         * @var Dest
         */
        protected $dest = null;

        /**
         * Выполняется перед началом процесса интеграции.
         * @return void
         */
        protected function start()
        {
            parent::start();

            $this->source = $this->getSource();
            $this->dest = $this->getDest();
        }

        /**
         * Производит процесс интеграции.
         * @return void
         */
        protected function main()
        {
            $items = $this->source->process();

            foreach ($items as $item)
            {
                $dests = $this->map($item);

                foreach ($dests as $dest)
                {
                    $this->dest->process($dest);
                }
            }
        }

        /**
         * Вызывается при возникновении ошибки в любом дочернем процессе.
         * @param  \Exception $error Ошибка.
         * @return void
         */
        protected function error($error)
        {
            parent::error($error);
            if ($this->source) $this->source->_revert();
            if ($this->dest) $this->dest->_revert();
        }

        /**
         * Вызывается при любом завершении процесса.
         * @return void
         */
        protected function end()
        {
            parent::end();

            if ($this->isError) return;

            $this->source->_commit();
            $this->dest->_commit();
        }


        /**
         * Запускает процесс интеграции для данного класса.
         * @return void
         */
        public static function execute()
        {
            $process = new static();
            $process->process();
        }
    }
}