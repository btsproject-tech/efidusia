<div class="row">
    <div class="col-md-9">
        <div class="mb-4">
            <label>Filter Date</label>

            <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd"
                data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                <input type="text" class="form-control" name="start" placeholder="Start Date" id="tgl_awal" />
                <input type="text" class="form-control" name="end" placeholder="End Date" id="tgl_akhir" />
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <label style="opacity: 0;">Filter Date</label>
        <div class="mb-4 d-grid gap-2">
            <button class="btn btn-block btn-warning" onclick="InvoiceReport.filterData(this)">Filter</button>
        </div>
    </div>
</div>

<div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
    <table id="table-data"
        class="table table-centered datatable dt-responsive nowrap dataTable no-footer dtr-inline collapsed"
        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Invoice Number</th>
                <th>Job number</th>
                <th>Customer Name</th>
                <th>Shipper</th>
                <th>Consignee</th>
                <th>BL No</th>
                <th>Vessel</th>
                <th>Voyage</th>
                <th>Port of Loading</th>
                <th>Port of Destination</th>
                <th>ETD</th>
                <th>ETA</th>
                <th>Delivery Type</th>
                <th>Total Invoice</th>
                <th>Total Cost</th>
                <th>Total Revenue</th>
                <th>GP Estimation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center" colspan="8">Tidak ada data ditemukan</td>
            </tr>
        </tbody>
    </table>
</div>
