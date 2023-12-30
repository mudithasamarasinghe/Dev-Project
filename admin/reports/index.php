<style>
    /*.badge {*/
    /*    border-radius: 0;*/
    /*    font-size: 12px;*/
    /*    line-height: 1;*/
    /*    padding: .375rem .5625rem;*/
    /*    font-weight: normal*/
    /*}*/

    /*.badge-outline-primary {*/
    /*    color: #405189;*/
    /*    border: 1px solid #405189;*/
    /*}*/

    /*.badge.badge-pill {*/
    /*    border-radius: 50%;*/
    /*    font-size: 1.1em;*/
    /*    padding: 10px;*/
    /*    align-items: center;*/
    /*    text-align: center;*/
    /*    vertical-align: middle;*/
    /*}*/

    /*.badge-outline-info {*/
    /*    color: #3da5f4;*/
    /*    border: 2px solid #3da5f4;*/
    /*}*/

    /*.badge-outline-danger {*/
    /*    color: #f1536e;*/
    /*    border: 1px solid #f1536e;*/
    /*}*/

    /*.badge-outline-success {*/
    /*    color: #00c689;*/
    /*    border: 1px solid #00c689;*/
    /*}*/

    /*.badge-outline-warning {*/
    /*    color: #fda006;*/
    /*    border: 1px solid #fda006;*/
    /*}*/

    a{
        color: white;
    }
</style>

<h1 class="">Reports</h1>
<hr>

<div class="row">

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon badge-info elevation-1"><a href="../admin/index.php?page=reports/report_sales" class="nav-link nav-receiving"><span class="info-box-text"><i class="fas fa-chart-bar"></i></span></a></span>
                <button type="button" class="btn" onclick="window.location.href='../admin/index.php?page=reports/report_sales'">
                    <span class="info-box-number text-center">Sales Report</span>
            </button>
            </span>
        </div>
    </div>
   
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon badge-info elevation-1"><a href="../admin/index.php?page=reports/report_disposal" class="nav-link nav-receiving"><span class="info-box-text"><i class="fas fa-chart-bar"></i></span></a></span>
            <button type="button" class="btn" onclick="window.location.href='../admin/index.php?page=reports/report_disposal'">
                <span class="info-box-number text-center">Disposal Report</span>
            </button>
            </span>
        </div>
    </div>

<!--    <div class="col-12 col-sm-6 col-md-3">-->
<!--        <div class="info-box bg-light shadow">-->
<!--            <span class="info-box-icon badge-dark elevation-1"><a href="../admin/index.php?page=reports/report_purchase_return" class="nav-link nav-receiving">-->
<!--                    <span class="info-box-text"><i class="fas fa-hand-holding-usd"></i></span></a></span>-->
<!--            <button type="button" class="btn" onclick="window.location.href='../admin/index.php?page=reports/report_purchase_return'">-->
<!--                <span class="info-box-number text-center">Purchase Return Report</span>-->
<!--            </button>-->
<!--            </span>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="col-12 col-sm-6 col-md-3">-->
<!--        <div class="info-box bg-light shadow">-->
<!--            <span class="info-box-icon badge-success elevation-1"><a href="../admin/index.php?page=reports/report_low_stock" class="nav-link nav-receiving"><span class="info-box-text"><i class="fas fa-search-dollar"></i></span></a></span>-->
<!--            <button type="button" class="btn" onclick="window.location.href='../admin/index.php?page=reports/report_low_stock'">-->
<!--                <span class="info-box-number text-center">Low Stock Report</span>-->
<!--            </button>-->
<!--            </span>-->
<!--        </div>-->
<!--    </div>-->

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon badge-danger elevation-1"><a href="../admin/index.php?page=reports/report_expiry_date" class="nav-link nav-receiving"><span class="info-box-text"><i class="fas fa-stopwatch"></i></span></a></span>
            <button type="button" class="btn" onclick="window.location.href='../admin/index.php?page=reports/report_expiry_date'">
                <span class="info-box-number text-center">Expiry Date Report</span>
            </button>
            </span>
        </div>
    </div>
   <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon badge-danger elevation-1"><a href="../admin/index.php?page=reports/report_purchase_order" class="nav-link nav-receiving"><span class="info-box-text"><i class="nav-icon fas fa-th-list"></i></span></a></span>
            <button type="button" class="btn" onclick="window.location.href='../admin/index.php?page=reports/report_purchase_order'">
                <span class="info-box-number text-center">Purchase Order Report</span>
            </button>
            </span>
        </div>
    </div>
</div>
