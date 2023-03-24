{include file="header-logged.tpl"}
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Welcome, {$name}
            <small>Payment Info</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-dashboard"></i> <a href="{$site_url}/dashboard/index.html">Dashboard</a></li>
            <li class="active"><i class="fa fa-bullhorn"></i> Payment Info</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="panel-title">Payment Info</h3>
                    </div>
                    <div class="box-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#details">Paypal</a></li>
                            <li><a href="{$site_url}/dashboard/payment.html?act=skrill">Skrill</a></li>
                            <li><a href="{$site_url}/dashboard/payment.html?act=bank">Bank</a></li>
                        </ul>
                        <div class="tab-content" style="border: 1px solid #ddd; border-top:0; padding: 10px">
                            <div id="details" class="tab-pane fade in active">

                                {include file="alerts.tpl"}
                                <form method="post">
                                    <div class="form-group">
                                        <input type="text" name="name" placeholder="Payee Name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="ac_number" placeholder="Account Number" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="bank_name" placeholder="Bank Name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="bank_address" class="form-control" placeholder="Bank Address"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="ifsc" placeholder="IFSC Code" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="swift" placeholder="Swift Code" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="tax" placeholder="TAX / PAN (Optional)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="prefered">
                                            <input type="checkbox" name="prefered" value="1">
                                            Set as prefered payment method for future payments.
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="pin" placeholder="PIN" class="form-control" required>
                                    </div>
                                    <button class="btn btn-success" type="submit">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {include file="footer-logged.tpl"}
