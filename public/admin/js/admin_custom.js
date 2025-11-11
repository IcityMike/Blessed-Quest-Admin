
function markAsRead(notificationId)
{
    // var url = '{{route("admin.notifications.markAsRead",":id")}}';
    
    url = readNotificationURL.replace(':id', notificationId);
    $.ajax({
        url: url,
        success:function(data)
        {
        }
    });
}


$(document).ready(function(){
    // from date - to date datepicker for all insurance type

    var currentYear = new Date().getFullYear();

    $('.from_date').datepicker({
        autoclose: true,
        /*startDate: new Date(),
        setDate: 'today',*/
        // defaultViewDate: {year: currentYear},
        format: 'dd/mm/yyyy'
    }).on('changeDate', function (selected) {
        $('.to_date').val("");
        var minDate = new Date(selected.date.valueOf());
        minDate = new Date(minDate.getFullYear()+1,minDate.getMonth(),minDate.getDate());
        // alert(minDate.getFullYear() + 1);
        $('.to_date').datepicker('setDate', minDate);
        $('.to_date').datepicker('setStartDate', minDate);
    });

    $('.to_date').datepicker({
        autoclose: true,
        startDate: new Date(),
        // setDate: 'today',
        // defaultViewDate: {year: currentYear},
        format: 'dd/mm/yyyy'
    }).on('changeDate', function (selected) {
    /*var maxDate = new Date(selected.date.valueOf());
    $('#date_of_factfind_info_collected').datepicker('setEndDate', maxDate);*/
    });

    // calculate total sum insured amount of personal vehicle insurance.
    $(document).on('keyup change', '.vehicle_sum_insured,.accessories_sum_insured', function() {
      //console.log($(this).hasClass('vehicle_sum_insured'));
        if ($(this).hasClass('vehicle_sum_insured')) {
         if(!isNaN($(".vehicle_sum_insured").val()) || !isNaN($(".accessories_sum_insured").val())){
            var vehicle_sum_insured = parseInt($(this).val());
            var accessories_sum_insured = parseInt($('.accessories_sum_insured').val());

            $('.total_sum_insured').val(parseInt($(this).val()) + parseInt($('.accessories_sum_insured').val()));
        }
        
      } else if ($(this).hasClass('accessories_sum_insured')) {
        if(!isNaN($(".vehicle_sum_insured").val()) || !isNaN($(".accessories_sum_insured").val())){
            var accessories_sum_insured = parseInt($(this).val());
            var vehicle_sum_insured = parseInt($('.vehicle_sum_insured').val());
            $('.total_sum_insured').val(parseInt($(this).val()) + parseInt($('.vehicle_sum_insured').val()));
        }
      }else{
        $(".total_sum_insured").val(0);
      }
    });


    if($('#turnover').val() >= 2000000){
        $(".waiver_status").show();
    }
    $(".turnover").keyup(function(){
        var turnover = $("#turnover").val();
        if(turnover >= 2000000){
            $(".waiver_status").show();
        }else{
            $(".waiver_status").hide();
        }
    });
    $(".trailer_radio input[type='radio']").on("change",function() {
        if($('#trailer_control').is(':checked')){
            $(".no_of_trailer_cls").show();
        }else{
            $(".no_of_trailer_cls").hide();
        }
    });

    $(".claim_radio input[type='radio']").on("change",function() {
        if($("#claim_insured").is(':checked')){
            $(".claim_insured_cls").show();
            $(".add-new-claim").show();
        }else{
            $(".claim_insured_cls").hide();
            $(".add-new-claim").hide();

        }
    });
    
    $(".add-new-claim").click(function(){
        
        if($("#claimRows").find(".claim-row").length < 5)
        {
            var claim_html = $("#claimRows").find(".claim-row").first().clone();
            var claim_index = $("#claimRows").find(".claim-row").length + 1;
            $("#claimRows").append(claim_html);
            $("#claimRows").find(".claim-row").last().find("input").val("");
            $("#claimRows").find(".claim-row").last().find("select").val("");
            $("#claimRows").find(".claim-row").last().find("textarea").val("");
            $("#claimRows").find(".claim-row").last().find(".remove-claim-col").show(); 
            $("#claimRows").find(".claim-row").last().find(".sub-title").first().html("Claim "+ ($("#claimRows").find(".claim-row").length)) ;
        }else{
            alert("Maximum 5 claims are allowed.");
        }
    });

    $(document).on("click",".remove-claim",function()
    {
        $(this).closest(".claim-row").remove();
        // $('#vehicle_insurance_form').parsley().refresh();
    });

    $(".export_radio input[type='radio']").on("change", function() {
        if ($("#customRadio1").is(':checked')) {
            $(".import_export").show();
        } else {
            $(".import_export").hide();
        }
    });

    $(document).on("change",".interested_party_radio",function(){
        if($("#interested_party").is(':checked')){
            $(".interested_parties_cls").show();
        }else{
            $(".interested_parties_cls").hide();

        }
    });

    $(document).on('change',".contents_inc_stock_radio", function(e) {
        if ($("#contents_inc_stock1").is(':checked')) {
            $(".contents_inc_stock_cls").show();
            $(".contents_stock_cls").hide();
        }else{
            $(".contents_inc_stock_cls").hide();
            $(".contents_stock_cls").show();

        }
    });

    $(document).on('change',".add_not_found_check", function(e) {
        if($(this).is(":checked")){
            // $(".add_not_found_cls").show();
            $(".custom_address_cls").parsley().reset();
            $(".custom_address_cls").removeAttr("required");
            $(".custom_address_cls").attr("disabled",true);
            $(".situation_address_custom").prop("required","required");
            $(".situation_address_custom").removeAttr("readonly");
        }else{
            // $(".add_not_found_cls").hide();
            $(".custom_address_cls").attr("required",true);
            $(".custom_address_cls").attr("disabled",false);
            $(".situation_address_custom").removeAttr("required");
            $(".situation_address_custom").attr("readonly",true);
        }
    });
    $(document).on("change",".section_check",function(){
        var checked_section = $(this).val();
        if(checked_section == 1){

            if($(this).is(":checked")){
                $(".business_property_section").removeClass('hidden');
                $(".business_property_section").addClass('show');
            }else{
                $(".business_property_section").removeClass('show');
                $(".business_property_section").addClass('hidden');
            }
        }

        if(checked_section == 2){
            if($(this).is(":checked")){
                $(".business_interruption_section").removeClass('hidden');
                $(".business_interruption_section").addClass('show');
            }else{
                $(".business_interruption_section").removeClass('show');
                $(".business_interruption_section").addClass('hidden');

            }
        }
        
        if(checked_section == 3){
            if($(this).is(":checked")){
                $(".theft_section").removeClass('hidden');
                $(".theft_section").addClass('show');
            }else{
                $(".theft_section").removeClass('show');
                $(".theft_section").addClass('hidden');
            }
        }
        
        if(checked_section == 4){
            if($(this).is(":checked")){
                $(".money_section").removeClass('hidden');
                $(".money_section").addClass('show');
            }else{
                $(".money_section").removeClass('show');
                $(".money_section").addClass('hidden');
            }
        }
        
        if(checked_section == 5){
            if($(this).is(":checked")){
                $(".machinery_breakdown").removeClass('hidden');
                $(".machinery_breakdown").addClass('show');
            }else{
                $(".machinery_breakdown").removeClass('show');
                $(".machinery_breakdown").addClass('hidden');
            }
        }
        
        if(checked_section == 6){
            if($(this).is(":checked")){
                $(".electronic_equipment").removeClass('hidden');
                $(".electronic_equipment").addClass('show');
            }else{
                $(".electronic_equipment").removeClass('show');
                $(".electronic_equipment").addClass('hidden');
            }
        }
        
        if(checked_section == 7){
            if($(this).is(":checked")){
                $("#public_product_cover").val('true');
                $(".public_and_products_liability").removeClass('hidden');
                $(".public_and_products_liability").addClass('show');
            }else{
                $("#public_product_cover").val('false');
                $(".public_and_products_liability").removeClass('show');
                $(".public_and_products_liability").addClass('hidden');
            }
        }
        
        if(checked_section == 8){
            if($(this).is(":checked")){
                $(".glass_section").removeClass('hidden');
                $(".glass_section").addClass('show');
            }else{
                $(".glass_section").removeClass('show');
                $(".glass_section").addClass('hidden');
            }
        }
    });


    $(document).on("change","#other_situation_cover_check",function(){
        if ($(this).is(":checked")) {
            $(".other_situation_cover_col").show();
        }
        else {
            $(".other_situation_cover_col").hide();
        }
    });

    $("#blanket_cover_check").click(function(){
        if($(this).is(":checked"))
        {
            $('#blanket_cover').removeAttr("disabled");
            $('.money_section_cover_cls').attr('disabled','disabled');
        }
        else{
            $('.money_section_cover_cls').removeAttr("disabled");
            $("#blanket_cover").prop("disabled", true);
        }
        $("#business_detail_form").parsley().reset();     
    });

    /**
     * On change of occupation, if other option is selected, show other occupation textbox 
     * otherwise hide
     */
     $(document).on("change","#occupation",function(){
        if($(this).val() == "Other")
        {
            $(".other_occupation_cls").show();
        }
        else{
            $(".other_occupation_cls").hide();
        }
    })

    /**
     * On change of roof type, if other option is selected, show other roof type textbox 
     * otherwise hide
     */
    $(document).on("change","#roof_type",function(){
         if($(this).val() == "Other")
        {
            $("#other_roof_type_col").show();
        }
        else{
            $("#other_roof_type_col").hide();
        }
    })

    /**
     * On change of floor type, if other option is selected, show other floor type textbox 
     * otherwise hide
     */
    $(document).on("change","#floor_type",function(){
        if($(this).val() == "Other")
        {
            $("#other_floor_type_col").show();
        }
        else{
            $("#other_floor_type_col").hide();
        }
    });

    /**
     * On change of walls type, if other option is selected, show other walls type textbox 
     * otherwise hide
     */
    $(document).on("change","#walls_type",function(){
        if($(this).val() == "Other")
        {
            $("#other_walls_type_col").show();
        }
        else{
            $("#other_walls_type_col").hide();
        }
    });

    /**
     * On click of fire security, if other option is selected, show other Fire Security textbox 
     * otherwise hide
     */
    $(document).on("change","#other_fire_security_check",function(){
        if ($(this).is(":checked")) {
            $(".other_fire_security_col").show();
        }
        else {
            $(".other_fire_security_col").hide();
        }
    });
    /**
     * On click of theft security, if other option is selected, show other theft Security textbox 
     * otherwise hide
     */
    $(document).on("change","#other_theft_security_check",function(){
        if ($(this).is(":checked")) {
            $("#other_theft_security_col").show();
        }
        else {
            $("#other_theft_security_col").hide();
        }
    });
    /**
     * On change of equipment_classification, if other option is selected, show other equipment_classification textbox 
     * otherwise hide
     */
    $("#equipment_classification").change(function(){
        if($(this).val() == "Other"){
            $("#other_equipment_classification_col").show();
        }else{
            $("#other_equipment_classification_col").hide();
        }
    })

    $(document).on("change",".sign_board_radio",function(){
        if($("#sign_board").is(':checked')){
            $(".sign_board_cls").show();
        }else{
            $(".sign_board_cls").hide();

        }
    })

    /**
     * On change of Rnewal not recieved, if renewal_term_check is selected, remove required validation
     */
    $(document).on("change","#renewal_term_check",function(){
        if ($(this).is(":checked")) {
            $(".renewal_cls").removeClass('hidden');
            $(".quote_cls").addClass('hidden');
            $(".quote_required").removeAttr("required");
            $(".quote_required").prop("disabled",true);
        }
        else {
            $(".quote_cls").removeClass('hidden');
            $(".renewal_cls").addClass('hidden');
            $(".quote_required").prop("required", "required");
            $(".quote_required").removeAttr("disabled");
        }
    });

    // calculate total invoice = premium + FSL + UW fees + stamp duty + broker fees + GSt = invoice total
    $(".invoice_total_cls").on('input',function () {
    var calculated_total_sum = 0;
 
    $(".invoice_total_cls").each(function () {
       var get_textbox_value = $(this).val();
       if ($.isNumeric(get_textbox_value)) {
          calculated_total_sum += parseFloat(get_textbox_value);
          }                  
        });
        $("#total_invoice_amount").val(calculated_total_sum);
    });

});


