<h1>An error occurred</h1>
<h2>{$message}</h2>

{if $display_exceptions}
    {if $exception and $exception|is_a:'Exception'}
        <hr/>
        <h2>Additional information:</h2>
        <h3>{get_class($exception)}</h3>
        <dl>
            <dt>File:</dt>
            <dd>
                <pre class="prettyprint linenums">{$exception->getFile()}:{$exception->getLine()}</pre>
            </dd>
            <dt>Message</dt>
            <dd>
                <pre class="prettyprint linenums">{$exception->getMessage()}</pre>
            </dd>
            <dt>Stack trace</dt>
            <dd>
                <pre class="prettyprint linenums">{$exception->getTraceAsString()}</pre>
            </dd>
        </dl>
        {assign e $exception->getPrevious()}
        {if $e}
            <hr/>
            <h2>Previous exceptions'</h2>
            <ul class="unstyled">
                {while $e}
                    <li>
                        <h3>{get_class($e)}</h3>
                        <dl>
                            <dt>File</dt>
                            <dd>
                                <pre class="prettyprint linenums">{$e->getFile()}:{$e->getLine()}</pre>
                            </dd>
                            <dt>Message:</dt>
                            <dd>
                                <pre class="prettyprint linenums">{$e->getMessage()}</pre>
                            </dd>
                            <dt>Stack trace:</dt>
                            <dd>
                                <pre class="prettyprint linenums">{$e->getTraceAsString()}</pre>
                            </dd>
                        </dl>
                    </li>
                    {assign e $e->getPrevious()}
                {/while}
            </ul>
        {/if}
    {else}
        <h3>No Exception available</h3>
    {/if}
{/if}