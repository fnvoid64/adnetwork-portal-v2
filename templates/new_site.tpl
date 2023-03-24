{include file="header-logged.tpl"}
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            New site
            <small>Add new site</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{$site_url}/dashboard.html"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">New site</li>
        </ol>
    </section>
    <section class="content">
        {include file="alerts.tpl"}
        <div class="box box-primary">
            <div class="wizard">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        {if $step eq 2}
                            <li role="presentation" class="disabled">
                                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Site details">
                                            <span class="round-tab" style="border: 2px solid green">
                                                <i class="fa fa-plus" style="color: green"></i>
                                            </span>
                                </a>
                            </li>
                            <li role="presentation" class="active">
                                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Verify ownership">
                                        <span class="round-tab">
                                            <i class="fa fa-wrench"></i>
                                        </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Setup adcodes">
                                        <span class="round-tab">
                                            <i class="fa fa-code"></i>
                                        </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                                        <span class="round-tab">
                                            <i class="fa fa-check"></i>
                                        </span>
                                </a>
                            </li>
                        {elseif $step eq 3}
                            <li role="presentation" class="disabled">
                                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Site details">
                                                <span class="round-tab" style="border: 2px solid green">
                                                    <i class="fa fa-plus" style="color: green"></i>
                                                </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Verify ownership">
                                            <span class="round-tab" style="border: 2px solid green">
                                                <i class="fa fa-wrench" style="color: green"></i>
                                            </span>
                                </a>
                            </li>
                            <li role="presentation" class="active">
                                <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Setup adcodes">
                                            <span class="round-tab">
                                                <i class="fa fa-code"></i>
                                            </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                                            <span class="round-tab">
                                                <i class="fa fa-check"></i>
                                            </span>
                                </a>
                            </li>
                        {elseif $step eq 4}
                            <li role="presentation" class="disabled">
                                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Site details">
                                                <span class="round-tab" style="border: 2px solid green">
                                                    <i class="fa fa-plus" style="color: green"></i>
                                                </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled text-success">
                                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Verify ownership">
                                            <span class="round-tab" style="border: 2px solid green">
                                                <i class="fa fa-wrench" style="color: green"></i>
                                            </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled text-success">
                                <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Setup adcodes">
                                            <span class="round-tab" style="border: 2px solid green">
                                                <i class="fa fa-code" style="color: green"></i>
                                            </span>
                                </a>
                            </li>
                            <li role="presentation" class="active">
                                <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                                            <span class="round-tab">
                                                <i class="fa fa-check"></i>
                                            </span>
                                </a>
                            </li>
                        {else}
                            <li role="presentation" class="active">
                                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Site details">
                                                <span class="round-tab">
                                                    <i class="fa fa-plus"></i>
                                                </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Verify ownership">
                                            <span class="round-tab">
                                                <i class="fa fa-wrench"></i>
                                            </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Setup adcodes">
                                            <span class="round-tab">
                                                <i class="fa fa-code"></i>
                                            </span>
                                </a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                                            <span class="round-tab">
                                                <i class="fa fa-check"></i>
                                            </span>
                                </a>
                            </li>
                        {/if}
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                {if $step eq 2}
                    {if isset($code)}
                        <div class="tab-pane active" role="tabpanel" id="step1" style="padding:5px 20px 20px 20px;">
                            <h3 class="page-header">Verify ownership</h3>
                            <p>
                                Please verify your site ownership by putting bellow code in the head tag of your site.<br/>
                                <textarea class="form-control" disabled><meta name="adzdollar-{$code}"/></textarea>
                            </p>
                            <form method="post">
                                <div class="form-group">
                                    <input type="hidden" name="verify" value="site"/>
                                    <button type="submit" class="btn btn-success">Verify</button>
                                </div>
                            </form>
                        </div>
                    {/if}
                {elseif $step eq 3}
                    {if isset($show)}
                        <div class="tab-pane active" role="tabpanel" id="step1" style="padding:5px 20px 20px 20px;">
                            <h3 class="page-header">Setup adcodes</h3>
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#js">Javscript</a></li>
                                <li><a data-toggle="tab" href="#html">HTML</a></li>
                            </ul>
                            <div class="tab-content" style="border: 1px solid #ddd; border-top:0; padding: 10px">
                                <div id="js" class="tab-pane fade in active">
                                    <h4>Javascript Adcodes (Non Adult)</h4>
                                    <p>
                                        <textarea class="form-control" disabled><script src="{$site_url}/ads/api.js?id={$id}&adult=0"></script></textarea>
                                    </p>
                                    <h4>Javascript Adcodes (Adult)</h4>
                                    <p>
                                        <textarea class="form-control" disabled><script src="{$site_url}/ads/api.js?id={$id}&adult=1"></script></textarea>
                                    </p>
                                </div>
                                <div id="html" class="tab-pane fade in">
                                    <h4>HTML Banner (Non Adult)</h4>
                                    <p>
                                        <textarea class="form-control" disabled><a href="{$site_url}/ads/api.html?id={$id}&adult=0"><img src="{$site_url}/ads/banner.gif" alt="Hot Videos"/></a></textarea>
                                    </p>
                                    <h4>HTML Banner (Adult)</h4>
                                    <p>
                                        <textarea class="form-control" disabled><a href="{$site_url}/ads/api.html?id={$id}&adult=1"><img src="{$site_url}/ads/banner.gif" alt="XxX Videos"/></a></textarea>
                                    </p>
                                    <h4>HTML Text (Non Adult)</h4>
                                    <p>
                                        <textarea class="form-control" disabled><a href="{$site_url}/ads/api.html?id={$id}&adult=0">Hot Video Downloads</a></textarea>
                                    </p>
                                    <h4>HTML Text (Adult)</h4>
                                    <p>
                                        <textarea class="form-control" disabled><a href="{$site_url}/ads/api.html?id={$id}&adult=1">XxX Video Downloads</a></textarea>
                                    </p>
                                </div>
                            </div>
                        </div>
                    {/if}
                {elseif $step eq 4}
                    <div class="tab-pane active" role="tabpanel" id="step1" style="padding:5px 20px 20px 20px;">
                        <h2 class="page-header text-success text-center">
                            <i class="fa fa-check"></i> You are all set!
                        </h2>
                        <p class="text-center">
                            You have successfully added your site in Adzdollar! Enjoy handsome revenue from Adzdollar.
                        </p>
                    </div>
                {else}
                    <div class="tab-pane active" role="tabpanel" id="step1" style="padding:5px 20px 20px 20px;">
                        <h3 class="page-header">Site Details</h3>
                        <form method="post">
                            <div class="form-group has-feedback">
                                <input type="text" name="name" class="form-control" placeholder="Site Name" required>
                                <span class="fa fa-file form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="url" name="url" id="url" class="form-control" placeholder="Site URL" required>
                                <span class="fa fa-globe form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <select name="category" class="form-control" required>
                                    <option value="">Category</option>
                                    <option value="Entertainment">Entertainment</option>
                                    <option value="Downloads">Downloads</option>
                                </select>
                                <span class="glyphicon glyphicon-world form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <textarea name="description" placeholder="Description" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success pull-right">Next</button>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                {/if}
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
</div>
{include file="footer-logged.tpl"}
{literal}
    <script>
        $(document).ready(function () {
            // Form
            $('#url').click(function () {
                $('#url').val("http://");
            });
            //Initialize tooltips
            $('.nav-tabs > li a[title]').tooltip();

            //Wizard
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

                var $target = $(e.target);

                if ($target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            $(".next-step").click(function (e) {

                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);

            });
            $(".prev-step").click(function (e) {

                var $active = $('.wizard .nav-tabs li.active');
                prevTab($active);

            });
        });

        function nextTab(elem) {
            $(elem).next().find('a[data-toggle="tab"]').click();
        }
        function prevTab(elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        }
    </script>
{/literal}
</body>
</html>