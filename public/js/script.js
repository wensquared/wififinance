"use strict";

// const { parseJSON } = require("jquery");

(function($){
    $(document).ready(function(){

        //TOASTR Einstellung
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        
        window.myToastr = function(typ,msg,title=undefined){
            switch(typ){
                case 'success' : toastr.success(msg, title || 'Success'); break;
                case 'info' : toastr.info(msg, title || 'Info'); break;
                case 'warning' : toastr.warning(msg, title || 'Warning'); break;
                case 'error' : toastr.error(msg, title || 'Error'); break;
            }
        }

        //Delete
        $('form.delete').on('submit',function(e){
            e.preventDefault();
            const form = $(this); 
            console.log(form);
            const deleteModal = $('#deleteModal');
            deleteModal.modal("show");

            $('#deleteModalLabel').text(form.data('title'));
            $('#deleteModalBody').html(form.data('body')); 

            $('#deleteModal .btn-secondary').off().on('click', function(e) { 
                deleteModal.modal("hide"); 
            });

            $('#deleteModal .close').off().on('click', function(e) { 
                deleteModal.modal("hide"); 
            });

            //Del btn event erstellen
            $('#deleteModal .btn-danger').off().on('click', function(e) { 
                deleteModal.modal("hide"); 

                $.ajax({
                    url: form.attr('action'),  
                    method: "DELETE", 
                    data: form.serialize(), 

                    success: function(response) {
                        if(response.status==200){
                            form.closest('tr').remove();  
                            window.myToastr('success', response.msg );
                        }
                        else{
                            window.myToastr('error', response.msg );
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.status,  xhr.statusText); 
                        window.myToastr('error', xhr.status +' Ein fehler ist passiert' );
                    }
                });
            });
        });

        // save/unsave to watchlist
        $('form.addWatchlist').on('submit', function (e) {
            e.preventDefault();
            console.log('in here');
            const form = $(this); 
            const btn = form.find('button')[0]

            $.ajax({
                url: form.attr('action'),  
                method: "POST", 
                data: form.serialize(), 

                success: function(response) {
                    if(response.status==200){
                        window.myToastr('success', response.msg );

                        if($(btn).hasClass('btn-outline-secondary')) {
                            $(btn).removeClass('btn-outline-secondary').addClass('btn-outline-danger');
                        }
                        else if($(btn).hasClass('btn-outline-danger')) {
                            $(btn).removeClass('btn-outline-danger').addClass('btn-outline-secondary');
                        }
                    }
                    else{
                        window.myToastr('error', response.msg );
                    }
                },
                error: function(xhr) {
                    console.log(xhr.status,  xhr.statusText); 
                    window.myToastr('error', xhr.status +' Ein fehler ist passiert' );
                }
            });
        });

        // Buy Modal
        $('button.buybtn').on('click',function(e){
            e.preventDefault();
            const form = $(this); 
            const buyModal = $('#buyModal');
            buyModal.modal("show");
            $('#buyPrice').attr('value',form.data('price'));
            $('#buyTicker').attr('value',form.data('ticker'));

            const numShares = parseInt(form.data('balance') / form.data('price'));
            $('#buyAmount').attr('max',numShares); 
            $('#buyNumShares').html('You can buy ' + numShares + ' shares.');

            $('#buyModal .btn-secondary').off().on('click', function(e) { 
                buyModal.modal("hide"); 
            });

            $('#buyModal .close').off().on('click', function(e) { 
                buyModal.modal("hide"); 
            });
        });
        // Sell Modal
        $('button.sellbtn').on('click',function(e){
            e.preventDefault();
            const form = $(this); 
            const sellModal = $('#sellModal');
            sellModal.modal("show");

            $('#sellPrice').attr('value',form.data('price'));
            $('#sellTicker').attr('value',form.data('ticker'));
            $('#sellAmount').attr('max',form.data('amount')); 

            $('#sellNumShares').html('You can sell ' + form.data('amount') + ' shares.');

            $('#sellModal .btn-secondary').off().on('click', function(e) { 
                sellModal.modal("hide"); 
            });

            $('#sellModal .close').off().on('click', function(e) { 
                sellModal.modal("hide"); 
            });
        });
        //

        //
    });
})(jQuery);