"use strict";

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

        function chartdata() {
            const labels = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
            ];
            const data = {
                labels: labels,
                datasets: [{
                label: 'My First dataset',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [0, 10, 5, 2, 20, 30, 45],
                }]
            };
            const config = {
                type: 'line',
                data: data,
                options: {}
            };
                // === include 'setup' then 'config' above ===
            
                /* var myChart = new Chart(
                document.getElementById('myChart'),
                config
                ); */
        }
    });
})(jQuery);