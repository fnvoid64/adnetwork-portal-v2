{include file="header.tpl"}
<section class="content">
    <div class="row">
        <div class="register-box" style="margin-top: 3%">
            <div class="register-logo">
                <a href="/"><img src="{$root}/assets/img/adzdollar.png" width="250px"/></a>
            </div>
            <div class="register-box-body">
                <p class="login-box-msg">Reset Password</p>
                {if isset($message)}
                    <div class="alert alert-success text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {$message}
                    </div>
                {/if}
                {$lp_form}

                {if $reset eq 1}
                    {if isset($reset_pw_error)}
                        <div class="alert alert-danger">
                            {foreach from=$reset_pw_error item=error}
                                {$error}
                                <br/>
                            {/foreach}
                        </div>
                    {/if}
                    {$reset_pw_form}
                {else}
                    {if isset($lp_reset_error)}
                        <div class="alert alert-danger">
                            {foreach from=$lp_reset_error item=error}
                                {$error}
                                <br/>
                            {/foreach}
                        </div>
                    {/if}
                    {$lp_form}
                {/if}
            </div>
        </div>
    </div>
{include file="footer.tpl"}