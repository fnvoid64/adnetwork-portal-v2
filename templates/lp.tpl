{include file="header.tpl"}
<section class="content">
    <div class="row">
        <div class="register-box" style="margin-top: 3%">
            <div class="register-logo">
                <a href="/"><img src="{$root}/assets/img/adzdollar.png" width="250px"/></a>
            </div>
            <div class="register-box-body">
                <p class="login-box-msg">Lost Password</p>
                {if isset($message)}
                    <div class="alert alert-success text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {$message}
                    </div>
                {/if}

                {if isset($lp_error)}
                    <div class="alert alert-danger text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {foreach from=$lp_error item=error}
                            {$error}
                            <br/>
                        {/foreach}
                    </div>
                {/if}

                {$lp_form}
            </div>
        </div>
    </div>
{include file="footer-logged.tpl"}