<div id="table-customer-filter-container">
    <form name="form-customer-filter" id="form-customer-filter">
        <fieldset class="form-group">
            <div class="input-group">
                <select id="filter_type" name="filter_type" class="form-control" style="display: none;">
                    <option>First Name</option>
                    <option>Last Name</option>
                    <option>Phone</option>
                    <option>Email</option>
                    <option>Infusionsoft Id</option>
                    <option>Database Id</option>
                </select>
                <input placeholder="Search by Name, Email, Phone, Infusionsoft Id or Database Id" type="text" class="form-control" name="filter">
                <span class="input-group-prepend cursor-pointer" id="filter-data" onclick="loadMasterList('','', $(this).closest('form'))">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                </span>
            </div>
        </fieldset>
    </form>
</div>