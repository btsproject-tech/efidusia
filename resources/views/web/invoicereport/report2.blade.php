<div class="row">
    <div class="col-md-9">
        <div class="mb-4">
            <label>Filter Date</label>

            <div class="input-daterange input-group" id="datepicker7" data-date-format="yyyy-mm-dd"
                data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker7'>
                <input type="text" class="form-control" name="start" placeholder="Start Date" id="tgl_awal_report" />
                <input type="text" class="form-control" name="end" placeholder="End Date" id="tgl_akhir_report" />
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <label style="opacity: 0;">Filter Date</label>
        <div class="mb-4 d-grid gap-2">
            <button class="btn btn-block btn-warning" onclick="ShipmentReport.filterDataReport2(this)">Filter</button>
        </div>
    </div>
</div>

<div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
    <table id="table-data-report"
        class="table table-centered datatable dt-responsive nowrap dataTable no-footer dtr-inline collapsed"
        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Job Number</th>
                <th>Manifest Number</th>
                <th>Container No</th>
                <th>Container Size</th>
                <th>Container Type</th>
                <th>Commodity</th>
                <th>Kind of Packages</th>
                <th>Description of Goods</th>
                <th>Marks and Numbers</th>
                <th>Container Number</th>
                <th>Seal Number</th>
                <th>Gross Weight</th>
                <th>Port of Destination</th>
                <th>Measurement</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center" colspan="37">Tidak ada data ditemukan</td>
            </tr>
        </tbody>
    </table>
</div>
