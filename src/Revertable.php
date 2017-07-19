<?php
namespace Integration
{
    /**
     * Типаж, делающий процесс обратимым, запрещая при этом использование
     * событийных методов error() и end().
     * @internal
     */
    trait Revertable
    {
        /**
         * Выполняется для подтверждении изменений в процессе.
         * Несмотря на модификатор, метод может быть вызван извне.
         * @return void
         */
        protected function commit() {}

        /**
         * Выполняется для отметы изменений в процессе.
         * Несмотря на модификатор, метод может быть вызван извне.
         * @return void
         */
        protected function revert() {}

        /**
         * Показывает, подтверждены ли изменения в процессе.
         * @var boolean
         */
        public $isCommited = false;

        /**
         * Показывает, отменены ли изменения в процессе.
         * @var boolean
         */
        public $isReverted = false;



        /**
         * Указывает процессу подтвердить свои изменения.
         * @internal
         * @return void.
         */
        public function _commit()
        {
            $this->resolve('commit', true);
        }

        /**
         * Указывает процессу отменить свои изменения.
         * @internal
         * @return void
         */
        public function _revert()
        {
            $this->resolve('revert', true);
        }




        /**
         * Показывает, обработаны ли изменения в процесе каким-либо образом.
         * @return boolean True или false.
         */
        private function isResolved()
        {
            $isResolved = $this->isCommited || $this->isReverted;
            return $isResolved;
        }

        /**
         * Выполняется при возникновении ошибки во время процесса.
         * @param  \Exception $error Исключение.
         * @return void
         */
        protected function error($error)
        {
            parent::error($error);
            $this->resolve('revert');
        }

        /**
         * Выполняется при любом завершении процесса.
         * @return void
         */
        protected function end()
        {
            parent::end();
            if ($this->isError) return;
            $this->resolve('commit');
        }

        /**
         * Разрешает изменения при процессе указанным способом.
         * @param  string  $type   Способ разрешения изменений - 'commit' или 'revert'.
         * @param  boolean $isStop Показывает, слеудет ли останавливать процесс после разрешения
         *                         изменений.
         * @return void
         */
        private function resolve($type, $isStop = false)
        {
            if ($this->isResolved()) return;

            if ($type === 'commit')
            {
                $this->isCommited = true;
                $this->commit();
            }
            else
            {
                $this->isReverted = true;
                $this->revert();
            }

            if ($isStop) $this->stop();
        }
    }
}