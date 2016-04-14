<h1>A 404 error occurred</h1>
<h2>{$message}</h2>

{if $reason}
    {assign reasonMessage 'We cannot determine at this time why a 404 was generated'}
    {if $reason == 'error-controller-cannot-dispatch'}{assign reasonMessage 'The requested controller was unable to dispatch the request.'}{/if}
    {if $reason == 'error-controller-not-found'}{assign reasonMessage 'The requested controller could not be mapped to an existing controller class.'}{/if}
    {if $reason == 'error-controller-invalid'}{assign reasonMessage 'The requested controller was not dispatchable.'}{/if}
    {if $reason == 'error-router-no-match'}{assign reasonMessage 'The requested URL could not be matched by routing.'}{/if}

    <p>{$reasonMessage}</p>
{/if}

{if isset($controller)}
    <dl>
        <dt>Controller:</dt>
        <dd>
            {$controller}
            {if $controller_class and $controller_class != $controller}
                resolves to {$controller_class}
            {/if}
        </dd>
    </dl>
{/if}

{if $display_exceptions}
    {if isset($exception) and $exception|is_a:'Exception'}
        <hr/>
        <h2>Additional information:</h2>
        <h3>{get_class($exception)}</h3>
        <dl>
            <dt>File</dt>
            <dd>
                <pre class="prettyprint linenums">{$exception->getFile()}:{$exception->getLine()}</pre>
            </dd>
            <dt>Message:</dt>
            <dd>
                <pre class="prettyprint linenums">{$exception->getMessage()}</pre>
            </dd>
            <dt>Stack trace:</dt>
            <dd>
                <pre class="prettyprint linenums">{$exception->getTraceAsString()}</pre>
            </dd>
        </dl>
        {assign e $exception->getPrevious()}
        {if $e}
            <hr/>
            <h2>Previous exceptions:</h2>
            <ul class="unstyled">
                {while $e}
                    <li>
                        <h3>{get_class($e)}</h3>
                        <dl>
                            <dt>File:</dt>
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