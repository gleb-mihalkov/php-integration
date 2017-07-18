<?php
namespace Integration
{
    use Process\Fetch as FetchProcess;

    /**
     * The base class of source's elements enumeration process.
     */
    abstract class Source extends FetchProcess
    {
        use Resolvable;
    }
}