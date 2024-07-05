					function redirect(x, blueprint) {

                        console.log(x);

 							jQuery.ajax({
                                                url: '../scripts/redirectpulse.php',
                                                type: 'POST',
                                                data: {
                                                    'z': x,
                                                    'blueprint': blueprint
                                                },
                                                success: function(results) {
                                                    jQuery("#x").html(results);
                                                }
                                            });




                                        }
