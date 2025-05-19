$(function(){
    //$('.spx_pro_box').hide();
    var variants = JSON.parse($('#mastervariant_rel').html());
    var masfiled = JSON.parse($('#coljsonVariant_rel').html());
    var currsign = $("#currency_sign").val();
    //crate html and add in page
    masfiled.forEach(colnm => {
        var htmlx = '<div class="variation-selectorpop" id="'+colnm+'-selector" data-set="'+colnm+'" data-val=""><label for="'+colnm+'">'+colnm.replace("_", " ")+':</label><div class="swatch-container" id="'+colnm+'-container"></div></div>';
        $("#variantmodal .product-container").append(htmlx);
    });
    // Initialize the object for each field
    var mast = {};
    masfiled.forEach(colnm => {
        mast[colnm] = new Set(); 
        $.each(variants, function(index, variant) {
            if(variant[colnm] != 0) { mast[colnm].add(variant[colnm]); }
        });
        mast[colnm] = [...mast[colnm]];
    });
    // Dynamically add value to variation
    masfiled.forEach(colnm => {
        $.each(mast[colnm], function(key, val) {
            if(colnm == "color") {
                $("#variantmodal #"+colnm+"-container").append('<div class="swatch" style="background-color: '+val+'" data-value="'+val+'"></div>');
            } else {
                $("#variantmodal #"+colnm+"-container").append('<div class="swatch" data-value="'+val+'">'+val+'</div>');
            }
        });
    });
    // Add class when clicking on variant option
    $("#variantmodal .swatch").on("click", function() {
        var selectedValue = $(this).data("value");
        var containerId = $(this).closest("#variantmodal .variation-selectorpop").attr("id");
        $(this).siblings().removeClass("selected");
        if($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            $("#" + containerId).attr('data-val', "");
            $('#variantmodal .spx_pro_box').hide();
        } else {
            if(!$(this).hasClass("disabled")) {
                $(this).addClass("selected");
                $("#variantmodal #" + containerId).attr('data-val', selectedValue);
            }
        }
        updateprice_pop();
        //$('.spx_pro_box').show();
        disableUnrelatedVariants_pop(); // Disable unrelated variations
    });
    // Clear selection option
    $("#variantmodal #clear-selection").on("click", function() { 
        $("#variantmodal .variation-selectorpop").attr('data-val', "");
        $("#variantmodal .swatch").removeClass("selected").removeClass("disabled");
        $("#variantmodal .variation-selectorpop").show(); 
        $('#variantmodal .spx_pro_box').hide();
        //$("#skuidx").val(0);
        $("#variantmodal .modal-body .addtocart").val(0);
        $("#variantmodal .spx_pro_box .sku_val").text("-");
        $("#variantmodal .spx_pro_box .price_val").text(currsign + "0");
        //
        $('#variantmodal .stockm').removeClass('outstock');
        $('#variantmodal .stockm').addClass('instock');
        $('#variantmodal .modal-body .addtocart').prop('disabled', false);
        $('#variantmodal .stockm').html('In Stock');
    });
    //pricce update
    function updateprice_pop(){ 
        const selctval = {};
        $("#variantmodal .variation-selectorpop").each(function() { 
            var colnm = $(this).attr('data-set');
            var valnm = $(this).attr('data-val') ? $(this).attr('data-val') : 0;
            selctval[colnm] = valnm;
        });
        //
        function checkVariant_pop(variant, selctval) {
            return Object.keys(selctval).every(key => {
                return variant[key] === selctval[key];
            });
        }
        //
        const matchedVariants = variants.filter(variant => checkVariant_pop(variant, selctval));
        if (matchedVariants.length > 0) {
            const variant = matchedVariants[0];
            //$("#price").text("Price: $"+ variant.sprice);
            $("#variantmodal .spx_pro_box .sku_val").text(variant.sku);
            $("#variantmodal .modal-body .addtocart").val(variant.id);
            if(variant.sprice == 0) { variant.sprice = variant.rprice; } 
            $("#variantmodal .spx_pro_box .price_val").text(currsign + variant.sprice);
            $('#variantmodal .spx_pro_box').show();
            //
            if(variant.stock == 0){
                $('#variantmodal .stockm').removeClass('instock');
                $('#variantmodal .stockm').addClass('outstock');
                $('#variantmodal .modal-body .addtocart').prop('disabled', true);
                $('#variantmodal .stockm').html('Unavailable');
            } else {
                $('#variantmodal .stockm').removeClass('outstock');
                $('#variantmodal .stockm').addClass('instock');
                $('#variantmodal .modal-body .addtocart').prop('disabled', false);
                $('#variantmodal .stockm').html('In Stock');
            }
        } else { 
            // (11);
            //$("#price").text("Price: $0");
            $("#variantmodal .spx_pro_box .sku_val").text("-");
            $("#variantmodal .spx_pro_box .price_val").text(currsign + "0");
            $('#variantmodal .spx_pro_box').hide();
        }
    }
    // Disable unrelated variations based on selected values
    function disableUnrelatedVariants_pop() {
        const selectedValues = {};
        $("#variantmodal .variation-selectorpop").each(function() {
            const colnm = $(this).attr('data-set');
            const valnm = $(this).attr('data-val') || null;
            selectedValues[colnm] = valnm;
        });
        //loop
        masfiled.forEach(colnm => {
            $("#variantmodal #" + colnm + "-container .swatch").each(function() {
                const value = $(this).data("value");
                if (value !== null) {
                    // Check if the current variant matches the selected ones
                    const hasMatchingVariants = variants.some(variant => {
                        return variant[colnm] === value && Object.keys(selectedValues).every(key => {
                            if (key === colnm) return true; // Skip the current field
                            return selectedValues[key] === null || selectedValues[key] === variant[key];
                        });
                    });
                    // Add or remove the disabled class
                    if (hasMatchingVariants) {
                        $(this).removeClass("disabled");
                    } else {
                        $(this).addClass("disabled");
                    }
                }
            });
        });
    }
});