$(function(){
    //$('.spx_pro_box').hide();
    var variants = JSON.parse($('#mastervariant').html());
    var masfiled = JSON.parse($('#coljsonVariant').html());
    var currsign = $("#currency_sign").val();
    //crate html and add in page
    masfiled.forEach(colnm => {
        var htmlx = '<div class="variation-selector" id="'+colnm+'-selector" data-set="'+colnm+'" data-val=""><label for="'+colnm+'">'+colnm.replace("_", " ")+':</label><div class="swatch-container" id="'+colnm+'-container"></div></div>';
        $("#addtocartform .product-container").append(htmlx);
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
                $("#addtocartform #"+colnm+"-container").append('<div class="swatch" style="background-color: '+val+'" data-value="'+val+'"></div>');
            } else {
                $("#addtocartform #"+colnm+"-container").append('<div class="swatch" data-value="'+val+'">'+val+'</div>');
            }
        });
    });
    // Add class when clicking on variant option
    $("#addtocartform .swatch").on("click", function() {
        var selectedValue = $(this).data("value");
        var containerId = $(this).closest("#addtocartform .variation-selector").attr("id");
        $(this).siblings().removeClass("selected");
        if($(this).hasClass("selected")) {
            $(this).removeClass("selected");
            $("#" + containerId).attr('data-val', "");
            $('#addtocartform .spx_pro_box').hide();
        } else {
            if(!$(this).hasClass("disabled")) {
                $(this).addClass("selected");
                $("#" + containerId).attr('data-val', selectedValue);
            }
        }
        updateprice();
        //$('.spx_pro_box').show();
        disableUnrelatedVariants(); // Disable unrelated variations
    });
    // Clear selection option
    $("#addtocartform #clear-selection").on("click", function() { 
        $("#addtocartform .variation-selector").attr('data-val', "");
        $("#addtocartform .swatch").removeClass("selected").removeClass("disabled");
        $("#addtocartform .variation-selector").show(); 
        $('#addtocartform .spx_pro_box').hide();
        //$("#skuidx").val(0);
        $("#addtocartform .modal-body .addtocart").val(0);
        $("#addtocartform .spx_pro_box .sku_val").text("-");
        $("#addtocartform .spx_pro_box .price_val").text(currsign + "0");
        //
        $('#addtocartform .stockm').removeClass('outstock');
        $('#addtocartform .stockm').addClass('instock');
        $('#addtocartform .modal-body .addtocart').prop('disabled', false);
        $('#addtocartform .stockm').html('In Stock');
    });
    //pricce update
    function updateprice(){ 
        const selctval = {};
        $(".variation-selector").each(function() { 
            var colnm = $(this).attr('data-set');
            var valnm = $(this).attr('data-val') ? $(this).attr('data-val') : 0;
            selctval[colnm] = valnm;
        });
        //
        function checkVariant(variant, selctval) {
            return Object.keys(selctval).every(key => {
                return variant[key] === selctval[key];
            });
        }
        //
        const matchedVariants = variants.filter(variant => checkVariant(variant, selctval));
        if (matchedVariants.length > 0) {
            const variant = matchedVariants[0];
            //$("#price").text("Price: $"+ variant.sprice);
            $(".spx_pro_box .sku_val").text(variant.sku);
            $(".modal-body .addtocart").val(variant.id);
            if(variant.sprice == 0) { variant.sprice = variant.rprice; } 
            $(".spx_pro_box .price_val").text(currsign + variant.sprice);
            $('.spx_pro_box').show();
            //
            if(variant.stock == 0){
                $('.stockm').removeClass('instock');
                $('.stockm').addClass('outstock');
                $('.modal-body .addtocart').prop('disabled', true);
                $('.stockm').html('Unavailable');
            } else {
                $('.stockm').removeClass('outstock');
                $('.stockm').addClass('instock');
                $('.modal-body .addtocart').prop('disabled', false);
                $('.stockm').html('In Stock');
            }
        } else { //alert(10);
            //$("#price").text("Price: $0");
            $(".spx_pro_box .sku_val").text("-");
            $(".spx_pro_box .price_val").text(currsign + "0");
            $('.spx_pro_box').hide();
        }
    }
    // Disable unrelated variations based on selected values
    function disableUnrelatedVariants() {
        const selectedValues = {};
        $("#addtocartform .variation-selector").each(function() {
            const colnm = $(this).attr('data-set');
            const valnm = $(this).attr('data-val') || null;
            selectedValues[colnm] = valnm;
        });
        //loop
        masfiled.forEach(colnm => {
            $("#addtocartform #" + colnm + "-container .swatch").each(function() {
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