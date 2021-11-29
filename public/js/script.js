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

            // console.log(form.data('title'));  
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
                        // console.log(response);
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
            console.log(form.serialize());

            $.ajax({
                url: form.attr('action'),  
                method: "POST", 
                data: form.serialize(), 

                success: function(response) {
                    // console.log(response);
                    if(response.status==200){
                        // form.closest('tr').remove();  
                        window.myToastr('success', response.msg );

                        if($('#btnHeart').hasClass('btn-outline-secondary')) {
                            $('#btnHeart').removeClass('btn-outline-secondary').addClass('btn-outline-danger');
                        }
                        else if($('#btnHeart').hasClass('btn-outline-danger')) {
                            $('#btnHeart').removeClass('btn-outline-danger').addClass('btn-outline-secondary');
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

        //

        $('button.buybtn').on('click',function(e){
            e.preventDefault();
            const form = $(this); 
            const buyModal = $('#buyModal');
            buyModal.modal("show");
            console.log(form.data('text'));

            $('#buyModal .btn-secondary').off().on('click', function(e) { 
                buyModal.modal("hide"); 
            });

            $('#buyModal .close').off().on('click', function(e) { 
                buyModal.modal("hide"); 
            });
        });
        //
        $('button.sellbtn').on('click',function(e){
            e.preventDefault();
            // const form = $(this); 
            const buyModal = $('#sellModal');
            buyModal.modal("show");

            $('#sellModal .btn-secondary').off().on('click', function(e) { 
                buyModal.modal("hide"); 
            });

            $('#sellModal .close').off().on('click', function(e) { 
                buyModal.modal("hide"); 
            });
        });
        //
        $('button.buybtn1').on('click',function(e){
            e.preventDefault();
            const form = $(this); 
            const buyModal1 = $('#buyModal1');
            buyModal1.modal("show");
            console.log(form.data('text'));
            $('#test p').text(form.data('text'));
            $('#test1 p').text(form.data('ticker'));
            console.log(form.data('price'));
            $('#price11').attr('value',form.data('price'));
            $('#ticker11').attr('value',form.data('ticker'));


            $('#buyModal1 .btn-secondary').off().on('click', function(e) { 
                buyModal1.modal("hide"); 
            });

            $('#buyModal1 .close').off().on('click', function(e) { 
                buyModal1.modal("hide"); 
            });
        });
        //
    });
})(jQuery);