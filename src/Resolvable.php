<?php
namespace Integration
{
    /**
     * The trait that contains the commit() and revert() methods.
     */
    trait Resolvable
    {
        /**
         * Shows if the commit() or revert() methods is already called.
         * @var boolean
         */
        private $isResolved = false;

        /**
         * Resolves the changes in process.
         * @return boolean True if base overrides must be called.
         */
        private function resolve($method, $isEnding = true)
        {
            if ($this->isResolved) return;

            $this->isResolved = true;
            $this->$method();

            $isEnding && $this->stop();
        }

        /**
         * Commits the changes.
         * @return void
         */
        protected function commit() {}

        /**
         * Reverts the changes.
         * @return void
         */
        protected function revert() {}

        /**
         * Executes before the process ending.
         * @return void
         */
        protected function end()
        {
            $method = $this->isError ? 'revert' : 'commit';
            $this->resolve($method, false);

            parent::end();
        }

        /**
         * Calls the protected commit() or revert() methods.
         * @param  string       $name The name of method.
         * @param  array<mixed> $args The arguments.
         * @return void
         */
        public function __call($name, $args)
        {           
            if ($name !== 'commit' && $name !== 'revert') return;
            $this->resolve($name);
        }
    }
}