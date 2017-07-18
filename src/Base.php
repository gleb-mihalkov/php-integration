<?php
namespace Integration
{
    use Process\Exec as ExecProcess;

    /**
     * Base class of the integration process.
     */
    abstract class Base extends ExecProcess
    {
        use Resolvable;

        /**
         * Gets the source element's fetching process.
         * @return Source The source element's fetching process.
         */
        abstract protected function getSource();

        /**
         * Gets the destination element's writing process.
         * @return Dest The destination element's writing process.
         */
        abstract protected function getDest();

        /**
         * Creates destination element's from one source element and enumerate it.
         * @param  mixed           $item Source element.
         * @return Iterator<mixed>       Iterator of the destination elements.
         */
        abstract protected function map($item);



        /**
         * The source's elements enumeration process.
         * @var Source
         */
        protected $source = null;

        /**
         * The destination's elements writing process.
         * @var Dest
         */
        protected $dest = null;



        /**
         * Running before you start the process.
         * @return void
         */
        protected function start()
        {
            parent::start();

            $this->source = $this->getSource();
            $this->dest = $this->getDest();
        }

        /**
         * The process of the integration.
         * @return void
         */
        protected function exec()
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
         * Commits the integration changes.
         * @return void
         */
        protected function commit()
        {
            $this->source->commit();
            $this->dest->commit();
        }

        /**
         * Reverts the integration changes.
         * @return void
         */
        protected function revert()
        {
            $this->source && $this->source->revert();
            $this->dest && $this->dest->revert();
        }


        /**
         * Executes process of the integration statically.
         * @return void
         */
        public static function execute()
        {
            $process = new static();
            $process->process();
        }
    }
}