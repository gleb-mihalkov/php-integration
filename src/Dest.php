<?php
namespace Integration
{
    use Process\Write as WriteProcess;

    /**
     * The base class of destination's elements writing process.
     */
    abstract class Dest extends WriteProcess
    {
        use Resolvable;
    }
}