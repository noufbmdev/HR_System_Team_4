$(".acc-filter-select").change(function(event) {
    $("#formAccounts").submit();
});


$(".filter_contract_type").change(function(event) {
    $("#formContractsFilter").submit();
});


$(".delete-contract").click(function(event) {
    $("#formContractRemove").submit();
});

$(".contract-remove").click(function(event) {
    var cid = $(this).attr("data-id");
    $("#frmContractId").val(cid);
});