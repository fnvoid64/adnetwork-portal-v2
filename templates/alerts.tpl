{if isset($message)}
    <div class="alert alert-success text-center">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {$message}
    </div>
{/if}
{if isset($errors)}
    <div class="alert alert-danger text-center">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {foreach from=$errors item=error}
            {$error}<br/>
        {/foreach}
    </div>
{/if}